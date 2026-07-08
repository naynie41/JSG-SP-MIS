<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Jobs/RefreshDashboardSnapshots.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Jobs\RefreshDashboardSnapshots
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-680f96566e406297a6d1c7e73dd4a9c9783fa95fc35a26a790bde03c29912537',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Jobs\\RefreshDashboardSnapshots',
        'filename' => '/var/www/html/app/Domain/Reporting/Jobs/RefreshDashboardSnapshots.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Jobs',
    'name' => 'App\\Domain\\Reporting\\Jobs\\RefreshDashboardSnapshots',
    'shortName' => 'RefreshDashboardSnapshots',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled recompute of the dashboard summary snapshots (PRD FR-RPT-01/02). Runs on
 * the queue so the aggregation never happens in a request. Keeps every scope\'s
 * snapshot fresh (freshness target: real-time / within 24h, §14).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 27,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Bus\\Queueable',
      3 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'snapshots' => 
          array (
            'name' => 'snapshots',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Services\\DashboardSnapshotService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 28,
            'endColumn' => 62,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Jobs',
        'declaringClassName' => 'App\\Domain\\Reporting\\Jobs\\RefreshDashboardSnapshots',
        'implementingClassName' => 'App\\Domain\\Reporting\\Jobs\\RefreshDashboardSnapshots',
        'currentClassName' => 'App\\Domain\\Reporting\\Jobs\\RefreshDashboardSnapshots',
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