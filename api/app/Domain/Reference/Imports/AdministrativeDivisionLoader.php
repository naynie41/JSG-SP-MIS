<?php

declare(strict_types=1);

namespace App\Domain\Reference\Imports;

use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Reference\Services\ReferenceDataCache;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Loads Jigawa LGAs and wards from an authoritative dataset FILE supplied by the
 * maintainer (HDX / GRID3 / State administrative records).
 *
 * The one rule this class exists to enforce: **it never invents data**. There is no
 * fallback list, no partial seed, no placeholder. If the file is absent, unreadable,
 * empty, or not credibly a Jigawa dataset, it throws
 * {@see ReferenceDatasetException} and writes nothing.
 *
 * Accepted shapes (see README):
 *   CSV  — header row with lga_name, ward_name (+ optional lga_code, ward_code)
 *   JSON — a flat list of the same keys, or nested: [{ name, code?, wards: [...] }]
 *
 * Codes are slugged from names when not supplied, using the same slug the registry
 * already uses ("Birnin Kudu" → birnin_kudu), so `lgas.code` lines up with both the
 * {@see LgaEnum} validation values and `geo_boundaries.code`.
 *
 * VALIDATION HAPPENS BEFORE ANY WRITE, so an error message can honestly say nothing
 * was loaded. Two checks reject a file that would otherwise look fine:
 *
 *  - an LGA the state does not have → wrong file (usually national data, unfiltered);
 *  - fewer than all 27 LGAs → a partial dataset, which is the dangerous case: it
 *    yields a lookup table that is silently missing real places.
 *
 * Ward counts are NOT checked against an expected total. Jigawa's ward count is
 * commonly cited as ~287, but that figure is not a fact this code is entitled to
 * enforce — the supplied file is the authority, and the loader reports what it found
 * so the maintainer can verify it.
 *
 * Idempotent: upserts by `code` / `(lga_id, code)`, so re-running with a corrected
 * file updates in place.
 */
class AdministrativeDivisionLoader
{
    private const REQUIRED_COLUMNS = ['lga_name', 'ward_name'];

    public function __construct(private readonly ReferenceDataCache $cache) {}

