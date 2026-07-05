<?php declare(strict_types = 1);

// osfsl-/var/www/html/app/Domain/Registry/Imports/ImportRowValidator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ImportRowValidator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9d396786d1ddc150f07d8a959b6e2340fe49b9dbe397f289593986f61fdf08ed-8.3.31-6.70.0.1',
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
 * registration (BeneficiaryRules), then classifies each failure per the PRD §9
 * locked decision:
 *   - A PRESENT-but-malformed IDENTITY field (name/phone/NIN/BVN) rejects the whole
 *     row — it is never partial-saved (FR-REG-05). Absent optional NIN/BVN is valid.
 *   - A NON-IDENTITY field failure drops/flags just that field (nulled in the
 *     returned payload); the row still saves (FR-REG-09).
 *   - A NIN/BVN uniqueness hit is a DUPLICATE signal, not a malformed-field reject;
 *     it is surfaced separately so the duplicate/serve flow (not the error report)
 *     handles it.
 * The three buckets feed the preview + batch error report (FR-REG-06).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 118,
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
            'startLine' => 26,
            'endLine' => 29,
            'startTokenPos' => 46,
            'startFilePos' => 1023,
            'endTokenPos' => 81,
            'endFilePos' => 1163,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 29,
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
            'startLine' => 40,
            'endLine' => 40,
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
 * @return array{
 *     payload: array<string, mixed>,
 *     identity_errors: list<array{field: string, message: string}>,
 *     dropped_fields: list<array{field: string, message: string}>,
 *     duplicate_errors: list<array{field: string, message: string}>,
 * }
 */',
        'startLine' => 40,
        'endLine' => 89,
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
            'startLine' => 95,
            'endLine' => 95,
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
        'startLine' => 95,
        'endLine' => 117,
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