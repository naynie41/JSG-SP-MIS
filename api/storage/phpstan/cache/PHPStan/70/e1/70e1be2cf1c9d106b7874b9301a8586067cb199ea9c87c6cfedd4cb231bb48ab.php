<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reference/Imports/LoadAdministrativeDivisions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Imports\LoadAdministrativeDivisions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-dfe96b814d906ce5fba62786eaac8f192ea87eaff18b3b1fb13c6ba63ca61280',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'filename' => '/var/www/html/app/Domain/Reference/Imports/LoadAdministrativeDivisions.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Imports',
    'name' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
    'shortName' => 'LoadAdministrativeDivisions',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Loads Jigawa LGA/Ward reference data from an authoritative dataset file:
 *
 *   php artisan reference:load-divisions
 *   php artisan reference:load-divisions storage/app/reference/jigawa-wards.csv
 *
 * With no argument it reads `config(\'reference.divisions.path\')`. Idempotent.
 * Fails loudly (non-zero exit, explanatory message) rather than seeding placeholders.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 66,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'reference:load-divisions {file? : path to the CSV/JSON dataset}\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 38,
            'startFilePos' => 565,
            'endTokenPos' => 38,
            'endFilePos' => 629,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 93,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Load Jigawa LGA/Ward reference data from an authoritative dataset file\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 47,
            'startFilePos' => 662,
            'endTokenPos' => 47,
            'endFilePos' => 733,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 102,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'loader' => 
          array (
            'name' => 'loader',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reference\\Imports\\AdministrativeDivisionLoader',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 28,
            'endColumn' => 63,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\LoadAdministrativeDivisions',
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