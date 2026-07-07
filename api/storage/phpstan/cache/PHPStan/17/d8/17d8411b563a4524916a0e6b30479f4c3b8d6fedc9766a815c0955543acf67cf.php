<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/Models/NotificationPreference.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\Models\NotificationPreference
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8e3f417b034240f0226a1dbd9d1a58bc06cc5000412c55df91fa951873333bc7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'filename' => '/var/www/html/app/Domain/Notification/Models/NotificationPreference.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification\\Models',
    'name' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
    'shortName' => 'NotificationPreference',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A user\'s notification channel preferences (PRD FR-NOT-02). Per-user (no MDA
 * scope). In-app is always delivered; these toggles gate outbound channels.
 *
 * @property string $id
 * @property string $user_id
 * @property bool $email_enabled
 * @property-read User $user
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 52,
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
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'notification_preferences\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 58,
            'startFilePos' => 627,
            'endTokenPos' => 58,
            'endFilePos' => 652,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'user_id\', \'email_enabled\']',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 33,
            'startTokenPos' => 69,
            'startFilePos' => 723,
            'endTokenPos' => 77,
            'endFilePos' => 773,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 33,
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
        'startLine' => 38,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Notification\\Models',
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'currentClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'aliasName' => NULL,
      ),
      'user' => 
      array (
        'name' => 'user',
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
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Models',
        'declaringClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'implementingClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
        'currentClassName' => 'App\\Domain\\Notification\\Models\\NotificationPreference',
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