<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Jobs/RunDueReportSchedules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Jobs\RunDueReportSchedules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1207a2577d08685952dd409fcc8c374d94bc1b4ad6b07a04a46a1d7e2515cff0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Jobs\\RunDueReportSchedules',
        'filename' => '/var/www/html/app/Domain/Reporting/Jobs/RunDueReportSchedules.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Jobs',
    'name' => 'App\\Domain\\Reporting\\Jobs\\RunDueReportSchedules',
    'shortName' => 'RunDueReportSchedules',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled sweep of due report schedules (PRD FR-RPT-04). Runs on the shared
 * scheduler/queue; each due schedule generates a report run (which is then delivered
 * to its validated recipients). Never runs a paused schedule.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 28,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Bus\\Queueable',
      3 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'schedules' => 
          array (
            'name' => 'schedules',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Services\\ReportScheduleService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 28,
            'endColumn' => 59,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Jobs',
        'declaringClassName' => 'App\\Domain\\Reporting\\Jobs\\RunDueReportSchedules',
        'implementingClassName' => 'App\\Domain\\Reporting\\Jobs\\RunDueReportSchedules',
        'currentClassName' => 'App\\Domain\\Reporting\\Jobs\\RunDueReportSchedules',
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