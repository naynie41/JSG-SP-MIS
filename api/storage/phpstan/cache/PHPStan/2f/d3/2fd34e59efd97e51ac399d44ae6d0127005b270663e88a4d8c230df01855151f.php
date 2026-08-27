<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Sync/Sources/SyncSourceResolver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Sources\SyncSourceResolver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ce38637bde5ea6faf12bd30a55dd63216a39fbdf87ad2ec7b358196a77de9ce4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'filename' => '/var/www/html/app/Domain/Sync/Sources/SyncSourceResolver.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Sources',
    'name' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
    'shortName' => 'SyncSourceResolver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Resolves the {@see SyncSource} client for a connector. Today every connector uses
 * the {@see MockSyncSource}; when a real SOCU / government-system endpoint is
 * provisioned, register its client here keyed by the connector\'s source or
 * `credentials_ref` (credentials from env) — the engine is untouched.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 25,
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
      'mock' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'implementingClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'name' => 'mock',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 33,
        'endColumn' => 69,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'mock' => 
          array (
            'name' => 'mock',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Sync\\Sources\\MockSyncSource',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 33,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 73,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Sources',
        'declaringClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'implementingClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'currentClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'aliasName' => NULL,
      ),
      'for' => 
      array (
        'name' => 'for',
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
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 25,
            'endColumn' => 48,
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
            'name' => 'App\\Domain\\Sync\\Sources\\SyncSource',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Sources',
        'declaringClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'implementingClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
        'currentClassName' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
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