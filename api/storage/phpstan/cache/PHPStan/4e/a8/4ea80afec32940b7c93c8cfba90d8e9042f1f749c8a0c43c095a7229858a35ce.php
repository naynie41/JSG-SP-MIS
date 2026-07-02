<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Concerns/AuthResponses.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Concerns\AuthResponses
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7667385dda4e1e79049e0e5548d395e0bccb4835c3d5bb8f84700606f42c2237',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'filename' => '/var/www/html/app/Http/Controllers/Concerns/AuthResponses.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Concerns',
    'name' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
    'shortName' => 'AuthResponses',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Shared auth error responses so login and the MFA challenge stay consistent.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 45,
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
      'invalidCredentials' => 
      array (
        'name' => 'invalidCredentials',
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
 * Generic credential failure — does not reveal whether the account exists
 * (SECURITY.md §2).
 */',
        'startLine' => 20,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Controllers\\Concerns',
        'declaringClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'implementingClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'currentClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'aliasName' => NULL,
      ),
      'accountLocked' => 
      array (
        'name' => 'accountLocked',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
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
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 38,
            'endColumn' => 47,
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
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Account temporarily locked after too many failed attempts (FR-UAM-06).
 * Includes a Retry-After header with the seconds until the lock clears.
 */',
        'startLine' => 29,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Controllers\\Concerns',
        'declaringClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'implementingClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
        'currentClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
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