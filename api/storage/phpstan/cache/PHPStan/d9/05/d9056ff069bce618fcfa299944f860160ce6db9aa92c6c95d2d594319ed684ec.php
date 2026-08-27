<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Console/Commands/VerifyAuditChain.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Console\Commands\VerifyAuditChain
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7c9fd94ad571b976b1e0365316f5772b8e0e0f2dc7aae5ef92b3900524efe32a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Console\\Commands\\VerifyAuditChain',
        'filename' => '/var/www/html/app/Console/Commands/VerifyAuditChain.php',
      ),
    ),
    'namespace' => 'App\\Console\\Commands',
    'name' => 'App\\Console\\Commands\\VerifyAuditChain',
    'shortName' => 'VerifyAuditChain',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Walks the audit-log hash chain and proves it is intact (NFR-AUD-01): every
 * chained entry must link to its predecessor\'s hash and re-hash to its stored
 * `entry_hash`. Any mutation, deletion, or reordering of a chained row surfaces
 * here as the first broken link. Intended for scheduled/ops use and for the
 * external pen-test to exercise.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 92,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'implementingClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'audit:verify-chain {--from=1 : Chain position to start from}\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 43,
            'startFilePos' => 562,
            'endTokenPos' => 43,
            'endFilePos' => 623,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 90,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'implementingClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Verify the tamper-evident hash chain of the audit log\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 52,
            'startFilePos' => 656,
            'endTokenPos' => 52,
            'endFilePos' => 710,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 85,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Console\\Commands',
        'declaringClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'implementingClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
        'currentClassName' => 'App\\Console\\Commands\\VerifyAuditChain',
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