<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Models/BenefitImportRow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Models\BenefitImportRow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-45c6e24e2d5747581b0b72e68d5d5b0caf8563e1fabee0accbf13df9ee5010f5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'filename' => '/var/www/html/app/Domain/Benefit/Models/BenefitImportRow.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Models',
    'name' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
    'shortName' => 'BenefitImportRow',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * One staged row of a benefit-delivery import (PRD FR-BEN-01/02, §8.3): a
 * reference to an existing beneficiary plus the parsed benefit fields and its
 * validation result. `benefit_id` is set on commit (idempotency). Not MDA-scoped
 * directly — reached through its (scoped) batch.
 *
 * @property string $id
 * @property string $benefit_import_batch_id
 * @property int $row_number
 * @property bool $is_valid
 * @property list<array{field: string, message: string}>|null $errors
 * @property bool $eligibility_flagged
 * @property string|null $resolved_beneficiary_id
 * @property array<string, mixed> $payload
 * @property string|null $benefit_id
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 85,
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
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'benefit_import_rows\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 73,
            'startFilePos' => 1175,
            'endTokenPos' => 73,
            'endFilePos' => 1195,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'benefit_import_batch_id\', \'row_number\', \'is_valid\', \'errors\', \'eligibility_flagged\', \'resolved_beneficiary_id\', \'payload\', \'benefit_id\']',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 49,
            'startTokenPos' => 84,
            'startFilePos' => 1266,
            'endTokenPos' => 110,
            'endFilePos' => 1474,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 49,
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
        'startLine' => 54,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
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
            'name' => 'Database\\Factories\\BenefitImportRowFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
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
 * @return BelongsTo<BenefitImportBatch, $this>
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
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
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
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