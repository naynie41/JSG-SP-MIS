<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Reporting/Services/DashboardMetricsService.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '18e37bfaf78463af4a6d3c7b7677dcbd' => 
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
      '1c5a137c11f19fd9f855c3694c24ec5a' => 
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
      '1a7ecd42baf4a062689c5ac05f2fe55b' => 
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
      '0dc7a60693ed392f46a2a9410adfe9fb' => 
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
      'ae891488e72f129d81556f7081aa8833' => 
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
      '2b8d409a83607d5086122a5c8f5a6b8e' => 
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
      'da05b169fb0d957d373005e7fd0f9b0e' => 
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
      '5d16a2f3ff761ef056d008256577622a' => 
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
      '8d3286a46c7a9e6e5d8dc2cc4d25f32f' => 
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
      '015b490187d095a5e60f71795baf395f' => 
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
      'c7750eeef50da75ed547e4fe3b034d2c' => 
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
      'd0017eb1c444515b2c063b8b212c9c0a' => 
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
      'ebfa77203b5312551d47dc27e4eecb63' => 
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
      'c0e1d0a35952594aefa35bc32af3205d' => 
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
      '7a05a10452995b27e5e61716b8bb11d7' => 
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
      '06b8ecd4413bf404e6fa24af146c594e' => 
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
      'efb14c30576e766fd19a703b73d0604d' => 
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
      'a41bff1e87228674b5120943e7cd4c2f' => 
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
      '638d602b9b27f63b4ed072f55ee3f47c' => 
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
      'b1cf87e4f1558e5417915e14736420f3' => 
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
      '32b2fc2f0a5bb88431b457d202b88c46' => 
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
      '12b88eb32ada8578e7cac5f3baadec2e' => 
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
      '1453b319d16b1cf9a5f270ef4ec8bd0a' => 
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
      '551f4a355b91c96e3e121c52c7051d6f' => 
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
      'a721c064998de2272853e14ed3f57643' => 
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
      '9537a61b8ca45c34dd11cba786dbb3f6' => 
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
      'ec90a66fc87d9ed7a8beb7f9000bb525' => 
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
      'bb9fd38f92a66bdc8f4ce9a57f4d68e9' => 
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
      'e9ff77468a8b4bfcad1eff536c61b4b5' => 
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
      '9dbe326c1529bdcf11924161da00dba9' => 
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
      '0a75f5644ee20debb8e65f7edd578f87' => 
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
      'd35be942d84c89ef5cc167987bad2d7f' => 
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
      '192ab830a6260dd471c8cd44159d0684' => 
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
      '1ccbb65b3b244192adf1ae08f6082e19' => 
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
      '5c2b488717af7a8d0d555fa63280a712' => 
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
      'db348ffd66af271d80933b2b4c4a19fb' => 
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
      '63822ab12b720c7c336ea12f341d38a3' => 
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
      'ee3e27122bad437ca40655b9042b1fcc' => 
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
      'b05dc48f8713b18bdc8ebaa7a269facf' => 
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
      '2352cce56b7c25c112670a1ee25b13f7' => 
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
      '211a12214f6e503811cafb27d061bb9d' => 
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
      'd06415ee71fc816ded8fe096673b53b2' => 
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
      '7005803612b1a48bbd04ea07a2ac6c13' => 
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
      'd7005cb69e960dd83bdbaec328ca9be1' => 
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
      '3ed30faa00f72a220faf57b11df5ed11' => 
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
      '/var/www/html/app/Domain/Reporting/Services/DashboardMetricsService.php' => 'b386e67cfdd226c396f19f1a674bd56a6ec7102f6dc28e63b9a4f105f94db98d',
    ),
  ),
));