    /**
     * @throws ReferenceDatasetException
     */
    public function loadFromFile(?string $path = null): DivisionLoadResult
    {
        $path = $path ?? (string) config('reference.divisions.path');

        if (! is_file($path)) {
            throw ReferenceDatasetException::fileMissing($path);
        }

        $contents = @file_get_contents($path);
        if ($contents === false) {
            throw ReferenceDatasetException::unreadable($path);
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $records = match ($extension) {
            'csv', 'txt' => $this->parseCsv($contents, $path),
            'json', 'geojson' => $this->parseJson($contents, $path),
            default => throw ReferenceDatasetException::unsupportedFormat($extension),
        };

        if ($records === []) {
            throw ReferenceDatasetException::empty($path);
        }

        return $this->load($records);
    }

    /**
     * @param  list<array<string, string>>  $records  each with lga_name/ward_name (+ optional codes)
     *
     * @throws ReferenceDatasetException
     */
    public function load(array $records): DivisionLoadResult
    {
        [$lgas, $wards] = $this->collate($records);

        $this->assertCoversJigawa(array_keys($lgas));

        return DB::transaction(fn (): DivisionLoadResult => $this->persist($lgas, $wards));
    }

    /**
     * Groups the flat rows into LGAs and their wards, rejecting internal contradictions.
     *
     * @param  list<array<string, string>>  $records
     * @return array{0: array<string, string>, 1: array<string, array<string, string>>}
     *                                                                                  [lga code => lga name, lga code => [ward code => ward name]]
     *
     * @throws ReferenceDatasetException
     */
    private function collate(array $records): array
    {
        /** @var array<string, string> $lgas */
        $lgas = [];
        /** @var array<string, array<string, string>> $wards */
        $wards = [];

        foreach ($records as $record) {
            $lgaName = trim($record['lga_name'] ?? '');
            if ($lgaName === '') {
                continue; // a blank trailing row, not a data error
            }

            $lgaCode = $this->slug($record['lga_code'] ?? '') ?: $this->slug($lgaName);
            $lgas[$lgaCode] ??= $lgaName;
            $wards[$lgaCode] ??= [];

            $wardName = trim($record['ward_name'] ?? '');
            if ($wardName === '') {
                continue; // an LGA row carrying no ward — kept, with zero wards
            }

            $wardCode = $this->slug($record['ward_code'] ?? '') ?: $this->slug($wardName);

            // The same ward listed twice is fine; the same code with a different name
            // is a contradiction the loader must not silently resolve.
            $existing = $wards[$lgaCode][$wardCode] ?? null;
            if ($existing !== null && $existing !== $wardName) {
                throw ReferenceDatasetException::conflictingWard($lgaCode, $wardCode, $existing, $wardName);
            }

            $wards[$lgaCode][$wardCode] = $wardName;
        }

        return [$lgas, $wards];
    }

    /**
     * The dataset must describe Jigawa, and all of it.
     *
     * @param  list<string>  $codes
     *
     * @throws ReferenceDatasetException
     */
    private function assertCoversJigawa(array $codes): void
    {
        $expected = array_map(fn (LgaEnum $lga): string => $lga->value, LgaEnum::cases());

        $unknown = array_values(array_diff($codes, $expected));
        if ($unknown !== []) {
            throw ReferenceDatasetException::unknownLgas($unknown);
        }

        $missing = array_values(array_diff($expected, $codes));
        if ($missing !== []) {
            throw ReferenceDatasetException::incompleteLgas($missing);
        }
    }

    /**
     * @param  array<string, string>  $lgas
     * @param  array<string, array<string, string>>  $wards
     */
    private function persist(array $lgas, array $wards): DivisionLoadResult
    {
        $lgasCreated = $lgasUpdated = $wardsCreated = $wardsUpdated = 0;
        $wardsPerLga = [];
        $seen = [];

        foreach ($lgas as $code => $name) {
            $lga = Lga::query()->firstOrNew(['code' => $code]);
            $exists = $lga->exists;
            $lga->name = $name;
            $lga->save();

            $exists ? $lgasUpdated++ : $lgasCreated++;

            $wardsPerLga[$code] = count($wards[$code] ?? []);

            foreach ($wards[$code] ?? [] as $wardCode => $wardName) {
                $ward = Ward::query()->firstOrNew(['lga_id' => $lga->id, 'code' => $wardCode]);
                $wardExists = $ward->exists;
                $ward->name = $wardName;
                $ward->save();

                $wardExists ? $wardsUpdated++ : $wardsCreated++;
                $seen[] = "{$code}/{$wardCode}";
            }
        }

        $this->cache->flush();

        return new DivisionLoadResult(
            lgasCreated: $lgasCreated,
            lgasUpdated: $lgasUpdated,
            wardsCreated: $wardsCreated,
            wardsUpdated: $wardsUpdated,
            wardsPerLga: $wardsPerLga,
            staleWards: $this->staleWards($seen),
            lgasWithoutWards: array_keys(array_filter($wardsPerLga, fn (int $n): bool => $n === 0)),
        );
    }

    /**
     * Wards already stored that this dataset does not mention.
     *
     * They are REPORTED, never deleted. Deleting silently would destroy rows that a
     * later step will have beneficiaries pointing at, and a dataset that merely omits
     * a ward is not the same claim as a dataset that retires one. The maintainer sees
     * the drift and decides.
     *
     * @param  list<string>  $seen
     * @return list<string>
     */
    private function staleWards(array $seen): array
    {
        // Joined and concatenated in PHP rather than SQL: string concatenation is the
        // one place sqlite and pgsql syntax diverge, and this runs once per load.
        $stored = DB::table('wards')
            ->join('lgas', 'lgas.id', '=', 'wards.lga_id')
            ->select('lgas.code as lga_code', 'wards.code as ward_code')
            ->get()
            ->map(fn (object $row): string => $row->lga_code.'/'.$row->ward_code)
            ->all();

        return array_values(array_diff($stored, $seen));
    }

    /**
     * @return list<array<string, string>>
     *
     * @throws ReferenceDatasetException
     */
    private function parseCsv(string $contents, string $path): array
    {
        $handle = fopen('php://memory', 'r+');
        if ($handle === false) {
            throw ReferenceDatasetException::unreadable($path);
        }

        fwrite($handle, $this->stripBom($contents));
        rewind($handle);

        $header = fgetcsv($handle);
        if (! is_array($header)) {
            throw ReferenceDatasetException::empty($path);
        }

        $columns = array_map(
            fn ($value): string => Str::of((string) $value)->trim()->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->value(),
            $header,
        );

        $missing = array_values(array_diff(self::REQUIRED_COLUMNS, $columns));
        if ($missing !== []) {
            fclose($handle);
            throw ReferenceDatasetException::missingColumns($missing, implode(', ', $columns));
        }

        $records = [];
        while (($row = fgetcsv($handle)) !== false) {
            // fgetcsv yields [null] for a blank line, and a row of bare commas is
            // equally empty — skip both rather than manufacturing an empty record.
            if (array_filter($row, fn (?string $value): bool => $value !== null && trim($value) !== '') === []) {
                continue;
            }

            $record = [];
            foreach ($columns as $index => $column) {
                $record[$column] = (string) ($row[$index] ?? '');
            }
            $records[] = $record;
        }

        fclose($handle);

        return $records;
    }

    /**
     * @return list<array<string, string>>
     *
     * @throws ReferenceDatasetException
     */
    private function parseJson(string $contents, string $path): array
    {
        $decoded = json_decode($this->stripBom($contents), true);

        if (! is_array($decoded)) {
            throw ReferenceDatasetException::malformed($path, 'not valid JSON.');
        }

        // Allow a wrapper key so an export like {"lgas": [...]} works unedited.
        foreach (['lgas', 'data', 'records'] as $wrapper) {
            if (isset($decoded[$wrapper]) && is_array($decoded[$wrapper])) {
                $decoded = $decoded[$wrapper];
                break;
            }
        }

        $records = [];
        foreach ($decoded as $entry) {
            if (! is_array($entry)) {
                throw ReferenceDatasetException::malformed($path, 'expected a list of objects.');
            }

            // Nested shape: { name|lga_name, code?, wards: [ {name|ward_name, code?} ] }
            if (isset($entry['wards']) && is_array($entry['wards'])) {
                $lgaName = (string) ($entry['lga_name'] ?? $entry['name'] ?? '');
                $lgaCode = (string) ($entry['lga_code'] ?? $entry['code'] ?? '');

                if ($entry['wards'] === []) {
                    $records[] = ['lga_name' => $lgaName, 'lga_code' => $lgaCode, 'ward_name' => '', 'ward_code' => ''];
                }

                foreach ($entry['wards'] as $ward) {
                    $records[] = [
                        'lga_name' => $lgaName,
                        'lga_code' => $lgaCode,
                        'ward_name' => is_array($ward) ? (string) ($ward['ward_name'] ?? $ward['name'] ?? '') : (string) $ward,
                        'ward_code' => is_array($ward) ? (string) ($ward['ward_code'] ?? $ward['code'] ?? '') : '',
                    ];
                }

                continue;
            }

            // Flat shape — same keys as the CSV.
            $records[] = [
                'lga_name' => (string) ($entry['lga_name'] ?? ''),
                'lga_code' => (string) ($entry['lga_code'] ?? ''),
                'ward_name' => (string) ($entry['ward_name'] ?? ''),
                'ward_code' => (string) ($entry['ward_code'] ?? ''),
            ];
        }

        if ($records !== [] && ! array_filter($records, fn (array $r): bool => trim($r['lga_name']) !== '')) {
            throw ReferenceDatasetException::missingColumns(['lga_name'], 'none of the objects carried an LGA name');
        }

        return $records;
    }

    private function stripBom(string $contents): string
    {
        return str_starts_with($contents, "\xEF\xBB\xBF") ? substr($contents, 3) : $contents;
    }

    /** The registry's slug, so codes match the Lga enum and geo_boundaries. */
    private function slug(string $value): string
    {
        return Str::of($value)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->value();
    }
}
