<?php declare(strict_types = 1);

// osfsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\database\seeders\PartnerDemoSeeder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Seeders\PartnerDemoSeeder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c3a80e4ae6f786db89cf3ed868d8ea575426d4f80dd76587ec14f5a826ca5cb6-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Seeders\\PartnerDemoSeeder',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/database/seeders/PartnerDemoSeeder.php',
      ),
    ),
    'namespace' => 'Database\\Seeders',
    'name' => 'Database\\Seeders\\PartnerDemoSeeder',
    'shortName' => 'PartnerDemoSeeder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Phase 6P Funding-Partner demo data (PRD FR-RPT-02) — enough synthetic, activity-precise
 * data that EVERY partner tab renders meaningfully: the overview, per-programme results
 * (all four delivery statuses), the funded-cohort registry (with a reduced funnel), the
 * coordination landscape + PROGRAMME OVERLAP, and the investment/coverage map.
 *
 * Two development partners (World Bank + UNICEF) fund OVERLAPPING programmes in a SHARED
 * LGA through DIFFERENT MDAs (so the overlap detector fires), with committed budgets,
 * delivered benefits across historical periods, varied demographics (gender/age/NIN),
 * households, and enrolled-but-not-yet-served beneficiaries (so the funnel narrows).
 *
 * LOCAL/STAGING ONLY — never real PII (all factory-generated), never production. Idempotent.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 154,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Seeder',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DEMO_PASSWORD' => 
      array (
        'declaringClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'implementingClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'name' => 'DEMO_PASSWORD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'partner-demo-1234\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 112,
            'startFilePos' => 1649,
            'endTokenPos' => 112,
            'endFilePos' => 1667,
          ),
        ),
        'docComment' => '/** Demo sign-in password for the seeded partner accounts (LOCAL/STAGING ONLY). */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'run' => 
      array (
        'name' => 'run',
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
        'startLine' => 41,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Seeders',
        'declaringClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'implementingClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'currentClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'aliasName' => NULL,
      ),
      'fund' => 
      array (
        'name' => 'fund',
        'parameters' => 
        array (
          'partner' => 
          array (
            'name' => 'partner',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'programme' => 
          array (
            'name' => 'programme',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Programme',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 42,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'mda' => 
          array (
            'name' => 'mda',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\Mda',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 64,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'lga' => 
          array (
            'name' => 'lga',
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
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 74,
            'endColumn' => 84,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'budget' => 
          array (
            'name' => 'budget',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 87,
            'endColumn' => 97,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'target' => 
          array (
            'name' => 'target',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 100,
            'endColumn' => 110,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'reached' => 
          array (
            'name' => 'reached',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 113,
            'endColumn' => 124,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
          'endsOn' => 
          array (
            'name' => 'endsOn',
            'default' => NULL,
            'type' => 
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
                      'name' => 'string',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 127,
            'endColumn' => 141,
            'parameterIndex' => 7,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 144,
            'endColumn' => 155,
            'parameterIndex' => 8,
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
        'docComment' => '/**
 * Fund an activity (committed budget) and deliver benefits to `$reached` distinct
 * beneficiaries across recent months, with varied demographics + a couple of
 * enrolled-but-not-yet-served beneficiaries so the reduced funnel narrows.
 *
 * @param  list<string>  $types  benefit types to cycle through
 */',
        'startLine' => 98,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Database\\Seeders',
        'declaringClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'implementingClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
        'currentClassName' => 'Database\\Seeders\\PartnerDemoSeeder',
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