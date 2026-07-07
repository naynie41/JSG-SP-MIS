<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Notification/Listeners/NotificationSubscriber.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Notification\Listeners\NotificationSubscriber
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2178313e62650f59d9193e3cbef25552a294274c4cfdbe28a2bfd77c4fb6afac',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'filename' => '/var/www/html/app/Domain/Notification/Listeners/NotificationSubscriber.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Notification\\Listeners',
    'name' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
    'shortName' => 'NotificationSubscriber',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Turns domain events into notifications (PRD FR-NOT-01), mirroring the
 * AuditEventSubscriber pattern. It resolves recipients (approvers in an MDA, or the
 * original requester) and hands a channel-agnostic message to the {@see Notifier}.
 * Bodies carry NO beneficiary PII — only a reference to the originating entity.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 128,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'notifier' => 
      array (
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'name' => 'notifier',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Notification\\Services\\Notifier',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 33,
        'endColumn' => 67,
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
          'notifier' => 
          array (
            'name' => 'notifier',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Notification\\Services\\Notifier',
                'isIdentifier' => false,
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
            'startColumn' => 33,
            'endColumn' => 67,
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
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'handleServiceRequestRaised' => 
      array (
        'name' => 'handleServiceRequestRaised',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 48,
            'endColumn' => 74,
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
        'startLine' => 28,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'handleServiceRequestAccepted' => 
      array (
        'name' => 'handleServiceRequestAccepted',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 50,
            'endColumn' => 78,
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
        'startLine' => 42,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'handleServiceRequestDeclined' => 
      array (
        'name' => 'handleServiceRequestDeclined',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 50,
            'endColumn' => 78,
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
        'startLine' => 55,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'handleOwnershipTransferRequested' => 
      array (
        'name' => 'handleOwnershipTransferRequested',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
            'startColumn' => 54,
            'endColumn' => 86,
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
        'startLine' => 68,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'approversIn' => 
      array (
        'name' => 'approversIn',
        'parameters' => 
        array (
          'mdaId' => 
          array (
            'name' => 'mdaId',
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'permission' => 
          array (
            'name' => 'permission',
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 49,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Users in an MDA holding a permission. Bypasses the MDA scope because the
 * actor firing the event is usually in a DIFFERENT MDA than the recipients.
 *
 * @return Collection<int, User>
 */',
        'startLine' => 88,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'requester' => 
      array (
        'name' => 'requester',
        'parameters' => 
        array (
          'requestedBy' => 
          array (
            'name' => 'requestedBy',
            'default' => NULL,
            'type' => 
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
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 32,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fromMdaId' => 
          array (
            'name' => 'fromMdaId',
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
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 54,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The requester to notify: the original requesting user if known, otherwise the
 * requesting MDA\'s users who can raise/act on requests.
 *
 * @return Collection<int, User>
 */',
        'startLine' => 104,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'aliasName' => NULL,
      ),
      'subscribe' => 
      array (
        'name' => 'subscribe',
        'parameters' => 
        array (
          'events' => 
          array (
            'name' => 'events',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Events\\Dispatcher',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 31,
            'endColumn' => 48,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<class-string, string>
 */',
        'startLine' => 119,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Notification\\Listeners',
        'declaringClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'implementingClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
        'currentClassName' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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