<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Resources/ImportBatchResource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Resources\ImportBatchResource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9f64da30ceecc01da138f0ec9813f5791114559f9c4928ae01fd1b4d259231b7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Resources\\ImportBatchResource',
        'filename' => '/var/www/html/app/Http/Resources/ImportBatchResource.php',
      ),
    ),
    'namespace' => 'App\\Http\\Resources',
    'name' => 'App\\Http\\Resources\\ImportBatchResource',
    'shortName' => 'ImportBatchResource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A bulk import batch with its progress summary (PRD FR-REG-02/06). Staged rows
 * are included as `rows` when the relation is loaded (the preview).
 *
 * @mixin ImportBatch
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 125,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Http\\Resources\\Json\\JsonResource',
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
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 29,
            'endColumn' => 44,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 23,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Resources',
        'declaringClassName' => 'App\\Http\\Resources\\ImportBatchResource',
        'implementingClassName' => 'App\\Http\\Resources\\ImportBatchResource',
        'currentClassName' => 'App\\Http\\Resources\\ImportBatchResource',
        'aliasName' => NULL,
      ),
      'matchingThresholds' => 
      array (
        'name' => 'matchingThresholds',
        'parameters' => 
        array (
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
                  'name' => 'array',
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
        'docComment' => '/**
 * Thresholds from the active matching configuration. Only meaningful once a
 * preview exists, so it is resolved lazily and returns null when no config
 * is active — the client falls back to showing the band alone.
 *
 * @return array{review: float, auto_accept: float|null}|null
 */',
        'startLine' => 112,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Http\\Resources',
        'declaringClassName' => 'App\\Http\\Resources\\ImportBatchResource',
        'implementingClassName' => 'App\\Http\\Resources\\ImportBatchResource',
        'currentClassName' => 'App\\Http\\Resources\\ImportBatchResource',
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