<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Scoring/MatchScore.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\MatchScore
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-513dcbcfac8da9b6c374dd8fb72444130588375a254b1f81001299072a7331b2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'filename' => '/var/www/html/app/Domain/Matching/Scoring/MatchScore.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Scoring',
    'name' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
    'shortName' => 'MatchScore',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * The result of scoring one candidate against one existing record. Carries the
 * normalised composite score, whether a deterministic key set matched, and a
 * PII-free, per-rule explanation (field names + similarities only — never the
 * raw values) for transparency and audit (PRD FR-DUP-03).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 35,
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
      'composite' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'name' => 'composite',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 9,
        'endColumn' => 31,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deterministic' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'name' => 'deterministic',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 9,
        'endColumn' => 34,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'explanation' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'name' => 'explanation',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 9,
        'endColumn' => 33,
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
          'composite' => 
          array (
            'name' => 'composite',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 9,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'deterministic' => 
          array (
            'name' => 'deterministic',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'explanation' => 
          array (
            'name' => 'explanation',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 9,
            'endColumn' => 33,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  list<array<string, mixed>>  $explanation  ordered rules that were evaluated
 */',
        'startLine' => 18,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
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
 * @return array{composite: float, deterministic: bool, explanation: list<array<string, mixed>>}
 */',
        'startLine' => 27,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Scoring',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
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