<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Registry\DuplicateQueueController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Registry\DuplicateQueueController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-cbf1f5c5e6a6d3ff93e67e987e3d7ef17b7e4f237cb7b93bb90996bef007a0e7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Http/Controllers/Api/V1/Registry/DuplicateQueueController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
    'shortName' => 'DuplicateQueueController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The duplicate queue: every flagged row across this MDA\'s imports, paginated
 * (FR-DUP-01/05).
 *
 * This exists because the console previously assembled the queue in the browser — fetch
 * page one of BATCHES, then one detail request per batch, then flatten. Three
 * consequences, all silent: only the first page of batches was reachable at all, the
 * page blocked until every fan-out request resolved, and a failed batch request was
 * skipped so the list was quietly incomplete. A module for clearing a backlog could not
 * see the backlog.
 *
 * Paginating ROWS instead of batches answers the question the officer is actually
 * asking. Scope comes from the global {@see MdaScope} on
 * `import_batches` via the join — this endpoint adds no authorization surface of its own.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 164,
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
      'PER_PAGE' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'name' => 'PER_PAGE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '25',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 95,
            'startFilePos' => 1462,
            'endTokenPos' => 95,
            'endFilePos' => 1463,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 32,
      ),
      'MAX_PER_PAGE' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'name' => 'MAX_PER_PAGE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '100',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 106,
            'startFilePos' => 1500,
            'endTokenPos' => 106,
            'endFilePos' => 1502,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
    ),
    'immediateProperties' => 
    array (
      'reveals' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'name' => 'reveals',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 33,
        'endColumn' => 78,
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
          'reveals' => 
          array (
            'name' => 'reveals',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 33,
            'endColumn' => 78,
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
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 82,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'aliasName' => NULL,
      ),
      'counts' => 
      array (
        'name' => 'counts',
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
 * Outstanding and total flagged rows per band, across everything in scope.
 *
 * Two aggregates rather than a page of rows: the tab needs a number, not a list.
 *
 * @return array<string, array<string, int>>
 */',
        'startLine' => 50,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'aliasName' => NULL,
      ),
      'activeThresholds' => 
      array (
        'name' => 'activeThresholds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array<string, float|null>|null */',
        'startLine' => 81,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'aliasName' => NULL,
      ),
      'index' => 
      array (
        'name' => 'index',
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
                'name' => 'App\\Http\\Requests\\Registry\\DuplicateQueueRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 27,
            'endColumn' => 56,
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
        'docComment' => NULL,
        'startLine' => 91,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\DuplicateQueueController',
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