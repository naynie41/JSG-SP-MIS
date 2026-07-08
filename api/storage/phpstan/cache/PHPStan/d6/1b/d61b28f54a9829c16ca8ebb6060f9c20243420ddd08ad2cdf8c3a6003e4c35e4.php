<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Matching/Models/MatchingConfig.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Matching\Models\MatchingConfig
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-45ac8dcc882927dba2ca43f8a8db8ac53d0be89ef2273ca3c05d1abd7786a93c',
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $author
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 103,
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
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 76,
            'startFilePos' => 1216,
            'endTokenPos' => 76,
            'endFilePos' => 1233,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
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
            'startLine' => 43,
            'endLine' => 53,
            'startTokenPos' => 87,
            'startFilePos' => 1304,
            'endTokenPos' => 116,
            'endFilePos' => 1544,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 53,
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
        'startLine' => 58,
        'endLine' => 69,
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
        'startLine' => 77,
        'endLine' => 80,
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
        'startLine' => 85,
        'endLine' => 88,
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
        'startLine' => 91,
        'endLine' => 94,
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
        'startLine' => 99,
        'endLine' => 102,
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