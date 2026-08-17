<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\DashboardMetricsService.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'b76f0f27ba80362632933f39ce9aaa2f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '5d1e545148aec4d42fd00d32f7cfeda0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'db3787ec243ecf3be65fa4a717c38366' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'compute',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '45d2a9321f984ac69a0e243fb390001c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'filterOptions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'f485a67d5497bf32fd283449ef37c682' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'registry',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '2ca389a0e846f633f18017ac266f9a4e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'fa377ec41b6828c17f80a9454c7accc5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'duplicates',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '2a39ca305ad4604de40d746d2c3499fe' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'benefits',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'a247b1aea933e3448a363f98fbfeab5b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'referrals',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '9961831d2f82308afe9a70729be804eb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'grievances',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '779738163fd2c4482c916e39308c5c7b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'coverage',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '3f3b8118180ed8a1c3808594ab11c5a4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'population',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '1cfeec8d5da42c53b1aa0cb5979a5d65' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'demographics',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'f7b4c93d6ff9150ef7f826342f00f18b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'ageBands',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '5db6f93bfee873d5126217edcd209e45' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'householdSize',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '4f2e52cad18a79100a8c016bb4438d43' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmePerformance',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '8582fd8c8a7cbdea185a40bdbcf88f02' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'registryQuality',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '69239290dbb40520fa0a4ce3e60393af' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'matchesSurfaced',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '2ac804c1f2a4afa5bc19145b15e7ff46' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'coordination',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '4dd48213d9f8645f43b36ebdd7dfbc71' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'jointProgrammes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'dc8f868b93013fafff1e1806eb6f8573' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerFunding',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '89a67909931edb0fb6dc39c56d0f3f2c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerReach',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '6fe7f9bd521fbcd96ef692770313ac98' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerRegistry',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '8db10b174fcf39e3d84a7b9d2b748ef0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'cohortHouseholdSize',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'f85e38d6de12674af6e3f8bc5e16fc3f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerProgrammes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'd45d8441c650b1f165ac6b7d7e0a9df0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmeStatusLight',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'd1b3df80cfe588a67278b22af506367d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerOutputIndicators',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '174d343a87286eabd62067ab6965272b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'zeroFilledSeries',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '4f39bd3ccd8a6be5e506f3c2250af341' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerCoordination',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'd7a3eecc615e3f6dfa76f997d3305a52' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmeOverlap',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '05a96f289f70ddd5dc0b904b2ac553dc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'partnerContributions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'a8bb7a45f5038a2fd7df68a6a961d862' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'coverageBands',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '86b3e454b7c4640cec16fde56f684be4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'trends',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '404c032be7966b6b207db19bd1f85bf7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'deferredSlots',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'abbd5ad502db0efcfd3c3e3cf163c4fc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'monthLabels',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '1bfe13b5d5684630df019cfa93d72a0a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'monthCountSeries',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '0a97cf8431b0e0b62e032f3926be64b9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmeGrowthSeries',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '92a17a3b86d754d9004735adc257604f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'programmeIdsInScope',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '9c4db497274a97712e55fc065a866a04' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'beneficiaryBase',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '0ebbcfe85ed6b96c29588624ae9e4d71' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'applyBeneficiaryFilter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '8695704092de91574e1eccbd0a134eac' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'applyActivityFilter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '57cfed192c051807c9874d0e139bce35' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'applyBenefitFilter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '673811974d957505856b7a5cd23684a5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'applyCoordinationFilter',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      '6c2a654f8c30036efa1ad478723e4e2f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'householdBase',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'dd4b8b4447a50babf44fc9b116ba3356' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Reporting\\Services',
         'uses' => 
        array (
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
          'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
          'lga' => 'App\\Domain\\Reference\\Models\\Lga',
          'referral' => 'App\\Domain\\Referral\\Models\\Referral',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'household' => 'App\\Domain\\Registry\\Models\\Household',
          'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
          'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
          'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
          'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
          'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
          'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
          'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'collection' => 'Illuminate\\Support\\Collection',
          'db' => 'Illuminate\\Support\\Facades\\DB',
        ),
         'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
         'functionName' => 'countBy',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Reporting\\Services',
           'uses' => 
          array (
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'ledgeraggregator' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'grievance' => 'App\\Domain\\Grievance\\Models\\Grievance',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'programmefunder' => 'App\\Domain\\Programme\\Models\\ProgrammeFunder',
            'lga' => 'App\\Domain\\Reference\\Models\\Lga',
            'referral' => 'App\\Domain\\Referral\\Models\\Referral',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'household' => 'App\\Domain\\Registry\\Models\\Household',
            'householdmembership' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'servicerequest' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
            'dashboardfilter' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'dashboardscope' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'syncconnector' => 'App\\Domain\\Sync\\Models\\SyncConnector',
            'syncrun' => 'App\\Domain\\Sync\\Models\\SyncRun',
            'builder' => 'Illuminate\\Database\\Eloquent\\Builder',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'collection' => 'Illuminate\\Support\\Collection',
            'db' => 'Illuminate\\Support\\Facades\\DB',
          ),
           'className' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Reporting\\Services\\DashboardMetricsService.php' => 'd9a8874cc600eecd7a15ad962728a0eb32831d7a375ad078396163217e633213',
    ),
  ),
));