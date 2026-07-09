<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/ExchangeDeleteCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-VladimirYuldashev\LaravelQueueRabbitMQ\Console\ExchangeDeleteCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-89957889d12872ea6ad7fdd3d58a47a2f7132ecc9fc334fdd0a44a2ae6ce04d6-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'filename' => '/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/ExchangeDeleteCommand.php',
      ),
    ),
    'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
    'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
    'shortName' => 'ExchangeDeleteCommand',
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
    'endLine' => 40,
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
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rabbitmq:exchange-delete
                            {name : The name of the exchange to delete}
                            {connection=rabbitmq : The name of the queue connection to use}
                            {--unused=0 : Check if exchange is unused}\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 14,
            'startTokenPos' => 38,
            'startFilePos' => 266,
            'endTokenPos' => 38,
            'endFilePos' => 526,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 72,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Delete exchange\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 47,
            'startFilePos' => 559,
            'endTokenPos' => 47,
            'endFilePos' => 575,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 47,
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
            'startLine' => 21,
            'endLine' => 21,
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
        'startLine' => 21,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ExchangeDeleteCommand',
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