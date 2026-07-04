<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Services/DoubleDippingDetector.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Services\DoubleDippingDetector
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7f9b6d0217777eb739d596d5079284332f2510b2e343d5aeb97097dfee5a7e6e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'filename' => '/var/www/html/app/Domain/Benefit/Services/DoubleDippingDetector.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Services',
    'name' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
    'shortName' => 'DoubleDippingDetector',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Detects potential double-dipping (PRD FR-BEN-05): after a delivery is recorded,
 * it looks — across ALL MDAs — for another delivery of the SAME benefit type to the
 * SAME beneficiary by a DIFFERENT MDA within an active rule\'s window, and raises a
 * flag. It only flags; it NEVER blocks the delivery. Runs as a system function
 * (scope-bypassed).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 69,
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
      'check' => 
      array (
        'name' => 'check',
        'parameters' => 
        array (
          'benefit' => 
          array (
            'name' => 'benefit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Models\\Benefit',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 27,
            'endColumn' => 42,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'aliasName' => NULL,
      ),
      'raiseFlag' => 
      array (
        'name' => 'raiseFlag',
        'parameters' => 
        array (
          'new' => 
          array (
            'name' => 'new',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Models\\Benefit',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 32,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'conflict' => 
          array (
            'name' => 'conflict',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Models\\Benefit',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 46,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rule' => 
          array (
            'name' => 'rule',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 65,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 54,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\DoubleDippingDetector',
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