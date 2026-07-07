<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/NotificationServiceProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\NotificationServiceProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1d6f1323ac9eeebf0162d6ec3a050737cece4fd3b9b2192da08d45880ec789ef',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'filename' => '/var/www/html/app/Domain/Notification/NotificationServiceProvider.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification',
    'name' => 'App\\Domain\\Notification\\NotificationServiceProvider',
    'shortName' => 'NotificationServiceProvider',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Wires the Notification domain (PRD FR-NOT-01/02): the Notifier with its channel
 * set (in-app + email real; SMS + WhatsApp stubbed/unavailable), and the
 * event-driven subscriber that turns domain events into notifications. Adding a
 * real SMS/WhatsApp client later is a one-line binding change here.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 38,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\ServiceProvider',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'register' => 
      array (
        'name' => 'register',
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
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification',
        'declaringClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'implementingClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'currentClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
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
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification',
        'declaringClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'implementingClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'currentClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
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