<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Services/LoginActivityService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Services\LoginActivityService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a2c6efe0ddbe86f108e79bc549a5702b284c73502bba70b1a9a98537284536a5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'filename' => '/var/www/html/app/Domain/Access/Services/LoginActivityService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Services',
    'name' => 'App\\Domain\\Access\\Services\\LoginActivityService',
    'shortName' => 'LoginActivityService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * LOGIN ACTIVITY read model (FR-UAM-04/06, FR-AUD-01) — a narrow, read-only projection
 * of the append-only audit log over authentication events, for the administration
 * console\'s User & Access section.
 *
 * It reuses the audit trail that Phase 1 already writes (`auth.*`, `mfa.*`, the
 * administrative `user.mfa_reset` / `user.password_reset_forced`) — no separate login
 * table and no duplicated logging. Only the audit ENVELOPE is projected: actor, action,
 * IP and time. The `before`/`after` payloads are never read or returned (SECURITY.md §6).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 112,
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
      'ACTIONS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'name' => 'ACTIONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'auth.login\', \'auth.login_failed\', \'auth.logout\', \'auth.account_locked\', \'mfa.enrolled\', \'mfa.disabled\', \'mfa.challenge_failed\', \'user.mfa_reset\', \'user.password_reset_forced\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 35,
            'startTokenPos' => 53,
            'startFilePos' => 923,
            'endTokenPos' => 82,
            'endFilePos' => 1178,
          ),
        ),
        'docComment' => '/** Audit actions that constitute authentication / account-security activity. */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'FAILURE_ACTIONS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'name' => 'FAILURE_ACTIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'auth.login_failed\', \'mfa.challenge_failed\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 95,
            'startFilePos' => 1291,
            'endTokenPos' => 100,
            'endFilePos' => 1335,
          ),
        ),
        'docComment' => '/** Actions that represent a failed or security-relevant attempt. */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 82,
      ),
      'SECURITY_ACTIONS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'name' => 'SECURITY_ACTIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'auth.account_locked\', \'user.mfa_reset\', \'user.password_reset_forced\', \'mfa.disabled\']',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 111,
            'startFilePos' => 1376,
            'endTokenPos' => 122,
            'endFilePos' => 1462,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 125,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'recent' => 
      array (
        'name' => 'recent',
        'parameters' => 
        array (
          'userId' => 
          array (
            'name' => 'userId',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 140,
                'startFilePos' => 1656,
                'endTokenPos' => 140,
                'endFilePos' => 1659,
              ),
            ),
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
                      'name' => 'null',
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
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 28,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'limit' => 
          array (
            'name' => 'limit',
            'default' => 
            array (
              'code' => '50',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 149,
                'startFilePos' => 1675,
                'endTokenPos' => 149,
                'endFilePos' => 1676,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 52,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'windowDays' => 
          array (
            'name' => 'windowDays',
            'default' => 
            array (
              'code' => '30',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 158,
                'startFilePos' => 1697,
                'endTokenPos' => 158,
                'endFilePos' => 1698,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 69,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
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
 * Recent authentication activity, newest first, optionally narrowed to one user.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 47,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'currentClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'aliasName' => NULL,
      ),
      'outcome' => 
      array (
        'name' => 'outcome',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
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
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 30,
            'endColumn' => 43,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 101,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
        'currentClassName' => 'App\\Domain\\Access\\Services\\LoginActivityService',
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