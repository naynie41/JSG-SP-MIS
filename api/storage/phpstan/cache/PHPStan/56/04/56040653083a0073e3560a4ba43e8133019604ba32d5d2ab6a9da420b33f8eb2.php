<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'a38b860835d3813f5a0e8113f63c0b0f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
      'd6527aa261277dadf43f723c883de53f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '1a0da5b80242036cfe117bb09780fccf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'c22f5f60e774d047649b2595ea06ecb5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '4bd242ea58f1c630f7f5797df3b0841a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '30a7dc18520095fad166210d1372803f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '7fbb5f61ede0e49c82e1f9aeb8931b8c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '33b8c285d4577968919db0bab584b3b0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3415e7eaf296ff5cf639b34355799e77' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Foundation\\Bus',
         'uses' => 
        array (
          'closure' => 'Closure',
          'dispatcher' => 'Illuminate\\Contracts\\Bus\\Dispatcher',
          'fluent' => 'Illuminate\\Support\\Fluent',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3a4119694390e312790ee172907074aa' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'c11354af4579dd8b53295a5fe0a46849' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'd249f203ccc116b2314192ecfc40ecfc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'e787528236ad546f90cc8dc3b687ac9a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'bc108603c57db8cda8c2b130b2eecabd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'ad730aafacdc61913139c33434b9ce4a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'f23f37d46cd45046bf6ea63eb57815c7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Support',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'datetimeinterface' => 'DateTimeInterface',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Support\\InteractsWithTime',
          3 => 'Illuminate\\Queue\\InteractsWithQueue',
          4 => NULL,
        ),
      )),
      'dbc2cb53a6b851eef8e2ab2c01ccf8ba' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0c8718691e3c0005a81f27b89e1f395a' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '167c9e8c5f1983617469a772a92c70e8' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'e39aa39d8d80731e08ca43355bfd8b51' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ae626f4589884dc6cfb59c8a1f0ca93c' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b8a3aa231aae63dc7e89723a8c2d0449' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3432a032c6647d498478951592ae2c93' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '832047797b5ae4e801578705b2bfa741' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b5609e6cb610a75b094c0afb6479b787' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '52b7dbd4353f7bc2727eaf8ede074495' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '8c0017794f1df3a741833b2e5757ada6' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '7d982551ee51e7bc70906b5d096653f2' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '547e7e99cdabb55decf499b6f86e6864' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'baf1c4b020f7fc64788c2c60a9581636' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\InteractsWithQueue',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'e8dfc9ee454c5bd0276bf3c51a2187f4' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6a9e25ce0bd6f509955332fe4506e924' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '7734fe0140e57e26d016c3d6b8f0bb2a' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '0c785f84555d17f0bf6f92a23085bf5d' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '585ff9c3fc5a9a24eac9ae58a4a2ad49' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'c4632c42c53f498c45ce37c4512a8156' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '75bd8c0de6f764ab1c1e1c46b621769c' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '3c73724b47522cc3a9ec42860f293fb1' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'e9f4d6616b10e542dc043c54172e8383' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '86cc8b171940c0bd4002265ee47c1b74' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'c6248affb6a09ef7fd3ad79c3da74713' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'c49675e31d8315ac175dffd7e4abcd31' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'cf69b802e1931db0952730f409470765' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '109dda172eff2162c7b673b8c35fb1b5' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'd8ab73c17df99f700682e96419f5329c' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b438e2b9daf41cc2835612b479327aa0' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ba511ac44ce099c199e1dc67f3eef270' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'b6f6821ea2d875a70f8c3a44740a57b9' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '44ad86ceb358dce5015d6999260243ab' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'be2db58ca0545fe87963d0baa929ef94' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Bus\\Queueable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '4965bb6575cf46891c00b0d5f84791a6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'dad4ad60938c54b4ae3de700a4074c0a' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'dc764a3fd2a45e9831f1462528b0e1a5' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'f7bfbf52cf484ebc7253440078a089ce' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'd78972722053660040775bd3145be22b' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      '265e329905019bcabe41137e498fc398' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      '8ba74a8ae49eb2df1652ae0117c7f682' => 
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
          3 => 'Illuminate\\Queue\\SerializesModels',
          4 => NULL,
        ),
      )),
      'a891870c50718da6bc0a9ef0a27053ec' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ff94166b6125647307271f73ceec730f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '9290c5e7d10598681f9fc23650af3d95' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Queue',
         'uses' => 
        array (
          'withoutrelations' => 'Illuminate\\Queue\\Attributes\\WithoutRelations',
          'reflectionclass' => 'ReflectionClass',
          'reflectionproperty' => 'ReflectionProperty',
        ),
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
          0 => '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php',
          1 => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
          2 => 'Illuminate\\Queue\\SerializesModels',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '718df6af9f7195d36f68296677b70581' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
            'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
            'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
           'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
      'b96f0293efcd9474dba69217c75c2ed8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
         'functionName' => 'handle',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
            'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
            'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
           'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
      'b5800ca3e1bb1bc7e2294cc53e84fb1a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Jobs',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
          'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
         'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
         'functionName' => 'failed',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Jobs',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'benefitdeliveryrowvalidator' => 'App\\Domain\\Benefit\\Imports\\BenefitDeliveryRowValidator',
            'benefitimportbatch' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
            'benefitimportrow' => 'App\\Domain\\Benefit\\Models\\BenefitImportRow',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'spreadsheetreader' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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
           'className' => 'App\\Domain\\Benefit\\Jobs\\ParseBenefitImport',
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
      '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php' => '85b1a3d2f8eff2ff0cb82a93373fbd16c06ea0e9c5fc23fbbb03e4fb7240ba38',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Bus/Dispatchable.php' => '551294291775e57fbd590f0ed288a91cca683d42fac08e60c87e39b73617d47b',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Queue/InteractsWithQueue.php' => '8d300c3adb967aa56c0827ba587e456e32e40fbb1c0d9f649f6bf7c0d876e937',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Support/InteractsWithTime.php' => 'ee4ef3a2e714fa539b223287a3a62b618b1d3a9e44f2e1f92981f2c3e2773ad5',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Bus/Queueable.php' => '7df8b51aab8bd3196229be1a8e398c2c2ec636ae1767ce499a64bfdbf5675c47',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Queue/SerializesModels.php' => '29ff50de875925956c56b217ef9b78643cef0e12e23885fdf37e0ed9b697e51d',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php' => 'd4cb97259a134d2089c54c969c2176704f0df2fa2483f149b61029c5c993f82d',
    ),
  ),
));