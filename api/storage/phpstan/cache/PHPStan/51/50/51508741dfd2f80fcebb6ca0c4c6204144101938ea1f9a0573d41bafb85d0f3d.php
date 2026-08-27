<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/ImportRowValidator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\ImportRowValidator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b57db7d617924f3d2e1a9208d3c4e53fbc3d15b70e43296f4b022dc715eb3703',
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
    'startLine' => 27,
    'endLine' => 158,
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
      'normalizer' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
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
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 69,
            'startFilePos' => 1236,
            'endTokenPos' => 71,
            'endFilePos' => 1259,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
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
                'startLine' => 29,
                'endLine' => 29,
                'startTokenPos' => 69,
                'startFilePos' => 1236,
                'endTokenPos' => 71,
                'endFilePos' => 1259,
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
            'startLine' => 29,
            'endLine' => 29,
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
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 112,
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
        'docComment' => '/**
 * The canonical field set — declared once in {@see CanonicalSchema}.
 *
 * @return list<string>
 */',
        'startLine' => 36,
        'endLine' => 39,
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
            'startLine' => 50,
            'endLine' => 50,
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
        'startLine' => 50,
        'endLine' => 119,
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
            'startLine' => 125,
            'endLine' => 125,
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
        'startLine' => 125,
        'endLine' => 157,
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