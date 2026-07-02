<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/Access/AccessController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Access\AccessController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-50e08fd003e95002e51e920225053678139ff7ff176e81700ecf1cc5d9165613',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'filename' => '/var/www/html/app/Http/Controllers/Api/V1/Access/AccessController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Access',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
    'shortName' => 'AccessController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Read-only administration views over the RBAC model (PRD FR-UAM-05): the
 * permission catalogue, the roles, and the role × permission matrix. Each
 * action is guarded by an explicit permission (see routes/api.php).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 88,
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
      'permissions' => 
      array (
        'name' => 'permissions',
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
 * The permission catalogue, grouped by module.
 */',
        'startLine' => 23,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Access',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'aliasName' => NULL,
      ),
      'roles' => 
      array (
        'name' => 'roles',
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
 * All roles with the permission keys they grant.
 */',
        'startLine' => 43,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Access',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'aliasName' => NULL,
      ),
      'matrix' => 
      array (
        'name' => 'matrix',
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
 * The role × permission matrix: the full permission key list plus, for each
 * role, the keys it holds. Lets an admin see exactly who can do what.
 */',
        'startLine' => 65,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Access',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Access\\AccessController',
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