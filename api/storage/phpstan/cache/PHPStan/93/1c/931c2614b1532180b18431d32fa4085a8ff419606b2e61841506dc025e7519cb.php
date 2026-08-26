<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Notification\NotificationServiceProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\NotificationServiceProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a9a207374f92e2f6b12eb0e3d07adb4801de861d9897aba067ece3dd36e153ba',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Notification/NotificationServiceProvider.php',
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
    'endLine' => 48,
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
      'CHANNELS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'implementingClassName' => 'App\\Domain\\Notification\\NotificationServiceProvider',
        'name' => 'CHANNELS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'notification.channels\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 77,
            'startFilePos' => 926,
            'endTokenPos' => 77,
            'endFilePos' => 948,
          ),
        ),
        'docComment' => '/** The container key for the registered channel set. */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
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
        'startLine' => 27,
        'endLine' => 42,
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
        'startLine' => 44,
        'endLine' => 47,
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