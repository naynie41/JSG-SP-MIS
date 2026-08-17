<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Models\ImportBatch.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportBatch
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-439f12d9fde5e7da8d6f6bd5cb8139970318a8689b1eaf04ee580ba7864e5f99',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Models/ImportBatch.php',
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
 * @property list<string>|null $detected_headers
 * @property array<string, string|null>|null $column_map
 * @property string|null $source_signature
 * @property Carbon|null $mapping_confirmed_at
 * @property string|null $mapping_confirmed_by
 * @property string|null $mapping_template_id
 * @property-read ImportMappingTemplate|null $mappingTemplate
 * @property-read User|null $mappingConfirmedBy
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
    'startLine' => 59,
    'endLine' => 229,
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
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 118,
            'startFilePos' => 2236,
            'endTokenPos' => 118,
            'endFilePos' => 2251,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
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
          'code' => '[\'owner_mda_id\', \'uploaded_by\', \'original_filename\', \'stored_path\', \'source\', \'activity_id\', \'draft_activity\', \'detected_headers\', \'column_map\', \'source_signature\', \'mapping_confirmed_at\', \'mapping_confirmed_by\', \'mapping_template_id\', \'status\', \'total_rows\', \'valid_rows\', \'invalid_rows\', \'rejected_rows\', \'dropped_field_rows\', \'committed_rows\', \'served_rows\', \'skipped_rows\', \'error\']',
          'attributes' => 
          array (
            'startLine' => 116,
            'endLine' => 140,
            'startTokenPos' => 340,
            'startFilePos' => 4140,
            'endTokenPos' => 411,
            'endFilePos' => 4716,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 140,
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
      'mappingIsConfirmed' => 
      array (
        'name' => 'mappingIsConfirmed',
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
        'docComment' => '/**
 * Whether a human has confirmed which source column is which canonical field
 * (CLAUDE.md §11). Nothing may be parsed, screened or committed until this is true.
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'aliasName' => NULL,
      ),
      'isProcessing' => 
      array (
        'name' => 'isProcessing',
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
        'docComment' => '/**
 * Whether the batch is waiting on the queue (nothing for a human to do yet).
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportBatch',
        'aliasName' => NULL,
      ),
      'processingForSeconds' => 
      array (
        'name' => 'processingForSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Seconds this batch has been waiting on the queue, or null when it is not waiting.
 *
 * Measured from `updated_at` — the last sign of life. A live worker at minimum flips
 * pending → processing, which touches the row; a batch whose timestamp is frozen is
 * one nothing has looked at.
 */',
        'startLine' => 89,
        'endLine' => 97,
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
      'processingLooksStalled' => 
      array (
        'name' => 'processingLooksStalled',
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
        'docComment' => '/**
 * Waiting on the queue for longer than parsing could plausibly take.
 *
 * Almost always means no queue worker is consuming — the failure mode that has no
 * error anywhere, because nothing failed: the job was never picked up. Computed
 * SERVER-side because only the server\'s clock can be trusted for this.
 */',
        'startLine' => 106,
        'endLine' => 111,
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
        'startLine' => 145,
        'endLine' => 163,
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
        'startLine' => 170,
        'endLine' => 173,
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
        'startLine' => 178,
        'endLine' => 181,
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
        'startLine' => 189,
        'endLine' => 192,
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
        'startLine' => 197,
        'endLine' => 200,
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
      'mappingConfirmedBy' => 
      array (
        'name' => 'mappingConfirmedBy',
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
        'startLine' => 205,
        'endLine' => 208,
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
      'mappingTemplate' => 
      array (
        'name' => 'mappingTemplate',
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
 * The saved mapping this batch used, when one applied (CLAUDE.md §11). Null for a
 * file shape seen for the first time — and the batch\'s own `column_map` is the
 * record of what was actually applied either way.
 *
 * @return BelongsTo<ImportMappingTemplate, $this>
 */',
        'startLine' => 217,
        'endLine' => 220,
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
        'startLine' => 225,
        'endLine' => 228,
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