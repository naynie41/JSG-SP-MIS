<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ImportRow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportRow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d24e362c65334ed4e6cd829f3943a565e894c54f9a7ff508f0a4e833d54a1d0e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'filename' => '/var/www/html/app/Domain/Registry/Models/ImportRow.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\ImportRow',
    'shortName' => 'ImportRow',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * One staged source row of a bulk import (PRD FR-REG-06). Holds the normalised
 * values and the row-level validation result; `beneficiary_id` is set when the
 * row is committed, which makes commits idempotent. Not MDA-scoped directly —
 * always reached through its (scoped, authorized) ImportBatch.
 *
 * @property string $id
 * @property string $import_batch_id
 * @property int $row_number
 * @property string|null $original_record_id
 * @property array<string, mixed> $payload
 * @property bool $is_valid
 * @property list<array{field: string, message: string}>|null $errors
 * @property string|null $beneficiary_id
 * @property-read ImportBatch $batch
 * @property-read Beneficiary|null $beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
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
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'import_rows\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 53,
            'startFilePos' => 1011,
            'endTokenPos' => 53,
            'endFilePos' => 1023,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'import_batch_id\', \'row_number\', \'original_record_id\', \'payload\', \'is_valid\', \'errors\', \'beneficiary_id\']',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 45,
            'startTokenPos' => 64,
            'startFilePos' => 1094,
            'endTokenPos' => 87,
            'endFilePos' => 1262,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 45,
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
        'startLine' => 50,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'aliasName' => NULL,
      ),
      'batch' => 
      array (
        'name' => 'batch',
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
 * @return BelongsTo<ImportBatch, $this>
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'aliasName' => NULL,
      ),
      'beneficiary' => 
      array (
        'name' => 'beneficiary',
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
 * @return BelongsTo<Beneficiary, $this>
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
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportRow',
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