<?php declare(strict_types = 1);

// osfsl-/var/www/html/app/Domain/Registry/Support/BeneficiaryRules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\BeneficiaryRules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e8b474377cefbfb1f1c2f1b854667e5cbdbf891eedf01a3498998eb8ad381254-8.3.31-6.70.0.1',
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
    'endLine' => 73,
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
            'startLine' => 35,
            'endLine' => 35,
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
        'startLine' => 35,
        'endLine' => 38,
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
        'startLine' => 43,
        'endLine' => 59,
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
 * @return array<string, string>
 */',
        'startLine' => 66,
        'endLine' => 72,
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