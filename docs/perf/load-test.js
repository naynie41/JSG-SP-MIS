// SP-MIS HTTP load test (NFR-PERF-01) — k6.
//
// Exercises the latency-critical paths under concurrent load and asserts the perf
// targets as k6 thresholds: duplicate check < 5s, standard pages < 3s (95th pct).
// Run against STAGING with de-identified/synthetic data — never production PII.
//
//   BASE_URL=https://staging.spmis.example/api/v1 \
//   TOKEN=<full-token> \
//   k6 run --vus 50 --duration 3m docs/perf/load-test.js
//
// Capture a representative dataset first (e.g. `php artisan perf:benchmark --seed=200000`
// on staging) so the numbers reflect real volume, then record the summary in
// docs/PERFORMANCE.md §Results.

import http from 'k6/http'
import { check, group } from 'k6'
import { Trend } from 'k6/metrics'

const BASE = __ENV.BASE_URL || 'http://localhost:8080/api/v1'
const TOKEN = __ENV.TOKEN || ''

const dupTrend = new Trend('duplicate_check_ms', true)
const pageTrend = new Trend('standard_page_ms', true)

export const options = {
  thresholds: {
    // Perf targets straight from NFR-PERF-01.
    duplicate_check_ms: ['p(95)<5000'],
    standard_page_ms: ['p(95)<3000'],
    http_req_failed: ['rate<0.01'],
  },
  scenarios: {
    steady: { executor: 'constant-vus', vus: Number(__ENV.VUS || 50), duration: __ENV.DURATION || '3m' },
  },
}

const authHeaders = { headers: { Authorization: `Bearer ${TOKEN}`, Accept: 'application/json' } }

export default function () {
  group('liveness', () => {
    const res = http.get(`${BASE}/health`)
    check(res, { 'health 200': (r) => r.status === 200 })
  })

  group('standard pages (<3s)', () => {
    for (const path of ['/beneficiaries?per_page=25', '/benefits?per_page=25', '/dashboard', '/programmes?per_page=25']) {
      const res = http.get(`${BASE}${path}`, authHeaders)
      pageTrend.add(res.timings.duration)
      check(res, { [`${path} ok`]: (r) => r.status === 200 })
    }
  })

  group('duplicate check (<5s)', () => {
    // Fuzzy "serve many" search runs the same engine as the pre-save duplicate check.
    const res = http.get(`${BASE}/beneficiaries/search?last_name=Bello&first_name=Amina`, authHeaders)
    dupTrend.add(res.timings.duration)
    check(res, { 'duplicate search ok': (r) => r.status === 200 })
  })
}
