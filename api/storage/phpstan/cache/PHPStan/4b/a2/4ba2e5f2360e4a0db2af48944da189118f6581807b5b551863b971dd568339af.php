<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Contracts/MatchScorer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Contracts\MatchScorer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-6a50d61d435858d12704ccce8ddd03866c7b7f03fb80dd7bde921fc99e7dd470',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Contracts\\MatchScorer',
        'filename' => '/var/www/html/app/Domain/Matching/Contracts/MatchScorer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Contracts',
    'name' => 'App\\Domain\\Matching\\Contracts\\MatchScorer',
    'shortName' => 'MatchScorer',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scores one candidate record against one existing record under a matching
 * configuration (PRD FR-DUP-03). This is the SINGLE seam an alternative scorer
 * implements — including a future external/AI scorer (FR-DUP-07) — so callers
 * (the MatchingEngine, the pre-save duplicate check, the standalone lookup) never
 * change when the scoring strategy changes.
 *
 * ## Contract
 * - Input records are associative arrays of canonical beneficiary fields
 *   (`nin`, `bvn`, `phone`, `first_name`, `last_name`, `date_of_birth`, `gender`,
 *   `lga`, `ward`, …). Values may be null/absent; implementations MUST treat a
 *   missing value on either side as "no evidence" (never a positive match).
 * - The returned {@see MatchScore} carries: a normalised composite in `[0,1]`, a
 *   `deterministic` flag (a decisive exact key set matched), and a structured,
 *   PII-free `explanation` of which rules fired (field NAMES + similarities only,
 *   never the raw values) — for transparency and audit.
 * - Banding (exact/probable/none) is applied by the engine from the config
 *   thresholds, NOT by the scorer. A scorer only measures similarity.
 * - Implementations MUST be pure (no side effects) and deterministic for a given
 *   input, so results are reproducible and auditable.
 *
 * The built-in {@see RuleBasedMatchScorer} provides the deterministic + fuzzy
 * scorer. To add an AI scorer later, implement this interface and bind it in the
 * MatchingServiceProvider — no caller edits needed.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 43,
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
      'score' => 
      array (
        'name' => 'score',
        'parameters' => 
        array (
          'candidate' => 
          array (
            'name' => 'candidate',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 27,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'existing' => 
          array (
            'name' => 'existing',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 45,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 62,
            'endColumn' => 83,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $candidate  the record being checked
 * @param  array<string, mixed>  $existing  an existing registry record
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 97,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Contracts',
        'declaringClassName' => 'App\\Domain\\Matching\\Contracts\\MatchScorer',
        'implementingClassName' => 'App\\Domain\\Matching\\Contracts\\MatchScorer',
        'currentClassName' => 'App\\Domain\\Matching\\Contracts\\MatchScorer',
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