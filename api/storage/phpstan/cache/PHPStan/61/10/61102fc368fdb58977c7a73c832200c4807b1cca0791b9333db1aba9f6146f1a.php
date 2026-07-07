<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/Contracts/NotificationChannel.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\Contracts\NotificationChannel
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-024de6263ac28837e31314ae4245d5c891989c33dda0e64fc0153f653ffe87db',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'filename' => '/var/www/html/app/Domain/Notification/Contracts/NotificationChannel.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification\\Contracts',
    'name' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
    'shortName' => 'NotificationChannel',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A delivery channel for notifications (PRD FR-NOT-01/02). In-app and email are
 * implemented; SMS and WhatsApp are stubbed and report themselves **unavailable**
 * until an external gateway is provided (never fabricated — see CLAUDE §8).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 25,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
      'key' => 
      array (
        'name' => 'key',
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
        'docComment' => '/** Stable channel key (in_app | email | sms | whatsapp). */',
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Contracts',
        'declaringClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'implementingClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'currentClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'aliasName' => NULL,
      ),
      'isAvailable' => 
      array (
        'name' => 'isAvailable',
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
        'docComment' => '/** Whether the channel can actually deliver right now (stubs return false). */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Contracts',
        'declaringClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'implementingClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'currentClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'aliasName' => NULL,
      ),
      'send' => 
      array (
        'name' => 'send',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 26,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'recipient' => 
          array (
            'name' => 'recipient',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 56,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'docComment' => '/** Deliver the message to the recipient. Only called when {@see isAvailable()}. */',
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Contracts',
        'declaringClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'implementingClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
        'currentClassName' => 'App\\Domain\\Notification\\Contracts\\NotificationChannel',
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