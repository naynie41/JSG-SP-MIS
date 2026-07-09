<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueBindCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-VladimirYuldashev\LaravelQueueRabbitMQ\Console\QueueBindCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bc368c05607344a4dc4c6cb3bebbe7c99b818dd9f19ecb58fd88ceaf68abd608-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'filename' => '/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueBindCommand.php',
      ),
    ),
    'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
    'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
    'shortName' => 'QueueBindCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 36,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rabbitmq:queue-bind                          
                           {queue}
                           {exchange}
                           {connection=rabbitmq : The name of the queue connection to use}
                           {--routing-key= : Bind queue to exchange via routing key}\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 15,
            'startTokenPos' => 38,
            'startFilePos' => 261,
            'endTokenPos' => 38,
            'endFilePos' => 556,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 86,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Bind queue to exchange\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 47,
            'startFilePos' => 589,
            'endTokenPos' => 47,
            'endFilePos' => 612,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 54,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'connector' => 
          array (
            'name' => 'connector',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Queue\\Connectors\\RabbitMQConnector',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 28,
            'endColumn' => 55,
            'parameterIndex' => 0,
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
        'docComment' => '/**
 * @throws Exception
 */',
        'startLine' => 22,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueBindCommand',
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