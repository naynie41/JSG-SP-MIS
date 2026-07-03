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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
      'de1de2606b6d7e2862965f739460a42c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
      '11cfe22a9b14b3f13eb170abc7d29330' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'registryReveal',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
      'e35feea5cfe7e55b4e73d94338d2ea54' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\ImportBatchController',
         'functionName' => 'batchReveal',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
          'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
          'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
          'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
          'collection' => 'Illuminate\\Support\\Collection',
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
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'commitimportbatch' => 'App\\Domain\\Registry\\Jobs\\CommitImportBatch',
            'parseimportbatch' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'resolveimportrowrequest' => 'App\\Http\\Requests\\Registry\\ResolveImportRowRequest',
            'uploadimportrequest' => 'App\\Http\\Requests\\Registry\\UploadImportRequest',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'importbatchresource' => 'App\\Http\\Resources\\ImportBatchResource',
            'importrowresource' => 'App\\Http\\Resources\\ImportRowResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
            'collection' => 'Illuminate\\Support\\Collection',
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
      '/var/www/html/app/Http/Controllers/Api/V1/Registry/ImportBatchController.php' => '2a4f835b4a503838d2542f6dde9c28d753e0411679d9accde5e976fb4eccfcdc',
    ),
  ),
));