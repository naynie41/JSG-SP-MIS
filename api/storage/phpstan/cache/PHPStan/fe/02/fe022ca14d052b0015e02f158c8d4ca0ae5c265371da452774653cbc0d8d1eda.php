<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Models/Programme.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Models\Programme
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-fff0c83972f6c9a80cfba2df8a15f711fb7a6229f8b886273dfe93504bf936ad',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Programme\\Models\\Programme',
        'filename' => '/var/www/html/app/Domain/Programme/Models/Programme.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Programme\\Models',
    'name' => 'App\\Domain\\Programme\\Models\\Programme',
    'shortName' => 'Programme',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A GLOBAL catalog entry for a social-protection programme *type* (PRD §10, ARCH
 * §12.4). It is NOT owned or scoped by any MDA — created only by the System
 * Administrator (optionally SP Coordination) and readable by all MDAs, who run it
 * through their own {@see Activity}. It carries type-level attributes only; budget,
 * funding and period live on the Activity. Auditable.
 *
 * @property string $id
 * @property string $name
 * @property string|null $objective
 * @property ProgrammeType $type
 * @property string|null $benefit_category
 * @property array<int, array<string, mixed>>|null $eligibility
 * @property bool $enforce_eligibility
 * @property ProgrammeStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 41,
    'endLine' => 103,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      3 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'programmes\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 114,
            'startFilePos' => 1681,
            'endTokenPos' => 114,
            'endFilePos' => 1692,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'objective\', \'type\', \'benefit_category\', \'eligibility\', \'enforce_eligibility\', \'status\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 60,
            'startTokenPos' => 125,
            'startFilePos' => 1763,
            'endTokenPos' => 151,
            'endFilePos' => 1944,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 60,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Programme\\Enums\\ProgrammeStatus::Draft->value, \'enforce_eligibility\' => false]',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 68,
            'startTokenPos' => 162,
            'startFilePos' => 2025,
            'endTokenPos' => 182,
            'endFilePos' => 2122,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 68,
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
        'startLine' => 73,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Programme',
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
            'name' => 'Database\\Factories\\ProgrammeFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'aliasName' => NULL,
      ),
      'creator' => 
      array (
        'name' => 'creator',
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
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'aliasName' => NULL,
      ),
      'activities' => 
      array (
        'name' => 'activities',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<Activity, $this>
 */',
        'startLine' => 99,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Programme',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Programme',
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