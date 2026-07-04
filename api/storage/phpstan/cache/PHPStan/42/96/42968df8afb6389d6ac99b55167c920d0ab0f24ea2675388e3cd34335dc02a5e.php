<?php declare(strict_types = 1);

// osfsl-/var/www/html/database/factories/ActivityFactory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Factories\ActivityFactory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4a43d95ad8a0cc757dca9a76d3bbc12d516e5815ccc9f08af88dbea6973bde08-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Factories\\ActivityFactory',
        'filename' => '/var/www/html/database/factories/ActivityFactory.php',
      ),
    ),
    'namespace' => 'Database\\Factories',
    'name' => 'Database\\Factories\\ActivityFactory',
    'shortName' => 'ActivityFactory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Synthetic activities. `owner_mda_id` is inherited from the parent programme so
 * the activity is scoped to the same MDA (matching the write path).
 *
 * @extends Factory<Activity>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 65,
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
        'declaringClassName' => 'Database\\Factories\\ActivityFactory',
        'implementingClassName' => 'Database\\Factories\\ActivityFactory',
        'name' => 'model',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\App\\Domain\\Programme\\Models\\Activity::class',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 53,
            'startFilePos' => 504,
            'endTokenPos' => 55,
            'endFilePos' => 518,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 39,
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
        'startLine' => 25,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\ActivityFactory',
        'implementingClassName' => 'Database\\Factories\\ActivityFactory',
        'currentClassName' => 'Database\\Factories\\ActivityFactory',
        'aliasName' => NULL,
      ),
      'configure' => 
      array (
        'name' => 'configure',
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
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\ActivityFactory',
        'implementingClassName' => 'Database\\Factories\\ActivityFactory',
        'currentClassName' => 'Database\\Factories\\ActivityFactory',
        'aliasName' => NULL,
      ),
      'forProgramme' => 
      array (
        'name' => 'forProgramme',
        'parameters' => 
        array (
          'programme' => 
          array (
            'name' => 'programme',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Programme',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 34,
            'endColumn' => 53,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Attach to an existing programme, inheriting its owner MDA.
 */',
        'startLine' => 58,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\ActivityFactory',
        'implementingClassName' => 'Database\\Factories\\ActivityFactory',
        'currentClassName' => 'Database\\Factories\\ActivityFactory',
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