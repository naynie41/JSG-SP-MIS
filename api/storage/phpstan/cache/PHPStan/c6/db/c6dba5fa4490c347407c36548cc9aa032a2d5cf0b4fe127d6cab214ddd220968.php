<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Http/Controllers/Api/V1/Registry/ImportBatchController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '97ed30ebbe22a81b7cd96217dd81ddbb' => 
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
      'ab7d72627410c50061048275d47938fa' => 
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
      'de1de2606b6d7e2862965f739460a42c' => 
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
      '5c27d5e9b23745c43540d0f281145f6a' => 
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
      'a29106a1b5f6ac93b8c0b47bf543ef98' => 
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
      '9e85c7088f218249f1215fd2cd17241e' => 
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
      '64ecd2616c255cef9a6326f1b6e72f66' => 
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
      '2ed08a044c296eb4cb1fdca9a710d2ad' => 
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
      '9c8586f351c9862b6de6bfb75c3fc60a' => 
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
      '3ffa20377ce98ca5a2c72d18bbd383b2' => 
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
      '/var/www/html/app/Http/Controllers/Api/V1/Registry/ImportBatchController.php' => 'c82526efce9cad59c23b42f0c849d847cdcb26762dd4a6cd10ffe324e9511a6c',
    ),
  ),
));