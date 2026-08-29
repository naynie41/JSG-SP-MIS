<?php declare(strict_types = 1);

// osfsl-/var/www/html/database/factories/SyncConnectorFactory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Factories\SyncConnectorFactory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7af0691e93083e5b70ea2749b8f8da1e8403c6e7051dafded5564ac55a29bed1-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Factories\\SyncConnectorFactory',
        'filename' => '/var/www/html/database/factories/SyncConnectorFactory.php',
      ),
    ),
    'namespace' => 'Database\\Factories',
    'name' => 'Database\\Factories\\SyncConnectorFactory',
    'shortName' => 'SyncConnectorFactory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @extends Factory<SyncConnector>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 72,
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
        'declaringClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'implementingClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'name' => 'model',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\App\\Domain\\Sync\\Models\\SyncConnector::class',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 83,
            'startFilePos' => 588,
            'endTokenPos' => 85,
            'endFilePos' => 607,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 44,
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
        'startLine' => 28,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'implementingClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'currentClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'aliasName' => NULL,
      ),
      'activityFor' => 
      array (
        'name' => 'activityFor',
        'parameters' => 
        array (
          'ownerMdaId' => 
          array (
            'name' => 'ownerMdaId',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 34,
            'endColumn' => 50,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An active activity owned by the connector\'s MDA, with a creator in that MDA —
 * the engine enrols as that user, so one without a creator would hold the run.
 */',
        'startLine' => 57,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'implementingClassName' => 'Database\\Factories\\SyncConnectorFactory',
        'currentClassName' => 'Database\\Factories\\SyncConnectorFactory',
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