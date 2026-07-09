<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Gis/GeoBoundary.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Gis\GeoBoundary
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b6efd1960ce962dcacecdc2e500223aa7c0cc0a54c17feeb3eac1665baffefca',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'filename' => '/var/www/html/app/Domain/Reporting/Gis/GeoBoundary.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Gis',
    'name' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
    'shortName' => 'GeoBoundary',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An administrative boundary (PRD FR-GIS-01): an LGA (level 2) or Ward (level 3). The
 * GeoJSON `geometry` drives the choropleth; `code` is the slug used to join coverage
 * aggregates to the shape.
 *
 * @property string $id
 * @property string $level
 * @property string $code
 * @property string $name
 * @property string|null $parent_code
 * @property array<string, mixed> $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 51,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
      'LEVEL_LGA' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'name' => 'LEVEL_LGA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'lga\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 55,
            'startFilePos' => 747,
            'endTokenPos' => 55,
            'endFilePos' => 751,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'LEVEL_WARD' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'name' => 'LEVEL_WARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ward\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 66,
            'startFilePos' => 785,
            'endTokenPos' => 66,
            'endFilePos' => 790,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'geo_boundaries\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 75,
            'startFilePos' => 817,
            'endTokenPos' => 75,
            'endFilePos' => 832,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'level\', \'code\', \'name\', \'parent_code\', \'geometry\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 40,
            'startTokenPos' => 86,
            'startFilePos' => 903,
            'endTokenPos' => 103,
            'endFilePos' => 969,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'casts' => 
      array (
        'name' => 'casts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, string>
 */',
        'startLine' => 45,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reporting\\Gis',
        'declaringClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'implementingClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
        'currentClassName' => 'App\\Domain\\Reporting\\Gis\\GeoBoundary',
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