<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Support/TokenAbility.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Support\TokenAbility
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f8c1ef67e624053f707a6ca7ca5dff8c4b47a40260e8d9f696eceecf0f9a5cee',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'filename' => '/var/www/html/app/Domain/Access/Support/TokenAbility.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Support',
    'name' => 'App\\Domain\\Access\\Support\\TokenAbility',
    'shortName' => 'TokenAbility',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Sanctum token ability constants used to distinguish stages of the auth flow.
 *
 *  - FULL: a fully authenticated session token (granted after MFA, if required).
 *  - MFA_CHALLENGE: short-lived token issued after password verification when the
 *    user must still pass a TOTP/recovery challenge. Can ONLY hit /mfa/challenge.
 *  - MFA_SETUP: short-lived token issued when an MFA-required user has not yet
 *    enrolled. Can ONLY hit the MFA enrol/verify endpoints.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 23,
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
      'FULL' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'implementingClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'name' => 'FULL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'*\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 33,
            'startFilePos' => 602,
            'endTokenPos' => 33,
            'endFilePos' => 604,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 28,
      ),
      'MFA_CHALLENGE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'implementingClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'name' => 'MFA_CHALLENGE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mfa-challenge\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 44,
            'startFilePos' => 641,
            'endTokenPos' => 44,
            'endFilePos' => 655,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'MFA_SETUP' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'implementingClassName' => 'App\\Domain\\Access\\Support\\TokenAbility',
        'name' => 'MFA_SETUP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mfa-setup\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 55,
            'startFilePos' => 688,
            'endTokenPos' => 55,
            'endFilePos' => 698,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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