<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Graduation/Models/GraduationEvent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\Models\GraduationEvent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-237c6b282631e2a7b9770840974c060601b64bbd3a3c053a410b981bf0b1ca1b',
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
    'startLine' => 36,
    'endLine' => 79,
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
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 85,
            'startFilePos' => 1337,
            'endTokenPos' => 85,
            'endFilePos' => 1355,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
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
            'startLine' => 51,
            'endLine' => 54,
            'startTokenPos' => 118,
            'startFilePos' => 1589,
            'endTokenPos' => 150,
            'endFilePos' => 1758,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 54,
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
        'startLine' => 43,
        'endLine' => 46,
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
        'startLine' => 59,
        'endLine' => 62,
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
        'startLine' => 67,
        'endLine' => 70,
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
        'startLine' => 75,
        'endLine' => 78,
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