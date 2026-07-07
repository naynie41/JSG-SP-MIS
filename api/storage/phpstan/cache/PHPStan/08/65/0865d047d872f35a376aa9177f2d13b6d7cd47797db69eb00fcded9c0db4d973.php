<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Grievance/Models/GrievanceSlaPolicy.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Grievance\Models\GrievanceSlaPolicy
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2910253196a52181630c5e82e196a910561b82529bf1e773670309e3843e7faa',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'filename' => '/var/www/html/app/Domain/Grievance/Models/GrievanceSlaPolicy.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Grievance\\Models',
    'name' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
    'shortName' => 'GrievanceSlaPolicy',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An admin-editable SLA window for a grievance category (PRD FR-GRM-03): how many
 * hours a grievance of `category` may remain unresolved before it is overdue. Global
 * config (not MDA-scoped); changes are audited.
 *
 * @property string $id
 * @property string $category
 * @property int $hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 46,
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
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'grievance_sla_policies\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 61,
            'startFilePos' => 713,
            'endTokenPos' => 61,
            'endFilePos' => 736,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'category\', \'hours\']',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 35,
            'startTokenPos' => 72,
            'startFilePos' => 807,
            'endTokenPos' => 80,
            'endFilePos' => 850,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 35,
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
        'startLine' => 40,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Grievance\\Models',
        'declaringClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
        'currentClassName' => 'App\\Domain\\Grievance\\Models\\GrievanceSlaPolicy',
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