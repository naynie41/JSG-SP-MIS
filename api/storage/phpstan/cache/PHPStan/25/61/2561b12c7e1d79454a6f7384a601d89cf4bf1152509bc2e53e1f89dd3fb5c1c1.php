<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Sync/Enums/SyncRowOutcome.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Sync\Enums\SyncRowOutcome
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1cf69fa777816ef76410dc6ff24cb96c74d07022f85d4cd7c5269839d77d1862',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'filename' => '/var/www/html/app/Domain/Sync/Enums/SyncRowOutcome.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Sync\\Enums',
    'name' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
    'shortName' => 'SyncRowOutcome',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => true,
    'isBackedEnum' => true,
    'modifiers' => 0,
    'docComment' => '/**
 * The outcome of a single synced record after it ran the SAME pipeline as import
 * (validation → dedup → ownership/provenance → idempotency).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 32,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
      'name' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'name' => 'name',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'value' => 
      array (
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'name' => 'value',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'counter' => 
      array (
        'name' => 'counter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Which run-summary counter this outcome increments. */',
        'startLine' => 21,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Sync\\Enums',
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'currentClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'aliasName' => NULL,
      ),
      'cases' => 
      array (
        'name' => 'cases',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Sync\\Enums',
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'currentClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
            'name' => 'static',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Sync\\Enums',
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'currentClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'aliasName' => NULL,
      ),
      'tryFrom' => 
      array (
        'name' => 'tryFrom',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'static',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Sync\\Enums',
        'declaringClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'implementingClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
        'currentClassName' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
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
    'backingType' => 
    array (
      'name' => 'string',
      'isIdentifier' => true,
    ),
    'cases' => 
    array (
      'Created' => 
      array (
        'name' => 'Created',
        'value' => 
        array (
          'code' => '\'created\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 32,
            'startFilePos' => 274,
            'endTokenPos' => 32,
            'endFilePos' => 282,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'Updated' => 
      array (
        'name' => 'Updated',
        'value' => 
        array (
          'code' => '\'updated\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 43,
            'startFilePos' => 358,
            'endTokenPos' => 43,
            'endFilePos' => 366,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'SkippedIdempotent' => 
      array (
        'name' => 'SkippedIdempotent',
        'value' => 
        array (
          'code' => '\'skipped_idempotent\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 54,
            'startFilePos' => 467,
            'endTokenPos' => 54,
            'endFilePos' => 486,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'FlaggedConflict' => 
      array (
        'name' => 'FlaggedConflict',
        'value' => 
        array (
          'code' => '\'flagged_conflict\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 65,
            'startFilePos' => 571,
            'endTokenPos' => 65,
            'endFilePos' => 588,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'RejectedIdentity' => 
      array (
        'name' => 'RejectedIdentity',
        'value' => 
        array (
          'code' => '\'rejected_identity\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 76,
            'startFilePos' => 673,
            'endTokenPos' => 76,
            'endFilePos' => 691,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'Error' => 
      array (
        'name' => 'Error',
        'value' => 
        array (
          'code' => '\'error\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 87,
            'startFilePos' => 767,
            'endTokenPos' => 87,
            'endFilePos' => 773,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 25,
      ),
    ),
  ),
));