<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/BeneficiaryConsent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\BeneficiaryConsent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5993664f377bcb25e0a238f8a2b71ec6c67c0fc7c97cd752a3452611e241b4a5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'filename' => '/var/www/html/app/Domain/Registry/Models/BeneficiaryConsent.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
    'shortName' => 'BeneficiaryConsent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * One entry in a beneficiary\'s append-only consent history (NFR-PRV-01). Each capture
 * or withdrawal writes a new row; the current status is denormalised onto the
 * beneficiary. Never updated or deleted — the lifecycle is the audit.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $purpose
 * @property ConsentStatus $status
 * @property string|null $basis
 * @property string|null $source
 * @property string|null $note
 * @property string|null $recorded_by
 * @property Carbon $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 67,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'beneficiary_consents\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 63,
            'startFilePos' => 918,
            'endTokenPos' => 63,
            'endFilePos' => 939,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamps' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 72,
            'startFilePos' => 968,
            'endTokenPos' => 72,
            'endFilePos' => 972,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'purpose\', \'status\', \'basis\', \'source\', \'note\', \'recorded_by\']',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 47,
            'startTokenPos' => 83,
            'startFilePos' => 1043,
            'endTokenPos' => 106,
            'endFilePos' => 1186,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'casts' => 
      array (
        'name' => 'casts',
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
 * @return array<string, string>
 */',
        'startLine' => 52,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'aliasName' => NULL,
      ),
      'beneficiary' => 
      array (
        'name' => 'beneficiary',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<Beneficiary, $this>
 */',
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
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