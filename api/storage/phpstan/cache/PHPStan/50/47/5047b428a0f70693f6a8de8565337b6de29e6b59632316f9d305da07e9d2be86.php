<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Concerns\ScopedToMda
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'filename' => '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Concerns',
    'name' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    'shortName' => 'ScopedToMda',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Opt-in MDA data-scoping for a model. Adding this trait applies the central
 * MdaScope global scope, so the model is automatically restricted to the
 * authenticated user\'s accessible MDAs (FR-UAM-03, FR-DSH-01).
 *
 * Phase 2+ models (e.g. beneficiaries with an `owner_mda_id`) just `use
 * ScopedToMda`. Models whose MDA column differs override mdaOwnershipColumn().
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 32,
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
      'bootScopedToMda' => 
      array (
        'name' => 'bootScopedToMda',
        'parameters' => 
        array (
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
        'startLine' => 19,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Access\\Concerns',
        'declaringClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'implementingClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'currentClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'aliasName' => NULL,
      ),
      'mdaOwnershipColumn' => 
      array (
        'name' => 'mdaOwnershipColumn',
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
 * The column holding the owning MDA\'s id. Defaults to the Phase 2 ownership
 * convention; override where the column differs (e.g. User uses `mda_id`).
 */',
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Concerns',
        'declaringClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'implementingClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
        'currentClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
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