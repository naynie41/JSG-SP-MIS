<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ImportRow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportRow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9666e37f0b29fc3de264dd5a9c87bb036df1aafee405ebbc5a339a0dff745310',
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
 * @property string|null $household_ref
 * @property string|null $household_role
 * @property bool $household_head
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
    'startLine' => 31,
    'endLine' => 82,
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
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 53,
            'startFilePos' => 1126,
            'endTokenPos' => 53,
            'endFilePos' => 1138,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
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
          'code' => '[\'import_batch_id\', \'row_number\', \'original_record_id\', \'household_ref\', \'household_role\', \'household_head\', \'payload\', \'is_valid\', \'errors\', \'beneficiary_id\']',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 51,
            'startTokenPos' => 64,
            'startFilePos' => 1209,
            'endTokenPos' => 96,
            'endFilePos' => 1454,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
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
        'endLine' => 65,
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
        'startLine' => 70,
        'endLine' => 73,
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
        'startLine' => 78,
        'endLine' => 81,
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