<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Events/OwnershipTransferRequested.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Events\OwnershipTransferRequested
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9b83576610d447c14c1a2069e041e37a55f334e9cbe2224710d26efa11c68957',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'filename' => '/var/www/html/app/Domain/Registry/Events/OwnershipTransferRequested.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Events',
    'name' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
    'shortName' => 'OwnershipTransferRequested',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when an MDA requests ownership of another MDA\'s beneficiary (PRD FR-OWN-05).
 * The CURRENT owner MDA\'s approvers (`from_mda_id`) are notified to decide.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 23,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Events\\Dispatchable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'transfer' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'name' => 'transfer',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 9,
        'endColumn' => 58,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'actor' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'name' => 'actor',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Access\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 9,
        'endColumn' => 35,
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
          'transfer' => 
          array (
            'name' => 'transfer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\OwnershipTransferRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 9,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'actor' => 
          array (
            'name' => 'actor',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 9,
            'endColumn' => 35,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Events',
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
        'currentClassName' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
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