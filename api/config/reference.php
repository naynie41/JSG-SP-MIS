<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Administrative divisions (LGA / Ward reference data)
    |--------------------------------------------------------------------------
    | Path to the AUTHORITATIVE dataset. The seeder and `reference:load-divisions`
    | read this file and fail loudly when it is absent — ward data is never
    | generated, guessed, or completed from memory.
    |
    | The default is the maintainer-supplied dataset COMMITTED to the repo, so a
    | fresh clone has the same wards as every other environment. It previously
    | pointed inside `storage/app`, which is gitignored: the file existed on one
    | machine and nowhere else, and ward pickers came up empty everywhere else.
    | Point `REFERENCE_DIVISIONS_PATH` at a newer file to supersede it.
    |
    | Expected columns are documented in app/Domain/Reference/README.md.
    */
    'divisions' => [
        'path' => env(
            'REFERENCE_DIVISIONS_PATH',
            database_path('data/jigawa-administrative-divisions.csv'),
        ),
    ],
];
