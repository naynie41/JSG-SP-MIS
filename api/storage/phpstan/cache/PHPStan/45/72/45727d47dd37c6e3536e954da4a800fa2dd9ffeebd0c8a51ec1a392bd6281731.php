<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Imports\ImportRowValidator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ImportRowValidator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a2a79f2a0e80d5129e9b0d6136343c7bf97ce353de73b10bcff97f1e0326ad45',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Imports/ImportRowValidator.php',
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
    'startLine' => 25,
    'endLine' => 121,
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
            'startLine' => 27,
            'endLine' => 30,
            'startTokenPos' => 51,
            'startFilePos' => 1073,
            'endTokenPos' => 86,
            'endFilePos' => 1213,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 30,
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
            'startLine' => 41,
            'endLine' => 41,
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
        'startLine' => 41,
        'endLine' => 92,
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
            'startLine' => 98,
            'endLine' => 98,
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
        'startLine' => 98,
        'endLine' => 120,
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