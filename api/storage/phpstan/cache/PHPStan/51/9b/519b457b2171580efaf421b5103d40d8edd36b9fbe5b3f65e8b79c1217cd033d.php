<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ImportBatch.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportBatch
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a40a9699c2bdf827b3f5d95716f9bfe525a86e52a584f455f07650a9c59b8a51',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'filename' => '/var/www/html/app/Domain/Registry/Models/ImportBatch.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\ImportBatch',
    'shortName' => 'ImportBatch',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A bulk import batch (PRD FR-REG-02/06). Owned by, and MDA-scoped to, the
 * uploading MDA; the lifecycle status transitions are audited. Volatile counters
 * are excluded from the audit to avoid noise.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property string|null $uploaded_by
 * @property string $original_filename
 * @property string $stored_path
 * @property RegistrationSource $source
 * @property string|null $activity_id
 * @property array<string, mixed>|null $draft_activity
 * @property ImportStatus $status
 * @property int $total_rows
 * @property int $valid_rows
 * @property int $invalid_rows
 * @property int $rejected_rows
 * @property int $dropped_field_rows
 * @property int $committed_rows
 * @property int $served_rows
 * @property int $skipped_rows
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mda|null $ownerMda
 * @property-read User|null $uploadedBy
 * @property-read Collection<int, ImportRow> $rows
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 51,
    'endLine' => 144,
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
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'import_batches\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 118,
            'startFilePos' => 1837,
            'endTokenPos' => 118,
            'endFilePos' => 1852,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'owner_mda_id\', \'uploaded_by\', \'original_filename\', \'stored_path\', \'source\', \'activity_id\', \'draft_activity\', \'status\', \'total_rows\', \'valid_rows\', \'invalid_rows\', \'rejected_rows\', \'dropped_field_rows\', \'committed_rows\', \'served_rows\', \'skipped_rows\', \'error\']',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 78,
            'startTokenPos' => 129,
            'startFilePos' => 1923,
            'endTokenPos' => 182,
            'endFilePos' => 2326,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 78,
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
        'startLine' => 83,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'aliasName' => NULL,
      ),
      'auditExcluded' => 
      array (
        'name' => 'auditExcluded',
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
 * Volatile counters produce no audit noise; status transitions still do.
 *
 * @return list<string>
 */',
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
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
 * The registered activity this upload is bound to (PRD §9, FR-REG-10). The
 * resulting intervention (enrollment) is recorded under it.
 *
 * @return BelongsTo<Activity, $this>
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'aliasName' => NULL,
      ),
      'uploadedBy' => 
      array (
        'name' => 'uploadedBy',
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
        'startLine' => 132,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
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
 * @return HasMany<ImportRow, $this>
 */',
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
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