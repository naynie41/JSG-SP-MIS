<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Services/TemporaryPasswordIssuer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Services\TemporaryPasswordIssuer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c9a671658d27f33034187969767948cae7210b9ea14495240868e0fa37cc6981',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'filename' => '/var/www/html/app/Domain/Access/Services/TemporaryPasswordIssuer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Services',
    'name' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
    'shortName' => 'TemporaryPasswordIssuer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Issues a one-time temporary password for an administrator-initiated reset
 * (SECURITY.md §2, FR-UAM-06).
 *
 * The password is GENERATED rather than chosen by the administrator: a human
 * picking "a password to read down the phone" picks a weak one, and the account
 * being recovered may hold beneficiary PII.
 *
 * It is set directly on the model rather than run through PasswordRules, which
 * matters operationally: PasswordRules calls the HaveIBeenPwned range API, so
 * validating here would put an outbound network dependency on the ONLY account
 * recovery path. A 32-character random string is not in a breach corpus.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 57,
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
    ),
    'immediateMethods' => 
    array (
      'issueFor' => 
      array (
        'name' => 'issueFor',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 30,
            'endColumn' => 39,
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
        'docComment' => '/**
 * Reset the user to a fresh temporary password and require them to change it.
 *
 * Returns the plaintext ONCE, for out-of-band handover. It is never audited,
 * logged, or persisted in plaintext — the model\'s `hashed` cast stores only
 * the hash.
 */',
        'startLine' => 32,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'currentClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'aliasName' => NULL,
      ),
      'generate' => 
      array (
        'name' => 'generate',
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
        'docComment' => '/**
 * 32 chars from Str::random (alphanumeric) plus fixed punctuation, so it also
 * satisfies any composition rule a future policy might add.
 */',
        'startLine' => 53,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
        'currentClassName' => 'App\\Domain\\Access\\Services\\TemporaryPasswordIssuer',
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