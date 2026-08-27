<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Sync/Sources/SyncSource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Sources\SyncSource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c60175eaf9773e628baa42e93a3301f87575ff96f51da1744510ffbcd1e649b4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Sources\\SyncSource',
        'filename' => '/var/www/html/app/Domain/Sync/Sources/SyncSource.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Sources',
    'name' => 'App\\Domain\\Sync\\Sources\\SyncSource',
    'shortName' => 'SyncSource',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Pulls RAW records from an external/source system for a connector (FR-DSH-02). Each
 * raw record is the source\'s own shape — the connector\'s {@see RegistrationSourceAdapter}
 * maps it onto the canonical schema before the shared import pipeline runs. This is the
 * only seam a real integration (SOCU, a government system) implements; the sync engine,
 * validation, dedup and ownership rules never change.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 22,
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
            'startLine' => 21,
            'endLine' => 21,
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
        'docComment' => '/**
 * @return iterable<int, array<string, mixed>> raw source records
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Sources',
        'declaringClassName' => 'App\\Domain\\Sync\\Sources\\SyncSource',
        'implementingClassName' => 'App\\Domain\\Sync\\Sources\\SyncSource',
        'currentClassName' => 'App\\Domain\\Sync\\Sources\\SyncSource',
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