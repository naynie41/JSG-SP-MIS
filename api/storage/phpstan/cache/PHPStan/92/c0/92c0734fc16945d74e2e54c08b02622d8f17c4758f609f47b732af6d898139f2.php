<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Models\BeneficiaryServiceGrant.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\BeneficiaryServiceGrant
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5e0e8e3e693686892213b1189f3619ee1049d8882618e12be9bdf67869cd4599',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Models/BeneficiaryServiceGrant.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
    'shortName' => 'BeneficiaryServiceGrant',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Explicit READ-access grant (PRD §12, FR-OWN-07): opened when the owner MDA
 * accepts a {@see ServiceRequest}. It gives the requesting MDA (`mda_id`) READ
 * access to the full beneficiary record — nothing else; ownership/edit never move.
 *
 * MDA-scoped on `mda_id` (the granted MDA) so an MDA sees only its own grants.
 * Auditable, but the semantic event (`beneficiary.access_granted`) is recorded
 * explicitly against the beneficiary when the grant opens.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $mda_id
 * @property string $service_request_id
 * @property string|null $granted_by
 * @property Carbon $granted_at
 * @property Carbon|null $revoked_at
 * @property string|null $revoked_by
 * @property string|null $revocation_reason
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $mda
 * @property-read User|null $revokedBy
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 105,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'beneficiary_service_grants\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 93,
            'startFilePos' => 1482,
            'endTokenPos' => 93,
            'endFilePos' => 1509,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'mda_id\', \'service_request_id\', \'granted_by\', \'granted_at\', \'revoked_at\', \'revoked_by\', \'revocation_reason\']',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 57,
            'startTokenPos' => 104,
            'startFilePos' => 1580,
            'endTokenPos' => 130,
            'endFilePos' => 1777,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 57,
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
        'startLine' => 62,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'aliasName' => NULL,
      ),
      'mdaOwnershipColumn' => 
      array (
        'name' => 'mdaOwnershipColumn',
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
        'docComment' => '/** The grant is scoped to the granted (requesting) MDA, not an owner column. */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
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
        'startLine' => 79,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'aliasName' => NULL,
      ),
      'mda' => 
      array (
        'name' => 'mda',
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
 * @return BelongsTo<Mda, $this>
 */',
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'aliasName' => NULL,
      ),
      'revokedBy' => 
      array (
        'name' => 'revokedBy',
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
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'aliasName' => NULL,
      ),
      'isActive' => 
      array (
        'name' => 'isActive',
        'parameters' => 
        array (
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
        'docComment' => '/** Whether this grant still opens access. The gates ask this via `hasActiveGrant()`. */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryServiceGrant',
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