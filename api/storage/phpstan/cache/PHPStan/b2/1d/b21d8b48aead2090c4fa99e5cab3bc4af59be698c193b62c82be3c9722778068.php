<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Benefit\BenefitController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '5985d26d515c209fc8743f6fa14deb25' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      '8a830839fd9cf4b7e080b77d30146255' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      'f35bafb214f3b2d51ab2d2dd6222acc8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'index',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      '2d1e8ca12bc3fc032d87549f41fdf76c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'aggregate',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      '5f430654c47457abc7e1a394064875d0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'store',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      '3e0b9a948f0901700ef207369391bbfa' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'show',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      'c599e86df2d1ec4ee87ee749e2732084' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'verify',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      'a63ac012d4a9fd2a43b8c08abdd7d057' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'ledger',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      'b8c9b17aee49c83d853d63a36f8b16c9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'canViewLedger',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      '66d7a9b186571d41f683c06e36fa7198' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
          'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
          'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
          'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
          'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
          'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
          'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
         'functionName' => 'activityMismatch',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Benefit',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'deliverynotauthorizedexception' => 'App\\Domain\\Benefit\\Exceptions\\DeliveryNotAuthorizedException',
            'notenrolledexception' => 'App\\Domain\\Benefit\\Exceptions\\NotEnrolledException',
            'processingconsentrequiredexception' => 'App\\Domain\\Benefit\\Exceptions\\ProcessingConsentRequiredException',
            'verificationunavailableexception' => 'App\\Domain\\Benefit\\Exceptions\\VerificationUnavailableException',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'benefitrecorder' => 'App\\Domain\\Benefit\\Services\\BenefitRecorder',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'aggregatebenefitsrequest' => 'App\\Http\\Requests\\Benefit\\AggregateBenefitsRequest',
            'recordbenefitrequest' => 'App\\Http\\Requests\\Benefit\\RecordBenefitRequest',
            'verifybenefitrequest' => 'App\\Http\\Requests\\Benefit\\VerifyBenefitRequest',
            'benefitresource' => 'App\\Http\\Resources\\BenefitResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Http\\Controllers\\Api\\V1\\Benefit\\BenefitController.php' => '683539f6b0ab2afdb39f5180848da23f8a668c317e811d963f7cd6dcc04c8d31',
    ),
  ),
));