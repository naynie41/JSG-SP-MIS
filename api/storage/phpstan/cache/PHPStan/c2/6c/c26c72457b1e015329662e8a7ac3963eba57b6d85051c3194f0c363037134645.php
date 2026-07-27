<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Sync\Models\SyncRun.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Models\SyncRun
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2530fe302ca6fa458b60cdf725f29bb91c59a2cbf344d0b216788d8658c33e83',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Sync/Models/SyncRun.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Models',
    'name' => 'App\\Domain\\Sync\\Models\\SyncRun',
    'shortName' => 'SyncRun',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * One execution of synchronization (FR-DSH-02, FR-REG-08) — scheduled, manually
 * triggered, or an offline batch. Carries the running tallies surfaced to admins.
 *
 * @property string $id
 * @property string|null $connector_id
 * @property SyncTrigger $trigger
 * @property string $source
 * @property string $owner_mda_id
 * @property ConflictPolicy $conflict_policy
 * @property SyncStatus $status
 * @property int $fetched_count
 * @property int $created_count
 * @property int $updated_count
 * @property int $skipped_count
 * @property int $flagged_count
 * @property int $rejected_count
 * @property int $error_count
 * @property string|null $error
 * @property string|null $triggered_by
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 90,
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
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'sync_runs\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 78,
            'startFilePos' => 1271,
            'endTokenPos' => 78,
            'endFilePos' => 1281,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'connector_id\', \'trigger\', \'source\', \'owner_mda_id\', \'conflict_policy\', \'status\', \'fetched_count\', \'created_count\', \'updated_count\', \'skipped_count\', \'flagged_count\', \'rejected_count\', \'error_count\', \'error\', \'triggered_by\', \'started_at\', \'finished_at\']',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 52,
            'startTokenPos' => 89,
            'startFilePos' => 1352,
            'endTokenPos' => 142,
            'endFilePos' => 1636,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 52,
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
        'startLine' => 57,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'aliasName' => NULL,
      ),
      'connector' => 
      array (
        'name' => 'connector',
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
 * @return BelongsTo<SyncConnector, $this>
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'aliasName' => NULL,
      ),
      'rows' => 
      array (
        'name' => 'rows',
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
 * @return HasMany<SyncRunRow, $this>
 */',
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncRun',
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