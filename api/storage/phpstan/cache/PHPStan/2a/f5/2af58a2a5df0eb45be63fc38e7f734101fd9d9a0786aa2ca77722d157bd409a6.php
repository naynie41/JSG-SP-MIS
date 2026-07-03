<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryController.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '2f91067c2f14812039180aa7eba93322' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '7492dc7dca34dcafb91ca8eef9c18133' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '88b030ef7c1fa620d5448461fd0fb0aa' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '309c14016e789d88f2322c30ff412c21' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
         'functionName' => 'update',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '0c6b32d931ce3ca525340ac73bc4008c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
         'functionName' => 'destroy',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '89218da8524f35a79e6f36a0baf00115' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
         'functionName' => 'lookup',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '0329a1148c1d2866eaf191d5ac490e24' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Registry',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
          'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
          'controller' => 'App\\Http\\Controllers\\Controller',
          'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
          'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
          'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
          'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
          'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
          'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
          'apiresponse' => 'App\\Support\\ApiResponse',
          'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
          'request' => 'Illuminate\\Http\\Request',
        ),
         'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
         'functionName' => 'search',
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
            'matchresult' => 'App\\Domain\\Matching\\Engine\\MatchResult',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'beneficiarylookupservice' => 'App\\Domain\\Registry\\Services\\BeneficiaryLookupService',
            'fuzzyduplicatefinder' => 'App\\Domain\\Registry\\Services\\FuzzyDuplicateFinder',
            'controller' => 'App\\Http\\Controllers\\Controller',
            'beneficiarylookuprequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryLookupRequest',
            'beneficiarymatchsearchrequest' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
            'updatebeneficiaryrequest' => 'App\\Http\\Requests\\Registry\\UpdateBeneficiaryRequest',
            'beneficiaryresource' => 'App\\Http\\Resources\\BeneficiaryResource',
            'beneficiaryrevealresource' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
            'matchcandidateresource' => 'App\\Http\\Resources\\MatchCandidateResource',
            'apiresponse' => 'App\\Support\\ApiResponse',
            'jsonresponse' => 'Illuminate\\Http\\JsonResponse',
            'request' => 'Illuminate\\Http\\Request',
          ),
           'className' => 'App\\Http\\Controllers\\Api\\V1\\Registry\\BeneficiaryController',
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
      '/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryController.php' => '1fc22dead786de1d7ae79e2423506f5e8b3ad69d64949cbff8259324dc693324',
    ),
  ),
));