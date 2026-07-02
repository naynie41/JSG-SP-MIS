<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Models/Mda.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Models\Mda
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f6259a074f9d66f3a1d70b6a00eb78cc70d713a12cedc5cf8128f26f0dcdfefd',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Models\\Mda',
        'filename' => '/var/www/html/app/Domain/Access/Models/Mda.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Models',
    'name' => 'App\\Domain\\Access\\Models\\Mda',
    'shortName' => 'Mda',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A Ministry, Department or Agency. Owns its users and (later) the beneficiary
 * records it originates.
 *
 * @property string $id
 * @property string $name
 * @property MdaType $type
 * @property MdaStatus $status
 * @property string|null $contact_person
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 106,
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
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'mdas\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 116,
            'startFilePos' => 1216,
            'endTokenPos' => 116,
            'endFilePos' => 1221,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'type\', \'contact_person\', \'contact_email\', \'contact_phone\', \'address\', \'status\']',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 62,
            'startTokenPos' => 149,
            'startFilePos' => 1541,
            'endTokenPos' => 172,
            'endFilePos' => 1692,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 62,
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
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Access\\Enums\\MdaStatus::Active->value]',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 71,
            'startTokenPos' => 183,
            'startFilePos' => 1839,
            'endTokenPos' => 196,
            'endFilePos' => 1891,
          ),
        ),
        'docComment' => '/**
 * Model-level default mirroring the database default.
 *
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 71,
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
        'docComment' => '/**
 * An MDA is scoped on its own primary key: a user sees their own MDA (and
 * any granted MDAs), unless they hold cross-mda.view (FR-UAM-03).
 */',
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'currentClassName' => 'App\\Domain\\Access\\Models\\Mda',
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
        'startLine' => 76,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'currentClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'aliasName' => NULL,
      ),
      'users' => 
      array (
        'name' => 'users',
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
 * @return HasMany<User, $this>
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
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'currentClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'aliasName' => NULL,
      ),
      'accessGrants' => 
      array (
        'name' => 'accessGrants',
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
 * Cross-MDA grants that give other users access to this MDA.
 *
 * @return HasMany<MdaAccessGrant, $this>
 */',
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'currentClassName' => 'App\\Domain\\Access\\Models\\Mda',
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
            'name' => 'Database\\Factories\\MdaFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 102,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\Mda',
        'currentClassName' => 'App\\Domain\\Access\\Models\\Mda',
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