<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Imports\AdministrativeDivisionLoader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Imports\AdministrativeDivisionLoader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-26aa645e68617114439515b617cc473590d878c09242247d36b40583cae5080a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Imports/AdministrativeDivisionLoader.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Imports',
    'name' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
    'shortName' => 'AdministrativeDivisionLoader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Loads Jigawa LGAs and wards from an authoritative dataset FILE supplied by the
 * maintainer (HDX / GRID3 / State administrative records).
 *
 * The one rule this class exists to enforce: **it never invents data**. There is no
 * fallback list, no partial seed, no placeholder. If the file is absent, unreadable,
 * empty, or not credibly a Jigawa dataset, it throws
 * {@see ReferenceDatasetException} and writes nothing.
 *
 * Accepted shapes (see README):
 *   CSV  — header row with lga_name, ward_name (+ optional lga_code, ward_code)
 *   JSON — a flat list of the same keys, or nested: [{ name, code?, wards: [...] }]
 *
 * Codes are slugged from names when not supplied, using the same slug the registry
 * already uses ("Birnin Kudu" → birnin_kudu), so `lgas.code` lines up with both the
 * {@see LgaEnum} validation values and `geo_boundaries.code`.
 *
 * VALIDATION HAPPENS BEFORE ANY WRITE, so an error message can honestly say nothing
 * was loaded. Two checks reject a file that would otherwise look fine:
 *
 *  - an LGA the state does not have → wrong file (usually national data, unfiltered);
 *  - fewer than all 27 LGAs → a partial dataset, which is the dangerous case: it
 *    yields a lookup table that is silently missing real places.
 *
 * Ward counts are NOT checked against an expected total. Jigawa\'s ward count is
 * commonly cited as ~287, but that figure is not a fact this code is entitled to
 * enforce — the supplied file is the authority, and the loader reports what it found
 * so the maintainer can verify it.
 *
 * Idempotent: upserts by `code` / `(lga_id, code)`, so re-running with a corrected
 * file updates in place.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 46,
    'endLine' => 358,
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
      'REQUIRED_COLUMNS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'name' => 'REQUIRED_COLUMNS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'lga_name\', \'ward_name\']',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 65,
            'startFilePos' => 2069,
            'endTokenPos' => 70,
            'endFilePos' => 2093,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
    ),
    'immediateProperties' => 
    array (
      'cache' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
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
        'startLine' => 50,
        'endLine' => 50,
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
            'startLine' => 50,
            'endLine' => 50,
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
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'loadFromFile' => 
      array (
        'name' => 'loadFromFile',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 55,
                'endLine' => 55,
                'startTokenPos' => 106,
                'startFilePos' => 2283,
                'endTokenPos' => 106,
                'endFilePos' => 2286,
              ),
            ),
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 34,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reference\\Imports\\DivisionLoadResult',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws ReferenceDatasetException
 */',
        'startLine' => 55,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'load' => 
      array (
        'name' => 'load',
        'parameters' => 
        array (
          'records' => 
          array (
            'name' => 'records',
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
            'startLine' => 87,
            'endLine' => 87,
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
            'name' => 'App\\Domain\\Reference\\Imports\\DivisionLoadResult',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  list<array<string, string>>  $records  each with lga_name/ward_name (+ optional codes)
 *
 * @throws ReferenceDatasetException
 */',
        'startLine' => 87,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'collate' => 
      array (
        'name' => 'collate',
        'parameters' => 
        array (
          'records' => 
          array (
            'name' => 'records',
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
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 30,
            'endColumn' => 43,
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
 * Groups the flat rows into LGAs and their wards, rejecting internal contradictions.
 *
 * @param  list<array<string, string>>  $records
 * @return array{0: array<string, string>, 1: array<string, array<string, string>>}
 *                                                                                  [lga code => lga name, lga code => [ward code => ward name]]
 *
 * @throws ReferenceDatasetException
 */',
        'startLine' => 105,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'assertCoversJigawa' => 
      array (
        'name' => 'assertCoversJigawa',
        'parameters' => 
        array (
          'codes' => 
          array (
            'name' => 'codes',
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
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 41,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The dataset must describe Jigawa, and all of it.
 *
 * @param  list<string>  $codes
 *
 * @throws ReferenceDatasetException
 */',
        'startLine' => 149,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'persist' => 
      array (
        'name' => 'persist',
        'parameters' => 
        array (
          'lgas' => 
          array (
            'name' => 'lgas',
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
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'wards' => 
          array (
            'name' => 'wards',
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
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 43,
            'endColumn' => 54,
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
            'name' => 'App\\Domain\\Reference\\Imports\\DivisionLoadResult',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, string>  $lgas
 * @param  array<string, array<string, string>>  $wards
 */',
        'startLine' => 168,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'staleWards' => 
      array (
        'name' => 'staleWards',
        'parameters' => 
        array (
          'seen' => 
          array (
            'name' => 'seen',
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
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 33,
            'endColumn' => 43,
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
 * Wards already stored that this dataset does not mention.
 *
 * They are REPORTED, never deleted. Deleting silently would destroy rows that a
 * later step will have beneficiaries pointing at, and a dataset that merely omits
 * a ward is not the same claim as a dataset that retires one. The maintainer sees
 * the drift and decides.
 *
 * @param  list<string>  $seen
 * @return list<string>
 */',
        'startLine' => 219,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'parseCsv' => 
      array (
        'name' => 'parseCsv',
        'parameters' => 
        array (
          'contents' => 
          array (
            'name' => 'contents',
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
            'startLine' => 238,
            'endLine' => 238,
            'startColumn' => 31,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 238,
            'endLine' => 238,
            'startColumn' => 49,
            'endColumn' => 60,
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
 * @return list<array<string, string>>
 *
 * @throws ReferenceDatasetException
 */',
        'startLine' => 238,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'parseJson' => 
      array (
        'name' => 'parseJson',
        'parameters' => 
        array (
          'contents' => 
          array (
            'name' => 'contents',
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
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 32,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 50,
            'endColumn' => 61,
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
 * @return list<array<string, string>>
 *
 * @throws ReferenceDatasetException
 */',
        'startLine' => 289,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'stripBom' => 
      array (
        'name' => 'stripBom',
        'parameters' => 
        array (
          'contents' => 
          array (
            'name' => 'contents',
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
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 31,
            'endColumn' => 46,
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
        'startLine' => 348,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'aliasName' => NULL,
      ),
      'slug' => 
      array (
        'name' => 'slug',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 354,
            'endLine' => 354,
            'startColumn' => 27,
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
        'docComment' => '/** The registry\'s slug, so codes match the Lga enum and geo_boundaries. */',
        'startLine' => 354,
        'endLine' => 357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
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