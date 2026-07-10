<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/UploadActivityImportRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\UploadActivityImportRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-481ba1a16ff601745a881bae5344adcb3f6196e6b10bb253a1f26d60cb1566cb',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'filename' => '/var/www/html/app/Http/Requests/Registry/UploadActivityImportRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Registry',
    'name' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
    'shortName' => 'UploadActivityImportRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Preview step of the activity-creation wizard\'s OPTIONAL inline upload (§10). Carries
 * the draft ACTIVITY fields (validated but NOT persisted) plus a REQUIRED beneficiary
 * file; validation + the duplicate cascade run in preview before anything is saved. On
 * confirm, the activity is created and the file committed under it, atomically.
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
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
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
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'aliasName' => NULL,
      ),
      'activityDraft' => 
      array (
        'name' => 'activityDraft',
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
 * The validated activity fields to stash until confirm.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
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