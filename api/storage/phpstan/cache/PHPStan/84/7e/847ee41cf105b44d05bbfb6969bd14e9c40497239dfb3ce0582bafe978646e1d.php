<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Registry/RegistryServiceProvider.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '57c525ced492bef8b56124001823d9ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
          'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
          'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
          'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
          'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
          'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
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
      '7e65edc8dcb1f222a50e513d60792104' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
          'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
          'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
          'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
          'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
          'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
         'functionName' => 'register',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry',
           'uses' => 
          array (
            'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
            'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
            'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
            'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
            'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
            'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
            'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
            'gate' => 'Illuminate\\Support\\Facades\\Gate',
            'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
          ),
           'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
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
      '103f03b3de121881c179e66ccc59d597' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
          'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
          'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
          'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
          'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
          'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
         'functionName' => 'boot',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry',
           'uses' => 
          array (
            'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
            'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
            'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
            'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
            'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
            'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
            'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
            'gate' => 'Illuminate\\Support\\Facades\\Gate',
            'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
          ),
           'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
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
      '1b84149f1d197de260978a2eace2c759' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
          'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
          'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
          'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
          'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
          'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
         'functionName' => 'registerPermissions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry',
           'uses' => 
          array (
            'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
            'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'programmematchingrouter' => 'App\\Domain\\Programme\\Services\\ProgrammeMatchingRouter',
            'beneficiaryrouter' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
            'duplicatechecker' => 'App\\Domain\\Registry\\Contracts\\DuplicateChecker',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'defaultimportadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\DefaultImportAdapter',
            'koboadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\KoboAdapter',
            'odkadapter' => 'App\\Domain\\Registry\\Imports\\Adapters\\OdkAdapter',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarydocument' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'serverequest' => 'App\\Domain\\Registry\\Models\\ServeRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'serverequestpolicy' => 'App\\Domain\\Registry\\Policies\\ServeRequestPolicy',
            'nullduplicatechecker' => 'App\\Domain\\Registry\\Services\\NullDuplicateChecker',
            'gate' => 'Illuminate\\Support\\Facades\\Gate',
            'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
          ),
           'className' => 'App\\Domain\\Registry\\RegistryServiceProvider',
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
      '/var/www/html/app/Domain/Registry/RegistryServiceProvider.php' => '6141b63778379014d0aaa2783b142586abc9dd0a3b6b0e627d9d50a06488f90b',
    ),
  ),
));