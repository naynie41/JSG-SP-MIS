<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Models/ActivityLocation.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Models\ActivityLocation
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f0fdaa2d48af15f86c9c2820e231e35e72b489734e9af9691c1621ca3512c6e1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'filename' => '/var/www/html/app/Domain/Programme/Models/ActivityLocation.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Programme\\Models',
    'name' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
    'shortName' => 'ActivityLocation',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * One declared place in an activity\'s location set: an LGA, and optionally one ward
 * within it. `ward_id === null` means "the whole LGA".
 *
 * DESCRIPTIVE ONLY — see the migration. Nothing validates uploaded beneficiaries
 * against these rows.
 *
 * Not MdaScoped: the parent {@see Activity} is, and every read goes through it. Not
 * Auditable either — a location set is edited as a whole, so the meaningful audit
 * entry is on the activity, not on individual rows appearing and disappearing.
 *
 * @property string $id
 * @property string $activity_id
 * @property string $lga_id
 * @property string|null $ward_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Activity $activity
 * @property-read Lga $lga
 * @property-read Ward|null $ward
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 75,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'activity_locations\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 68,
            'startFilePos' => 1213,
            'endTokenPos' => 68,
            'endFilePos' => 1232,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'activity_id\', \'lga_id\', \'ward_id\']',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 79,
            'startFilePos' => 1303,
            'endTokenPos' => 87,
            'endFilePos' => 1338,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 63,
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
      'isWholeLga' => 
      array (
        'name' => 'isWholeLga',
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
        'docComment' => '/** True when this row declares the whole LGA rather than a single ward. */',
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
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
        'startLine' => 55,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'aliasName' => NULL,
      ),
      'lga' => 
      array (
        'name' => 'lga',
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
 * @return BelongsTo<Lga, $this>
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
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'aliasName' => NULL,
      ),
      'ward' => 
      array (
        'name' => 'ward',
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
 * @return BelongsTo<Ward, $this>
 */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\ActivityLocation',
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