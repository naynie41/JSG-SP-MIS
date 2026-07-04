<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Models\BenefitImportBatch
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ab0350f31226f879a93debe2a0daf5bde93fabb2f916626b5d1c96fdd2e8be1c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'filename' => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Models',
    'name' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
    'shortName' => 'BenefitImportBatch',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A bulk benefit-delivery import batch (PRD FR-BEN-01/02, §8.3): a distribution
 * list keyed to an activity. Scoped to (and owned by) the importing = delivering
 * MDA. Reuses the Phase 2 import lifecycle.
 *
 * @property string $id
 * @property string $mda_id
 * @property string $activity_id
 * @property string $programme_id
 * @property string|null $uploaded_by
 * @property string $original_filename
 * @property string $stored_path
 * @property string $source
 * @property ImportStatus $status
 * @property int $total_rows
 * @property int $valid_rows
 * @property int $invalid_rows
 * @property int $committed_rows
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Activity $activity
 * @property-read Programme $programme
 * @property-read Mda $mda
 * @property-read Collection<int, BenefitImportRow> $rows
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 49,
    'endLine' => 136,
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
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'benefit_import_batches\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 128,
            'startFilePos' => 1835,
            'endTokenPos' => 128,
            'endFilePos' => 1858,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'mda_id\', \'activity_id\', \'programme_id\', \'uploaded_by\', \'original_filename\', \'stored_path\', \'source\', \'status\', \'total_rows\', \'valid_rows\', \'invalid_rows\', \'committed_rows\', \'error\']',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 84,
            'startTokenPos' => 192,
            'startFilePos' => 2224,
            'endTokenPos' => 233,
            'endFilePos' => 2517,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 84,
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
        'docComment' => NULL,
        'startLine' => 56,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
        'docComment' => '/** Volatile progress counters shouldn\'t generate audit noise. */',
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
        'startLine' => 89,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
            'name' => 'Database\\Factories\\BenefitImportBatchFactory',
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
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
        'startLine' => 116,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'aliasName' => NULL,
      ),
      'mda' => 
      array (
        'name' => 'mda',
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
        'startLine' => 124,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
 * @return HasMany<BenefitImportRow, $this>
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
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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