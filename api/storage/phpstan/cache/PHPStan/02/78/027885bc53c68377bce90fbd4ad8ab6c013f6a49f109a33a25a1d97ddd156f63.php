<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Jobs\ParseImportBatch.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'bf42e714f5642251a66db42108afdd6b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      'ecf17fb1094bce8bcb643ff4c36a2a9c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '92f308bfd8c3b39502f3e3462d998760' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatch',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '006af4e07c84ea1523a24674b2d7bf61' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatchIf',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '61604e4e8c59ba35a09274ca6fbda4bb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatchUnless',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '38a856bc9e8ec097977b2651f34b2389' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatchSync',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '027a2a58feede272468204f196d7c634' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatchAfterResponse',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'd677bfd01e8f2677cb209ed414eadb75' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'withChain',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '046ca668613b702d63216cd688ca06fc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'newPendingDispatch',
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
         'typeAliasClassName' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'bde5821e60fe902f7b9552b4e805089f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6dbce1ba23c2fe866fec243ec8b07c53' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'f346ae52fe4ad1830ca987f56070a0c1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'secondsUntil',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      '436dede09175ea6d92d0147ec9a9dd65' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'availableAt',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      '6ace150ddbee3ed6c358da386652990d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'parseDateInterval',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      '20b42e45f69ba9950b6dfe21e1cda94c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'currentTime',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      '193a74f955dc1002a9e86dbfab73ffc2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'runTimeForHumans',
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
         'typeAliasClassName' => 'Illuminate\\Support\\InteractsWithTime',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'dd4d66066c3a52d70c217c1726d30f63' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'attempts',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'bfc2cc3cebffbf2aba5a3bfe029f20da' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'delete',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '9fc313d7095866e5465b0f26fec16dc1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'fail',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3fdcda63ff6c526e0923daa40afcdc8b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'release',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ab41c4776f0e6ee849d786fff4431f97' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'withFakeQueueInteractions',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '2da92be502b4c569313bdd7639433bca' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertDeleted',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '00795bb1c4e25cf84468286a7db341ec' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertNotDeleted',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b034d4a96151a3d55d9094dabaa5dbd1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertFailed',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '4a6dc900815c7031d396109e7e3a10b6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertFailedWith',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '7bcff37370af9f1e319d2fbe9295f182' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertNotFailed',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '8ea259d3b3bdde2783f582348675bd5f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertReleased',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ecad53d0468f59fad6bfe60b207ea3d6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertNotReleased',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b31b0ba610cd81daf64b029781450c11' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'ensureQueueInteractionsHaveBeenFaked',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '09211a804798c6b9798a679422aff81e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'datetimeinterface' => 'DateTimeInterface',
          'jobcontract' => 'Illuminate\\Contracts\\Queue\\Job',
          'fakejob' => 'Illuminate\\Queue\\Jobs\\FakeJob',
          'interactswithtime' => 'Illuminate\\Support\\InteractsWithTime',
          'invalidargumentexception' => 'InvalidArgumentException',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'setJob',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'e8b55e188893368235be637d1f405fbf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '5cdc8556f4735937dbb3b3ef4d6c7807' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'onConnection',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '87512df75b85d1cebededb120627c212' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'onQueue',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b351d48000c4f1f85779f631613dce7e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'onGroup',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '9b7da9093d58c68d5ca1657328fd51dd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'withDeduplicator',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'a355a463ee4e11eca117a62b229be1a1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'allOnConnection',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '15ecb21c8ab77e57ca123b5441a53b26' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'allOnQueue',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'd9be710d438477633056c65866737c07' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'delay',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'f87419dab483627beae73546f74790c1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'withoutDelay',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3ba33161571975a9b62b3b241a4e4ca4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'afterCommit',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'bbcc465f1ea328aaf6f831487ec89085' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'beforeCommit',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '8da1a0c9c775fad4469b9ca35ee4366e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'through',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6d471bcf094d9f371d9bf4092f03ae03' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'chain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3361d05d0aa4d02be416ff662b783c53' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'prependToChain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '154991313e91fdf67e5d866dfac9f0c6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'appendToChain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '35b99ba913a17c223b058aebf9342ea2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'serializeJob',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'cd5dd266a797a278c23f350aae01b899' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'dispatchNextJobInChain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '9c40d704ae5953435f47b332573c7d2e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'invokeChainCatchCallbacks',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b21537dd2cfc2789433e86a902cccaf7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertHasChain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0fd9bb58cddbcae807c5a361d3dd2fb1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'callqueuedclosure' => 'Illuminate\\Queue\\CallQueuedClosure',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'serializableclosure' => 'Laravel\\SerializableClosure\\SerializableClosure',
          'phpunit' => 'PHPUnit\\Framework\\Assert',
          'runtimeexception' => 'RuntimeException',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'assertDoesntHaveChain',
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
         'typeAliasClassName' => 'Illuminate\\Bus\\Queueable',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '5f9af3d8ee2f389c441bb8591f140a60' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesModels',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'd96d298a1e06f00e83da333cb54a29e0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      '1222fd99a20398ab912932b08dd6c54e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'getSerializedPropertyValue',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'bb1cd71102ef7232b5982b9a253fe43b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'getRestoredPropertyValue',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'ce212a7d08aa105f569c1de8551fd826' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'restoreCollection',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'a7f46a4a29f3abd0ff81a5924c0f84c4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'restoreModel',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      '552522b4e92b45170363dedc716be56a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'modelidentifier' => 'Illuminate\\Contracts\\Database\\ModelIdentifier',
          'queueablecollection' => 'Illuminate\\Contracts\\Queue\\QueueableCollection',
          'queueableentity' => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'aspivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\AsPivot',
          'pivot' => 'Illuminate\\Database\\Eloquent\\Relations\\Pivot',
          'collection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'getQueryForModelRestoration',
         'templatePhpDocNodes' => 
        array (
          'TModel' => 
          array (
            0 => '@template',
            1 => 
            \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
               'name' => 'TModel',
               'bound' => 
              \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                 'name' => '\\Illuminate\\Database\\Eloquent\\Model',
                 'attributes' => 
                array (
                  'startLine' => 4,
                  'endLine' => 4,
                ),
              )),
               'default' => NULL,
               'lowerBound' => NULL,
               'description' => '',
               'attributes' => 
              array (
                'startLine' => 4,
                'endLine' => 4,
              ),
            )),
          ),
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      '743203d3dd24d046abf0f14eec7d3f2c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => '__serialize',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesModels',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '18124ce50025282c026769736dc7480e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => '__unserialize',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesModels',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'dc567fc1679b332eb59924ae6ec2d88a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'getPropertyValue',
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
         'typeAliasClassName' => 'Illuminate\\Queue\\SerializesModels',
         'traitData' => 
        array (
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php',
          1 => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'bfcd59c60ff87a9c5c5a0a6c86ad98ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      '0fd8124a11e9bedbf0520cc645dc1325' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'handle',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      'c70741472f2a84690259cf8e749fa7da' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'failed',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      '870acaa72a03a04b9bdf4bc543f11f7e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'tag',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      'c7fbe8807fb742f5310b594bf8fb82c5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'isTruthy',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      'd637edad36a84bbe4128371f036e4111' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
          'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
          'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
          'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
          'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
          'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
          'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
          'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
          'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
          'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
          'queueable' => 'Illuminate\\Bus\\Queueable',
          'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
          'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
          'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
          'db' => 'Illuminate\\Support\\Facades\\DB',
          'storage' => 'Illuminate\\Support\\Facades\\Storage',
          'str' => 'Illuminate\\Support\\Str',
          'throwable' => 'Throwable',
        ),
         'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
         'functionName' => 'autoResolve',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'exactmatchbehaviour' => 'App\\Domain\\Matching\\Enums\\ExactMatchBehaviour',
            'matchingconfig' => 'App\\Domain\\Matching\\Models\\MatchingConfig',
            'matchingconfigservice' => 'App\\Domain\\Matching\\Services\\MatchingConfigService',
            'importrowresolution' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'importduplicatessurfaced' => 'App\\Domain\\Registry\\Events\\ImportDuplicatesSurfaced',
            'sourceadapterregistry' => 'App\\Domain\\Registry\\Imports\\Adapters\\SourceAdapterRegistry',
            'columnmapper' => 'App\\Domain\\Registry\\Imports\\ColumnMapper',
            'importrowvalidator' => 'App\\Domain\\Registry\\Imports\\ImportRowValidator',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'importbatch' => 'App\\Domain\\Registry\\Models\\ImportBatch',
            'importrow' => 'App\\Domain\\Registry\\Models\\ImportRow',
            'batchduplicatescreener' => 'App\\Domain\\Registry\\Services\\BatchDuplicateScreener',
            'householdingestionservice' => 'App\\Domain\\Registry\\Services\\HouseholdIngestionService',
            'queueable' => 'Illuminate\\Bus\\Queueable',
            'shouldqueue' => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
            'dispatchable' => 'Illuminate\\Foundation\\Bus\\Dispatchable',
            'interactswithqueue' => 'Illuminate\\Queue\\InteractsWithQueue',
            'serializesmodels' => 'Illuminate\\Queue\\SerializesModels',
            'db' => 'Illuminate\\Support\\Facades\\DB',
            'storage' => 'Illuminate\\Support\\Facades\\Storage',
            'str' => 'Illuminate\\Support\\Str',
            'throwable' => 'Throwable',
          ),
           'className' => 'App\\Domain\\Registry\\Jobs\\ParseImportBatch',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Jobs\\ParseImportBatch.php' => '5b28b1ce0f4ffc081ee913bbc1151aa09f751d2413cb044eb44c527ba454fb14',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Foundation\\Bus\\Dispatchable.php' => '551294291775e57fbd590f0ed288a91cca683d42fac08e60c87e39b73617d47b',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Queue\\InteractsWithQueue.php' => '8d300c3adb967aa56c0827ba587e456e32e40fbb1c0d9f649f6bf7c0d876e937',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Support\\InteractsWithTime.php' => 'ee4ef3a2e714fa539b223287a3a62b618b1d3a9e44f2e1f92981f2c3e2773ad5',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Bus\\Queueable.php' => '7df8b51aab8bd3196229be1a8e398c2c2ec636ae1767ce499a64bfdbf5675c47',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php' => '29ff50de875925956c56b217ef9b78643cef0e12e23885fdf37e0ed9b697e51d',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php' => 'd4cb97259a134d2089c54c969c2176704f0df2fa2483f149b61029c5c993f82d',
    ),
  ),
));