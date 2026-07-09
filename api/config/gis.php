<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | GIS boundary loader (PRD FR-GIS-01)
    |--------------------------------------------------------------------------
    | Which GeoJSON `feature.properties` the boundary loader reads for each admin
    | level. The join `code` is normalised to a lowercase slug (e.g. "Birnin Kudu"
    | → "birnin_kudu") so it matches the registry's LGA/Ward values; by default the
    | slug is derived from the NAME property. If your GeoJSON carries a distinct code
    | that already matches the registry values, point `code_property` at it.
    |
    |   admin level 2 = LGA, admin level 3 = Ward. A ward's `parent_property` links
    |   it to its LGA (also slugged).
    */
    'properties' => [
        'lga' => [
            'code_property' => env('GIS_LGA_CODE_PROPERTY', 'name'),
            'name_property' => env('GIS_LGA_NAME_PROPERTY', 'name'),
        ],
        'ward' => [
            'code_property' => env('GIS_WARD_CODE_PROPERTY', 'name'),
            'name_property' => env('GIS_WARD_NAME_PROPERTY', 'name'),
            'parent_property' => env('GIS_WARD_PARENT_PROPERTY', 'lga_name'),
        ],
    ],
];
