<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Imports\SeedJigawaLgas.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Imports\SeedJigawaLgas
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b0677e08c2b7092621415ce2eacf08ac8525bcdb646990f417581c65d47e1a26',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Imports/SeedJigawaLgas.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Imports',
    'name' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
    'shortName' => 'SeedJigawaLgas',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Seeds the 27 Jigawa LGAs — and NO wards — from {@see LgaEnum}:
 *
 *   php artisan reference:seed-lgas
 *
 * This is NOT a substitute for `reference:load-divisions`. It exists because the LGA list
 * and the ward list have different provenance:
 *
 *  - The 27 LGAs are already committed, authoritative reference data. They are the values
 *    `beneficiaries.lga` is validated against (FR-REG-04/05, a locked decision), and
 *    `AdministrativeDivisionLoader` checks supplied files against this same enum. Writing
 *    them into `lgas` copies a fact the repository already asserts.
 *  - Ward names are NOT in this repository and are never generated. They come only from a
 *    maintainer-supplied dataset.
 *
 * So this command makes the legitimate intermediate state reachable: LGAs known, wards not
 * yet supplied. That state is what the activity location set needs in order to record
 * whole-LGA coverage, and what the activity-location backfill needs in order to resolve
 * existing LGA values instead of refusing to run.
 *
 * Idempotent. Never touches wards. Once a real dataset arrives,
 * `reference:load-divisions` updates these rows in place (it matches on the same `code`)
 * and adds the wards.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'reference:seed-lgas\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 57,
            'startFilePos' => 1539,
            'endTokenPos' => 57,
            'endFilePos' => 1559,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Seed the 27 Jigawa LGAs from the committed enum (no wards — those need a dataset)\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 66,
            'startFilePos' => 1592,
            'endTokenPos' => 66,
            'endFilePos' => 1676,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 115,
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
      'handle' => 
      array (
        'name' => 'handle',
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 28,
            'endColumn' => 52,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 42,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\SeedJigawaLgas',
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