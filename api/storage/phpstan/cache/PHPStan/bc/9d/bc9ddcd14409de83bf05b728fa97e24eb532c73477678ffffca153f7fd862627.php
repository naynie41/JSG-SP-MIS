<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Support/RegistrationSourceRule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\RegistrationSourceRule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-05a83c4f1c02c73cf6317f4adea2fb6b80a0c578834acb8b52603aee42122fa2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\RegistrationSourceRule',
        'filename' => '/var/www/html/app/Domain/Registry/Support/RegistrationSourceRule.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\RegistrationSourceRule',
    'shortName' => 'RegistrationSourceRule',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Guards the one thing every registry record must be able to answer: where it came from
 * (FR-REG-03, CLAUDE.md §8).
 *
 * Ingestion is bulk/source-only, and each door — file import, REST intake, connector
 * sync, offline batch — knows its own source. So a record without one is not a user
 * error to be defaulted away; it is a code path that forgot, and defaulting it produces
 * a plausible-looking lie that the audit trail cannot distinguish from the truth.
 *
 * Shared by {@see Beneficiary} and
 * {@see Household} so the two cannot drift on a rule that
 * only means anything if it holds everywhere.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 59,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'assertAssignable' => 
      array (
        'name' => 'assertAssignable',
        'parameters' => 
        array (
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'entity' => 
          array (
            'name' => 'entity',
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 60,
            'endColumn' => 73,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws InvalidArgumentException when the source is absent or not assignable
 */',
        'startLine' => 30,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RegistrationSourceRule',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RegistrationSourceRule',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\RegistrationSourceRule',
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