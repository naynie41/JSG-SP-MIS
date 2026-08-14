<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Support\CanonicalSchema.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\CanonicalSchema
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-60946f2c218bb1ccabaa2f2e16ba51968a6dd12891f7661b6ba542fdd0a262a2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Support/CanonicalSchema.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
    'shortName' => 'CanonicalSchema',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * The canonical SP-MIS beneficiary schema (PRD FR-REG-04).
 *
 * This is SP-MIS\'s INTERNAL standard, not a demand on the MDAs. A source file may use
 * any column names it likes; the Data Import & Mapping layer maps whatever arrives onto
 * these fields, and everything downstream — validation, the duplicate cascade, household
 * formation, the registrar — speaks only this vocabulary.
 *
 * It exists as one declaration because the field list had drifted into three copies
 * ({@see ImportRowValidator}, `SyncEngine::CANONICAL_FIELDS`
 * and {@see BeneficiaryRules}), which is how a field ends up honoured on one ingestion
 * door and silently dropped on another.
 *
 * Two groupings matter and are NOT the same thing:
 *  - REQUIRED vs OPTIONAL — whether a value must be present at all.
 *  - IDENTITY vs NON-IDENTITY — what happens when a PRESENT value is malformed
 *    (FR-REG-05: identity rejects the whole row; FR-REG-09: non-identity drops the field
 *    and keeps the row). An optional field can still be an identity field: an absent NIN
 *    is fine, a malformed one rejects the row.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 143,
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
      'FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'name' => 'FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'first_name\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => true, \'note\' => \'Given name\'], \'middle_name\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => true, \'note\' => \'Other name(s)\'], \'last_name\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => true, \'note\' => \'Surname; also the fuzzy blocking key\'], \'nin\' => [\'type\' => \'digits:11\', \'required\' => false, \'identity\' => true, \'note\' => \'National Identification Number; deterministic match stage 1\'], \'bvn\' => [\'type\' => \'digits:11\', \'required\' => false, \'identity\' => true, \'note\' => \'Bank Verification Number; deterministic match stage 2\'], \'phone\' => [\'type\' => \'phone\', \'required\' => false, \'identity\' => true, \'note\' => \'Nigerian MSISDN in any written form\'], \'date_of_birth\' => [\'type\' => \'date\', \'required\' => true, \'identity\' => false, \'note\' => \'Must be in the past; part of the blocking key\'], \'gender\' => [\'type\' => \'enum\', \'required\' => true, \'identity\' => false, \'note\' => \'male | female | other\'], \'address\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => false, \'note\' => \'Residential address\'], \'lga\' => [\'type\' => \'enum\', \'required\' => true, \'identity\' => false, \'note\' => \'A Jigawa LGA\'], \'ward\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => false, \'note\' => \'Ward within the LGA\']]',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 51,
            'startTokenPos' => 45,
            'startFilePos' => 1618,
            'endTokenPos' => 421,
            'endFilePos' => 3019,
          ),
        ),
        'docComment' => '/**
 * Core beneficiary fields, in presentation order.
 *
 * `required` is about presence; `identity` is about the malformed-value rule.
 *
 * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'HOUSEHOLD_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'name' => 'HOUSEHOLD_FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'household_ref\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => false, \'note\' => "The source\'s own household key"], \'household_role\' => [\'type\' => \'enum\', \'required\' => false, \'identity\' => false, \'note\' => \'head | spouse | child | parent | sibling | other\'], \'household_head\' => [\'type\' => \'boolean\', \'required\' => false, \'identity\' => false, \'note\' => \'Truthy marks this person as the head\']]',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 64,
            'startTokenPos' => 434,
            'startFilePos' => 3373,
            'endTokenPos' => 538,
            'endFilePos' => 3809,
          ),
        ),
        'docComment' => '/**
 * Household grouping (FR-REG-04 "household reference"). Not beneficiary columns —
 * they drive {@see HouseholdIngestionService}, which
 * forms the household and opens a membership.
 *
 * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'PROVENANCE_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'name' => 'PROVENANCE_FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'original_record_id\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => false, \'note\' => "The source\'s own record id; doubles as the idempotency key"]]',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 69,
            'startTokenPos' => 551,
            'startFilePos' => 3931,
            'endTokenPos' => 587,
            'endFilePos' => 4105,
          ),
        ),
        'docComment' => '/** Provenance the source supplies; the rest is stamped by the registrar. */',
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'fields' => 
      array (
        'name' => 'fields',
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
        'docComment' => '/** @return list<string> */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'allFields' => 
      array (
        'name' => 'allFields',
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
        'docComment' => '/** @return list<string> */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'identityFields' => 
      array (
        'name' => 'identityFields',
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
 * Fields whose PRESENT-but-malformed value rejects the whole row (FR-REG-05).
 *
 * @return list<string>
 */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'nonIdentityFields' => 
      array (
        'name' => 'nonIdentityFields',
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
 * Fields whose failure drops just that value and keeps the row (FR-REG-09).
 *
 * @return list<string>
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'requiredFields' => 
      array (
        'name' => 'requiredFields',
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
        'docComment' => '/** @return list<string> */',
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'isIdentityField' => 
      array (
        'name' => 'isIdentityField',
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
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 44,
            'endColumn' => 56,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 109,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'confirmationRequiredFields' => 
      array (
        'name' => 'confirmationRequiredFields',
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
 * The fields whose column mapping a human must confirm on EVERY import
 * (CLAUDE.md §11): NIN, BVN, name and phone.
 *
 * These are the values the duplicate cascade treats as identity. A wrong mapping
 * here does not produce a validation error — it produces a confident, wrong answer:
 * a `national_id` column holding voter\'s-card numbers mapped to NIN makes the
 * deterministic stage declare two strangers the same person.
 *
 * `middle_name` is excluded deliberately. It is an identity field for the
 * malformed-value rule, but sources routinely omit it and requiring a decision on it
 * would add a click that carries no risk — and a guard people click through
 * mechanically stops being a guard.
 *
 * @return list<string>
 */',
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'aliasName' => NULL,
      ),
      'typeOf' => 
      array (
        'name' => 'typeOf',
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
            'startColumn' => 35,
            'endColumn' => 47,
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
        'docComment' => '/** The declared type of a canonical field, or null when the field is unknown. */',
        'startLine' => 136,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
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