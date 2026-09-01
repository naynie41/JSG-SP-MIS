<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Middleware/RequirePasswordChange.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Middleware\RequirePasswordChange
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-bf3ab26c0d11132ad0399708e9c030e9346d57dff1436b91474719d0740b7126',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Middleware\\RequirePasswordChange',
        'filename' => '/var/www/html/app/Http/Middleware/RequirePasswordChange.php',
      ),
    ),
    'namespace' => 'App\\Http\\Middleware',
    'name' => 'App\\Http\\Middleware\\RequirePasswordChange',
    'shortName' => 'RequirePasswordChange',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Blocks a user carrying `must_change_password` from doing anything except
 * changing it (SECURITY.md §2, FR-UAM-06).
 *
 * Registered GLOBALLY on the api group with a small named allow-list, rather than
 * being added group by group in routes/api.php. That direction is deliberate: a
 * route added later is protected by DEFAULT, and forgetting to opt in cannot
 * silently leave a hole. It matches the deny-by-default posture of the
 * `permission` middleware and the scope-bypass allow-list.
 *
 * Reads the bearer token directly, the way EnforceIdleTimeout does, because the
 * default guard is `web` (session) — $request->user() would resolve the wrong
 * guard, and global middleware runs before the route\'s auth:sanctum anyway.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 75,
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
      'ALLOWED_ROUTES' => 
      array (
        'declaringClassName' => 'App\\Http\\Middleware\\RequirePasswordChange',
        'implementingClassName' => 'App\\Http\\Middleware\\RequirePasswordChange',
        'name' => 'ALLOWED_ROUTES',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'health\', \'auth.login\', \'auth.logout\', \'auth.me\', \'auth.password\', \'auth.mfa.challenge\', \'auth.mfa.enroll\', \'auth.mfa.verify\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 47,
            'startTokenPos' => 58,
            'startFilePos' => 1399,
            'endTokenPos' => 84,
            'endFilePos' => 1596,
          ),
        ),
        'docComment' => '/**
 * Routes reachable while a password change is outstanding.
 *
 * The MFA routes MUST be here. A user whose role mandates MFA receives a SETUP
 * token from login, not a full one; blocking enrolment would deadlock them
 * between "enrol MFA first" and "change password first" with no way out.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
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
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 28,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 46,
            'endColumn' => 58,
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
            'name' => 'Symfony\\Component\\HttpFoundation\\Response',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Middleware',
        'declaringClassName' => 'App\\Http\\Middleware\\RequirePasswordChange',
        'implementingClassName' => 'App\\Http\\Middleware\\RequirePasswordChange',
        'currentClassName' => 'App\\Http\\Middleware\\RequirePasswordChange',
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