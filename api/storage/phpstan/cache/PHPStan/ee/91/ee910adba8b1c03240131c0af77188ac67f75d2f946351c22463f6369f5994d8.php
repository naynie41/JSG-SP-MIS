<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Sync\Sources\MockSyncSource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Sources\MockSyncSource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-0224acd4a98371ec01d708b1e64109c5c208eb95592c534023ce2e339cd34004',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Sync/Sources/MockSyncSource.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Sources',
    'name' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
    'shortName' => 'MockSyncSource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A documented STAND-IN source used until a real integration is provisioned. It
 * returns raw records from `config(\'sync.mock_records.<source>\')` so the full sync
 * pipeline (validation → dedup → ownership/provenance → idempotency → conflict) is
 * exercisable end-to-end without a live endpoint.
 *
 * 🔶 REAL ACCESS NEEDED: to sync live SOCU / government-system data, provide the
 * endpoint URL, auth method and credentials (as env, keyed by the connector\'s
 * `credentials_ref`) and the source\'s field names. Then bind a real {@see SyncSource}
 * (e.g. an HTTP client) in {@see SyncSourceResolver} — nothing else changes.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Sync\\Sources\\SyncSource',
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
      'fetch' => 
      array (
        'name' => 'fetch',
        'parameters' => 
        array (
          'connector' => 
          array (
            'name' => 'connector',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Sync\\Models\\SyncConnector',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 27,
            'endColumn' => 50,
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
            'name' => 'iterable',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Sources',
        'declaringClassName' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
        'implementingClassName' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
        'currentClassName' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
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