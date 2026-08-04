<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\AdminSettingsService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\AdminSettingsService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a53f0ed5beb353e40a7d68403cb02d45bdfa12b12694b7c880dc4e6aa312fd3f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/AdminSettingsService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
    'shortName' => 'AdminSettingsService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The EFFECTIVE configuration behind the administration console\'s Settings page.
 *
 * Everything here is **read-only and derived** — the live value of an existing config
 * key, a registered channel\'s own availability, or a locked domain constant. The
 * console deliberately has no settings store of its own: a value that is set by
 * environment/config is reported with the key that owns it, so an administrator can
 * see what is in force and where to change it, and the console can never drift from
 * the deployment\'s real configuration.
 *
 * The two things an administrator genuinely CHANGES from Settings are handled by their
 * existing owners: the permission matrix (`role_permission` via
 * {@see RolePermissionService}) and their own notification
 * preferences (`/notifications/preferences`).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 145,
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
      'channels' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'name' => 'channels',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'iterable',
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
          'channels' => 
          array (
            'name' => 'channels',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
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
        'docComment' => '/**
 * @param  iterable<NotificationChannel>  $channels
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
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
 * @return array<string, mixed>
 */',
        'startLine' => 37,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'general' => 
      array (
        'name' => 'general',
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
 * @return list<array{label: string, value: string, source: string}>
 */',
        'startLine' => 50,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'security' => 
      array (
        'name' => 'security',
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
 * @return array<string, mixed>
 */',
        'startLine' => 65,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'registry' => 
      array (
        'name' => 'registry',
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
 * @return array<string, mixed>
 */',
        'startLine' => 91,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'notifications' => 
      array (
        'name' => 'notifications',
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
 * Channel availability is asked of each registered channel, so a stubbed
 * SMS/WhatsApp integration reports itself as unavailable rather than the console
 * claiming a delivery path that does not exist.
 *
 * @return list<array{key: string, available: bool}>
 */',
        'startLine' => 123,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'row' => 
      array (
        'name' => 'row',
        'parameters' => 
        array (
          'label' => 
          array (
            'name' => 'label',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 26,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'source' => 
          array (
            'name' => 'source',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 2,
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
 * @return array{label: string, value: string, source: string}
 */',
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'aliasName' => NULL,
      ),
      'bool' => 
      array (
        'name' => 'bool',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 27,
            'endColumn' => 37,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 141,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSettingsService',
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