<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Access/Models/MdaAccessGrant.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Models\MdaAccessGrant
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-80454f5cca1295177440dad59922c69b175d5bf25e20ef46174a8e4a926daa17',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'filename' => '/var/www/html/app/Domain/Access/Models/MdaAccessGrant.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Models',
    'name' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
    'shortName' => 'MdaAccessGrant',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An explicit grant allowing a user to access an MDA other than their own
 * (PRD FR-UAM-03, FR-DSH-01). Consumed by the central MDA data-scoping logic.
 *
 * @property string $id
 * @property string $user_id
 * @property string $mda_id
 * @property string|null $granted_by
 * @property string|null $reason
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 74,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'mda_access_grants\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 58,
            'startFilePos' => 717,
            'endTokenPos' => 58,
            'endFilePos' => 735,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'user_id\', \'mda_id\', \'granted_by\', \'reason\', \'expires_at\']',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 39,
            'startTokenPos' => 69,
            'startFilePos' => 806,
            'endTokenPos' => 86,
            'endFilePos' => 911,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 39,
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
        'startLine' => 44,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'currentClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'aliasName' => NULL,
      ),
      'user' => 
      array (
        'name' => 'user',
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
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'currentClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'aliasName' => NULL,
      ),
      'mda' => 
      array (
        'name' => 'mda',
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
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'currentClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'aliasName' => NULL,
      ),
      'grantedBy' => 
      array (
        'name' => 'grantedBy',
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
        'startLine' => 70,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
        'currentClassName' => 'App\\Domain\\Access\\Models\\MdaAccessGrant',
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