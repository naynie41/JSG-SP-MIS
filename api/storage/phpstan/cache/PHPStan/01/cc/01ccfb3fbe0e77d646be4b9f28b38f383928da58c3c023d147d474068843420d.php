<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ImportRow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportRow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-aaad26e56a07ae6f82c0511c68061c99209b4a97158956b8a49a856ec10bd58e',
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
 * @property string|null $match_band
 * @property list<array<string, mixed>>|null $match_candidates
 * @property string|null $resolution
 * @property string|null $resolution_note
 * @property string|null $resolved_beneficiary_id
 * @property string|null $resolved_by
 * @property Carbon|null $resolved_at
 * @property string|null $beneficiary_id
 * @property-read ImportBatch $batch
 * @property-read Beneficiary|null $beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 99,
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
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 58,
            'startFilePos' => 1462,
            'endTokenPos' => 58,
            'endFilePos' => 1474,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
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
          'code' => '[\'import_batch_id\', \'row_number\', \'original_record_id\', \'household_ref\', \'household_role\', \'household_head\', \'payload\', \'is_valid\', \'errors\', \'match_band\', \'match_candidates\', \'resolution\', \'resolution_note\', \'resolved_beneficiary_id\', \'resolved_by\', \'resolved_at\', \'beneficiary_id\']',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 66,
            'startTokenPos' => 69,
            'startFilePos' => 1545,
            'endTokenPos' => 122,
            'endFilePos' => 1970,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 66,
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
        'startLine' => 71,
        'endLine' => 82,
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
        'startLine' => 87,
        'endLine' => 90,
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
        'startLine' => 95,
        'endLine' => 98,
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