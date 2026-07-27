<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\RegistryServiceProvider.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'a0445aeb209ae8e7614278027d73e572' => 
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
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
      'bc96f35755a56151088e9d78027d3004' => 
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
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
      '97da4e00c9a2f7897bfd707ebfc1f7c7' => 
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
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
      '97160ca294683edd8c6ad0325eadfcb0' => 
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
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
          'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
          'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
          'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
          'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'beneficiarydocumentpolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryDocumentPolicy',
            'beneficiarypolicy' => 'App\\Domain\\Registry\\Policies\\BeneficiaryPolicy',
            'householdpolicy' => 'App\\Domain\\Registry\\Policies\\HouseholdPolicy',
            'importbatchpolicy' => 'App\\Domain\\Registry\\Policies\\ImportBatchPolicy',
            'ownermdapolicy' => 'App\\Domain\\Registry\\Policies\\OwnerMdaPolicy',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\RegistryServiceProvider.php' => '8782c969857fb9c0dcf5210ce7f3b65a97b61ba51a5dbc64c7cc2abca8a6edba',
    ),
  ),
));