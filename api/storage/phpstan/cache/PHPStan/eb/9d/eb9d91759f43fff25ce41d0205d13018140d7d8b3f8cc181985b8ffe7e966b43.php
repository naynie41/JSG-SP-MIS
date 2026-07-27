<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Benefit\Services\BenefitRecorder.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '6d58589edfcd4603edb379f271eeb3e1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
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
      '6d7be728d858f7a4f9ba49c1ee0db900' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
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
      'ed830cca563578c4acccc82fba059d91' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
         'functionName' => 'record',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
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
      'd89353843d16234118a0ff4f84e1c99d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
         'functionName' => 'verify',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
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
      '1c47b9e62e10729bd455d01745e0b80d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Services',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
         'functionName' => 'applyVerification',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Services',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'enrollmentstatus' => 'App\\Domain\\Programme\\Enums\\EnrollmentStatus',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'consentgate' => 'App\\Domain\\Registry\\Services\\ConsentGate',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Benefit\\Services\\BenefitRecorder.php' => '3260cdd273c69f6ba969b701b8696d4d52c9a4f6d175577af8424c819fa4d89c',
    ),
  ),
));