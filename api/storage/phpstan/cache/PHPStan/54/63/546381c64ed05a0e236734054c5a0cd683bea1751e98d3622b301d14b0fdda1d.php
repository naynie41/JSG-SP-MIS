<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Support/BeneficiaryRules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\BeneficiaryRules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-16d885cf6ea754a4516cf9ea000eb4d22a82e0697bde19484731e713f69da1a8',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'filename' => '/var/www/html/app/Domain/Registry/Support/BeneficiaryRules.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
    'shortName' => 'BeneficiaryRules',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * The canonical beneficiary-registration validation rules (PRD FR-REG-04/05),
 * shared so every ingestion path — bulk import (ImportRowValidator) and the REST
 * intake (ApiRegistrationRequest) — enforces the same mandatory fields + formats.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 117,
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
      'IDENTITY_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'name' => 'IDENTITY_FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'first_name\', \'middle_name\', \'last_name\', \'phone\', \'nin\', \'bvn\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 50,
            'startFilePos' => 794,
            'endTokenPos' => 67,
            'endFilePos' => 858,
          ),
        ),
        'docComment' => '/**
 * Identity fields (PRD §9, FR-REG-05): name, phone, NIN, BVN. When one of
 * these is PRESENT but malformed the WHOLE row is rejected — an identity field
 * is never partial-saved. (Absent optional NIN/BVN/phone is still valid.)
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 101,
      ),
      'NON_IDENTITY_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'name' => 'NON_IDENTITY_FIELDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'date_of_birth\', \'gender\', \'address\', \'lga\', \'ward\']',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 80,
            'startFilePos' => 1114,
            'endTokenPos' => 94,
            'endFilePos' => 1166,
          ),
        ),
        'docComment' => '/**
 * Non-identity fields (PRD §9, FR-REG-09): a failure here drops/flags just that
 * field and the row still saves. All of these are nullable in the schema.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 93,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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
            'startLine' => 40,
            'endLine' => 40,
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
        'docComment' => '/**
 * Delegates to {@see CanonicalSchema}, which is the authoritative declaration. The
 * two consts above stay because an admin endpoint publishes them as its response
 * shape; `RegistryRulesConsistencyTest` asserts they never drift apart from it.
 */',
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'aliasName' => NULL,
      ),
      'forRegistration' => 
      array (
        'name' => 'forRegistration',
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
 * @return array<string, mixed>
 */',
        'startLine' => 48,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'aliasName' => NULL,
      ),
      'earliestDateOfBirth' => 
      array (
        'name' => 'earliestDateOfBirth',
        'parameters' => 
        array (
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
        'docComment' => '/** The earliest date of birth accepted as real data (config, not a literal here). */',
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'aliasName' => NULL,
      ),
      'messages' => 
      array (
        'name' => 'messages',
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
 * Human-readable messages shared by both entry paths.
 *
 * Every message names the FIELD and the REASON, so a row-level error report is
 * actionable without opening the rules: "Date of birth cannot be in the future"
 * tells an officer what to change; "The date of birth field must be a date before
 * today" makes them work it out.
 *
 * @return array<string, string>
 */',
        'startLine' => 92,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\BeneficiaryRules',
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