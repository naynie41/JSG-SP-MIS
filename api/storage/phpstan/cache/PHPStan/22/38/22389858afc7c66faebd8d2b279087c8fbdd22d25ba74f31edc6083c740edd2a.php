<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Sync\Models\SyncConnector.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Models\SyncConnector
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-80400a44ad20f0223ce9afb87a9f93410e77238538022f3cafc8655c1cda3e0a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Sync/Models/SyncConnector.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Models',
    'name' => 'App\\Domain\\Sync\\Models\\SyncConnector',
    'shortName' => 'SyncConnector',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A configured external/source system to synchronize from (FR-DSH-02): a SOCU or
 * government-system feed, owned by an MDA, with a conflict policy and a schedule.
 * Credentials are NOT stored here — `credentials_ref` keys into config/env.
 *
 * @property string $id
 * @property string $name
 * @property RegistrationSource $source
 * @property string $owner_mda_id
 * @property ConflictPolicy $conflict_policy
 * @property string|null $credentials_ref
 * @property bool $enabled
 * @property string|null $schedule
 * @property Carbon|null $last_run_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 78,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'sync_connectors\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 88,
            'startFilePos' => 1177,
            'endTokenPos' => 88,
            'endFilePos' => 1193,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'source\', \'owner_mda_id\', \'conflict_policy\', \'credentials_ref\', \'enabled\', \'schedule\', \'last_run_at\']',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 51,
            'startTokenPos' => 99,
            'startFilePos' => 1264,
            'endTokenPos' => 125,
            'endFilePos' => 1444,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 51,
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
        'startLine' => 56,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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
            'name' => 'Database\\Factories\\SyncConnectorFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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