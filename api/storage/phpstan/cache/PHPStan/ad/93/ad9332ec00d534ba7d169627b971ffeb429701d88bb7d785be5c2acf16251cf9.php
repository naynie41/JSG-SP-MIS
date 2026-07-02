<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/UploadDocumentRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\UploadDocumentRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-afb6739df07c937169649c6eed7595493ff9b7309ac9e9a5850ebb71d16af65f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
        'filename' => '/var/www/html/app/Http/Requests/Registry/UploadDocumentRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Registry',
    'name' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
    'shortName' => 'UploadDocumentRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Upload a supporting document for a beneficiary (PRD FR-REG-07, SECURITY.md §5).
 * Validates type, size AND actual content (`mimetypes` reads the real MIME via
 * finfo, not just the extension), restricting uploads to PDFs and common image
 * scans up to 5 MB.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 40,
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
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
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
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\UploadDocumentRequest',
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