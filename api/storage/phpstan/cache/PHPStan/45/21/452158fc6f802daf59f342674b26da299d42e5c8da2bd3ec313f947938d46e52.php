<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Support/PasswordRules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Support\PasswordRules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-317ee13fcbbfd9a9df5acc408f9089882c0ef520a7054682186e23cb08d05c78',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Support\\PasswordRules',
        'filename' => '/var/www/html/app/Domain/Access/Support/PasswordRules.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Support',
    'name' => 'App\\Domain\\Access\\Support\\PasswordRules',
    'shortName' => 'PasswordRules',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Central password policy (SECURITY.md §2): minimum 12 characters and checked
 * against known breached passwords (HaveIBeenPwned k-anonymity range API).
 *
 * Reused anywhere a password is set so the policy stays consistent.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 30,
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
      'default' => 
      array (
        'name' => 'default',
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
 * @return array<int, Rule|Password|string>
 */',
        'startLine' => 21,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Access\\Support',
        'declaringClassName' => 'App\\Domain\\Access\\Support\\PasswordRules',
        'implementingClassName' => 'App\\Domain\\Access\\Support\\PasswordRules',
        'currentClassName' => 'App\\Domain\\Access\\Support\\PasswordRules',
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