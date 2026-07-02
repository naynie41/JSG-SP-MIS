<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/OwnershipTransferRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\OwnershipTransferRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-244f7f3ca07c9e25ed4b1542a1de21e1af7b26a1fac3024110914370c9f40c6c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'filename' => '/var/www/html/app/Domain/Registry/Models/OwnershipTransferRequest.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
    'shortName' => 'OwnershipTransferRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A request to transfer beneficiary ownership between MDAs (PRD FR-OWN-05).
 * Audited via Auditable so request + decision are on the record.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property OwnershipTransferStatus $status
 * @property string|null $reason
 * @property string|null $requested_by
 * @property string|null $decided_by
 * @property Carbon|null $decided_at
 * @property string|null $decision_reason
 * @property-read Beneficiary $beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 103,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'ownership_transfer_requests\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 96,
            'startFilePos' => 1252,
            'endTokenPos' => 96,
            'endFilePos' => 1280,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 53,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'from_mda_id\', \'to_mda_id\', \'status\', \'reason\', \'requested_by\', \'decided_by\', \'decided_at\', \'decision_reason\']',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 54,
            'startTokenPos' => 107,
            'startFilePos' => 1351,
            'endTokenPos' => 136,
            'endFilePos' => 1558,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 54,
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
        'startLine' => 59,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
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
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'aliasName' => NULL,
      ),
      'fromMda' => 
      array (
        'name' => 'fromMda',
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
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'aliasName' => NULL,
      ),
      'toMda' => 
      array (
        'name' => 'toMda',
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
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'aliasName' => NULL,
      ),
      'requestedBy' => 
      array (
        'name' => 'requestedBy',
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
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
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
            'name' => 'Database\\Factories\\OwnershipTransferRequestFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 99,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
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