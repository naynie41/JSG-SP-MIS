<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/ConsumeCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-VladimirYuldashev\LaravelQueueRabbitMQ\Console\ConsumeCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9ee12d7c7df29dda9384da24276b800af742310b2465b972f8c7edf629133c39-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'filename' => '/var/www/html/vendor/composer/../vladimir-yuldashev/laravel-queue-rabbitmq/src/Console/ConsumeCommand.php',
      ),
    ),
    'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
    'name' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
    'shortName' => 'ConsumeCommand',
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
    'endLine' => 66,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Queue\\Console\\WorkCommand',
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
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rabbitmq:consume
                            {connection? : The name of the queue connection to work}
                            {--name=default : The name of the consumer}
                            {--queue= : The name of the queue to work. Please notice that there is no support for multiple queues}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--delay=0 : The number of seconds to delay failed jobs (Deprecated)}
                            {--backoff=0 : The number of seconds to wait before retrying a job that encountered an uncaught exception}
                            {--max-jobs=0 : The number of jobs to process before stopping}
                            {--max-time=0 : The maximum number of seconds the worker should run}
                            {--force : Force the worker to run even in maintenance mode}
                            {--memory=128 : The memory limit in megabytes}
                            {--sleep=3 : Number of seconds to sleep when no job is available}
                            {--rest=0 : Number of seconds to rest between jobs}
                            {--timeout=60 : The number of seconds a child process can run}
                            {--tries=1 : Number of times to attempt a job before logging it failed}
                            {--json : Output the queue worker information as JSON}

                            {--max-priority=}
                            {--consumer-tag}
                            {--prefetch-size=0}
                            {--prefetch-count=1000}
                           \'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 33,
            'startTokenPos' => 38,
            'startFilePos' => 260,
            'endTokenPos' => 38,
            'endFilePos' => 1975,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Consume messages\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 47,
            'startFilePos' => 2008,
            'endTokenPos' => 47,
            'endFilePos' => 2025,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 48,
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
        'startLine' => 37,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'aliasName' => NULL,
      ),
      'consumerTag' => 
      array (
        'name' => 'consumerTag',
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
        'docComment' => NULL,
        'startLine' => 52,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console',
        'declaringClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'implementingClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
        'currentClassName' => 'VladimirYuldashev\\LaravelQueueRabbitMQ\\Console\\ConsumeCommand',
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