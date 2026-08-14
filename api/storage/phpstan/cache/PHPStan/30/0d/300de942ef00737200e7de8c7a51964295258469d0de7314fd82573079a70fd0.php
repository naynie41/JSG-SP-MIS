<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Imports\ColumnMapper.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ColumnMapper
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-089f166b161c8e2e88e759147eb8822a6213d2a229a8684218be9dc78a781455',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Imports/ColumnMapper.php',
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
    'startLine' => 22,
    'endLine' => 257,
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
          'code' => '[\'first_name\' => [\'first_name\', \'firstname\', \'given_name\', \'givenname\', \'fname\', \'forename\'], \'middle_name\' => [\'middle_name\', \'middlename\', \'other_name\', \'othername\', \'other_names\'], \'last_name\' => [\'last_name\', \'lastname\', \'surname\', \'family_name\', \'familyname\', \'lname\'], \'nin\' => [\'nin\', \'national_identification_number\', \'nin_number\', \'national_id\'], \'bvn\' => [\'bvn\', \'bank_verification_number\', \'bvn_number\'], \'phone\' => [\'phone\', \'phone_number\', \'phone_no\', \'msisdn\', \'mobile\', \'mobile_number\', \'telephone\'], \'date_of_birth\' => [\'date_of_birth\', \'dob\', \'birth_date\', \'birthdate\', \'date_of_birth_dd_mm_yyyy\'], \'gender\' => [\'gender\', \'sex\'], \'address\' => [\'address\', \'home_address\', \'residential_address\'], \'lga\' => [\'lga\', \'local_government\', \'local_government_area\'], \'ward\' => [\'ward\', \'ward_name\'], \'household_ref\' => [\'household_id\', \'household_ref\', \'household_code\', \'household\', \'hh_id\'], \'household_role\' => [\'household_role\', \'relationship\', \'role_in_household\', \'hh_role\'], \'household_head\' => [\'household_head\', \'is_head\', \'head\', \'hh_head\'], \'original_record_id\' => [\'original_record_id\', \'record_id\', \'uuid\', \'_id\', \'_uuid\', \'instanceid\', \'__id\', \'id\']]',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 47,
            'startTokenPos' => 43,
            'startFilePos' => 1145,
            'endTokenPos' => 336,
            'endFilePos' => 2443,
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
        'startLine' => 31,
        'endLine' => 47,
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
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 349,
            'startFilePos' => 2782,
            'endTokenPos' => 360,
            'endFilePos' => 2823,
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
        'startLine' => 56,
        'endLine' => 56,
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
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 379,
            'startFilePos' => 2911,
            'endTokenPos' => 381,
            'endFilePos' => 2934,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
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
                'startLine' => 58,
                'endLine' => 58,
                'startTokenPos' => 379,
                'startFilePos' => 2911,
                'endTokenPos' => 381,
                'endFilePos' => 2934,
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
            'startLine' => 58,
            'endLine' => 58,
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
        'startLine' => 58,
        'endLine' => 58,
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
            'startLine' => 69,
            'endLine' => 69,
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
        'startLine' => 69,
        'endLine' => 78,
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
            'startLine' => 87,
            'endLine' => 87,
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
        'startLine' => 87,
        'endLine' => 123,
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
            'startLine' => 132,
            'endLine' => 132,
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
            'startLine' => 132,
            'endLine' => 132,
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
            'startLine' => 132,
            'endLine' => 132,
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
        'startLine' => 132,
        'endLine' => 147,
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
            'startLine' => 161,
            'endLine' => 161,
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
            'startLine' => 161,
            'endLine' => 161,
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
            'startLine' => 161,
            'endLine' => 161,
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
        'startLine' => 161,
        'endLine' => 179,
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
            'startLine' => 193,
            'endLine' => 193,
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
            'startLine' => 193,
            'endLine' => 193,
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
        'startLine' => 193,
        'endLine' => 205,
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
            'startLine' => 217,
            'endLine' => 217,
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
        'startLine' => 217,
        'endLine' => 223,
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
            'startLine' => 233,
            'endLine' => 233,
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
            'startLine' => 233,
            'endLine' => 233,
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
        'startLine' => 233,
        'endLine' => 244,
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
            'startLine' => 247,
            'endLine' => 247,
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
        'startLine' => 247,
        'endLine' => 256,
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