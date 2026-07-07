<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Grievance/Jobs/EscalateOverdueGrievances.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Grievance\Jobs\EscalateOverdueGrievances
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-33ac339573028f109ccd54f33c04529cff633c870eeb8ab401742458c61ea106',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
        'filename' => '/var/www/html/app/Domain/Grievance/Jobs/EscalateOverdueGrievances.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Grievance\\Jobs',
    'name' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
    'shortName' => 'EscalateOverdueGrievances',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled SLA sweep (PRD FR-GRM-03), mirroring {@see EscalateOverdueReferrals}.
 * Flags unresolved grievances that have breached their CATEGORY window (measured from
 * when they were logged) and escalates one tier per elapsed window, capped at the
 * configured chain length, dispatching {@see GrievanceSlaBreached} whenever the level
 * increases. It NEVER changes a grievance\'s status — no auto-close; only escalate + notify.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 81,
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
      'ACTIVE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
        'implementingClassName' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
        'name' => 'ACTIVE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Grievance\\Enums\\GrievanceStatus::Open, \\App\\Domain\\Grievance\\Enums\\GrievanceStatus::Assigned, \\App\\Domain\\Grievance\\Enums\\GrievanceStatus::InProgress]',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 37,
            'startTokenPos' => 116,
            'startFilePos' => 1310,
            'endTokenPos' => 133,
            'endFilePos' => 1419,
          ),
        ),
        'docComment' => '/** Non-terminal statuses that can breach an SLA (still unresolved). */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
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
          'audit' => 
          array (
            'name' => 'audit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Audit\\Services\\AuditLogger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 28,
            'endColumn' => 45,
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
        'startLine' => 39,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Grievance\\Jobs',
        'declaringClassName' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
        'implementingClassName' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
        'currentClassName' => 'App\\Domain\\Grievance\\Jobs\\EscalateOverdueGrievances',
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