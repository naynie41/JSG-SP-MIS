<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Events/CrossMdaAccessRevoked.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Events\CrossMdaAccessRevoked
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7eef2c7af90de99a2eb106983c061e4a9c7c4660702a72d4e4e4dc3007c1349b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'filename' => '/var/www/html/app/Domain/Access/Events/CrossMdaAccessRevoked.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Events',
    'name' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
    'shortName' => 'CrossMdaAccessRevoked',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when a cross-MDA access grant is revoked (FR-DSH-01). Audit hook for the
 * audit-log step (FR-AUD-01).
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
      'grant' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'implementingClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'name' => 'grant',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
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
        'endColumn' => 45,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'actor' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'implementingClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'name' => 'actor',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'App\\Domain\\Access\\Models\\User',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 70,
            'startFilePos' => 491,
            'endTokenPos' => 70,
            'endFilePos' => 494,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 9,
        'endColumn' => 43,
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
          'grant' => 
          array (
            'name' => 'grant',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
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
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'actor' => 
          array (
            'name' => 'actor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 21,
                'endLine' => 21,
                'startTokenPos' => 70,
                'startFilePos' => 491,
                'endTokenPos' => 70,
                'endFilePos' => 494,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'App\\Domain\\Access\\Models\\User',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
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
            'endColumn' => 43,
            'parameterIndex' => 1,
            'isOptional' => true,
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
        'namespace' => 'App\\Domain\\Access\\Events',
        'declaringClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'implementingClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
        'currentClassName' => 'App\\Domain\\Access\\Events\\CrossMdaAccessRevoked',
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