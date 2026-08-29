<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Graduation/Models/GraduationEvent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\Models\GraduationEvent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-0157d1ef24618c27cc65c439752caf659f939a388f2d8e5dda49e2a813008d7d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'filename' => '/var/www/html/app/Domain/Graduation/Models/GraduationEvent.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Graduation\\Models',
    'name' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
    'shortName' => 'GraduationEvent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The permanent record that a beneficiary/household graduated from a programme
 * (FR-GRD-02). Recording it flips the ENROLMENT status to graduated but NEVER deletes
 * the beneficiary or their benefit ledger — the full history is preserved.
 *
 * Scoped on `mda_id` — the MDA that ran the programme (the enrolling MDA) — so the
 * graduation history is visible to that MDA and to oversight roles.
 *
 * @property string $id
 * @property string $enrollment_id
 * @property string|null $beneficiary_id
 * @property string|null $household_id
 * @property string $programme_id
 * @property string|null $activity_id
 * @property string $mda_id
 * @property string|null $criteria_id
 * @property string|null $reason
 * @property string|null $decided_by
 * @property Carbon $graduated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 38,
    'endLine' => 110,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      1 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'graduation_events\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 95,
            'startFilePos' => 1414,
            'endTokenPos' => 95,
            'endFilePos' => 1432,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'enrollment_id\', \'beneficiary_id\', \'household_id\', \'programme_id\', \'activity_id\', \'mda_id\', \'criteria_id\', \'reason\', \'decided_by\', \'graduated_at\']',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 56,
            'startTokenPos' => 128,
            'startFilePos' => 1666,
            'endTokenPos' => 160,
            'endFilePos' => 1835,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 56,
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
        'docComment' => '/** Scoped to the MDA that ran the programme, not an `owner_mda_id`. */',
        'startLine' => 45,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
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
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
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
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
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
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'aliasName' => NULL,
      ),
      'household' => 
      array (
        'name' => 'household',
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
 * The household, when the enrolment was a household one.
 *
 * @return BelongsTo<Household, $this>
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
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'aliasName' => NULL,
      ),
      'decidedBy' => 
      array (
        'name' => 'decidedBy',
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
 * The officer who decided. A graduation is a judgement about a person\'s
 * circumstances, so the record names who made it — an id alone is not accountability.
 *
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'aliasName' => NULL,
      ),
      'criteria' => 
      array (
        'name' => 'criteria',
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
 * @return BelongsTo<GraduationCriteria, $this>
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationEvent',
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