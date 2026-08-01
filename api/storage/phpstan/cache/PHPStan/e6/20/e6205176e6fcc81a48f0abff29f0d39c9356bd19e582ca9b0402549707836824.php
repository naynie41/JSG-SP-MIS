<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Requests\Programme\StoreActivityRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Programme\StoreActivityRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-908d1c28ac8aca40eac1bcaa16eca3ec5d3354dc052ac115709500de2bb2e682',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Requests/Programme/StoreActivityRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Programme',
    'name' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
    'shortName' => 'StoreActivityRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Create an activity under a programme (PRD FR-PRG-02, §10). This is the NO-file path:
 * it creates an activity that does NOT involve beneficiaries. A beneficiary-involving
 * activity (`involves_beneficiaries = true`) requires a mandatory upload and is created
 * through the activity-import wizard ({@see UploadActivityImportRequest} → confirm), so
 * a target count and a file are BOTH prohibited here. Ownership is enforced in the
 * controller/policy; monetary amounts are integer minor units (kobo, NGN).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 70,
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
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Programme',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
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
        'startLine' => 32,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Programme',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
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
                'name' => 'Illuminate\\Contracts\\Validation\\Validator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 56,
            'endLine' => 56,
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
        'startLine' => 56,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Programme',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\StoreActivityRequest',
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