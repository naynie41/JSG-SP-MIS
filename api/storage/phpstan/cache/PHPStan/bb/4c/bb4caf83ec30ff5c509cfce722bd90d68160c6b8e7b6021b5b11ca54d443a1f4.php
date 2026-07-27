<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Sync\Jobs\RunDueSyncConnectors.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Jobs\RunDueSyncConnectors
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-fa22d7ca4a2803b7270df7f08104a07d6b23de587b8b497e6c60aef98cfd7bbc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Jobs\\RunDueSyncConnectors',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Sync/Jobs/RunDueSyncConnectors.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Jobs',
    'name' => 'App\\Domain\\Sync\\Jobs\\RunDueSyncConnectors',
    'shortName' => 'RunDueSyncConnectors',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled fan-out (FR-DSH-02): on each scheduler tick, dispatch a {@see
 * RunSyncConnector} for every enabled connector. The scheduler cadence in
 * bootstrap/app.php IS the sync frequency; RunSyncConnector\'s uniqueness prevents a
 * new tick from overlapping a still-running sync.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 32,
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
        'startLine' => 25,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Jobs',
        'declaringClassName' => 'App\\Domain\\Sync\\Jobs\\RunDueSyncConnectors',
        'implementingClassName' => 'App\\Domain\\Sync\\Jobs\\RunDueSyncConnectors',
        'currentClassName' => 'App\\Domain\\Sync\\Jobs\\RunDueSyncConnectors',
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