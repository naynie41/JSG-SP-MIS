<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Benefit\BenefitServiceProvider.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'e1f8d94b7edcc7dd9483499b0c556314' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'servicerequestauthorizer' => 'App\\Domain\\Benefit\\Authorization\\ServiceRequestAuthorizer',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitflag' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'doubledippingrule' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
          'benefitflagpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitFlagPolicy',
          'benefitimportpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitImportPolicy',
          'benefitpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitPolicy',
          'doubledippingrulepolicy' => 'App\\Domain\\Benefit\\Policies\\DoubleDippingRulePolicy',
          'deliveryauthorization' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
          'verifierregistry' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
          'biometricverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\BiometricVerifier',
          'fieldconfirmationverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\FieldConfirmationVerifier',
          'otpverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\OtpVerifier',
          'signatureverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\SignatureVerifier',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Benefit\\BenefitServiceProvider',
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
      '4415e8fb83dc1364283d475b099d5c62' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'servicerequestauthorizer' => 'App\\Domain\\Benefit\\Authorization\\ServiceRequestAuthorizer',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitflag' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'doubledippingrule' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
          'benefitflagpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitFlagPolicy',
          'benefitimportpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitImportPolicy',
          'benefitpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitPolicy',
          'doubledippingrulepolicy' => 'App\\Domain\\Benefit\\Policies\\DoubleDippingRulePolicy',
          'deliveryauthorization' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
          'verifierregistry' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
          'biometricverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\BiometricVerifier',
          'fieldconfirmationverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\FieldConfirmationVerifier',
          'otpverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\OtpVerifier',
          'signatureverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\SignatureVerifier',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Benefit\\BenefitServiceProvider',
         'functionName' => 'register',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit',
           'uses' => 
          array (
            'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
            'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'servicerequestauthorizer' => 'App\\Domain\\Benefit\\Authorization\\ServiceRequestAuthorizer',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitflag' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
            'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
            'doubledippingrule' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
            'benefitflagpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitFlagPolicy',
            'benefitimportpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitImportPolicy',
            'benefitpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitPolicy',
            'doubledippingrulepolicy' => 'App\\Domain\\Benefit\\Policies\\DoubleDippingRulePolicy',
            'deliveryauthorization' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
            'verifierregistry' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
            'biometricverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\BiometricVerifier',
            'fieldconfirmationverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\FieldConfirmationVerifier',
            'otpverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\OtpVerifier',
            'signatureverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\SignatureVerifier',
            'gate' => 'Illuminate\\Support\\Facades\\Gate',
            'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
          ),
           'className' => 'App\\Domain\\Benefit\\BenefitServiceProvider',
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
      '31c17ebc9a5da473697e449d475280ec' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit',
         'uses' => 
        array (
          'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
          'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
          'servicerequestauthorizer' => 'App\\Domain\\Benefit\\Authorization\\ServiceRequestAuthorizer',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitflag' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'doubledippingrule' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
          'benefitflagpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitFlagPolicy',
          'benefitimportpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitImportPolicy',
          'benefitpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitPolicy',
          'doubledippingrulepolicy' => 'App\\Domain\\Benefit\\Policies\\DoubleDippingRulePolicy',
          'deliveryauthorization' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
          'verifierregistry' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
          'biometricverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\BiometricVerifier',
          'fieldconfirmationverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\FieldConfirmationVerifier',
          'otpverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\OtpVerifier',
          'signatureverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\SignatureVerifier',
          'gate' => 'Illuminate\\Support\\Facades\\Gate',
          'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
        ),
         'className' => 'App\\Domain\\Benefit\\BenefitServiceProvider',
         'functionName' => 'boot',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit',
           'uses' => 
          array (
            'permissionaction' => 'App\\Domain\\Access\\Enums\\PermissionAction',
            'permissionregistry' => 'App\\Domain\\Access\\Support\\PermissionRegistry',
            'servicerequestauthorizer' => 'App\\Domain\\Benefit\\Authorization\\ServiceRequestAuthorizer',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitflag' => 'App\\Domain\\Benefit\\Models\\BenefitFlag',
            'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
            'doubledippingrule' => 'App\\Domain\\Benefit\\Models\\DoubleDippingRule',
            'benefitflagpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitFlagPolicy',
            'benefitimportpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitImportPolicy',
            'benefitpolicy' => 'App\\Domain\\Benefit\\Policies\\BenefitPolicy',
            'doubledippingrulepolicy' => 'App\\Domain\\Benefit\\Policies\\DoubleDippingRulePolicy',
            'deliveryauthorization' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
            'verifierregistry' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
            'biometricverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\BiometricVerifier',
            'fieldconfirmationverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\FieldConfirmationVerifier',
            'otpverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\OtpVerifier',
            'signatureverifier' => 'App\\Domain\\Benefit\\Services\\Verifiers\\SignatureVerifier',
            'gate' => 'Illuminate\\Support\\Facades\\Gate',
            'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
          ),
           'className' => 'App\\Domain\\Benefit\\BenefitServiceProvider',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Benefit\\BenefitServiceProvider.php' => '54342bf4f9df217ab8d9e614dee2d2f58c0d59f7703e7888939eb225c895bb11',
    ),
  ),
));