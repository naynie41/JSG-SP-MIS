<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Notification\Listeners\NotificationSubscriber.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'c5c08c5a48641e9d79eb30a2bd1ad73d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'd20dacaa7b56fdb4d839e89e9629f3bb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'ce94a0beeb9238b68595a03f7c0c254b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleServiceRequestRaised',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '7f88543931c94289c63858ba0248cfcf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleServiceRequestAccepted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '864d0f5dcc0c317a3a71db8609321ac9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleBeneficiaryAccessRevoked',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '3319cae6a512ba8e9028725081747fec' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleServiceRequestDeclined',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '0f2621c4ea2a50620794b12d925714ff' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleOwnershipTransferRequested',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'e080f22987e09f98b7a5eaca9b4b58f3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleImportDuplicatesSurfaced',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '5b78b926c700299283fe15a56c0eaae5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleImportBatchCompleted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '0cf954fdc9cfaa3a0008699d13acbf73' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'uploader',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'ed2e3d403ae2ac1529a1de3f76003e79' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'approversIn',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '68ed7f9b43854398dd51947679290e6c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'requester',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'ee68182047827a7da6a1012ce27a6b97' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleReferralStatusChanged',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'c36c26c9bf5c8a7e1c03b1aec12676c3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleReferralSlaBreached',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'b2cafdcd3f2904b1a25a7319d4806447' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'bothParties',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'c1e0696ceba776d300342ca6693ac2d9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'escalationTier',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'e17e2bd384d81e02a50108b8b7f5c4bf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleGrievanceAssigned',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'b6305103ee98421eac428a62436691cc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleGrievanceResolved',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '1ab129c9afe527df14d98893942b7010' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleGrievanceSlaBreached',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '7b1c0c65d4bd918588b515ecc549e72e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'grievanceEscalationTier',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '4112bc1e7c91bcac616f06718125b4b3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleReportReady',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '4c26cfb70abe3db183c44f663cf210fc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'handleBeneficiaryGraduated',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      '564fb5051f74f86f4a3417ebc6f6c624' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Notification\\Listeners',
         'uses' => 
        array (
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
          'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
          'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
          'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
          'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
          'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
          'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
          'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
          'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
          'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
          'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
          'dispatcher' => 'Illuminate\\Events\\Dispatcher',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
         'functionName' => 'subscribe',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Notification\\Listeners',
           'uses' => 
          array (
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'beneficiarygraduated' => 'App\\Domain\\Graduation\\Events\\BeneficiaryGraduated',
            'grievanceassigned' => 'App\\Domain\\Grievance\\Events\\GrievanceAssigned',
            'grievanceresolved' => 'App\\Domain\\Grievance\\Events\\GrievanceResolved',
            'grievanceslabreached' => 'App\\Domain\\Grievance\\Events\\GrievanceSlaBreached',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'notifier' => 'App\\Domain\\Notification\\Services\\Notifier',
            'notificationmessage' => 'App\\Domain\\Notification\\Support\\NotificationMessage',
            'referralslabreached' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
            'referralstatuschanged' => 'App\\Domain\\Referral\\Events\\ReferralStatusChanged',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiaryaccessrevoked' => 'App\\Domain\\Registry\\Events\\BeneficiaryAccessRevoked',
            'importbatchcompleted' => 'App\\Domain\\Registry\\Events\\ImportBatchCompleted',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'ownershiptransferrequested' => 'App\\Domain\\Registry\\Events\\OwnershipTransferRequested',
            'servicerequestaccepted' => 'App\\Domain\\Registry\\Events\\ServiceRequestAccepted',
            'servicerequestdeclined' => 'App\\Domain\\Registry\\Events\\ServiceRequestDeclined',
            'servicerequestraised' => 'App\\Domain\\Registry\\Events\\ServiceRequestRaised',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'reportready' => 'App\\Domain\\Reporting\\Events\\ReportReady',
            'dispatcher' => 'Illuminate\\Events\\Dispatcher',
            'collection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Notification\\Listeners\\NotificationSubscriber',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Notification\\Listeners\\NotificationSubscriber.php' => 'bb6f7082e3c595075eb370e5fa74895a20bea73e4b6b9734ccec2e67578f1cc2',
    ),
  ),
));