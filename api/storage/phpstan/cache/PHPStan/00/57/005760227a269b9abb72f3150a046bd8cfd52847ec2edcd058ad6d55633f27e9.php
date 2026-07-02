<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Services/PermissionSynchronizer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Services\PermissionSynchronizer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a533bcaaf7a6ec8c525f40b8237cb509e97afae3ad2702df976e2d016c108aad',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'filename' => '/var/www/html/app/Domain/Access/Services/PermissionSynchronizer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Services',
    'name' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
    'shortName' => 'PermissionSynchronizer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Persists the registered permissions (PermissionRegistry) to the database.
 * Idempotent: upserts by key. Does not delete unknown rows, so a permission a
 * module stops registering must be removed deliberately.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 39,
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
      'registry' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'name' => 'registry',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
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
          'registry' => 
          array (
            'name' => 'registry',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
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
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 81,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'currentClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'aliasName' => NULL,
      ),
      'sync' => 
      array (
        'name' => 'sync',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return int the number of permissions synced
 */',
        'startLine' => 22,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
        'currentClassName' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
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