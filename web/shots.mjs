import { chromium } from 'playwright'
import { createServer } from 'vite'

const scratch = process.argv[2]
const server = await createServer({ server: { port: 5199 }, logLevel: 'error' })
await server.listen()
const base = 'http://localhost:5199'

const browser = await chromium.launch()
const shots = [
  ['landing-desktop', '/', { width: 1440, height: 900 }],
  ['landing-mobile', '/', { width: 390, height: 844 }],
  ['login-desktop', '/login', { width: 1440, height: 900 }],
]
for (const [name, path, viewport] of shots) {
  const page = await browser.newPage({ viewport })
  await page.goto(base + path, { waitUntil: 'networkidle' })
  await page.evaluate(() => document.fonts.ready)
  await page.waitForTimeout(400)
  const overflow = await page.evaluate(
    () => document.documentElement.scrollWidth > document.documentElement.clientWidth,
  )
  console.log(`${name}: horizontal overflow = ${overflow}`)
  await page.screenshot({ path: `${scratch}/${name}.png` })
  await page.close()
}
await browser.close()
await server.close()
