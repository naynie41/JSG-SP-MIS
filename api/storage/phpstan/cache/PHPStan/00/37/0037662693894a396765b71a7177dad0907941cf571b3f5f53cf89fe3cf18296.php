<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/Comparators/DateProximityComparator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\Comparators\DateProximityComparator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b41853fd80c0dabe9402bc9520db835d800587f0c32036d957745fefda2bdffe',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/Comparators/DateProximityComparator.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
    'name' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
    'shortName' => 'DateProximityComparator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Date-of-birth proximity (PRD FR-DUP-03): exact dates score 1.0; nearby dates
 * (typos, transposed digits) decay linearly to 0 over a ~3-year window, so a
 * plausible DOB typo still contributes to the score rather than zeroing it.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 35,
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
      'WINDOW_DAYS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
        'name' => 'WINDOW_DAYS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1095',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 35,
            'startFilePos' => 414,
            'endTokenPos' => 35,
            'endFilePos' => 417,
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
            'startLine' => 16,
            'endLine' => 16,
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
            'startLine' => 16,
            'endLine' => 16,
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
        'startLine' => 16,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring\\Comparators',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\Comparators\\DateProximityComparator',
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