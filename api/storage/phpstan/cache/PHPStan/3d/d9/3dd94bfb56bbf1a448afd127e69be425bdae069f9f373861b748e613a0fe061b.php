<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/Comparators/JaroWinklerComparator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\Comparators\JaroWinklerComparator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f6ab7f5b1d9902af627998b41e0149c93d4907c5d8b5a1046663b3ddcfb36eef',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/Comparators/JaroWinklerComparator.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
    'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
    'shortName' => 'JaroWinklerComparator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Jaro-Winkler string similarity (`[0, 1]`) — the default for names, tolerant of
 * typos and transpositions while rewarding a shared prefix. Pure PHP so it runs
 * identically on PostgreSQL and sqlite (no pg_trgm dependency).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 113,
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
      'PREFIX_SCALE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'name' => 'PREFIX_SCALE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0.1',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 35,
            'startFilePos' => 408,
            'endTokenPos' => 35,
            'endFilePos' => 410,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'MAX_PREFIX' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'name' => 'MAX_PREFIX',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '4',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 46,
            'startFilePos' => 445,
            'endTokenPos' => 46,
            'endFilePos' => 445,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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
            'startLine' => 18,
            'endLine' => 18,
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
            'startLine' => 18,
            'endLine' => 18,
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
        'startLine' => 18,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'aliasName' => NULL,
      ),
      'jaro' => 
      array (
        'name' => 'jaro',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
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
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 27,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
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
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 38,
            'endColumn' => 46,
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
        'startLine' => 37,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'aliasName' => NULL,
      ),
      'commonPrefix' => 
      array (
        'name' => 'commonPrefix',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
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
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
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
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 46,
            'endColumn' => 54,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 95,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\JaroWinklerComparator',
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