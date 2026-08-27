<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/UploadImportRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\UploadImportRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b1b6a27859b1def5ed9e62eb4c8e1c9ba991c47bc0b99169f0d6f75be3033ba9',
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
 * PROGRAMME-first (revises the activity-first rule in PRD §9 / CLAUDE.md §9): every
 * upload must name a catalog programme, and MAY additionally name an `activity_id` the
 * caller\'s MDA owns. `programme_id` is required only when no activity is given — an
 * activity already names its programme, so the two together are one fact, not two.
 *
 * Registering people under a catalog programme is a complete act — the enrollment records
 * that they are on that programme. An activity adds *which MDA-run instance* delivered to
 * them, which an intake frequently does not know yet; requiring one made officers invent
 * placeholder activities, and a placeholder is a worse record than an honest absence.
 *
 * When both are given they must agree: an activity belongs to exactly one programme, and
 * accepting a contradiction would leave the batch\'s own `programme_id` disagreeing with
 * the programme its enrollments actually land in.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 117,
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
        'startLine' => 37,
        'endLine' => 40,
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
        'startLine' => 45,
        'endLine' => 63,
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
            'startLine' => 65,
            'endLine' => 65,
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
        'startLine' => 65,
        'endLine' => 89,
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
        'startLine' => 96,
        'endLine' => 116,
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