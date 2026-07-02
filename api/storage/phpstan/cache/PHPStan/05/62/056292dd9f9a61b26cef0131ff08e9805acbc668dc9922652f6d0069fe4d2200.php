<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Contracts/DuplicateChecker.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Contracts\DuplicateChecker
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-07a38eba165f1ce7ca7cc6e0b0f418a834e27678da7ecd92825b273def8a8dcc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
        'filename' => '/var/www/html/app/Domain/Registry/Contracts/DuplicateChecker.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Contracts',
    'name' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
    'shortName' => 'DuplicateChecker',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Pre-save duplicate-check extension point (PRD FR-DUP, Phase 3).
 *
 * Manual registration (FR-REG-01) calls this immediately before persisting a new
 * beneficiary. Phase 2 ships a no-op implementation (NullDuplicateChecker) so the
 * seam exists without changing behaviour; Phase 3 binds a real checker that runs
 * fuzzy matching and either blocks the save or surfaces reveal candidates — no
 * caller changes required.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 27,
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
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 27,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'ownerMdaId' => 
          array (
            'name' => 'ownerMdaId',
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
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 46,
            'endColumn' => 63,
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
        'docComment' => '/**
 * Inspect a candidate registration before it is saved. Implementations may
 * throw to block the save (e.g. a hard NIN collision) or record/flag likely
 * duplicates for review. The no-op implementation does nothing.
 *
 * @param  array<string, mixed>  $attributes  the validated registration payload
 * @param  string  $ownerMdaId  the MDA that will own the new record
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Contracts',
        'declaringClassName' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
        'implementingClassName' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
        'currentClassName' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
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