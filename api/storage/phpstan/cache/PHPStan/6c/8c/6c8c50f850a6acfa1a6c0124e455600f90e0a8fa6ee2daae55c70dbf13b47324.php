<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Models/ReportDefinition.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Models\ReportDefinition
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-892a6ff86fa1b2af407f2ecaee50845fcc352aa2ab2af6da0ce4f6893835fb3a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'filename' => '/var/www/html/app/Domain/Reporting/Models/ReportDefinition.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Models',
    'name' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
    'shortName' => 'ReportDefinition',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A saved ad-hoc report definition (PRD FR-RPT-03), reusable by its owner and the
 * basis for scheduling (6.6). Holds only whitelisted keys — no scope, no PII.
 *
 * @property string $id
 * @property string $name
 * @property string $dataset
 * @property array<string, mixed> $definition
 * @property string|null $owner_user_id
 * @property string|null $owner_mda_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 61,
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
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'report_definitions\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 58,
            'startFilePos' => 787,
            'endTokenPos' => 58,
            'endFilePos' => 806,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'dataset\', \'definition\', \'owner_user_id\', \'owner_mda_id\']',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 40,
            'startTokenPos' => 69,
            'startFilePos' => 877,
            'endTokenPos' => 86,
            'endFilePos' => 989,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 40,
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
        'startLine' => 45,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'aliasName' => NULL,
      ),
      'toAdHoc' => 
      array (
        'name' => 'toAdHoc',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDefinition',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Rebuild the ad-hoc definition value object (dataset + saved spec). */',
        'startLine' => 53,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportDefinition',
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