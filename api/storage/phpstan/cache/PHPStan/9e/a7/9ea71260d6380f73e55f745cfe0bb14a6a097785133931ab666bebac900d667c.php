<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Events/MfaEnrolled.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Events\MfaEnrolled
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-54bd3fb003c5ee5fc3ac649ad69aee0b2cb788a097da24ba49f4b148bb25f3cf',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
        'filename' => '/var/www/html/app/Domain/Access/Events/MfaEnrolled.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Events',
    'name' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
    'shortName' => 'MfaEnrolled',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when a user enables MFA. Audit hook — the audit-log step (next) will
 * listen for this to record the enrolment (FR-AUD-01).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 19,
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
      'user' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
        'implementingClassName' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
        'name' => 'user',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Access\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 33,
        'endColumn' => 58,
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 33,
            'endColumn' => 58,
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
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Events',
        'declaringClassName' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
        'implementingClassName' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
        'currentClassName' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
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