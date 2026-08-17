<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Models\Lga.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Models\Lga
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ec11637781a3ca3f3a1336fa52b9fb191e77881289c157a0c3ced05e6f4ab5c6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Models\\Lga',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Models/Lga.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Models',
    'name' => 'App\\Domain\\Reference\\Models\\Lga',
    'shortName' => 'Lga',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A Local Government Area — reference data, not owned by any MDA.
 *
 * Deliberately NOT MdaScoped and NOT Auditable: this is a shared lookup list every
 * MDA reads identically, and it changes only when a maintainer loads a new
 * authoritative dataset (which the loader reports on) — not through user action.
 *
 * `code` is the slug shared with {@see \\App\\Domain\\Registry\\Enums\\Lga} and
 * `geo_boundaries.code`. The enum remains the validation authority for
 * `beneficiaries.lga` (FR-REG-04/05, a locked decision); this table is the
 * navigable hierarchy that the enum cannot express, since the enum has no wards.
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $state
 * @property string|null $latitude
 * @property string|null $longitude
 * @property array<string, mixed>|null $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Ward> $wards
 * @property-read int|null $wards_count
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 75,
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
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'lgas\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 78,
            'startFilePos' => 1523,
            'endTokenPos' => 78,
            'endFilePos' => 1528,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'code\', \'name\', \'state\', \'latitude\', \'longitude\', \'geometry\']',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 51,
            'startTokenPos' => 89,
            'startFilePos' => 1599,
            'endTokenPos' => 109,
            'endFilePos' => 1675,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 51,
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
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'aliasName' => NULL,
      ),
      'wards' => 
      array (
        'name' => 'wards',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<Ward, $this>
 */',
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Lga',
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
            'name' => 'Database\\Factories\\LgaFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Reference\\Models',
        'declaringClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'implementingClassName' => 'App\\Domain\\Reference\\Models\\Lga',
        'currentClassName' => 'App\\Domain\\Reference\\Models\\Lga',
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