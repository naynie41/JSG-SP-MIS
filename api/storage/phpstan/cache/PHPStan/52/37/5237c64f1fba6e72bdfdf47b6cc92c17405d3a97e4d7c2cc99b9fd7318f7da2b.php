<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Gis/LoadGeoBoundaries.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Gis\LoadGeoBoundaries
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-66b41c1dc83fbbf0107817c81a1f6a1015fcf40dbb64c49e09f734cae67a100a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'filename' => '/var/www/html/app/Domain/Reporting/Gis/LoadGeoBoundaries.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Gis',
    'name' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
    'shortName' => 'LoadGeoBoundaries',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Loads Jigawa LGA/Ward boundaries from a GeoJSON file (PRD FR-GIS-01):
 *
 *   php artisan gis:load-boundaries lga  storage/app/boundaries/jigawa-lga.geojson
 *   php artisan gis:load-boundaries ward storage/app/boundaries/jigawa-ward.geojson
 *
 * Idempotent (upserts by level + code). LGA first; wards are additive. See
 * {@see BoundaryLoader} for the expected GeoJSON properties.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 52,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'gis:load-boundaries {level : lga or ward} {file : path to a GeoJSON FeatureCollection}\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 38,
            'startFilePos' => 566,
            'endTokenPos' => 38,
            'endFilePos' => 653,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 116,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Load LGA/Ward administrative boundaries from a GeoJSON file into PostGIS\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 47,
            'startFilePos' => 686,
            'endTokenPos' => 47,
            'endFilePos' => 759,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 104,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'loader' => 
          array (
            'name' => 'loader',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Gis\\BoundaryLoader',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 28,
            'endColumn' => 49,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Gis',
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
        'currentClassName' => 'App\\Domain\\Reporting\\Gis\\LoadGeoBoundaries',
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