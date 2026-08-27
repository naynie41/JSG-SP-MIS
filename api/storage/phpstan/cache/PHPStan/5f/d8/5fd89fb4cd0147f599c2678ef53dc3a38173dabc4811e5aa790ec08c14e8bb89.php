<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Audit/Models/AuditLog.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Audit\Models\AuditLog
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-339ea8c889c477ab1dd847693c63cb9ec521c6a6e83f65bd02f8aba7d25d2589',
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
 *
 * Tamper-evidence is layered (Phase 7 hardening):
 *  1. no UPDATE/DELETE/TRUNCATE — app guard + PostgreSQL triggers;
 *  2. hash chain — every entry stores `chain_position`, the previous entry\'s
 *     `entry_hash`, and a SHA-256 over its own canonical payload + that hash, so
 *     any edit/removal/reorder breaks every later hash. Verified offline with
 *     `php artisan audit:verify-chain`. Rows older than the chain migration have
 *     a NULL position (pre-chain era; the log itself was never mutable).
 *
 * @property string $id
 * @property string|null $actor_id
 * @property string|null $actor_mda_id
 * @property string $action
 * @property string|null $entity_type
 * @property string|null $entity_id
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $correlation_id
 * @property int|null $chain_position
 * @property string|null $prev_hash
 * @property string|null $entry_hash
 * @property Carbon|null $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 43,
    'endLine' => 186,
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
      'GENESIS_HASH' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'name' => 'GENESIS_HASH',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'0000000000000000000000000000000000000000000000000000000000000000\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 70,
            'startFilePos' => 1705,
            'endTokenPos' => 70,
            'endFilePos' => 1770,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 99,
      ),
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
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 79,
            'startFilePos' => 1799,
            'endTokenPos' => 79,
            'endFilePos' => 1803,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
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
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 88,
            'startFilePos' => 1830,
            'endTokenPos' => 88,
            'endFilePos' => 1840,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
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
            'startLine' => 53,
            'endLine' => 65,
            'startTokenPos' => 97,
            'startFilePos' => 1870,
            'endTokenPos' => 132,
            'endFilePos' => 2109,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 65,
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
        'startLine' => 70,
        'endLine' => 77,
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
 * triggers; also enforced on sqlite used in tests). The creating hook links
 * each new entry into the hash chain.
 */',
        'startLine' => 84,
        'endLine' => 110,
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
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 119,
                'endLine' => 119,
                'startTokenPos' => 390,
                'startFilePos' => 4081,
                'endTokenPos' => 391,
                'endFilePos' => 4082,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 26,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
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
 * Insert with a small retry: concurrent writers may race for the same chain
 * position; the partial-unique index rejects the loser, which simply re-reads
 * the new head and links again. Never retried for any other failure.
 *
 * @param  array<string, mixed>  $options
 */',
        'startLine' => 119,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Models',
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'currentClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'aliasName' => NULL,
      ),
      'computeEntryHash' => 
      array (
        'name' => 'computeEntryHash',
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
        'docComment' => '/**
 * The entry\'s tamper-evident hash: SHA-256 over the canonical JSON of every
 * persisted field plus the previous entry\'s hash. Deterministic across
 * drivers (sorted keys, fixed timestamp format), so a verifier can recompute
 * it from the stored row alone.
 */',
        'startLine' => 144,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Models',
        'declaringClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'implementingClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'currentClassName' => 'App\\Domain\\Audit\\Models\\AuditLog',
        'aliasName' => NULL,
      ),
      'canonicalize' => 
      array (
        'name' => 'canonicalize',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
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
                      'name' => 'array',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 42,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'array',
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
 * Recursively key-sort an array so hashing is independent of insertion order.
 *
 * @param  array<array-key, mixed>|null  $value
 * @return array<array-key, mixed>|null
 */',
        'startLine' => 171,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
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