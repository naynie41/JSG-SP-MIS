<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Models/Activity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Models\Activity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-03f0b4950c66e6cfcc0f65692ea000ebfa47b76648d1e6a551653c1c50153b92',
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
 * An MDA-owned unit of work that runs a global catalog {@see Programme} (PRD §10,
 * ARCH §12.4, FR-PRG-02). `owner_mda_id` is the CREATING MDA — its own MdaScope
 * column — so one programme can be run by many MDAs, each through its own activity.
 * The MDA-specific execution details (budget, funding source, schedule, period)
 * live here, not on the programme. Auditable; money is integer minor units (kobo,
 * NGN). A PostGIS `geom` column exists on PostgreSQL for later GIS work.
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
 * @property string|null $funding_source
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
    'startLine' => 50,
    'endLine' => 128,
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
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 121,
            'startFilePos' => 2041,
            'endTokenPos' => 121,
            'endFilePos' => 2052,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
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
          'code' => '[\'programme_id\', \'owner_mda_id\', \'name\', \'description\', \'target_count\', \'lga\', \'ward\', \'location_description\', \'schedule\', \'starts_on\', \'ends_on\', \'budget_amount\', \'funding_source\', \'status\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 76,
            'startTokenPos' => 132,
            'startFilePos' => 2123,
            'endTokenPos' => 179,
            'endFilePos' => 2454,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 76,
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
            'startLine' => 81,
            'endLine' => 83,
            'startTokenPos' => 190,
            'startFilePos' => 2535,
            'endTokenPos' => 203,
            'endFilePos' => 2591,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 83,
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
        'startLine' => 88,
        'endLine' => 98,
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
        'startLine' => 100,
        'endLine' => 103,
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
        'startLine' => 108,
        'endLine' => 111,
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
        'startLine' => 116,
        'endLine' => 119,
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
        'startLine' => 124,
        'endLine' => 127,
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