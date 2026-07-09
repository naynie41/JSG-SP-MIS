<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueDeclareCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-VladimirYuldashev\LaravelQueueRabbitMQ\Console\QueueDeclareCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f3205ca2eca5bd9fc0464ab9141ad977bb067f5f78270e74e2b5a66936e23cb1-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'filename' => '/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueDeclareCommand.php',
      ),
    ),
    'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
    'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
    'shortName' => 'QueueDeclareCommand',
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
    'endLine' => 56,
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
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rabbitmq:queue-declare
                           {name : The name of the queue to declare}
                           {connection=rabbitmq : The name of the queue connection to use}
                           {--max-priority}
                           {--durable=1}
                           {--auto-delete=0}
                           {--quorum=0}\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 17,
            'startTokenPos' => 38,
            'startFilePos' => 264,
            'endTokenPos' => 38,
            'endFilePos' => 617,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Declare queue\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 47,
            'startFilePos' => 650,
            'endTokenPos' => 47,
            'endFilePos' => 664,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 45,
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
            'startLine' => 24,
            'endLine' => 24,
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
        'startLine' => 24,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeclareCommand',
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