<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Events/ReportReady.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Events\ReportReady
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-902e71f6dd7ff94c17fe80cfcb9bf59934f64595f3830594f78847ae558e78e5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Events\\ReportReady',
        'filename' => '/var/www/html/app/Domain/Reporting/Events/ReportReady.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Events',
    'name' => 'App\\Domain\\Reporting\\Events\\ReportReady',
    'shortName' => 'ReportReady',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when a queued report finishes generating (PRD FR-RPT-03/FR-NOT-01). The
 * notification subscriber alerts the requester (in-app + email) with a deep link to
 * the run, so heavy reports don\'t block the request.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 20,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Events\\Dispatchable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'run' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Events\\ReportReady',
        'implementingClassName' => 'App\\Domain\\Reporting\\Events\\ReportReady',
        'name' => 'run',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Models\\ReportRun',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 33,
        'endColumn' => 62,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'run' => 
          array (
            'name' => 'run',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Models\\ReportRun',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 33,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 66,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Events',
        'declaringClassName' => 'App\\Domain\\Reporting\\Events\\ReportReady',
        'implementingClassName' => 'App\\Domain\\Reporting\\Events\\ReportReady',
        'currentClassName' => 'App\\Domain\\Reporting\\Events\\ReportReady',
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