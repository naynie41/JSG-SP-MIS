<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Models/ReportSchedule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Models\ReportSchedule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8a140ce5887a4b1bbb5a7ccddda9cc95d82a40551c908ad03dd26b30f86ce9a9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'filename' => '/var/www/html/app/Domain/Reporting/Models/ReportSchedule.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Models',
    'name' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
    'shortName' => 'ReportSchedule',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A scheduled report (PRD FR-RPT-04). Captures WHAT to generate (a standard key or a
 * saved ad-hoc definition), the frequency/format/delivery, the owner\'s SCOPE, and the
 * validated recipients. Runs unattended on the scheduler; the captured scope + covered
 * recipients guarantee it never delivers out-of-scope data. Personal to owner.
 *
 * @property string $id
 * @property string $name
 * @property string|null $report_key
 * @property string|null $report_definition_id
 * @property string $format
 * @property string $frequency
 * @property string $delivery
 * @property string $status
 * @property string $scope_kind
 * @property string $scope_label
 * @property list<string>|null $scope_mda_ids
 * @property list<string>|null $scope_programme_ids
 * @property list<string> $recipient_user_ids
 * @property string|null $owner_user_id
 * @property string|null $owner_mda_id
 * @property Carbon|null $last_run_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ReportDefinition|null $definition
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 113,
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
      'STATUS_ACTIVE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'STATUS_ACTIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'active\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 65,
            'startFilePos' => 1444,
            'endTokenPos' => 65,
            'endFilePos' => 1451,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'STATUS_PAUSED' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'STATUS_PAUSED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paused\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 76,
            'startFilePos' => 1488,
            'endTokenPos' => 76,
            'endFilePos' => 1495,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'DELIVERY_LINK' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'DELIVERY_LINK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'link\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 87,
            'startFilePos' => 1532,
            'endTokenPos' => 87,
            'endFilePos' => 1537,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'DELIVERY_ATTACHMENT' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'DELIVERY_ATTACHMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'attachment\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 98,
            'startFilePos' => 1580,
            'endTokenPos' => 98,
            'endFilePos' => 1591,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
      'FREQUENCIES' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'FREQUENCIES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'daily\', \'weekly\', \'monthly\']',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 111,
            'startFilePos' => 1655,
            'endTokenPos' => 119,
            'endFilePos' => 1684,
          ),
        ),
        'docComment' => '/** @var list<string> */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 62,
      ),
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'report_schedules\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 128,
            'startFilePos' => 1711,
            'endTokenPos' => 128,
            'endFilePos' => 1728,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'report_key\', \'report_definition_id\', \'format\', \'frequency\', \'delivery\', \'status\', \'scope_kind\', \'scope_label\', \'scope_mda_ids\', \'scope_programme_ids\', \'recipient_user_ids\', \'owner_user_id\', \'owner_mda_id\', \'last_run_on\']',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 63,
            'startTokenPos' => 139,
            'startFilePos' => 1799,
            'endTokenPos' => 186,
            'endFilePos' => 2059,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 63,
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
        'startLine' => 68,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'aliasName' => NULL,
      ),
      'definition' => 
      array (
        'name' => 'definition',
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
 * @return BelongsTo<ReportDefinition, $this>
 */',
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
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
        'docComment' => NULL,
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'aliasName' => NULL,
      ),
      'isActive' => 
      array (
        'name' => 'isActive',
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
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'aliasName' => NULL,
      ),
      'dueOn' => 
      array (
        'name' => 'dueOn',
        'parameters' => 
        array (
          'today' => 
          array (
            'name' => 'today',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\Carbon',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 27,
            'endColumn' => 39,
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
        'docComment' => '/** Whether the schedule is due to run on a given day (active + frequency elapsed). */',
        'startLine' => 97,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Models',
        'declaringClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'implementingClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
        'currentClassName' => 'App\\Domain\\Reporting\\Models\\ReportSchedule',
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