<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Benefit/BenefitServiceProvider.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '98088c994146f05ec70fc2af8a5ade6a' => 
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
      'bedd874c5653a2c877b426ed54745b4b' => 
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
      'bf2779df74b850dc7cada0aaf48aa50f' => 
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
      '/var/www/html/app/Domain/Benefit/BenefitServiceProvider.php' => '54342bf4f9df217ab8d9e614dee2d2f58c0d59f7703e7888939eb225c895bb11',
    ),
  ),
));