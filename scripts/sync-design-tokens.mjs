#!/usr/bin/env node
// Generates web/DESIGN.md from the frontmatter of the repo-root DESIGN.md.
//
// Why this exists: the Impeccable detector resolves DESIGN.md by walking UP from
// each scanned file and stopping at the first project-root marker. web/package.json
// is such a marker, so files under web/src can never see the repo-root DESIGN.md —
// the design-system-* rules silently go inert and token drift stops being checked.
// Mirroring just the token block into web/ makes them fire again.
//
// The mirror is generated, never hand-edited. Root DESIGN.md stays canonical.
//
//   node scripts/sync-design-tokens.mjs           # write web/DESIGN.md
//   node scripts/sync-design-tokens.mjs --check    # exit 1 if out of date

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const repoRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const SOURCE = path.join(repoRoot, 'DESIGN.md');
const MIRROR = path.join(repoRoot, 'web', 'DESIGN.md');

function readFrontmatter(file) {
  const lines = fs.readFileSync(file, 'utf8').split(/\r?\n/);
  if (lines[0]?.trim() !== '---') {
    throw new Error(`${path.relative(repoRoot, file)} has no YAML frontmatter — the detector needs it to check token drift.`);
  }
  const end = lines.indexOf('---', 1);
  if (end === -1) throw new Error(`${path.relative(repoRoot, file)} has an unterminated frontmatter block.`);
  return lines.slice(1, end).join('\n');
}

function render(frontmatter) {
  return [
    '---',
    frontmatter,
    '---',
    '',
    '# DESIGN.md (generated mirror)',
    '',
    '> **Do not edit.** Generated from the repo-root [`DESIGN.md`](../DESIGN.md) by',
    '> `scripts/sync-design-tokens.mjs`. Only the token frontmatter is mirrored here,',
    '> so the Impeccable detector can resolve a design system from inside `web/`',
    '> (its walk-up stops at `web/package.json`). The prose design system — components,',
    '> states, rules — lives in the root file and is the one to read and edit.',
    '',
    'Regenerate with `npm run sync:design`; verify with `npm run check:design`.',
    '',
  ].join('\n');
}

const expected = render(readFrontmatter(SOURCE));
const checkMode = process.argv.includes('--check');
const actual = fs.existsSync(MIRROR) ? fs.readFileSync(MIRROR, 'utf8') : null;

if (checkMode) {
  if (actual === expected) {
    console.log('web/DESIGN.md is in sync with DESIGN.md.');
    process.exit(0);
  }
  console.error('web/DESIGN.md is out of date. Run `npm run sync:design`.');
  process.exit(1);
}

if (actual === expected) {
  console.log('web/DESIGN.md already up to date.');
} else {
  fs.writeFileSync(MIRROR, expected);
  console.log(`Wrote ${path.relative(repoRoot, MIRROR)}`);
}
