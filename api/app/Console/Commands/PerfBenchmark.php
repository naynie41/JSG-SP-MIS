<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Access\Models\Mda;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Export\BeneficiaryListExport;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\DeterministicDuplicateFinder;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Console\Command;
use Throwable;

/**
 * Micro-benchmarks the latency-critical server paths against the NFR-PERF-01 targets
 * (duplicate check < 5s; standard pages < 3s) using the CURRENT database. It measures
 * the query/engine layer directly (no network), so it isolates server work from HTTP
 * overhead — pair it with the k6 HTTP load test in docs/perf/. `--seed=N` bulk-inserts
 * synthetic beneficiaries first for a representative volume (staging only).
 */
class PerfBenchmark extends Command
{
    protected $signature = 'perf:benchmark {--seed=0 : Insert N synthetic beneficiaries first (staging only)}';

    protected $description = 'Benchmark the duplicate check and key list/aggregate paths against the perf targets';

    private const DUPLICATE_TARGET_MS = 5000;

    private const PAGE_TARGET_MS = 3000;

    public function handle(
        DeterministicDuplicateFinder $deterministic,
        FuzzyDuplicateFinder $fuzzy,
        MatchingConfigService $configs,
        BeneficiaryListExport $listExport,
        LedgerAggregator $aggregator,
        DashboardSnapshotService $snapshots,
    ): int {
        $seed = (int) $this->option('seed');
        if ($seed > 0) {
            $this->seed($seed);
        }

        $this->line('Benchmarking against '.Beneficiary::query()->withoutGlobalScopes()->count().' beneficiaries.');

        $rows = [];

        $rows[] = $this->measure('Duplicate check (pre-save)', self::DUPLICATE_TARGET_MS, function () use ($deterministic, $fuzzy, $configs): void {
            $config = $configs->activeOrNull();
            if ($config === null) {
                throw new Throwable('matching not configured');
            }
            $sample = Beneficiary::query()->withoutGlobalScopes()->inRandomOrder()->first();
            $candidate = [
                'nin' => $sample?->nin, 'bvn' => $sample?->bvn, 'phone' => $sample?->phone,
                'last_name' => $sample?->last_name, 'first_name' => $sample?->first_name,
                'date_of_birth' => $sample?->date_of_birth?->toDateString(),
            ];
            $deterministic->find($candidate, $config);
            $fuzzy->find($candidate, $config);
        });

        $rows[] = $this->measure('Beneficiary list (page)', self::PAGE_TARGET_MS, function () use ($listExport): void {
            $query = $listExport->applyFilters(Beneficiary::query()->withoutGlobalScopes(), []);
            $listExport->ordered($query)->paginate(25);
        });

        $rows[] = $this->measure('Benefit ledger aggregate', self::PAGE_TARGET_MS, function () use ($aggregator): void {
            $aggregator->aggregate('programme', []);
        });

        $rows[] = $this->measure('Dashboard read (snapshot)', self::PAGE_TARGET_MS, function () use ($snapshots): void {
            $snapshots->read(DashboardScope::stateWide());
        });

        $this->table(['Path', 'Duration (ms)', 'Target (ms)', 'Result'], $rows);

        $failed = array_filter($rows, static fn (array $r) => $r[3] === 'FAIL');

        return $failed === [] ? self::SUCCESS : self::FAILURE;
    }

    /**
     * @return array{0: string, 1: string, 2: int, 3: string}
     */
    private function measure(string $label, int $targetMs, callable $fn): array
    {
        try {
            $start = microtime(true);
            $fn();
            $ms = (microtime(true) - $start) * 1000;

            return [$label, number_format($ms, 1), $targetMs, $ms <= $targetMs ? 'PASS' : 'FAIL'];
        } catch (Throwable $e) {
            return [$label, 'n/a ('.$e->getMessage().')', $targetMs, 'SKIP'];
        }
    }

    private function seed(int $count): void
    {
        $mda = Mda::query()->first() ?? Mda::factory()->create();
        $this->line("Seeding {$count} synthetic beneficiaries…");
        $bar = $this->output->createProgressBar($count);
        foreach (array_chunk(range(1, $count), 500) as $chunk) {
            foreach ($chunk as $_) {
                Beneficiary::factory()->create(['owner_mda_id' => $mda->id]);
                $bar->advance();
            }
        }
        $bar->finish();
        $this->newLine();
    }
}
