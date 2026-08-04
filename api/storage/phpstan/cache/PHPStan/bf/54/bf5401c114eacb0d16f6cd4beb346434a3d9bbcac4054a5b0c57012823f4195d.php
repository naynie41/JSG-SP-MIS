<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Notification\BroadcastController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Notification\BroadcastController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-84fe2ef5e051d1ef045353cc84e96706cccd9f2e8fa8ed57be90a18414299e9e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Controllers/Api/V1/Notification/BroadcastController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Notification',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
    'shortName' => 'BroadcastController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * System-wide announcements from the administration console (FR-NOT-01). Delivery runs
 * through the existing Phase 5 {@see Notifier}, so
 * channel availability and recipient preferences are honoured unchanged.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 57,
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
      'broadcasts' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'name' => 'broadcasts',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Notification\\Services\\BroadcastService',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 33,
        'endColumn' => 77,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'broadcasts' => 
          array (
            'name' => 'broadcasts',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Notification\\Services\\BroadcastService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 33,
            'endColumn' => 77,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 81,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Notification',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'aliasName' => NULL,
      ),
      'audience' => 
      array (
        'name' => 'audience',
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
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 30,
            'endColumn' => 45,
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
        'docComment' => '/** How many active users a given audience filter reaches (pre-send confirmation). */',
        'startLine' => 26,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Notification',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'aliasName' => NULL,
      ),
      'store' => 
      array (
        'name' => 'store',
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
                'name' => 'App\\Http\\Requests\\Notification\\BroadcastRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 27,
            'endColumn' => 51,
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
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Notification',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Notification\\BroadcastController',
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