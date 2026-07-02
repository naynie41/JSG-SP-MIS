<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/Adapters/DefaultImportAdapter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\Adapters\DefaultImportAdapter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-919074dd7218fdd7ccc3ac59c0b9c3264e013e3141ef090376874ac70e2fbbd8',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'filename' => '/var/www/html/app/Domain/Registry/Imports/Adapters/DefaultImportAdapter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
    'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
    'shortName' => 'DefaultImportAdapter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Adapter for plain Excel/CSV uploads whose columns already use the canonical
 * beneficiary field names. Provenance is set per batch (excel vs csv) at
 * construction, so the same mapping serves both.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 27,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\FieldMappingAdapter',
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
      'source' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'name' => 'source',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 33,
        'endColumn' => 75,
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
          'source' => 
          array (
            'name' => 'source',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 33,
            'endColumn' => 75,
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
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 79,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'aliasName' => NULL,
      ),
      'source' => 
      array (
        'name' => 'source',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'aliasName' => NULL,
      ),
      'idKeys' => 
      array (
        'name' => 'idKeys',
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
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
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