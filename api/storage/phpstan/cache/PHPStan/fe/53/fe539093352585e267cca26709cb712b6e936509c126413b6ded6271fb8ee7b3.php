<?php declare(strict_types = 1);

// osfsl-/var/www/html/database/factories/UserFactory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Factories\UserFactory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3ad9d97be0dc35905d0dad66bb9f9894386ea4aa984a7876bb4d8b358e725a99-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Factories\\UserFactory',
        'filename' => '/var/www/html/database/factories/UserFactory.php',
      ),
    ),
    'namespace' => 'Database\\Factories',
    'name' => 'Database\\Factories\\UserFactory',
    'shortName' => 'UserFactory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @extends Factory<User>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 62,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
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
      'model' => 
      array (
        'declaringClassName' => 'Database\\Factories\\UserFactory',
        'implementingClassName' => 'Database\\Factories\\UserFactory',
        'name' => 'model',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\App\\Domain\\Access\\Models\\User::class',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 63,
            'startFilePos' => 384,
            'endTokenPos' => 65,
            'endFilePos' => 394,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'password' => 
      array (
        'declaringClassName' => 'Database\\Factories\\UserFactory',
        'implementingClassName' => 'Database\\Factories\\UserFactory',
        'name' => 'password',
        'modifiers' => 18,
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
                  'name' => 'string',
                  'isIdentifier' => true,
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
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 81,
            'startFilePos' => 510,
            'endTokenPos' => 81,
            'endFilePos' => 513,
          ),
        ),
        'docComment' => '/**
 * The current password being used by the factory.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 46,
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
      'definition' => 
      array (
        'name' => 'definition',
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
 * @return array<string, mixed>
 */',
        'startLine' => 29,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\UserFactory',
        'implementingClassName' => 'Database\\Factories\\UserFactory',
        'currentClassName' => 'Database\\Factories\\UserFactory',
        'aliasName' => NULL,
      ),
      'forMda' => 
      array (
        'name' => 'forMda',
        'parameters' => 
        array (
          'mda' => 
          array (
            'name' => 'mda',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 215,
                'startFilePos' => 1121,
                'endTokenPos' => 215,
                'endFilePos' => 1124,
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
                      'name' => 'App\\Domain\\Access\\Models\\Mda',
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 28,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Attach the user to a specific (or new) MDA.
 */',
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\UserFactory',
        'implementingClassName' => 'Database\\Factories\\UserFactory',
        'currentClassName' => 'Database\\Factories\\UserFactory',
        'aliasName' => NULL,
      ),
      'unverified' => 
      array (
        'name' => 'unverified',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the model\'s email address should be unverified.
 */',
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\UserFactory',
        'implementingClassName' => 'Database\\Factories\\UserFactory',
        'currentClassName' => 'Database\\Factories\\UserFactory',
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