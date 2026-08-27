<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/FieldNormalizer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\FieldNormalizer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d6b993e628a9a931e824623777a1b39c996f1f5e72ba36e94673dbdecad2ae81',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/FieldNormalizer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring',
    'name' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
    'shortName' => 'FieldNormalizer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Normalises a raw field value into a comparable form before scoring.
 *
 * Both sides of a comparison pass through here, so this is where two written forms of
 * the same fact are reconciled. It delegates to the one {@see NormalizationService} that
 * the registry uses, rather than keeping a second set of rules: when the two drifted, a
 * value stored one way and compared another silently stopped matching.
 *
 * Two of those drifts were real and are fixed by delegating:
 *  - PHONE: digit-stripping alone left `+2348031234567` and `08031234567` as different
 *    strings, so the exact phone comparator never matched them.
 *  - DATE: `strtotime()` reads `12/03/1995` as 3 December (month-first). Sources here
 *    are written day-first, so the date — and the birth-year half of the blocking key —
 *    could be nine months out.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 58,
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
      'normalizer' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'name' => 'normalizer',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Support\\NormalizationService',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Domain\\Registry\\Support\\NormalizationService()',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 49,
            'startFilePos' => 1128,
            'endTokenPos' => 51,
            'endFilePos' => 1151,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 33,
        'endColumn' => 108,
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
          'normalizer' => 
          array (
            'name' => 'normalizer',
            'default' => 
            array (
              'code' => 'new \\App\\Domain\\Registry\\Support\\NormalizationService()',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 49,
                'startFilePos' => 1128,
                'endTokenPos' => 51,
                'endFilePos' => 1151,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Support\\NormalizationService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 33,
            'endColumn' => 108,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 112,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'aliasName' => NULL,
      ),
      'normalize' => 
      array (
        'name' => 'normalize',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
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
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 31,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\FieldNormalizer',
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