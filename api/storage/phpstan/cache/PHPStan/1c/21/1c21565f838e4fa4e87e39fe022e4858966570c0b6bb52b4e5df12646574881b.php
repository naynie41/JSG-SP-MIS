<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/Access/AccessController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Access\AccessController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8f493204741d0ef812168b4091fc2f40107f47bdf8512312f97a2f0418c750e5',
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
 * Administration views over the RBAC model (PRD FR-UAM-05): the permission catalogue,
 * the roles, and the role × permission matrix — plus the matrix WRITE that backs the
 * console\'s permission editor. Each action is guarded by an explicit permission (see
 * routes/api.php); the write additionally enforces the SECURITY.md invariants in
 * {@see RolePermissionService}.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 138,
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
        'startLine' => 29,
        'endLine' => 44,
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
        'startLine' => 49,
        'endLine' => 65,
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
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Services\\RolePermissionService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 28,
            'endColumn' => 57,
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
 * The role × permission matrix: the full permission key list plus, for each
 * role, the keys it holds. Lets an admin see exactly who can do what.
 */',
        'startLine' => 71,
        'endLine' => 105,
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
      'updatePermissions' => 
      array (
        'name' => 'updatePermissions',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Http\\Requests\\Access\\UpdateRolePermissionsRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 9,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Services\\RolePermissionService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 2,
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
 * Replace a role\'s permission set (the console\'s matrix editor). Writes the
 * existing `role_permission` pivot, so the change takes effect on the next request
 * with no second store to keep in step. Audited as `role.permissions_updated`.
 */',
        'startLine' => 112,
        'endLine' => 137,
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