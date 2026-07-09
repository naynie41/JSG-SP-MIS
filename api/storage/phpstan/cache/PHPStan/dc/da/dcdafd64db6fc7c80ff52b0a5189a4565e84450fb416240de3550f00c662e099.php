<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Models/ReportRun.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Models\ReportRun
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2793228d889e663871b3371890daa77fd4a781d5da63c9bd78a4d0573243aeae',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'filename' => '/var/www/html/app/Domain/Reporting/Models/ReportRun.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Models',
    'name' => 'App\\Domain\\Reporting\\Models\\ReportRun',
    'shortName' => 'ReportRun',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A generated report run (PRD FR-RPT-03). Holds the requested report + format, the
 * captured scope, and the lifecycle/status of generation. Personal to the requester
 * (queried by `requested_by`); the payload is aggregate-only, so it is not Auditable
 * as a model — generation + downloads are audited explicitly.
 *
 * @property string $id
 * @property string $report_key
 * @property string $report_label
 * @property string $format
 * @property string $status
 * @property string $scope_kind
 * @property string $scope_label
 * @property list<string>|null $scope_mda_ids
 * @property list<string>|null $scope_programme_ids
 * @property array<string, mixed>|null $params
 * @property array<string, mixed>|null $definition
 * @property int|null $row_count
 * @property string|null $file_path
 * @property string|null $file_name
 * @property string|null $error
 * @property string|null $requested_by
 * @property string|null $requested_mda_id
 * @property string|null $schedule_id
 * @property list<string>|null $recipient_user_ids
 * @property string|null $delivery
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 42,
    'endLine' => 104,
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
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 65,
            'startFilePos' => 1544,
            'endTokenPos' => 65,
            'endFilePos' => 1552,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'STATUS_PROCESSING' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'STATUS_PROCESSING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'processing\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 76,
            'startFilePos' => 1593,
            'endTokenPos' => 76,
            'endFilePos' => 1604,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'STATUS_READY' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'STATUS_READY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ready\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 87,
            'startFilePos' => 1640,
            'endTokenPos' => 87,
            'endFilePos' => 1646,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'STATUS_FAILED' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'STATUS_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'failed\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 98,
            'startFilePos' => 1683,
            'endTokenPos' => 98,
            'endFilePos' => 1690,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'report_runs\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 107,
            'startFilePos' => 1717,
            'endTokenPos' => 107,
            'endFilePos' => 1729,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'report_key\', \'report_label\', \'format\', \'status\', \'scope_kind\', \'scope_label\', \'scope_mda_ids\', \'scope_programme_ids\', \'params\', \'definition\', \'row_count\', \'file_path\', \'file_name\', \'error\', \'requested_by\', \'requested_mda_id\', \'schedule_id\', \'recipient_user_ids\', \'delivery\', \'completed_at\']',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 64,
            'startTokenPos' => 118,
            'startFilePos' => 1800,
            'endTokenPos' => 180,
            'endFilePos' => 2130,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 64,
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
        'startLine' => 69,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'aliasName' => NULL,
      ),
      'isAccessibleBy' => 
      array (
        'name' => 'isAccessibleBy',
        'parameters' => 
        array (
          'userId' => 
          array (
            'name' => 'userId',
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
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 36,
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
        'docComment' => '/** Users allowed to access this run: the requester plus any scheduled recipients. */',
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'aliasName' => NULL,
      ),
      'adHocDefinition' => 
      array (
        'name' => 'adHocDefinition',
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
                  'name' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDefinition',
                  'isIdentifier' => false,
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
        'docComment' => '/** The ad-hoc definition this run was built from, if it is an ad-hoc report. */',
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'aliasName' => NULL,
      ),
      'toScope' => 
      array (
        'name' => 'toScope',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Rebuild the scope this run was requested under. */',
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'aliasName' => NULL,
      ),
      'isReady' => 
      array (
        'name' => 'isReady',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
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