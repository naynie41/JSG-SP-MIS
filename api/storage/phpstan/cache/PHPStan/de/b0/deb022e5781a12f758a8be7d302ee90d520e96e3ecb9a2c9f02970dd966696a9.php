<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/HealthController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\HealthController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-26d690b2b0b2ae7d192487f87e9c6bda8ffe434a6aac9ada2ebf73cfa171000b',
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
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 76,
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
        'docComment' => '/**
 * Liveness/readiness probe.
 *
 * Confirms the API is up, the database is reachable, and the PostGIS
 * extension is installed. Returns 200 only when the database is healthy,
 * otherwise 503 with the standard error envelope.
 */',
        'startLine' => 22,
        'endLine' => 44,
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
        'startLine' => 49,
        'endLine' => 75,
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