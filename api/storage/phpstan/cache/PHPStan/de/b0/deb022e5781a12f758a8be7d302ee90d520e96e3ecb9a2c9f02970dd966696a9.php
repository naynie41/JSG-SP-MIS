<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/HealthController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\HealthController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1aedac156f565ee883e7c7aebca9411a3fc0c2bff93648fa4d9d7b472afd03d1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'filename' => '/var/www/html/app/Http/Controllers/Api/V1/HealthController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
    'shortName' => 'HealthController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Health, readiness and operational metrics (NFR-AVAIL-01).
 *
 * `/up` (framework) is the bare liveness probe. This readiness probe additionally
 * confirms the critical dependencies — database (+ PostGIS) and cache — are usable,
 * and surfaces the statelessness configuration (session/cache/queue drivers) so a
 * load balancer / orchestrator can verify a node is fit to serve. Metrics are a
 * small, non-PII operational snapshot for monitoring, and are permission-gated.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 146,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Http\\Controllers\\Controller',
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
      'show' => 
      array (
        'name' => 'show',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Readiness probe: 200 only when the database and cache are healthy. */',
        'startLine' => 32,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'aliasName' => NULL,
      ),
      'metrics' => 
      array (
        'name' => 'metrics',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Operational metrics for monitoring (permission-gated; no PII). Surfaces the
 * signals ops actually alerts on: last successful backup, dashboard-snapshot
 * freshness, and coarse table volumes.
 */',
        'startLine' => 68,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'aliasName' => NULL,
      ),
      'checkDatabase' => 
      array (
        'name' => 'checkDatabase',
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
 * @return array{connected: bool, driver: string, postgis: array{enabled: bool, version: string|null}}
 */',
        'startLine' => 95,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'aliasName' => NULL,
      ),
      'checkCache' => 
      array (
        'name' => 'checkCache',
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
 * @return array{ok: bool, store: string}
 */',
        'startLine' => 120,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'aliasName' => NULL,
      ),
      'isStateless' => 
      array (
        'name' => 'isStateless',
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
        'docComment' => '/** Whether session + cache are shared stores and the queue is durable (not sync). */',
        'startLine' => 138,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\HealthController',
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