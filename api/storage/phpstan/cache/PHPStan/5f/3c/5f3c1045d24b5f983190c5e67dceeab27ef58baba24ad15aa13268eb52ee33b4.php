<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Http/Controllers/Api/V1/MfaController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '4ac390d38e1c6814333f6be1d96a5788' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
      'bb99c14ff56f725332406288dfdc1abc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
          0 => '/var/www/html/app/Http/Controllers/Api/V1/MfaController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '25ac59810df5246fc9853d6990f83a48' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
           'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
          0 => '/var/www/html/app/Http/Controllers/Api/V1/MfaController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0a46d2fce62fafc495405266217c152d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
           'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
          0 => '/var/www/html/app/Http/Controllers/Api/V1/MfaController.php',
          1 => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
          2 => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0e77571f1866aa0d405bfd0bc5bc5064' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
      '80480b9921f6cdbfe8ff918e2262ba06' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
         'functionName' => 'enroll',
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
      '9cef262b2c6092d3f83f718bb15dea79' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
         'functionName' => 'verify',
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
      'bcb3f5fefa093397ef7bef5d9d378141' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
         'functionName' => 'disable',
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
      '0f3c831f29466779b1a66495f17cf394' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
         'functionName' => 'challenge',
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
      '4ac0ebf0265e194e7a9552e28e9f3b93' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
         'functionName' => 'verifyAnyCode',
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
      '02c9db97d473069bed8fe82acef9a34d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1',
         'uses' => 
        array (
          'accountlocked' => 'App\\Domain\\Access\\Events\\AccountLocked',
          'mfachallengefailed' => 'App\\Domain\\Access\\Events\\MfaChallengeFailed',
          'mfadisabled' => 'App\\Domain\\Access\\Events\\MfaDisabled',
          'mfaenrolled' => 'App\\Domain\\Access\\Events\\MfaEnrolled',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'authtokenissuer' => 'App\\Domain\\Access\\Services\\AuthTokenIssuer',
          'mfaservice' => 'App\\Domain\\Access\\Services\\MfaService',
          'tokenability' => 'App\\Domain\\Access\\Support\\TokenAbility',
          'authresponses' => 'App\\Http\\Controllers\\Concerns\\AuthResponses',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'mfacoderequest' => 'App\\Http\\Requests\\Auth\\MfaCodeRequest',
          'userresource' => 'App\\Http\\Resources\\UserResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\MfaController',
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
    ),
    1 => 
    array (
      '/var/www/html/app/Http/Controllers/Api/V1/MfaController.php' => '50ff7f80419d750c29cbe71aecf3e8b00903ede510b7309895254a182f87b427',
      '/var/www/html/app/Http/Controllers/Concerns/AuthResponses.php' => '7667385dda4e1e79049e0e5548d395e0bccb4835c3d5bb8f84700606f42c2237',
    ),
  ),
));