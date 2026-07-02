<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Audit/Models/AuditLog.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Audit\Models\AuditLog
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7990b6b04f64b90d3ea36b5735a22ea90fb3d906fdc2738ef33f7621234761ef',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'filename' => '/var/www/html/app/Domain/Audit/Models/AuditLog.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Audit\\Models',
    'name' => 'App\\Domain\\Audit\\Models\\AuditLog',
    'shortName' => 'AuditLog',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An immutable audit log entry (FR-AUD-01). Append-only: the model refuses to be
 * updated or deleted in application code, and the database enforces the same with
 * triggers (NFR-AUD-01). Never make this model Auditable (no recursion).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 64,
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
      'timestamps' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 53,
            'startFilePos' => 506,
            'endTokenPos' => 53,
            'endFilePos' => 510,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'audit_log\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 62,
            'startFilePos' => 537,
            'endTokenPos' => 62,
            'endFilePos' => 547,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
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
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'actor_id\', \'actor_mda_id\', \'action\', \'entity_type\', \'entity_id\', \'before\', \'after\', \'ip_address\', \'user_agent\', \'correlation_id\', \'created_at\']',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 36,
            'startTokenPos' => 71,
            'startFilePos' => 577,
            'endTokenPos' => 106,
            'endFilePos' => 816,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 36,
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
        'startLine' => 41,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Audit\\Models',
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'currentClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Application-level append-only guard (defence in depth alongside the DB
 * triggers; also enforced on sqlite used in tests).
 */',
        'startLine' => 54,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Audit\\Models',
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'currentClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
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