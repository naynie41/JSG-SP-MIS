<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Graduation\Models\GraduationCriteria.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\Models\GraduationCriteria
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-598a2e9a73e8dc0b3cd7987821ad9309678e6591b0ce8abfe327ec597b953370',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Graduation/Models/GraduationCriteria.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Graduation\\Models',
    'name' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
    'shortName' => 'GraduationCriteria',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An MDA-owned, admin-editable graduation criteria set for a catalog programme
 * (FR-GRD-01). The `rules` are configuration (a list of {type, threshold}), never
 * hard-coded logic. MDA-scoped on `owner_mda_id`, so each MDA defines its own criteria
 * for the programmes it runs without touching the shared programme catalog (§10).
 *
 * @property string $id
 * @property string $programme_id
 * @property string $owner_mda_id
 * @property string $name
 * @property CriteriaLogic $logic
 * @property list<array{type: string, threshold: int|float}> $rules
 * @property bool $is_active
 * @property string|null $created_by
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 86,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      3 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'graduation_criteria\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 108,
            'startFilePos' => 1403,
            'endTokenPos' => 108,
            'endFilePos' => 1423,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'programme_id\', \'owner_mda_id\', \'name\', \'logic\', \'rules\', \'is_active\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 52,
            'startTokenPos' => 119,
            'startFilePos' => 1494,
            'endTokenPos' => 142,
            'endFilePos' => 1641,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 52,
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
        'startLine' => 57,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'aliasName' => NULL,
      ),
      'programme' => 
      array (
        'name' => 'programme',
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
 * @return BelongsTo<Programme, $this>
 */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'aliasName' => NULL,
      ),
      'ownerMda' => 
      array (
        'name' => 'ownerMda',
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
 * @return BelongsTo<Mda, $this>
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
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
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
            'name' => 'Database\\Factories\\GraduationCriteriaFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Graduation\\Models',
        'declaringClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'implementingClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
        'currentClassName' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
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