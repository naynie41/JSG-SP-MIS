<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/HouseholdMembership.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\HouseholdMembership
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d16869f3e73b469a4e1aeb88440bda81436f5a244cc987d5cbcb4618797c08ce',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'filename' => '/var/www/html/app/Domain/Registry/Models/HouseholdMembership.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
    'shortName' => 'HouseholdMembership',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A beneficiary\'s membership of a household over time. Open while `left_at` is
 * null; the DB enforces at most one open membership per beneficiary. Audited so
 * moves/splits are traceable.
 *
 * @property string $id
 * @property string $household_id
 * @property string $beneficiary_id
 * @property HouseholdRole $role_in_household
 * @property Carbon $joined_at
 * @property Carbon|null $left_at
 * @property-read Household $household
 * @property-read Beneficiary $beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 89,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'household_memberships\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 86,
            'startFilePos' => 1094,
            'endTokenPos' => 86,
            'endFilePos' => 1116,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'household_id\', \'beneficiary_id\', \'role_in_household\', \'joined_at\', \'left_at\']',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 46,
            'startTokenPos' => 97,
            'startFilePos' => 1187,
            'endTokenPos' => 114,
            'endFilePos' => 1312,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 46,
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
        'startLine' => 51,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 60,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'aliasName' => NULL,
      ),
      'household' => 
      array (
        'name' => 'household',
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
 * @return BelongsTo<Household, $this>
 */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'aliasName' => NULL,
      ),
      'beneficiary' => 
      array (
        'name' => 'beneficiary',
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
 * @return BelongsTo<Beneficiary, $this>
 */',
        'startLine' => 80,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
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
            'name' => 'Database\\Factories\\HouseholdMembershipFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
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