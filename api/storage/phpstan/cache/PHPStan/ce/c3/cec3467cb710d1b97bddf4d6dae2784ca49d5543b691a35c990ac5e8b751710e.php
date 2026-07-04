<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Models/DoubleDippingRule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Models\DoubleDippingRule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ebc75dd07505f13868661f44d1c40d38be2b3c18051c597b98c936beadcfb282',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'filename' => '/var/www/html/app/Domain/Benefit/Models/DoubleDippingRule.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Models',
    'name' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
    'shortName' => 'DoubleDippingRule',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A configurable double-dipping rule (PRD FR-BEN-05): a window plus the benefit
 * types it applies to. System-wide, admin-editable, audited.
 *
 * @property string $id
 * @property string $name
 * @property int $period_days
 * @property list<string>|null $benefit_types
 * @property bool $is_active
 * @property string|null $description
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 68,
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
      3 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'double_dipping_rules\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 84,
            'startFilePos' => 1015,
            'endTokenPos' => 84,
            'endFilePos' => 1036,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'period_days\', \'benefit_types\', \'is_active\', \'description\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 95,
            'startFilePos' => 1107,
            'endTokenPos' => 112,
            'endFilePos' => 1188,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 109,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'is_active\' => true]',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 123,
            'startFilePos' => 1269,
            'endTokenPos' => 129,
            'endFilePos' => 1289,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 50,
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
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
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
            'name' => 'Database\\Factories\\DoubleDippingRuleFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'aliasName' => NULL,
      ),
      'appliesTo' => 
      array (
        'name' => 'appliesTo',
        'parameters' => 
        array (
          'benefitType' => 
          array (
            'name' => 'benefitType',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 31,
            'endColumn' => 49,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Whether this rule applies to the given benefit type. */',
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Models',
        'declaringClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'implementingClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
        'currentClassName' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
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