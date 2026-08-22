<?php declare(strict_types = 1);

// osfsl-C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/database/factories/LgaFactory.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Factories\LgaFactory
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b57688ac7971817012f234b4fb2de84b5c807b1388d46bf66dff835621a138af-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Factories\\LgaFactory',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/database/factories/LgaFactory.php',
      ),
    ),
    'namespace' => 'Database\\Factories',
    'name' => 'Database\\Factories\\LgaFactory',
    'shortName' => 'LgaFactory',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * TEST FIXTURES ONLY.
 *
 * Draws from the real 27-LGA enum (which is committed reference data, not a guess) so
 * fixtures stay consistent with validation. It is never used by a seeder — production
 * LGA rows come only from the maintainer-supplied dataset.
 *
 * @extends Factory<Lga>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 56,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
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
      'model' => 
      array (
        'declaringClassName' => 'Database\\Factories\\LgaFactory',
        'implementingClassName' => 'Database\\Factories\\LgaFactory',
        'name' => 'model',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\App\\Domain\\Reference\\Models\\Lga::class',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 52,
            'startFilePos' => 556,
            'endTokenPos' => 54,
            'endFilePos' => 565,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'next' => 
      array (
        'declaringClassName' => 'Database\\Factories\\LgaFactory',
        'implementingClassName' => 'Database\\Factories\\LgaFactory',
        'name' => 'next',
        'modifiers' => 20,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 69,
            'startFilePos' => 923,
            'endTokenPos' => 69,
            'endFilePos' => 923,
          ),
        ),
        'docComment' => '/**
 * Walks the 27 enum cases in order rather than picking at random.
 *
 * `lgas.code` is unique, so a random pick makes any test that creates two LGAs
 * fail roughly one run in twenty-seven — a flake that looks like an unrelated bug
 * and is nearly impossible to reproduce on demand.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 33,
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
      'definition' => 
      array (
        'name' => 'definition',
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
 * @return array<string, mixed>
 */',
        'startLine' => 36,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\LgaFactory',
        'implementingClassName' => 'Database\\Factories\\LgaFactory',
        'currentClassName' => 'Database\\Factories\\LgaFactory',
        'aliasName' => NULL,
      ),
      'forEnum' => 
      array (
        'name' => 'forEnum',
        'parameters' => 
        array (
          'lga' => 
          array (
            'name' => 'lga',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Enums\\Lga',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 29,
            'endColumn' => 40,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Factories',
        'declaringClassName' => 'Database\\Factories\\LgaFactory',
        'implementingClassName' => 'Database\\Factories\\LgaFactory',
        'currentClassName' => 'Database\\Factories\\LgaFactory',
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