<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Console/Commands/CreateAdminUser.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Console\Commands\CreateAdminUser
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-973437edea2fe1281c81e72cd85742802984728036d78931c3ff0398f46cb39b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Console\\Commands\\CreateAdminUser',
        'filename' => '/var/www/html/app/Console/Commands/CreateAdminUser.php',
      ),
    ),
    'namespace' => 'App\\Console\\Commands',
    'name' => 'App\\Console\\Commands\\CreateAdminUser',
    'shortName' => 'CreateAdminUser',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Creates the initial System Administrator in production (the DevUserSeeder is
 * local-only). The password is PROMPTED (never an argument/env, so it stays out of
 * shell history and process listings) and validated against the app\'s password
 * policy (min 12, breached-password check). No control is weakened — the account
 * is subject to the same RBAC and mandatory MFA (SysAdmin role) as any other; the
 * admin completes MFA enrolment on first login.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 76,
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
        'declaringClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'implementingClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'spmis:create-admin {email} {--name=SP-MIS Administrator}\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 68,
            'startFilePos' => 868,
            'endTokenPos' => 68,
            'endFilePos' => 925,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 86,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'implementingClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Create the initial System Administrator (password prompted, not passed as an argument)\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 77,
            'startFilePos' => 958,
            'endTokenPos' => 77,
            'endFilePos' => 1045,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 118,
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
        'startLine' => 29,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Console\\Commands',
        'declaringClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'implementingClassName' => 'App\\Console\\Commands\\CreateAdminUser',
        'currentClassName' => 'App\\Console\\Commands\\CreateAdminUser',
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