<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/RecordConsentRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\RecordConsentRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-fc86b945269b087cb44576c36cee2b76caa34bd547ad7db9399a9531fdbc784e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
        'filename' => '/var/www/html/app/Http/Requests/Registry/RecordConsentRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Registry',
    'name' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
    'shortName' => 'RecordConsentRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Record (grant or withdraw) a beneficiary\'s consent for a PURPOSE (NFR-PRV-01).
 * Only the owner MDA may — enforced by the controller policy. `unknown` is not
 * settable via the API; you explicitly grant or withdraw. The purpose defaults to
 * cross-MDA sharing; any other purpose must be one registered in config/privacy.php.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 37,
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
        'startLine' => 19,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
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
        'startLine' => 27,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\RecordConsentRequest',
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