<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/Comparators/ComparatorRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\Comparators\ComparatorRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-0b09da4939d73f4a49fac70f941cd234a548781475e41d2f2e33462570295971',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/Comparators/ComparatorRegistry.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
    'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
    'shortName' => 'ComparatorRegistry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Resolves a {@see Comparator} for a configured comparator key. New comparators
 * are added by registering them here — the scorer never changes.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 35,
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
      'comparators' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'name' => 'comparators',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/** @var array<string, Comparator> */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 31,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'comparator' => 
          array (
            'name' => 'comparator',
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 25,
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
            'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\Comparator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\ComparatorRegistry',
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