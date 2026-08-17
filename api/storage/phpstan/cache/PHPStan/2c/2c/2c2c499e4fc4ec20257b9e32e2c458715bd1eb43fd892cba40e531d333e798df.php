<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Services\ReferenceDataCache.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Services\ReferenceDataCache
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a4ab666aaa3e3f890fc5da3cecc2f89b28aa5ddbf0bc25f88431da6447593331',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Services/ReferenceDataCache.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Services',
    'name' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
    'shortName' => 'ReferenceDataCache',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Caches the administrative-division lookups.
 *
 * These lists are read on nearly every form and filter, are identical for every user
 * (no MDA scoping, no PII), and change only when a maintainer loads a new dataset —
 * the ideal cache shape.
 *
 * Invalidation is by VERSION COUNTER, not by cache tags: the default store is
 * `database` (see config/cache.php), and tags are unsupported there — a tag-based
 * flush would silently do nothing in production while passing in a redis-backed test.
 * Bumping an integer that is baked into every key works on every store, and leaves the
 * stale entries to expire on their own TTL.
 *
 * {@see AdministrativeDivisionLoader} calls flush() after
 * a load, so a re-seed is visible immediately.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 99,
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
      'VERSION_KEY' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'name' => 'VERSION_KEY',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'reference.divisions.version\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 51,
            'startFilePos' => 1065,
            'endTokenPos' => 51,
            'endFilePos' => 1093,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 62,
      ),
      'TTL_SECONDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'name' => 'TTL_SECONDS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '86400',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 64,
            'startFilePos' => 1220,
            'endTokenPos' => 64,
            'endFilePos' => 1224,
          ),
        ),
        'docComment' => '/** Safety net only — a load flushes explicitly, so this rarely decides anything. */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'lgas' => 
      array (
        'name' => 'lgas',
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
 * @return list<array<string, mixed>>
 */',
        'startLine' => 38,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Services',
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'currentClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'aliasName' => NULL,
      ),
      'wardsFor' => 
      array (
        'name' => 'wardsFor',
        'parameters' => 
        array (
          'lgaId' => 
          array (
            'name' => 'lgaId',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 30,
            'endColumn' => 42,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wards of one LGA, ordered by name — the second step of the cascading selector.
 *
 * @return list<array<string, mixed>>
 */',
        'startLine' => 63,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Services',
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'currentClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'aliasName' => NULL,
      ),
      'flush' => 
      array (
        'name' => 'flush',
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
        'docComment' => '/**
 * Invalidate everything by moving the version every key is built from.
 */',
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Services',
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'currentClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'aliasName' => NULL,
      ),
      'version' => 
      array (
        'name' => 'version',
        'parameters' => 
        array (
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
        'startLine' => 90,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Services',
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'currentClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'aliasName' => NULL,
      ),
      'key' => 
      array (
        'name' => 'key',
        'parameters' => 
        array (
          'suffix' => 
          array (
            'name' => 'suffix',
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
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 26,
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
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Services',
        'declaringClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'implementingClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
        'currentClassName' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
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