<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Models/DashboardSnapshot.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Models\DashboardSnapshot
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-55dbd4dda223505288adfe32cbb8c8e78dddcee87b559575d2955b5f6084bb20',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'filename' => '/var/www/html/app/Domain/Reporting/Models/DashboardSnapshot.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Models',
    'name' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
    'shortName' => 'DashboardSnapshot',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A precomputed dashboard metric payload for one resolved scope (PRD FR-RPT-01/02).
 * The request path reads this summary row instead of scanning the raw tables. Not
 * MDA-scoped as a model — access is mediated by the resolved scope (the caller only
 * ever reads the snapshot for their own scope) and the payload is aggregate-only.
 *
 * @property string $id
 * @property string $scope_key
 * @property string $scope_kind
 * @property string|null $scope_label
 * @property array<string, mixed> $metrics
 * @property Carbon $computed_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
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
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'dashboard_snapshots\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 53,
            'startFilePos' => 829,
            'endTokenPos' => 53,
            'endFilePos' => 849,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'scope_key\', \'scope_kind\', \'scope_label\', \'metrics\', \'computed_at\']',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 39,
            'startTokenPos' => 64,
            'startFilePos' => 920,
            'endTokenPos' => 81,
            'endFilePos' => 1034,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 39,
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
        'startLine' => 44,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\DashboardSnapshot',
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