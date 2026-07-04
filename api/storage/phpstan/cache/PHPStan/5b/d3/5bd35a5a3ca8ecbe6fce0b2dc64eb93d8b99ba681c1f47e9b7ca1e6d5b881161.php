<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Models/BenefitFlag.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Models\BenefitFlag
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-611280023203f333dc50f797d13717b72575efa9f63983a358d118d5e3227c8a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'filename' => '/var/www/html/app/Domain/Benefit/Models/BenefitFlag.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Models',
    'name' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
    'shortName' => 'BenefitFlag',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A potential double-dipping flag (PRD FR-BEN-05) between two deliveries of the
 * same benefit type to the same beneficiary by different MDAs. Advisory only —
 * never blocks. Audited.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $benefit_id
 * @property string $related_benefit_id
 * @property string|null $rule_id
 * @property string $benefit_type
 * @property string $from_mda_id
 * @property string $other_mda_id
 * @property BenefitFlagStatus $status
 * @property string $reason
 * @property string|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property string|null $review_note
 * @property Carbon|null $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 89,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'benefit_flags\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 81,
            'startFilePos' => 1188,
            'endTokenPos' => 81,
            'endFilePos' => 1202,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'benefit_id\', \'related_benefit_id\', \'rule_id\', \'benefit_type\', \'from_mda_id\', \'other_mda_id\', \'status\', \'reason\', \'reviewed_by\', \'reviewed_at\', \'review_note\']',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 48,
            'startTokenPos' => 92,
            'startFilePos' => 1273,
            'endTokenPos' => 130,
            'endFilePos' => 1472,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 48,
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
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Benefit\\Enums\\BenefitFlagStatus::Open->value]',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 141,
            'startFilePos' => 1553,
            'endTokenPos' => 151,
            'endFilePos' => 1596,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 73,
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
        'startLine' => 58,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
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
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'aliasName' => NULL,
      ),
      'fromMda' => 
      array (
        'name' => 'fromMda',
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
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'aliasName' => NULL,
      ),
      'otherMda' => 
      array (
        'name' => 'otherMda',
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
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
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