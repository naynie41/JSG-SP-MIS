<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/ColumnMapper.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ColumnMapper
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-cfcb8274e7202a9b977c4d04e425b3ab4f5c297c3ca8b9576d835d577a3cd886',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'filename' => '/var/www/html/app/Domain/Registry/Imports/ColumnMapper.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports',
    'name' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
    'shortName' => 'ColumnMapper',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Detects a source file\'s columns, SUGGESTS canonical mappings, and applies a confirmed
 * mapping to produce canonical rows (CLAUDE.md §11, PRD v1.7).
 *
 * Suggestions are advisory and nothing more. The whole point of this layer is that a
 * machine guess about which column is the NIN never reaches the duplicate cascade
 * unreviewed — a wrong identity mapping does not fail loudly, it silently declares two
 * different citizens to be the same person.
 *
 * Pure: no database, no clock. Given the same headers it always proposes the same
 * mapping, so what an officer is asked to confirm does not drift between uploads.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 272,
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
      'ALIASES' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'name' => 'ALIASES',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    // One name column, split into first/last at apply() time (see NameSplitter).
    \'full_name\' => [\'full_name\', \'fullname\', \'full_names\', \'name\', \'names\', \'beneficiary_name\'],
    \'first_name\' => [\'first_name\', \'firstname\', \'given_name\', \'givenname\', \'fname\', \'forename\'],
    \'middle_name\' => [\'middle_name\', \'middlename\', \'other_name\', \'othername\', \'other_names\'],
    \'last_name\' => [\'last_name\', \'lastname\', \'surname\', \'family_name\', \'familyname\', \'lname\'],
    \'nin\' => [\'nin\', \'national_identification_number\', \'nin_number\', \'national_id\'],
    \'bvn\' => [\'bvn\', \'bank_verification_number\', \'bvn_number\'],
    \'phone\' => [\'phone\', \'phone_number\', \'phone_no\', \'msisdn\', \'mobile\', \'mobile_number\', \'telephone\'],
    \'date_of_birth\' => [\'date_of_birth\', \'dob\', \'birth_date\', \'birthdate\', \'date_of_birth_dd_mm_yyyy\'],
    \'gender\' => [\'gender\', \'sex\'],
    \'address\' => [\'address\', \'home_address\', \'residential_address\'],
    // "LG" and "Council" are both how MDAs write the Local Government.
    \'lga\' => [\'lga\', \'lg\', \'local_government\', \'local_government_area\', \'council\'],
    \'ward\' => [\'ward\', \'ward_name\'],
    \'household_ref\' => [\'household_id\', \'household_ref\', \'household_code\', \'household\', \'hh_id\'],
    \'household_role\' => [\'household_role\', \'relationship\', \'role_in_household\', \'hh_role\'],
    \'household_head\' => [\'household_head\', \'is_head\', \'head\', \'hh_head\'],
    \'original_record_id\' => [\'original_record_id\', \'record_id\', \'uuid\', \'_id\', \'_uuid\', \'instanceid\', \'__id\', \'id\'],
]',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 51,
            'startTokenPos' => 48,
            'startFilePos' => 1191,
            'endTokenPos' => 375,
            'endFilePos' => 2769,
          ),
        ),
        'docComment' => '/**
 * Canonical field => candidate header spellings, best first. These are the same
 * aliases the adapters used to apply SILENTLY; here they only ever pre-fill a
 * proposal that a human still has to accept.
 *
 * @var array<string, list<string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'AMBIGUOUS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'name' => 'AMBIGUOUS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'national_id\', \'id\', \'head\', \'household\']',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 388,
            'startFilePos' => 3108,
            'endTokenPos' => 399,
            'endFilePos' => 3149,
          ),
        ),
        'docComment' => '/**
 * Aliases that must never be auto-suggested with high confidence because the header
 * is genuinely ambiguous about WHICH identifier it holds. `national_id` is the
 * motivating case: it is used for NIN, for a voter\'s card, and for a state ID.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 73,
      ),
    ),
    'immediateProperties' => 
    array (
      'normalizer' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'name' => 'normalizer',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Support\\NormalizationService',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Domain\\Registry\\Support\\NormalizationService()',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 418,
            'startFilePos' => 3237,
            'endTokenPos' => 420,
            'endFilePos' => 3260,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 33,
        'endColumn' => 108,
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
          'normalizer' => 
          array (
            'name' => 'normalizer',
            'default' => 
            array (
              'code' => 'new \\App\\Domain\\Registry\\Support\\NormalizationService()',
              'attributes' => 
              array (
                'startLine' => 62,
                'endLine' => 62,
                'startTokenPos' => 418,
                'startFilePos' => 3237,
                'endTokenPos' => 420,
                'endFilePos' => 3260,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Support\\NormalizationService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 62,
            'endLine' => 62,
            'startColumn' => 33,
            'endColumn' => 108,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 112,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'signature' => 
      array (
        'name' => 'signature',
        'parameters' => 
        array (
          'headers' => 
          array (
            'name' => 'headers',
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
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 31,
            'endColumn' => 44,
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
        'docComment' => '/**
 * A stable fingerprint of a file\'s SHAPE — its set of columns, order-independent.
 *
 * A saved template is offered only for a file with the same signature, so when an
 * MDA changes its export the old mapping is not silently reapplied to columns that
 * have moved or been renamed.
 *
 * @param  list<string>  $headers
 */',
        'startLine' => 73,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'suggest' => 
      array (
        'name' => 'suggest',
        'parameters' => 
        array (
          'headers' => 
          array (
            'name' => 'headers',
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
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 29,
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
 * Propose a canonical field for each header. Advisory only.
 *
 * @param  list<string>  $headers
 * @return array<string, array{header: ?string, confidence: string, reason: string}>
 *                                                                                   keyed by canonical field
 */',
        'startLine' => 91,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'exactHeaderFor' => 
      array (
        'name' => 'exactHeaderFor',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'headers' => 
          array (
            'name' => 'headers',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 52,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'taken' => 
          array (
            'name' => 'taken',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 68,
            'endColumn' => 79,
            'parameterIndex' => 2,
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
        'docComment' => '/**
 * A header whose canonicalised name IS one of the field\'s aliases.
 *
 * @param  list<string>  $headers
 * @param  list<string>  $taken
 * @return array{0: string, 1: string, 2: string}|null
 */',
        'startLine' => 136,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'fuzzyHeaderFor' => 
      array (
        'name' => 'fuzzyHeaderFor',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
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
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'headers' => 
          array (
            'name' => 'headers',
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
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 52,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'taken' => 
          array (
            'name' => 'taken',
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
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 68,
            'endColumn' => 79,
            'parameterIndex' => 2,
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
        'docComment' => '/**
 * A header that CONTAINS an alias — `beneficiary_phone_number` for `phone`.
 *
 * Only that direction, and only for aliases of a real length. The reverse (an alias
 * containing the header) matches on fragments: `hh_id` "resembles" a column called
 * `_id`, which is how a household reference ends up claiming a record id. Always
 * reported as LOW — this is the guess most likely to be wrong.
 *
 * @param  list<string>  $headers
 * @param  list<string>  $taken
 * @return array{0: string, 1: string, 2: string}|null
 */',
        'startLine' => 165,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'apply' => 
      array (
        'name' => 'apply',
        'parameters' => 
        array (
          'rawRow' => 
          array (
            'name' => 'rawRow',
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
            'startLine' => 197,
            'endLine' => 197,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columnMap' => 
          array (
            'name' => 'columnMap',
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
            'startLine' => 197,
            'endLine' => 197,
            'startColumn' => 42,
            'endColumn' => 57,
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
 * Apply a CONFIRMED mapping to one raw row.
 *
 * A canonical field present in the map with a null header is confirmed ABSENT and
 * stays absent; a field missing from the map entirely is unmapped and also yields
 * null. The raw row is never modified — the returned array is a new canonical
 * representation, and the uploaded file itself is only ever read.
 *
 * @param  array<string, string>  $rawRow  header-keyed source values
 * @param  array<string, string|null>  $columnMap  canonical field => source header
 * @return array<string, string|null>
 */',
        'startLine' => 197,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'unconfirmedIdentityFields' => 
      array (
        'name' => 'unconfirmedIdentityFields',
        'parameters' => 
        array (
          'columnMap' => 
          array (
            'name' => 'columnMap',
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
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 47,
            'endColumn' => 62,
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
 * Which confirmation-required fields the given map has NOT settled (CLAUDE.md §11).
 *
 * "Settled" means the key is PRESENT — pointing at a header, or explicitly at null
 * to say this source does not carry it. A key that is simply missing is unanswered,
 * and that is what this returns.
 *
 * @param  array<string, string|null>  $columnMap
 * @return list<string>
 */',
        'startLine' => 232,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'unknownHeaders' => 
      array (
        'name' => 'unknownHeaders',
        'parameters' => 
        array (
          'columnMap' => 
          array (
            'name' => 'columnMap',
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
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'headers' => 
          array (
            'name' => 'headers',
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
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 54,
            'endColumn' => 67,
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
 * Headers named in a map that the file does not actually contain — a stale template
 * applied to a changed export, which would silently map the field to nothing.
 *
 * @param  array<string, string|null>  $columnMap
 * @param  list<string>  $headers
 * @return list<string>
 */',
        'startLine' => 248,
        'endLine' => 259,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'aliasName' => NULL,
      ),
      'canonicaliseHeader' => 
      array (
        'name' => 'canonicaliseHeader',
        'parameters' => 
        array (
          'header' => 
          array (
            'name' => 'header',
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
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 41,
            'endColumn' => 54,
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
        'docComment' => '/** Lower-case, strip group prefixes and fold separators, so headers compare sanely. */',
        'startLine' => 262,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
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