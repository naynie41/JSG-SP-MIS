<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Models/ReportRun.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Models\ReportRun
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5386016caf18aaed4ecdfe5f378bba919406ab244ce83068f8f3399c014f5bda',
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
    'endLine' => 127,
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
      'KEY_ADHOC' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'KEY_ADHOC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'adhoc\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 111,
            'startFilePos' => 1808,
            'endTokenPos' => 111,
            'endFilePos' => 1814,
          ),
        ),
        'docComment' => '/** Report keys whose run carries a `definition` rather than a catalogue key. */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'KEY_SEGMENT' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportRun',
        'name' => 'KEY_SEGMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'segment\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 122,
            'startFilePos' => 1849,
            'endTokenPos' => 122,
            'endFilePos' => 1857,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 41,
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
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 131,
            'startFilePos' => 1884,
            'endTokenPos' => 131,
            'endFilePos' => 1896,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
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
          'code' => '[\'report_key\', \'report_label\', \'format\', \'status\', \'scope_kind\', \'scope_label\', \'scope_governance\', \'scope_mda_ids\', \'scope_programme_ids\', \'params\', \'definition\', \'row_count\', \'file_path\', \'file_name\', \'error\', \'requested_by\', \'requested_mda_id\', \'schedule_id\', \'recipient_user_ids\', \'delivery\', \'completed_at\']',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 69,
            'startTokenPos' => 142,
            'startFilePos' => 1967,
            'endTokenPos' => 207,
            'endFilePos' => 2317,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 69,
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
        'startLine' => 74,
        'endLine' => 86,
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
            'startLine' => 89,
            'endLine' => 89,
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
        'docComment' => '/**
 * The ad-hoc definition this run was built from, if it is an ad-hoc report.
 *
 * Keyed on `report_key`, not merely on `definition` being present: the segment
 * builder persists a definition of its own shape, and dispatching on presence alone
 * would hand a segment run to the ad-hoc aggregator — which would either fail or,
 * worse, render a different report than the one that was requested and audited.
 */',
        'startLine' => 102,
        'endLine' => 109,
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
        'startLine' => 112,
        'endLine' => 121,
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
        'startLine' => 123,
        'endLine' => 126,
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