<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/Registry/ActivityImportController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Registry\ActivityImportController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-324b7a055f6f663812fa2ea0c2e70e21e9d1d11662624003dcdc4b384e969ba4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'filename' => '/var/www/html/app/Http/Controllers/Api/V1/Registry/ActivityImportController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
    'shortName' => 'ActivityImportController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The activity-creation wizard\'s OPTIONAL inline upload (PRD §10). Preview stages an
 * UNBOUND import batch (the activity is not yet saved) and runs the existing
 * validation + duplicate cascade "before saving"; confirm atomically creates the
 * activity and commits the file under it. It reuses the Phase 2 import pipeline
 * (ParseImportBatch, the /beneficiaries/imports preview + row-resolve endpoints), the
 * Phase 3 dedup + resolution, the Service Request, and the shared {@see ImportCommitter}
 * — no duplicated logic. The standalone Import Center is unaffected.
 *
 * The no-file case does not touch this controller — the wizard calls POST /activities.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 119,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Http\\Controllers\\Controller',
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
      'store' => 
      array (
        'name' => 'store',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Http\\Requests\\Registry\\UploadActivityImportRequest',
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
            'startColumn' => 27,
            'endColumn' => 62,
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
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Stage an unbound preview batch for a draft activity + file, and queue parsing/dedup. */',
        'startLine' => 37,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'aliasName' => NULL,
      ),
      'confirm' => 
      array (
        'name' => 'confirm',
        'parameters' => 
        array (
          'batch' => 
          array (
            'name' => 'batch',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 29,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'committer' => 
          array (
            'name' => 'committer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Services\\ImportCommitter',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 44,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Confirm: atomically create the activity, bind the batch, and commit the file
 * under it (new beneficiaries + interventions; pending Service Requests for served
 * duplicates). Any failure rolls back everything, including the activity.
 */',
        'startLine' => 77,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ActivityImportController',
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