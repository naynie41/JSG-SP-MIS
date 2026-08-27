<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Sync/Models/SyncRunRow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Models\SyncRunRow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8177ece4a478ba8d75012546cbdb69c354ced5f7b6754939ae88511bb98bdf7e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'filename' => '/var/www/html/app/Domain/Sync/Models/SyncRunRow.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Models',
    'name' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
    'shortName' => 'SyncRunRow',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The append-only, per-record outcome of a sync run (FR-DSH-02) — the sync log an
 * admin reads to see exactly what each record did. Never updated or deleted.
 *
 * @property string $id
 * @property string $sync_run_id
 * @property string|null $original_record_id
 * @property SyncRowOutcome $outcome
 * @property string|null $beneficiary_id
 * @property string|null $match_band
 * @property array<string, mixed>|null $detail
 * @property Carbon $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 65,
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
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'sync_run_rows\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 63,
            'startFilePos' => 833,
            'endTokenPos' => 63,
            'endFilePos' => 847,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamps' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 72,
            'startFilePos' => 876,
            'endTokenPos' => 72,
            'endFilePos' => 880,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'sync_run_id\', \'original_record_id\', \'outcome\', \'beneficiary_id\', \'match_band\', \'detail\']',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 44,
            'startTokenPos' => 83,
            'startFilePos' => 951,
            'endTokenPos' => 103,
            'endFilePos' => 1095,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 44,
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
        'startLine' => 49,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'aliasName' => NULL,
      ),
      'run' => 
      array (
        'name' => 'run',
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
 * @return BelongsTo<SyncRun, $this>
 */',
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
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