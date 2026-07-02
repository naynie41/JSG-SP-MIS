<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Http/Controllers/Api/V1/AuthController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'c51cefad291528c73eeddcb486ff057f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '05ef4f46bb92459555aec3fd433762e2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Http/Controllers/Api/V1/AuthController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0eddf59e6aeb21924c2cff00d72d27cc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'invalidCredentials',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Concerns',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Http/Controllers/Api/V1/AuthController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b95c2053950b24ac2f9525fba86323d5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'accountLocked',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Concerns',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Http/Controllers/Api/V1/AuthController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6765601ae70e547b2b2f51b6f44fcd1f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'a6ed98cd219c350c6d9b1bc73fecba53' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'login',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '512b52278b6afde09293a7a499b9731a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'me',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '8dd131ce7422d3d875dae88b5f85c8cd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'logout',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'ddacc8e8ba4a557736b41014efd4a65b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'changePassword',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'f50fa1e0e688d2a86621bfe8174f4d90' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'fullTokenResponse',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '1b0e965ab3fac58b34a16fe483b8d786' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'userstatus' => 'App\\Domain\\Access\\Enums\\UserStatus',
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'changepasswordrequest' => 'App\\Http\\Requests\\Auth\\ChangePasswordRequest',
          'loginrequest' => 'App\\Http\\Requests\\Auth\\LoginRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\AuthController',
         'functionName' => 'credentialsAreValid',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      '/var/www/html/app/Http/Controllers/Api/V1/AuthController.php' => '32b23f3f6e7c8b13457e68a81c3aad585b6e819d3bae83cc6921126f77ddfb18',
      '/var/www/html/app/Http/Controllers/Concerns/AuthResponses.php' => '7667385dda4e1e79049e0e5548d395e0bccb4835c3d5bb8f84700606f42c2237',
    ),
  ),
));