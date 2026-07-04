<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Models/Benefit.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Models\Benefit
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-564535f42ca329420820ed8e0e402e226d374dbd19b2768211ad217235b9e729',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'filename' => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Models',
    'name' => 'App\\Domain\\Benefit\\Models\\Benefit',
    'shortName' => 'Benefit',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A benefit-ledger entry: one benefit DELIVERED to a beneficiary (PRD FR-BEN-01/02).
 * Records delivery only — never a money movement (§2.3); `monetary_value` is data.
 *
 * Scoped on `mda_id` — the DELIVERING MDA — which may differ from the beneficiary\'s
 * owner MDA (a serving MDA delivers without owning). Auditable.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $programme_id
 * @property string|null $activity_id
 * @property string|null $enrollment_id
 * @property string $mda_id
 * @property BenefitType $benefit_type
 * @property string|null $quantity
 * @property string|null $unit
 * @property int|null $monetary_value
 * @property string|null $funding_source
 * @property Carbon $delivery_date
 * @property string|null $lga
 * @property string|null $ward
 * @property BenefitStatus $status
 * @property VerificationMethod $verification_method
 * @property string|null $verification_reference
 * @property string|null $verified_by
 * @property Carbon|null $verified_at
 * @property string|null $recorded_by
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Programme $programme
 * @property-read Mda $mda
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 60,
    'endLine' => 167,
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
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      3 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
      4 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'benefits\'',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 146,
            'startFilePos' => 2330,
            'endTokenPos' => 146,
            'endFilePos' => 2339,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'programme_id\', \'activity_id\', \'enrollment_id\', \'mda_id\', \'benefit_type\', \'quantity\', \'unit\', \'monetary_value\', \'funding_source\', \'delivery_date\', \'lga\', \'ward\', \'status\', \'verification_method\', \'verification_reference\', \'verified_by\', \'verified_at\', \'recorded_by\', \'notes\']',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 97,
            'startTokenPos' => 179,
            'startFilePos' => 2561,
            'endTokenPos' => 241,
            'endFilePos' => 3020,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Benefit\\Enums\\BenefitStatus::Recorded->value, \'verification_method\' => \\App\\Domain\\Benefit\\Enums\\VerificationMethod::None->value]',
          'attributes' => 
          array (
            'startLine' => 102,
            'endLine' => 105,
            'startTokenPos' => 252,
            'startFilePos' => 3101,
            'endTokenPos' => 276,
            'endFilePos' => 3225,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 105,
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
        'docComment' => '/** Scoped to the delivering MDA, not an `owner_mda_id`. */',
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'aliasName' => NULL,
      ),
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
        'startLine' => 110,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Database\\Factories\\BenefitFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 123,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'aliasName' => NULL,
      ),
      'programme' => 
      array (
        'name' => 'programme',
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
 * @return BelongsTo<Programme, $this>
 */',
        'startLine' => 139,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'aliasName' => NULL,
      ),
      'activity' => 
      array (
        'name' => 'activity',
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
 * @return BelongsTo<Activity, $this>
 */',
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'aliasName' => NULL,
      ),
      'enrollment' => 
      array (
        'name' => 'enrollment',
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
 * @return BelongsTo<Enrollment, $this>
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\Benefit',
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