<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Events/ImportBatchCompleted.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Events\ImportBatchCompleted
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2e635fdea0d2029b22eea6114fd84f51fb64637044debf4e10aa7db58de4f3e2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'filename' => '/var/www/html/app/Domain/Registry/Events/ImportBatchCompleted.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Events',
    'name' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
    'shortName' => 'ImportBatchCompleted',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A confirmed import finished committing (PRD FR-REG-02). Fired from
 * {@see ImportCommitter} once the batch reaches `completed`, so it covers both entry
 * points — the standalone Import Center\'s queued commit and the activity wizard\'s
 * atomic confirm.
 *
 * An import is asynchronous: the officer who confirmed it has usually navigated away by
 * the time it lands, which is precisely why the result belongs in the notification bell
 * rather than only on the batch screen.
 *
 * Counts only — never a name or an identifier.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 38,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Events\\Dispatchable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'batch' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'name' => 'batch',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 9,
        'endColumn' => 42,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'committed' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'name' => 'committed',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/** Rows that created a new beneficiary. */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 9,
        'endColumn' => 38,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'served' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'name' => 'served',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/** Rows linked to an existing record (a request-to-serve was raised). */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 9,
        'endColumn' => 35,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'skipped' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'name' => 'skipped',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/** Rows discarded, or flagged and left undecided. */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 9,
        'endColumn' => 36,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'own' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'name' => 'own',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 104,
            'startFilePos' => 1328,
            'endTokenPos' => 104,
            'endFilePos' => 1328,
          ),
        ),
        'docComment' => '/** Rows matching a beneficiary this MDA already owns (a re-upload of its own data). */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 9,
        'endColumn' => 36,
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
          'batch' => 
          array (
            'name' => 'batch',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\ImportBatch',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 9,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'committed' => 
          array (
            'name' => 'committed',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'served' => 
          array (
            'name' => 'served',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 9,
            'endColumn' => 35,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'skipped' => 
          array (
            'name' => 'skipped',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 9,
            'endColumn' => 36,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'own' => 
          array (
            'name' => 'own',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 36,
                'endLine' => 36,
                'startTokenPos' => 104,
                'startFilePos' => 1328,
                'endTokenPos' => 104,
                'endFilePos' => 1328,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 9,
            'endColumn' => 36,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Events',
        'declaringClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'implementingClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
        'currentClassName' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
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