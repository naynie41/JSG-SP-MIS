<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Administrative divisions (LGA / Ward reference data)
    |--------------------------------------------------------------------------
    | Path to the AUTHORITATIVE dataset the maintainer supplies (HDX / GRID3 /
    | State administrative records). The seeder and `reference:load-divisions`
    | read this file and fail loudly when it is absent — no ward data ships in
    | this repository, and none is generated.
    |
    | Expected columns are documented in app/Domain/Reference/README.md.
    */
    'divisions' => [
        'path' => env(
            'REFERENCE_DIVISIONS_PATH',
            storage_path('app/reference/jigawa-administrative-divisions.csv'),
        ),
    ],
];
