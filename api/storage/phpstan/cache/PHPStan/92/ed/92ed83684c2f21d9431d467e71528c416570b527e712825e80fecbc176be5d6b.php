<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueDeleteCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-VladimirYuldashev\LaravelQueueRabbitMQ\Console\QueueDeleteCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-22b468fcfb054a1ec1e3cd8218380549aa30a52fab00f0127de9682612569529-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'filename' => '/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/QueueDeleteCommand.php',
      ),
    ),
    'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
    'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
    'shortName' => 'QueueDeleteCommand',
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
    'endLine' => 42,
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
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rabbitmq:queue-delete
                           {name : The name of the queue to delete}
                           {connection=rabbitmq : The name of the queue connection to use}
                           {--unused=0 : Check if queue has no consumers}
                           {--empty=0 : Check if queue is empty}\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 15,
            'startTokenPos' => 38,
            'startFilePos' => 263,
            'endTokenPos' => 38,
            'endFilePos' => 583,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 66,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Delete queue\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 47,
            'startFilePos' => 616,
            'endTokenPos' => 47,
            'endFilePos' => 629,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 44,
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
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\QueueDeleteCommand',
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