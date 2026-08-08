<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Matching\Scoring\MatchScore.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Scoring\MatchScore
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9f53b04c942773c62cb180cda85c7ac227d34020adf77508118420a200d51e03',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Matching/Scoring/MatchScore.php',
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
    'endLine' => 161,
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
      'EXACT_AT' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'name' => 'EXACT_AT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0.999',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 129,
            'startFilePos' => 1127,
            'endTokenPos' => 129,
            'endFilePos' => 1131,
          ),
        ),
        'docComment' => '/** Similarity at or above which a fuzzy field reads as the same value. */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'NEAR_AT' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'name' => 'NEAR_AT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0.85',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 142,
            'startFilePos' => 1239,
            'endTokenPos' => 142,
            'endFilePos' => 1242,
          ),
        ),
        'docComment' => '/** Similarity at or above which a fuzzy field reads as a near miss. */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
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
      'fieldComparisons' => 
      array (
        'name' => 'fieldComparisons',
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
 * Per-field verdicts for the human adjudication screen (FR-DUP-09).
 *
 * The officer answering "is this the same person?" needs to see WHICH fields
 * agreed and which did not. They cannot be shown the existing record\'s
 * values — MatchReveal withholds NIN/BVN/phone/DOB precisely because the
 * record belongs to another MDA (FR-DUP-04) — so the comparison is expressed
 * as verdicts computed here, server-side. Nothing in the returned structure
 * carries a field value; it is field names, booleans and similarities only.
 *
 * A field that matched a deterministic key set is reported `exact`
 * regardless of its fuzzy similarity: that is what made it a definitive
 * duplicate.
 *
 * @return list<array{field: string, verdict: string, similarity: float|null, weight: float|null, participated: bool, deterministic: bool}>
 */',
        'startLine' => 58,
        'endLine' => 110,
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
      'verdictFor' => 
      array (
        'name' => 'verdictFor',
        'parameters' => 
        array (
          'entry' => 
          array (
            'name' => 'entry',
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
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 40,
            'endColumn' => 51,
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
        'docComment' => '/**
 * @param  array<string, mixed>  $entry
 */',
        'startLine' => 115,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Matching\\Scoring',
        'declaringClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'implementingClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'currentClassName' => 'App\\Domain\\Matching\\Scoring\\MatchScore',
        'aliasName' => NULL,
      ),
      'matchedFields' => 
      array (
        'name' => 'matchedFields',
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
 * The field names that drove the match (deterministic keys + strong fuzzy
 * fields) — for transparency; never the raw values. Shared by the batch
 * screener and the ad-hoc serve search so both explain matches identically.
 *
 * @return list<string>
 */',
        'startLine' => 146,
        'endLine' => 160,
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