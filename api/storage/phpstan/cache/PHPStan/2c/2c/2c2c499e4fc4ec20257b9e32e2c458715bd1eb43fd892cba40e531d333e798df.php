<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Services\ReferenceDataCache.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Services\ReferenceDataCache
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b2920f29a2acf4c3973e6a4ac471d31b6cce33f80cbdeb2f76db9518f9a79aa0',
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
    'startLine' => 30,
    'endLine' => 145,
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
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 61,
            'startFilePos' => 1154,
            'endTokenPos' => 61,
            'endFilePos' => 1182,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
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
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 74,
            'startFilePos' => 1309,
            'endTokenPos' => 74,
            'endFilePos' => 1313,
          ),
        ),
        'docComment' => '/** Safety net only — a load flushes explicitly, so this rarely decides anything. */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
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
        'startLine' => 40,
        'endLine' => 58,
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
            'startLine' => 65,
            'endLine' => 65,
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
        'startLine' => 65,
        'endLine' => 82,
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
      'wardKeysByLgaCode' => 
      array (
        'name' => 'wardKeysByLgaCode',
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
 * Every ward name folded to a comparison key, grouped by its LGA\'s code.
 *
 * Import validation asks "is this a real ward?" once per ROW, so it cannot go to the
 * database each time — a 5,000-row file would issue 5,000 queries. This is the whole
 * list in one cached array, keyed by the LGA `code` (which is the `Lga` enum value the
 * beneficiary row normalizes to), so the check is an array lookup.
 *
 * Shape: `[\'birnin_kudu\' => [\'limawa\' => \'Limawa\', …], …]` — key for comparison,
 * value kept for messages that need to name a real ward.
 *
 * @return array<string, array<string, string>>
 */',
        'startLine' => 97,
        'endLine' => 126,
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
        'startLine' => 131,
        'endLine' => 134,
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
        'startLine' => 136,
        'endLine' => 139,
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
            'startLine' => 141,
            'endLine' => 141,
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
        'startLine' => 141,
        'endLine' => 144,
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