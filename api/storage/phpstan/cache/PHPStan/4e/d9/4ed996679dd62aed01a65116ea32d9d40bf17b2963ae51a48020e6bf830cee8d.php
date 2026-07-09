<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Gis/BoundaryLoader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Gis\BoundaryLoader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-10054d0fd82098484831fcb3806a64ab23f547324a7e08dbfbe4faf8909d9b45',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'filename' => '/var/www/html/app/Domain/Reporting/Gis/BoundaryLoader.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Gis',
    'name' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
    'shortName' => 'BoundaryLoader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Loads administrative boundaries from a GeoJSON FeatureCollection (PRD FR-GIS-01).
 * Idempotent — upserts by (level, code). LGA first; wards are additive.
 *
 * Expected GeoJSON: a `FeatureCollection` whose features have a polygon/multipolygon
 * `geometry` and `properties` carrying the admin code + name (and, for wards, the
 * parent LGA). Which property names are read is configured in `config/gis.php`
 * (defaults derive the join `code` from the NAME, slugged to match registry values):
 *
 *   LGA  properties → name (→ code slug), name
 *   Ward properties → name (→ code slug), name, lga_name (→ parent_code slug)
 *
 * On PostgreSQL the PostGIS `geom` column is populated too (heat-map extension point).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 88,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'load' => 
      array (
        'name' => 'load',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 26,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'featureCollection' => 
          array (
            'name' => 'featureCollection',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 41,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $featureCollection
 * @return int number of boundaries loaded
 */',
        'startLine' => 31,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Gis',
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'currentClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'aliasName' => NULL,
      ),
      'slug' => 
      array (
        'name' => 'slug',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Gis',
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'currentClassName' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));