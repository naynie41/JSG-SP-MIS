<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Sharing\DataSharingController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Sharing\DataSharingController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-28746063a2a3690ba4d7384c9ff19c0dffc17c7a0627cc07cb1f49ce9074645f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Controllers/Api/V1/Sharing/DataSharingController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
    'shortName' => 'DataSharingController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Data-sharing oversight (FR-DSH-01): who can access what across MDAs, and why.
 *
 * Lists every ACTIVE cross-MDA grant, of both kinds, because they differ in the way
 * that matters to whoever reviews them:
 *
 *  - **Service-Request grant** — per BENEFICIARY. The owner MDA approved one record.
 *  - **Administrative grant** (FR-UAM-03) — per MDA. An administrator opened another
 *    MDA\'s scoped data to a named user, with a reason and an optional expiry. This is
 *    the widest grant in the system; a report that omitted it would answer "who can
 *    access what" wrongly.
 *
 * Each row carries its {@see SharingBasis}, its scope, and whether the consent gate
 * currently makes it EFFECTIVE. Oversight-only (`cross-mda.view`); names only — never
 * raw identifiers.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 169,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Http\\Controllers\\Controller',
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
      'grants' => 
      array (
        'name' => 'grants',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 28,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'guard' => 
          array (
            'name' => 'guard',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Sharing\\DataSharingGuard',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 46,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'aliasName' => NULL,
      ),
      'serviceGrants' => 
      array (
        'name' => 'serviceGrants',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Sharing\\DataSharingGuard',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 36,
            'endColumn' => 58,
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
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Per-beneficiary grants opened by an accepted Service Request.
 *
 * Returned as a BASE collection (`toBase`): mapping an Eloquent collection to
 * arrays keeps the Eloquent class, whose `merge()` expects models and calls
 * `getKey()` on them.
 */',
        'startLine' => 74,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'aliasName' => NULL,
      ),
      'serviceGrantRow' => 
      array (
        'name' => 'serviceGrantRow',
        'parameters' => 
        array (
          'grant' => 
          array (
            'name' => 'grant',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 38,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'guard' => 
          array (
            'name' => 'guard',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Sharing\\DataSharingGuard',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 70,
            'endColumn' => 92,
            'parameterIndex' => 1,
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
 * One service-grant row. The beneficiary relation is eager-loaded and backed by a
 * foreign key, so it is present; the guard is asked for the consent verdict rather
 * than the status being re-interpreted here.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 96,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'aliasName' => NULL,
      ),
      'adminGrants' => 
      array (
        'name' => 'adminGrants',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Whole-MDA administrative grants (FR-UAM-03). These have no single subject, so
 * `beneficiary_id` is null and the consent column reports the GATE rather than one
 * person\'s status — the gate is evaluated per record at read time.
 */',
        'startLine' => 127,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'aliasName' => NULL,
      ),
      'mdaRef' => 
      array (
        'name' => 'mdaRef',
        'parameters' => 
        array (
          'mda' => 
          array (
            'name' => 'mda',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 29,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @param object|null $mda */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sharing',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Sharing\\DataSharingController',
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