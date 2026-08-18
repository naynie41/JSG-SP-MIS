<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Requests\Sync\ConfirmConnectorMappingRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Sync\ConfirmConnectorMappingRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5314fb8c2706c2e320d13cc315f9bb40a35e8cd796ecbbdb7462803bc8eced7b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Requests/Sync/ConfirmConnectorMappingRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Sync',
    'name' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
    'shortName' => 'ConfirmConnectorMappingRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Approve a sync connector\'s column mapping (CLAUDE.md §11).
 *
 * Same shape as the import mapping request: a canonical field pointing at a source
 * field, or explicitly at NULL for "this source does not carry it". A field left out is
 * unanswered, and the completeness check lives in the service where an explicit null can
 * be told apart from an absent key.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 64,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Http\\FormRequest',
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
      'authorize' => 
      array (
        'name' => 'authorize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Sync',
        'declaringClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'currentClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'aliasName' => NULL,
      ),
      'rules' => 
      array (
        'name' => 'rules',
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
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Sync',
        'declaringClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'currentClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'aliasName' => NULL,
      ),
      'withValidator' => 
      array (
        'name' => 'withValidator',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Validation\\Validator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 35,
            'endColumn' => 54,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Sync',
        'declaringClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'currentClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'aliasName' => NULL,
      ),
      'columnMap' => 
      array (
        'name' => 'columnMap',
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
 * @return array<string, string|null>
 */',
        'startLine' => 54,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Sync',
        'declaringClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
        'currentClassName' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
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