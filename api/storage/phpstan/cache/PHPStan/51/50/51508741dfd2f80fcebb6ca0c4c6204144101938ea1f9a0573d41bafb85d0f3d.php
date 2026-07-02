<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/ImportRowValidator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ImportRowValidator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-4b9cd3a7db3904c03bbb28845ca5b9a22db37908f162717ace54e63d2c63f790',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'filename' => '/var/www/html/app/Domain/Registry/Imports/ImportRowValidator.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports',
    'name' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
    'shortName' => 'ImportRowValidator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Normalises and validates a single import row using the SAME rules as manual
 * registration (BeneficiaryRules), so a file row is accepted iff the equivalent
 * manual registration would be (PRD FR-REG-05). Returns the cleaned payload plus
 * a structured, row-level error list for the preview (FR-REG-06).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 71,
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
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'name' => 'FIELDS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'first_name\', \'middle_name\', \'last_name\', \'nin\', \'bvn\', \'phone\', \'date_of_birth\', \'gender\', \'address\', \'lga\', \'ward\']',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 22,
            'startTokenPos' => 46,
            'startFilePos' => 581,
            'endTokenPos' => 81,
            'endFilePos' => 721,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'validate' => 
      array (
        'name' => 'validate',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 30,
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
 * @param  array<string, string>  $values  header-keyed source values
 * @return array{payload: array<string, mixed>, valid: bool, errors: list<array{field: string, message: string}>}
 */',
        'startLine' => 28,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'aliasName' => NULL,
      ),
      'normalise' => 
      array (
        'name' => 'normalise',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 32,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, string>  $values
 * @return array<string, mixed>
 */',
        'startLine' => 48,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
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