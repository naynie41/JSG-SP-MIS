<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Requests\Registry\UploadActivityImportRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\UploadActivityImportRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5cc5d39a76317beda2e0d33fb922795ba3e89f541b3ee5241e5b5ad4b24d17bc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Requests/Registry/UploadActivityImportRequest.php',
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
 * Preview step of the activity-creation wizard\'s MANDATORY upload for a beneficiary-
 * involving activity (§10). Reaching this endpoint means the activity involves
 * beneficiaries, so a `target_beneficiaries` count and a beneficiary file are BOTH
 * required, and the draft is stamped `involves_beneficiaries = true`. It carries the
 * draft ACTIVITY fields (validated but NOT persisted); validation + the duplicate
 * cascade run in preview before anything is saved. On confirm, the activity is created
 * and the file committed under it, atomically.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 72,
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
        'startLine' => 32,
        'endLine' => 57,
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
 * The validated activity fields to stash until confirm. Reaching this endpoint
 * means the activity involves beneficiaries, so the flag is stamped on here.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 65,
        'endLine' => 71,
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