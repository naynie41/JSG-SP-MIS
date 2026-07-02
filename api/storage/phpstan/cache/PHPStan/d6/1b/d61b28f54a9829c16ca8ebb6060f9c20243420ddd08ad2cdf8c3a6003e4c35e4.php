<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Models/MatchingConfig.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Models\MatchingConfig
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9cd6661890ca5fab9d7981b2008513e3963bf6ab1f66806dc733cbfd572fa7f3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'filename' => '/var/www/html/app/Domain/Matching/Models/MatchingConfig.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Matching\\Models',
    'name' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
    'shortName' => 'MatchingConfig',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A versioned duplicate-matching configuration (PRD FR-DUP-02/03). Each row is an
 * immutable version; the active one drives the engine. Auditable, so every
 * published change is recorded.
 *
 * @property string $id
 * @property int $version
 * @property bool $is_active
 * @property list<list<string>> $deterministic_rules
 * @property list<array{field: string, comparator: string, weight: float}> $fuzzy_fields
 * @property float $review_threshold
 * @property float|null $auto_accept_threshold
 * @property ExactMatchBehaviour $exact_match_behaviour
 * @property string|null $description
 * @property string|null $created_by
 * @property-read User|null $author
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 100,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'matching_configs\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 71,
            'startFilePos' => 1111,
            'endTokenPos' => 71,
            'endFilePos' => 1128,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'version\', \'is_active\', \'deterministic_rules\', \'fuzzy_fields\', \'review_threshold\', \'auto_accept_threshold\', \'exact_match_behaviour\', \'description\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 50,
            'startTokenPos' => 82,
            'startFilePos' => 1199,
            'endTokenPos' => 111,
            'endFilePos' => 1439,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'casts' => 
      array (
        'name' => 'casts',
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
 * @return array<string, string>
 */',
        'startLine' => 55,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Matching\\Models',
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'currentClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'aliasName' => NULL,
      ),
      'deterministicKeySets' => 
      array (
        'name' => 'deterministicKeySets',
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
 * Deterministic exact-match key sets — a candidate matches if every field in
 * any one set is present and equal.
 *
 * @return list<list<string>>
 */',
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Models',
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'currentClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'aliasName' => NULL,
      ),
      'fuzzyFields' => 
      array (
        'name' => 'fuzzyFields',
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
 * @return list<array{field: string, comparator: string, weight: float}>
 */',
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Models',
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'currentClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'aliasName' => NULL,
      ),
      'totalFuzzyWeight' => 
      array (
        'name' => 'totalFuzzyWeight',
        'parameters' => 
        array (
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
        'docComment' => '/** Sum of the configured fuzzy weights (the score denominator). */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Models',
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'currentClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'aliasName' => NULL,
      ),
      'author' => 
      array (
        'name' => 'author',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Matching\\Models',
        'declaringClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'implementingClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
        'currentClassName' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
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