<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Grievance/Events/GrievanceSlaBreached.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Grievance\Events\GrievanceSlaBreached
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b9ed5e62bb9f41e2a61bb402eeefc7d0007c0d7dafeea05b870b16260ff32a76',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'filename' => '/var/www/html/app/Domain/Grievance/Events/GrievanceSlaBreached.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Grievance\\Events',
    'name' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
    'shortName' => 'GrievanceSlaBreached',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when a grievance is overdue against its category SLA (PRD FR-GRM-03). The
 * scheduled sweep escalates (bumping `escalation_level`) and dispatches this at each
 * new level; the notification subscriber alerts the handling MDA\'s grievance team +
 * the escalation tier. Nothing is auto-closed.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 24,
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
      'grievance' => 
      array (
        'declaringClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'implementingClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'name' => 'grievance',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 9,
        'endColumn' => 44,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'level' => 
      array (
        'declaringClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'implementingClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'name' => 'level',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 9,
        'endColumn' => 34,
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
          'grievance' => 
          array (
            'name' => 'grievance',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Grievance\\Models\\Grievance',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 9,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Events',
        'declaringClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'implementingClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
        'currentClassName' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
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