<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/Comparators/PhoneticComparator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\Comparators\PhoneticComparator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-16e0f06a6a024ca27942b0f9c51ecce965235d7a76c71c077039dc89cc0dd2f6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/Comparators/PhoneticComparator.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
    'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
    'shortName' => 'PhoneticComparator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Name similarity that blends phonetics with edit distance (PRD FR-DUP-03): a
 * phonetic-code match scores 1.0 (handles Hausa/Nigerian spelling variance);
 * otherwise it falls back to Jaro-Winkler so near-misses still score.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 32,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Matching\\Scoring\\Comparators\\Comparator',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'encoder' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'name' => 'encoder',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Domain\\Matching\\Support\\PhoneticEncoder()',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 49,
            'startFilePos' => 505,
            'endTokenPos' => 51,
            'endFilePos' => 523,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 9,
        'endColumn' => 71,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fallback' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'name' => 'fallback',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator()',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 64,
            'startFilePos' => 585,
            'endTokenPos' => 66,
            'endFilePos' => 609,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 9,
        'endColumn' => 84,
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
          'encoder' => 
          array (
            'name' => 'encoder',
            'default' => 
            array (
              'code' => 'new \\App\\Domain\\Matching\\Support\\PhoneticEncoder()',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 49,
                'startFilePos' => 505,
                'endTokenPos' => 51,
                'endFilePos' => 523,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 9,
            'endColumn' => 71,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'fallback' => 
          array (
            'name' => 'fallback',
            'default' => 
            array (
              'code' => 'new \\App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator()',
              'attributes' => 
              array (
                'startLine' => 18,
                'endLine' => 18,
                'startTokenPos' => 64,
                'startFilePos' => 585,
                'endTokenPos' => 66,
                'endFilePos' => 609,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
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
            'startColumn' => 9,
            'endColumn' => 84,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 16,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'aliasName' => NULL,
      ),
      'compare' => 
      array (
        'name' => 'compare',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 41,
            'endColumn' => 50,
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
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\PhoneticComparator',
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