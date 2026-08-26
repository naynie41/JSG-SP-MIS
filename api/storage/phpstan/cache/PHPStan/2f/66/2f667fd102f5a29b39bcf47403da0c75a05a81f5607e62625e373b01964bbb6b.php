<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Http\Controllers\Api\V1\Registry\ImportBatchController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'a343f037cf182f0f90cba84b36399646' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      'dc663b97fd7efd6f61aa19c240b93fd0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      '2ac6d1d97048a3e9b390f8d86e32f2f0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'index',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      '19728505011c9abe05883cccbd0414ea' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'store',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      'f4589b2db4069712379b00147d9bee79' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'mapping',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      '2ca9606ebe7eb07a422fcf0707113a6d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'confirmMapping',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      'aa5edbf6a062e3979ea2c3ac2e612647' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'show',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      'c0d8404d8ea355e6a9b548ddbb71d1d3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'attachMatchReveals',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      '0c1fcd497cd0d6c2e3d40b28c401d766' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'resolveRow',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      '36ccebaefc9c9bf2e7ace661dfd7f1ce' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
          'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'domainexception' => 'DomainException',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'confirm',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'matchband' => 'App\\Domain\\Matching\\Enums\\MatchBand',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importmappingservice' => 'App\\Domain\\Registry\\Services\\ImportMappingService',
            'matchrevealassembler' => 'App\\Domain\\Registry\\Services\\MatchRevealAssembler',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'confirmmappingrequest' => 'App\\Http\\Requests\\Registry\\ConfirmMappingRequest',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'domainexception' => 'DomainException',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController.php' => 'c82526efce9cad59c23b42f0c849d847cdcb26762dd4a6cd10ffe324e9511a6c',
    ),
  ),
));