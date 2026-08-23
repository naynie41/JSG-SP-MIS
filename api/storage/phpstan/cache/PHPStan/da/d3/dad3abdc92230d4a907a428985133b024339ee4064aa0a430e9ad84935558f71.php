<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Support\CanonicalSchema.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\CanonicalSchema
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-354cc3f1ce6cb7cccd2b7862e2e3b3e14af83fe275b394f6d16ab146bb41000b',
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
    'startLine' => 32,
    'endLine' => 228,
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
          'code' => '[\'first_name\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => true, \'note\' => \'Given name\'], \'middle_name\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => true, \'note\' => \'Other name(s)\'], \'last_name\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => true, \'note\' => \'Surname; also the fuzzy blocking key\'], \'nin\' => [\'type\' => \'digits:11\', \'required\' => false, \'identity\' => true, \'note\' => \'National Identification Number; deterministic match stage 1\'], \'bvn\' => [\'type\' => \'digits:11\', \'required\' => false, \'identity\' => true, \'note\' => \'Bank Verification Number; deterministic match stage 2\'], \'phone\' => [\'type\' => \'phone\', \'required\' => false, \'identity\' => true, \'note\' => \'Nigerian MSISDN in any written form\'], \'date_of_birth\' => [\'type\' => \'date\', \'required\' => true, \'identity\' => false, \'note\' => \'Must be in the past; part of the blocking key\', \'segment\' => [\'kind\' => \'age\', \'label\' => \'Age\', \'unit\' => \'years\']], \'gender\' => [\'type\' => \'enum\', \'required\' => true, \'identity\' => false, \'note\' => \'male | female | other\', \'segment\' => [\'kind\' => \'enum\', \'label\' => \'Gender\', \'values\' => \\App\\Domain\\Registry\\Enums\\Gender::class]], \'address\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => false, \'note\' => \'Residential address\'], \'lga\' => [\'type\' => \'enum\', \'required\' => true, \'identity\' => false, \'note\' => \'A Jigawa LGA\', \'segment\' => [\'kind\' => \'enum\', \'label\' => \'LGA\', \'values\' => \\App\\Domain\\Registry\\Enums\\Lga::class]], \'ward\' => [\'type\' => \'string\', \'required\' => true, \'identity\' => false, \'note\' => \'Ward within the LGA\', \'segment\' => [\'kind\' => \'lookup\', \'label\' => \'Ward\']]]',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 70,
            'startTokenPos' => 55,
            'startFilePos' => 2764,
            'endTokenPos' => 536,
            'endFilePos' => 4444,
          ),
        ),
        'docComment' => '/**
 * Core beneficiary fields, in presentation order.
 *
 * `required` is about presence; `identity` is about the malformed-value rule.
 *
 * `segment` is a THIRD, independent axis: whether the field describes a GROUP of
 * people rather than one person, and so may be offered as a report filter
 * (FR-RPT-03). Declaring it here rather than in the reporting layer is what lets a
 * new schema field appear as a filter with no code change — and what stops the two
 * from drifting, which is how a field ends up filterable in one report and missing
 * from another.
 *
 * An IDENTITY field is never segmentable, and {@see self::segmentableFields()}
 * enforces that regardless of what is declared: NIN, BVN, phone and name pick out
 * individuals, not segments. They are masked in output and are never a filter.
 *
 * `segment.kind` tells the builder how to filter:
 *   enum   — multi-select over `values` (a backed enum class)
 *   lookup — multi-select over values present in the data (free text, e.g. ward)
 *   age    — a numeric range, derived from a DATE column
 *   date   — a calendar range
 *
 * @var array<string, array{type: string, required: bool, identity: bool, note: string, segment?: array<string, mixed>}>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 70,
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
            'startLine' => 79,
            'endLine' => 83,
            'startTokenPos' => 549,
            'startFilePos' => 4798,
            'endTokenPos' => 653,
            'endFilePos' => 5234,
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
        'startLine' => 79,
        'endLine' => 83,
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
            'startLine' => 86,
            'endLine' => 88,
            'startTokenPos' => 666,
            'startFilePos' => 5356,
            'endTokenPos' => 702,
            'endFilePos' => 5530,
          ),
        ),
        'docComment' => '/** Provenance the source supplies; the rest is stamped by the registrar. */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'DERIVED_SOURCE_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
        'name' => 'DERIVED_SOURCE_FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'full_name\' => [\'type\' => \'string\', \'required\' => false, \'identity\' => true, \'note\' => \'One name column; split into first/last name\']]',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 105,
            'startTokenPos' => 715,
            'startFilePos' => 6310,
            'endTokenPos' => 751,
            'endFilePos' => 6459,
          ),
        ),
        'docComment' => '/**
 * Source-shape fields that are MAPPABLE but are not beneficiary columns — they are
 * derived into canonical fields at mapping time and never stored under these names.
 *
 * `full_name` exists because many MDA exports carry one `Name` column. Mapping such a
 * column to both `first_name` and `last_name` is what produced records reading
 * "Rekiya Bagwai Rekiya Bagwai"; mapping it here instead lets
 * {@see NameSplitter} derive the two properly. It is an
 * IDENTITY field: it is the name, so a malformed value must reject the row exactly as
 * a malformed `first_name` would.
 *
 * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 105,
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
        'startLine' => 108,
        'endLine' => 111,
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
      'mappableFields' => 
      array (
        'name' => 'mappableFields',
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
 * Everything a column may be mapped ONTO, including the derived source fields.
 *
 * @return list<string>
 */',
        'startLine' => 118,
        'endLine' => 121,
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
        'startLine' => 124,
        'endLine' => 127,
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
        'startLine' => 134,
        'endLine' => 137,
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
        'startLine' => 144,
        'endLine' => 147,
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
        'startLine' => 150,
        'endLine' => 153,
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
            'startLine' => 155,
            'endLine' => 155,
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
        'startLine' => 155,
        'endLine' => 158,
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
        'startLine' => 176,
        'endLine' => 184,
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
      'segmentableFields' => 
      array (
        'name' => 'segmentableFields',
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
 * The fields that may be offered as REPORT FILTERS (FR-RPT-03), keyed by field name.
 *
 * Two rules, in this order:
 *  1. An identity field is excluded, always — even if something declares `segment`
 *     on it. This is the structural guarantee that NIN, BVN, phone and name can
 *     never become a filter: a filter on an identifier is not a segment, it is a
 *     search for one person wearing a report\'s clothing.
 *  2. Of what remains, a field is offered only if it DECLARES how it segments.
 *
 * Rule 2 is why `address` is absent. It is non-identity — a malformed one is
 * dropped, not fatal — but it is free text that narrows to a household or a person,
 * so it describes an individual rather than a group. Silence here is the safe
 * default: a new field appears as a filter because someone decided it segments, not
 * because nobody stopped it.
 *
 * @return array<string, array<string, mixed>>
 */',
        'startLine' => 204,
        'endLine' => 217,
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
            'startLine' => 220,
            'endLine' => 220,
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
        'startLine' => 220,
        'endLine' => 227,
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