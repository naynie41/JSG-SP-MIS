<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Mail/ScheduledReportMail.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Mail\ScheduledReportMail
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2b7284fc44f96c18cd6883ccbc5307c2a70ad530b454b468860e98a1c9b56db7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'filename' => '/var/www/html/app/Domain/Reporting/Mail/ScheduledReportMail.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Mail',
    'name' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
    'shortName' => 'ScheduledReportMail',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Emails a scheduled report as an ATTACHMENT (PRD FR-RPT-04, attachment delivery). The
 * file is aggregate, de-identified data (SECURITY.md) and only sent to scope-validated
 * recipients. Queued so sending never blocks. For "link" delivery the notification
 * (in-app + email link) is used instead — no file leaves the system.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 46,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Mail\\Mailable',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Bus\\Queueable',
      1 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'run' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'implementingClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
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
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 9,
        'endColumn' => 38,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'recipientName' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'implementingClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'name' => 'recipientName',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 9,
        'endColumn' => 45,
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
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'recipientName' => 
          array (
            'name' => 'recipientName',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 9,
            'endColumn' => 45,
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
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Mail',
        'declaringClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'implementingClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'currentClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'aliasName' => NULL,
      ),
      'build' => 
      array (
        'name' => 'build',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Mail',
        'declaringClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'implementingClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
        'currentClassName' => 'App\\Domain\\Reporting\\Mail\\ScheduledReportMail',
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