<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/Adapters/FieldMappingAdapter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\Adapters\FieldMappingAdapter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-be1d9238ee2cddf7dd0bbff3d93dd0488fcd252e0950b52b1e221e64a604b7a8',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'filename' => '/var/www/html/app/Domain/Registry/Imports/Adapters/FieldMappingAdapter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
    'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
    'shortName' => 'FieldMappingAdapter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Base adapter that maps a raw record onto canonical beneficiary fields using a
 * table of per-field aliases. Source keys are canonicalised first — group
 * prefixes (`personal/first_name`, `respondent-nin`) are stripped and the name is
 * lower-cased — so form/export column names line up with the schema.
 *
 * Concrete adapters supply their provenance, any extra aliases, and the meta
 * key(s) that carry the source\'s own record id.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 113,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'BASE_ALIASES' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'name' => 'BASE_ALIASES',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'first_name\' => [\'first_name\', \'firstname\', \'given_name\', \'givenname\', \'fname\'], \'middle_name\' => [\'middle_name\', \'middlename\', \'other_name\', \'othername\'], \'last_name\' => [\'last_name\', \'lastname\', \'surname\', \'family_name\', \'familyname\', \'lname\'], \'nin\' => [\'nin\', \'national_id\', \'national_identification_number\', \'nin_number\'], \'bvn\' => [\'bvn\', \'bank_verification_number\'], \'phone\' => [\'phone\', \'phone_number\', \'phone_no\', \'msisdn\', \'mobile\', \'telephone\'], \'date_of_birth\' => [\'date_of_birth\', \'dob\', \'birth_date\', \'birthdate\'], \'gender\' => [\'gender\', \'sex\'], \'address\' => [\'address\', \'home_address\', \'residential_address\'], \'lga\' => [\'lga\', \'local_government\', \'local_government_area\'], \'ward\' => [\'ward\', \'ward_name\']]',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 31,
            'startTokenPos' => 39,
            'startFilePos' => 736,
            'endTokenPos' => 230,
            'endFilePos' => 1551,
          ),
        ),
        'docComment' => '/** Canonical field => candidate source keys (already canonicalised), in priority order. */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'map' => 
      array (
        'name' => 'map',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
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
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 25,
            'endColumn' => 34,
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
        'docComment' => NULL,
        'startLine' => 33,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'aliasName' => NULL,
      ),
      'idKeys' => 
      array (
        'name' => 'idKeys',
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
 * The source\'s own record-id meta keys (already canonicalised), in priority
 * order — e.g. Kobo\'s `_id`, ODK\'s `instanceid`.
 *
 * @return list<string>
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'aliasName' => NULL,
      ),
      'extraAliases' => 
      array (
        'name' => 'extraAliases',
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
 * Extra source-specific aliases, merged over the base table.
 *
 * @return array<string, list<string>>
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'aliasName' => NULL,
      ),
      'aliases' => 
      array (
        'name' => 'aliases',
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
 * @return array<string, list<string>>
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'aliasName' => NULL,
      ),
      'extractRecordId' => 
      array (
        'name' => 'extractRecordId',
        'parameters' => 
        array (
          'canonical' => 
          array (
            'name' => 'canonical',
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
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 38,
            'endColumn' => 53,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, string>  $canonical
 */',
        'startLine' => 81,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'aliasName' => NULL,
      ),
      'canonicaliseKeys' => 
      array (
        'name' => 'canonicaliseKeys',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 39,
            'endColumn' => 48,
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
 * Lower-case keys and strip group prefixes so `Personal/First Name`,
 * `respondent-first_name` and `first_name` all resolve to `first_name`.
 *
 * @param  array<string, mixed>  $raw
 * @return array<string, string>
 */',
        'startLine' => 99,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
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