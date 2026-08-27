<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Sync/Models/SyncConnector.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Models\SyncConnector
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f9e490a293e041a3f856e561fe4a89d848fc8ca02d6ff8ad54eac56edef1357a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'filename' => '/var/www/html/app/Domain/Sync/Models/SyncConnector.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Models',
    'name' => 'App\\Domain\\Sync\\Models\\SyncConnector',
    'shortName' => 'SyncConnector',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A configured external/source system to synchronize from (FR-DSH-02): a SOCU or
 * government-system feed, owned by an MDA, with a conflict policy and a schedule.
 * Credentials are NOT stored here — `credentials_ref` keys into config/env.
 *
 * @property string $id
 * @property string $name
 * @property RegistrationSource $source
 * @property string $owner_mda_id
 * @property ConflictPolicy $conflict_policy
 * @property string|null $credentials_ref
 * @property bool $enabled
 * @property string|null $schedule
 * @property Carbon|null $last_run_at
 * @property array<string, string|null>|null $column_map
 * @property string|null $source_signature
 * @property Carbon|null $mapping_confirmed_at
 * @property string|null $mapping_confirmed_by
 * @property Carbon|null $mapping_stale_at
 * @property string|null $mapping_stale_reason
 * @property-read User|null $mappingConfirmedBy
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 40,
    'endLine' => 140,
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
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'sync_connectors\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 93,
            'startFilePos' => 1544,
            'endTokenPos' => 93,
            'endFilePos' => 1560,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'source\', \'owner_mda_id\', \'conflict_policy\', \'credentials_ref\', \'enabled\', \'schedule\', \'last_run_at\', \'column_map\', \'source_signature\', \'mapping_confirmed_at\', \'mapping_confirmed_by\', \'mapping_stale_at\', \'mapping_stale_reason\']',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 65,
            'startTokenPos' => 104,
            'startFilePos' => 1631,
            'endTokenPos' => 148,
            'endFilePos' => 1985,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
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
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'aliasName' => NULL,
      ),
      'mappingStatus' => 
      array (
        'name' => 'mappingStatus',
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
 * The connector\'s mapping state, as an administrator needs to see it.
 *
 * `stale` is the one that matters: a standing approval that no longer describes what
 * the source is sending. It is a distinct state from "never configured" because the
 * remedy differs — one needs a first mapping, the other needs a REVIEW of a mapping
 * that used to be right.
 */',
        'startLine' => 91,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'aliasName' => NULL,
      ),
      'mappingIsStale' => 
      array (
        'name' => 'mappingIsStale',
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
        'docComment' => '/** Whether the source\'s shape has moved since the mapping was approved. */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'aliasName' => NULL,
      ),
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
 * Whether a person has approved which source field holds each identity value
 * (CLAUDE.md §11). A connector runs unattended, so this confirmation is given once at
 * configuration time and STANDS for later runs — but a run whose records no longer
 * match {@see $source_signature} must stop and ask again.
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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
 * Who gave the standing mapping approval. Shown in the UI because a standing
 * approval is accountable to a person, not just a timestamp.
 *
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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
            'name' => 'Database\\Factories\\SyncConnectorFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Sync\\Models',
        'declaringClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'implementingClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
        'currentClassName' => 'App\\Domain\\Sync\\Models\\SyncConnector',
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