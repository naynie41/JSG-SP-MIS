<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Scopes/MdaScope.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Scopes\MdaScope
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-478e85b109874681106a1684c196f21c2dba4647cfa7797df0be396bd0b242d5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        'filename' => '/var/www/html/app/Domain/Access/Scopes/MdaScope.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Scopes',
    'name' => 'App\\Domain\\Access\\Scopes\\MdaScope',
    'shortName' => 'MdaScope',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Central MDA data-scoping (PRD FR-UAM-03, FR-DSH-01). Restricts every query on
 * an MDA-owned model to the authenticated user\'s accessible MDAs:
 *
 *  - No authenticated user (console, queue, pre-auth) → no restriction.
 *  - User holds the `cross-mda.view` permission → no restriction (oversight roles).
 *  - Otherwise → limited to the user\'s own MDA plus any active cross-MDA grants.
 *
 * Applied via the ScopedToMda trait so modules opt in by default and scoping is
 * never re-implemented ad hoc in controllers (SECURITY.md §3).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Scope',
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
      'apply' => 
      array (
        'name' => 'apply',
        'parameters' => 
        array (
          'builder' => 
          array (
            'name' => 'builder',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 27,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 45,
            'endColumn' => 56,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Scopes',
        'declaringClassName' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        'implementingClassName' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        'currentClassName' => 'App\\Domain\\Access\\Scopes\\MdaScope',
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