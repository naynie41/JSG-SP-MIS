<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/UploadImportRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\UploadImportRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c95e59dc9e89819ffdcee0e177d04b687a443732c8116871ddedf894055a4a81',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'filename' => '/var/www/html/app/Http/Requests/Registry/UploadImportRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Registry',
    'name' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
    'shortName' => 'UploadImportRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Upload a file for bulk beneficiary import (PRD FR-REG-02). Accepts plain
 * Excel/CSV as well as Kobo/ODK exports; the optional `source` selects the
 * ingestion adapter (and thus the stamped provenance). When omitted, the source
 * is inferred from the file extension (excel/csv).
 *
 * Activity-first (PRD §9, FR-REG-10): a required, valid `activity_id` that the
 * caller\'s MDA owns must accompany every upload — the resulting intervention is
 * recorded under it. An upload with no/invalid activity, or one the caller\'s MDA
 * cannot use, is refused here.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
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
        'startLine' => 27,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
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
        'startLine' => 35,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'aliasName' => NULL,
      ),
      'usableActivityRule' => 
      array (
        'name' => 'usableActivityRule',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Closure',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The activity must exist and be owned by the caller\'s own MDA — the importing
 * MDA. Resolved without the global MDA scope so a cross-MDA activity fails as
 * "not usable" (a clear 422) rather than a bare "not found".
 */',
        'startLine' => 51,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
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