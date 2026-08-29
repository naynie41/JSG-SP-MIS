<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Http/Controllers/Api/V1/Sync/SyncController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '07f76356f50608084db622a7ec9f74c2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '00c7fe2d07a596618d26d613de3d34e5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '4eb8047518ee3fb9abef12c2f7a20d86' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'connectors',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      'e1ec587a29ac57ddf8c0328bdea5f156' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'runs',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '7f2687a7fdbec98d9cd89545ac8da95e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'run',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '81912aafc41977bc7ac2fa5ac6de4eff' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'mapping',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '384a0a3547dad747d0f4082ece237b58' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'confirmMapping',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '8461b35b7067386ba892bdb74be51e59' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'setEnabled',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '554631b4c3688a0f06dc8c736e4ce290' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'setActivity',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '0864a0c69e209a8935d7d169d49b39ab' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'trigger',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '9d54eda716d30b1913cb2e02f6bd5a15' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
          'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
          'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
          'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
          'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'rule' => 'Illuminate\\Validation\\Rule',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
         'functionName' => 'offlineBatch',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Sync',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'runsyncconnector' => 'App\\Domain\\Sync\\Jobs\\RunSyncConnector',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'connectormappingservice' => 'App\\Domain\\Sync\\Services\\ConnectorMappingService',
            'syncengine' => 'App\\Domain\\Sync\\Services\\SyncEngine',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmconnectormappingrequest' => 'App\\Http\\Requests\\Sync\\ConfirmConnectorMappingRequest',
            'offlinebatchrequest' => 'App\\Http\\Requests\\Sync\\OfflineBatchRequest',
            'syncconnectorresource' => 'App\\Http\\Resources\\SyncConnectorResource',
            'syncrunresource' => 'App\\Http\\Resources\\SyncRunResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'rule' => 'Illuminate\\Validation\\Rule',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Sync\\SyncController',
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
      '/var/www/html/app/Http/Controllers/Api/V1/Sync/SyncController.php' => '8a6ac1ae5340093319b8d7dcc8aab283983030d36f507f122dc55ceb98989a91',
    ),
  ),
));