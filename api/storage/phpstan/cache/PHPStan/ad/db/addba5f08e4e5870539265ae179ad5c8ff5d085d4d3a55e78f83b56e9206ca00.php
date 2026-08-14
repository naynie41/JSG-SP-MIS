<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Sync\Services\SyncEngine.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '4c99776dbd74f6b663800528353d2ec7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'a8b869a132c6f61228a4f30b89363f91' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'canonicalFields',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'a56e6fec7a09273c06a25cf0ad6e1cba' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      '8f366a99a41addcc5f323a62900ae1f8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'runConnector',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      '29f0953811e7d07c45a24e1e625a8fff' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'runOfflineBatch',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'ac2bdd92322f71ca936b8050d1ce0b09' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'startRun',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'b944dd46d69d37840cffc5e46011d082' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'execute',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      '0d640435e849163dea2d3b98e60ca89c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'process',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'ab313affb96ade83e1806b57c26b5532' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'processRecord',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'f3e1e2d715077dc18d613cc62fe6930d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'stringOrNull',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      '1e133aea3dff00b5ef5f1382393f5cb8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'applyUpdate',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'e95172ed697ae13f47db26ded62f78a3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'firstRegistryReference',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      '33596def097b29c2d64289a4f115ba38' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Sync\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
          'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
          'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
          'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
          'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
          'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
          'arr' => 'Illuminate\\Support\\Arr',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
         'functionName' => 'summary',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Sync\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'registrationsourceadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'beneficiaryregistrar' => 'App\\Domain\\Registry\\Services\\BeneficiaryRegistrar',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'canonicalschema' => 'App\\Domain\\Registry\\Support\\CanonicalSchema',
            'conflictpolicy' => 'App\\Domain\\Sync\\Enums\\ConflictPolicy',
            'syncrowoutcome' => 'App\\Domain\\Sync\\Enums\\SyncRowOutcome',
            'syncstatus' => 'App\\Domain\\Sync\\Enums\\SyncStatus',
            'synctrigger' => 'App\\Domain\\Sync\\Enums\\SyncTrigger',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'syncrunrow' => 'App\\Domain\\Sync\\Models\\SyncRunRow',
            'syncsourceresolver' => 'App\\Domain\\Sync\\Sources\\SyncSourceResolver',
            'arr' => 'Illuminate\\Support\\Arr',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Sync\\Services\\SyncEngine',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Sync\\Services\\SyncEngine.php' => '515eaa368a3f58d2f9e068aed2f6b9d15734104e27a9a0b26176fe37651fe1f1',
    ),
  ),
));