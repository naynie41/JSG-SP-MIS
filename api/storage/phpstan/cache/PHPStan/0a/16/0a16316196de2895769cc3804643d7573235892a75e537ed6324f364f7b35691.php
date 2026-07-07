<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Grievance/Models/Grievance.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Grievance\Models\Grievance
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-736416937988ff2b10560021b26ef9ce8a53e40e9d1570ced11cece9fa85caef',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'filename' => '/var/www/html/app/Domain/Grievance/Models/Grievance.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Grievance\\Models',
    'name' => 'App\\Domain\\Grievance\\Models\\Grievance',
    'shortName' => 'Grievance',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A grievance handled within one MDA (PRD FR-GRM-01/02). MDA-scoped on
 * `handling_mda_id` (only that MDA + oversight see it) and Auditable; transitions
 * are audited explicitly so the volatile columns are excluded from the generic
 * update audit.
 *
 * @property string $id
 * @property string $handling_mda_id
 * @property string|null $beneficiary_id
 * @property GrievanceCategory $category
 * @property GrievanceChannel $channel
 * @property string $description
 * @property GrievanceStatus $status
 * @property string|null $assignee_user_id
 * @property string|null $resolution_notes
 * @property string|null $submitted_by
 * @property int $escalation_level
 * @property Carbon|null $assigned_at
 * @property Carbon|null $started_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $sla_breached_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mda $handlingMda
 * @property-read Beneficiary|null $beneficiary
 * @property-read User|null $assignee
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 49,
    'endLine' => 137,
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
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'grievances\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 113,
            'startFilePos' => 1818,
            'endTokenPos' => 113,
            'endFilePos' => 1829,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'handling_mda_id\', \'beneficiary_id\', \'category\', \'channel\', \'description\', \'status\', \'assignee_user_id\', \'resolution_notes\', \'submitted_by\', \'escalation_level\', \'assigned_at\', \'started_at\', \'resolved_at\', \'closed_at\', \'sla_breached_at\']',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 74,
            'startTokenPos' => 124,
            'startFilePos' => 1900,
            'endTokenPos' => 171,
            'endFilePos' => 2263,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 74,
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
        'startLine' => 79,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'aliasName' => NULL,
      ),
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
        'docComment' => '/** Scoped to the handling MDA, not the Phase 2 owner column. */',
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
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
 * Transitions/assignment are audited explicitly; keep them out of the generic
 * update audit.
 *
 * @return list<string>
 */',
        'startLine' => 106,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'aliasName' => NULL,
      ),
      'handlingMda' => 
      array (
        'name' => 'handlingMda',
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
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
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
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'aliasName' => NULL,
      ),
      'assignee' => 
      array (
        'name' => 'assignee',
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
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\Grievance',
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