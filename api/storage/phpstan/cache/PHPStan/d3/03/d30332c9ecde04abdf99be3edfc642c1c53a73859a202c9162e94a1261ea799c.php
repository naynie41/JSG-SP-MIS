<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Models/Activity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Models\Activity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-df2f9e14ac484b9e848f863c52ff06f50e5a57f9227d3d7ca592ee4f07697104',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Programme\\Models\\Activity',
        'filename' => '/var/www/html/app/Domain/Programme/Models/Activity.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Programme\\Models',
    'name' => 'App\\Domain\\Programme\\Models\\Activity',
    'shortName' => 'Activity',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A unit of work under a programme (PRD FR-PRG-02). `owner_mda_id` is denormalised
 * from the parent programme so the shared MdaScope applies directly — an activity
 * is scoped to (and mutable only by) the programme\'s owner MDA. Auditable; budget
 * is integer minor units (kobo, NGN). A PostGIS `geom` column exists on PostgreSQL
 * for later GIS work (not surfaced yet).
 *
 * @property string $id
 * @property string $programme_id
 * @property string $owner_mda_id
 * @property string $name
 * @property string|null $description
 * @property int|null $target_count
 * @property string|null $lga
 * @property string|null $ward
 * @property string|null $location_description
 * @property array<string, mixed>|null $schedule
 * @property Carbon|null $starts_on
 * @property Carbon|null $ends_on
 * @property int|null $budget_amount
 * @property ActivityStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Programme $programme
 * @property-read Mda $ownerMda
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 48,
    'endLine' => 125,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'activities\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 121,
            'startFilePos' => 1885,
            'endTokenPos' => 121,
            'endFilePos' => 1896,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'programme_id\', \'owner_mda_id\', \'name\', \'description\', \'target_count\', \'lga\', \'ward\', \'location_description\', \'schedule\', \'starts_on\', \'ends_on\', \'budget_amount\', \'status\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 73,
            'startTokenPos' => 132,
            'startFilePos' => 1967,
            'endTokenPos' => 176,
            'endFilePos' => 2272,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 73,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Programme\\Enums\\ActivityStatus::Draft->value]',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 80,
            'startTokenPos' => 187,
            'startFilePos' => 2353,
            'endTokenPos' => 200,
            'endFilePos' => 2409,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 80,
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
        'startLine' => 85,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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
            'name' => 'Database\\Factories\\ActivityFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'ownerMda' => 
      array (
        'name' => 'ownerMda',
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
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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