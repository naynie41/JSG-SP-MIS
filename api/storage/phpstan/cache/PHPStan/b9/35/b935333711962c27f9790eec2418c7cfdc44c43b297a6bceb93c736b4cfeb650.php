<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/Mail/NotificationMail.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\Mail\NotificationMail
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-9527a3d1217f814e8be0720941274df2adcbb57a6c20ca7ada61624ca745b728',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'filename' => '/var/www/html/app/Domain/Notification/Mail/NotificationMail.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification\\Mail',
    'name' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
    'shortName' => 'NotificationMail',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The email rendering of a notification (PRD FR-NOT-01). Queued (rabbitmq) so
 * sending never blocks the request. Uses the SMTP config in `config/mail.php`
 * (`MAIL_MAILER=log` in dev).
 *
 * **Email is not a secure channel** (NDPA/NDPR minimisation). It sits on third-party
 * relays, in inboxes on unmanaged devices, and in backups nobody in this system
 * controls. So the body carries only what the subscriber composed — an event, a
 * requesting organisation, a link — and NEVER beneficiary identity: no name, no NIN,
 * no BVN, no phone. The record itself is reached by logging in, where scope, role and
 * the audit trail all still apply. Everything here is escaped; nothing is interpolated
 * from a beneficiary.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 77,
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
      'message' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'implementingClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'name' => 'message',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 9,
        'endColumn' => 52,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'recipientName' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'implementingClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
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
        'startLine' => 32,
        'endLine' => 32,
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
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 9,
            'endColumn' => 52,
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
            'startLine' => 32,
            'endLine' => 32,
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
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Mail',
        'declaringClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'implementingClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'currentClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
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
        'startLine' => 35,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Mail',
        'declaringClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'implementingClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'currentClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'aliasName' => NULL,
      ),
      'footer' => 
      array (
        'name' => 'footer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * How to stop receiving these. Preferences ARE the unsubscribe mechanism
 * (FR-NOT-02): these are operational notices to named officers about their own
 * caseload, not marketing, so the control lives behind their login rather than on a
 * public one-click token endpoint — a URL that mutates a user\'s settings without
 * authentication is a surface this system does not need.
 */',
        'startLine' => 66,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Notification\\Mail',
        'declaringClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'implementingClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
        'currentClassName' => 'App\\Domain\\Notification\\Mail\\NotificationMail',
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