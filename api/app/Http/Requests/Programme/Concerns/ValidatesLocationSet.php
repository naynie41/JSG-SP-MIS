<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme\Concerns;

use App\Domain\Programme\Services\ActivityLocationService;
use Illuminate\Contracts\Validation\Validator;

/**
 * The activity location-set rules, shared by every request that accepts one so the
 * three entry points cannot drift apart.
 *
 * Submitted shape — one entry per LGA:
 *
 *   "locations": [
 *     { "lga_id": "…", "ward_ids": ["…", "…"] },   // specific wards
 *     { "lga_id": "…", "whole_lga": true }          // the whole LGA
 *   ]
 *
 * Enforced here:
 *  - every `lga_id` and `ward_id` exists in the GEO.1 lookups;
 *  - each ward belongs to the LGA it was submitted under (the rule that matters —
 *    ward codes repeat across Jigawa, so a ward id under the wrong LGA is a real and
 *    silent way to mis-target an activity);
 *  - an LGA appears at most once, so its wards are unambiguous;
 *  - `whole_lga` and `ward_ids` are not both given.
 *
 * NOT enforced, deliberately: anything about the beneficiaries uploaded under the
 * activity. The set is a plan, not a constraint on the people.
 */
trait ValidatesLocationSet
{
    /**
     * @return array<string, mixed>
     */
    protected function locationSetRules(): array
    {
        return [
            'locations' => ['sometimes', 'array', 'max:27'], // Jigawa has 27 LGAs
            'locations.*.lga_id' => ['required', 'uuid', 'exists:lgas,id'],
            'locations.*.whole_lga' => ['sometimes', 'boolean'],
            'locations.*.ward_ids' => ['sometimes', 'array'],
            'locations.*.ward_ids.*' => ['uuid', 'exists:wards,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function locationSetMessages(): array
    {
        return [
            'locations.*.lga_id.exists' => 'That LGA is not in the reference data.',
            'locations.*.ward_ids.*.exists' => 'That ward is not in the reference data.',
        ];
    }

    /**
     * Cross-field checks that need the database, run only once the per-field rules
     * above have passed — otherwise a nonexistent ward id would produce two errors
     * saying different things.
     */
    protected function validateLocationSet(Validator $validator): void
    {
        $locations = $this->input('locations');

        if (! is_array($locations) || $locations === [] || $validator->errors()->isNotEmpty()) {
            return;
        }

        /** @var list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}> $set */
        $set = $locations;
        $service = app(ActivityLocationService::class);

        foreach ($set as $index => $entry) {
            if (($entry['whole_lga'] ?? false) === true && ($entry['ward_ids'] ?? []) !== []) {
                $validator->errors()->add(
                    "locations.{$index}.whole_lga",
                    'Choose either the whole LGA or specific wards, not both.',
                );
            }
        }

        $duplicates = $service->duplicateLgas($set);
        if ($duplicates !== []) {
            $names = $service->lgaNames($duplicates);
            $validator->errors()->add(
                'locations',
                'Each LGA may appear only once — list all of its wards in a single entry. Repeated: '
                .implode(', ', array_values($names)).'.',
            );
        }

        // The rule this whole trait exists for.
        $misplaced = $service->misplacedWards($set);
        foreach ($misplaced as $wardId => $lgaId) {
            foreach ($set as $index => $entry) {
                $position = array_search($wardId, $entry['ward_ids'] ?? [], true);
                if ($entry['lga_id'] === $lgaId && $position !== false) {
                    $validator->errors()->add(
                        "locations.{$index}.ward_ids.{$position}",
                        'That ward does not belong to the selected LGA.',
                    );
                }
            }
        }
    }
}
