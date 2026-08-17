<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Reference\AdministrativeDivisionController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Reference\AdministrativeDivisionController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ae98774035ded4d707158d5711fad61b8d2d046653c1fc073f210ed951c45850',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Controllers/Api/V1/Reference/AdministrativeDivisionController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reference',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
    'shortName' => 'AdministrativeDivisionController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * READ-ONLY LGA/Ward reference lookups (the cascading selector).
 *
 * There is no write side by design: this data comes from an authoritative dataset
 * loaded by a maintainer (`reference:load-divisions`), not from user input. An API
 * that let a user add a ward would let the lookup table drift away from the source it
 * is supposed to reproduce — the same reasoning that makes registry ingestion
 * bulk/source-only (CLAUDE.md §8).
 *
 * Both responses are cached; see {@see ReferenceDataCache} for the invalidation model.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 57,
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
      'cache' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'name' => 'cache',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 33,
        'endColumn' => 74,
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
          'cache' => 
          array (
            'name' => 'cache',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reference\\Services\\ReferenceDataCache',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 33,
            'endColumn' => 74,
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
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reference',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'aliasName' => NULL,
      ),
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
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * All 27 LGAs, with the number of wards loaded for each.
 *
 * `ward_count` is what tells a client whether ward data is present at all: a fresh
 * install has LGAs from the dataset and zero wards until one is supplied.
 */',
        'startLine' => 34,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reference',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'aliasName' => NULL,
      ),
      'wards' => 
      array (
        'name' => 'wards',
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
                'name' => 'App\\Http\\Requests\\Reference\\ListWardsRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 27,
            'endColumn' => 51,
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
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The wards of one LGA — step two of the cascade.
 */',
        'startLine' => 47,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reference',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reference\\AdministrativeDivisionController',
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