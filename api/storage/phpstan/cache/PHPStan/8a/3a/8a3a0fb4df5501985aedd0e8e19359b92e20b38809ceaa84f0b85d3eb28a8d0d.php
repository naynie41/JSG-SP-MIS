<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Support/PhoneticEncoder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Support\PhoneticEncoder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c3bb4cf143a17feea7d3eabdab4c278640724f5d5df1674634109f08158e033f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'filename' => '/var/www/html/app/Domain/Matching/Support/PhoneticEncoder.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Support',
    'name' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
    'shortName' => 'PhoneticEncoder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A lightweight phonetic encoder tuned for Hausa/Nigerian name variance
 * (PRD FR-DUP-03). It folds common spelling variants onto a shared consonant
 * skeleton so that, e.g., Muhammad / Mohammed / Muhammadu, Ibrahim / Ibraheem,
 * Sani / Saani, and Sadiq / Saddiq / Sadqi (transposed) all encode alike.
 *
 * Used two ways: the full `code()` drives the phonetic name comparator; the short
 * `block()` prefix drives candidate blocking (higher recall for the same key).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 47,
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
      'VOWELS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'implementingClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'name' => 'VOWELS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'a\', \'e\', \'i\', \'o\', \'u\']',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 31,
            'startFilePos' => 604,
            'endTokenPos' => 45,
            'endFilePos' => 628,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'FOLDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'implementingClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'name' => 'FOLDS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'ph\' => \'f\', \'ck\' => \'k\', \'q\' => \'k\', \'x\' => \'ks\']',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 58,
            'startFilePos' => 714,
            'endTokenPos' => 85,
            'endFilePos' => 764,
          ),
        ),
        'docComment' => '/** Common cluster folds before skeletonisation. */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 78,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'code' => 
      array (
        'name' => 'code',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 26,
            'endColumn' => 37,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Full consonant-skeleton code (first letter preserved, doubles collapsed). */',
        'startLine' => 24,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Support',
        'declaringClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'implementingClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'currentClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'aliasName' => NULL,
      ),
      'block' => 
      array (
        'name' => 'block',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 27,
            'endColumn' => 38,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Short blocking prefix of the code (groups more variants together). */',
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Support',
        'declaringClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'implementingClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
        'currentClassName' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
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