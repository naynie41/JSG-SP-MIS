<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/Models/Notification.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\Models\Notification
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9fb0da8933a12a90a31c67434338e8e6f0e9548fd098afda44e9a35ccd5aa98b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\Models\\Notification',
        'filename' => '/var/www/html/app/Domain/Notification/Models/Notification.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification\\Models',
    'name' => 'App\\Domain\\Notification\\Models\\Notification',
    'shortName' => 'Notification',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An in-app notification addressed to one recipient user (PRD FR-NOT-01). MDA-scoped
 * on `recipient_mda_id` (the recipient\'s MDA at send time) for cross-MDA isolation,
 * and always queried per-user (recipient_user_id) by the controller. Not Auditable —
 * notifications are derived, high-volume records.
 *
 * @property string $id
 * @property string $recipient_user_id
 * @property string|null $recipient_mda_id
 * @property string $type
 * @property string $subject
 * @property string|null $body
 * @property array<string, mixed>|null $payload
 * @property string|null $related_type
 * @property string|null $related_id
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property-read User $recipient
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 79,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      1 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'notifications\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 80,
            'startFilePos' => 1228,
            'endTokenPos' => 80,
            'endFilePos' => 1242,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'recipient_user_id\', \'recipient_mda_id\', \'type\', \'subject\', \'body\', \'payload\', \'related_type\', \'related_id\', \'read_at\']',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 53,
            'startTokenPos' => 91,
            'startFilePos' => 1313,
            'endTokenPos' => 120,
            'endFilePos' => 1511,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 53,
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
        'startLine' => 58,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Notification\\Models',
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'currentClassName' => 'App\\Domain\\Notification\\Models\\Notification',
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
        'docComment' => '/** Scoped to the recipient\'s MDA, not the Phase 2 owner column. */',
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Models',
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'currentClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'aliasName' => NULL,
      ),
      'recipient' => 
      array (
        'name' => 'recipient',
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
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Models',
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\Notification',
        'currentClassName' => 'App\\Domain\\Notification\\Models\\Notification',
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