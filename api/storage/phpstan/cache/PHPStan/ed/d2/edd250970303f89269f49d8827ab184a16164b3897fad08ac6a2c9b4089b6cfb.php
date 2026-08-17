<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Models\Ward.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Models\Ward
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-3373c96b0a834e1e15ec581f3e892ee9b4e6c9d7ea33c4918e92f57f9ddf9a40',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Models\\Ward',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Models/Ward.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Models',
    'name' => 'App\\Domain\\Reference\\Models\\Ward',
    'shortName' => 'Ward',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A ward — reference data, always belonging to exactly one LGA.
 *
 * `code` is unique only WITHIN its LGA: ward names repeat across Jigawa, so a ward is
 * identified by the pair (lga_id, code) and never by code alone. Any future resolution
 * of a free-text ward value must therefore go through its LGA first.
 *
 * @property string $id
 * @property string $lga_id
 * @property string $code
 * @property string $name
 * @property string|null $latitude
 * @property string|null $longitude
 * @property array<string, mixed>|null $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lga|null $lga
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 68,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'wards\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 73,
            'startFilePos' => 1119,
            'endTokenPos' => 73,
            'endFilePos' => 1125,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'lga_id\', \'code\', \'name\', \'latitude\', \'longitude\', \'geometry\']',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 44,
            'startTokenPos' => 84,
            'startFilePos' => 1196,
            'endTokenPos' => 104,
            'endFilePos' => 1273,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 44,
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
        'startLine' => 49,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'aliasName' => NULL,
      ),
      'lga' => 
      array (
        'name' => 'lga',
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
 * @return BelongsTo<Lga, $this>
 */',
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Database\\Factories\\WardFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Ward',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Ward',
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