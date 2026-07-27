<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Privacy\Jobs\EnforceDataRetention.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Privacy\Jobs\EnforceDataRetention
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-6b4300ef156ceb24b7f3fd1dd3b203a35faa740940c393284f520c0f8bb8658c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Privacy\\Jobs\\EnforceDataRetention',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Privacy/Jobs/EnforceDataRetention.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Privacy\\Jobs',
    'name' => 'App\\Domain\\Privacy\\Jobs\\EnforceDataRetention',
    'shortName' => 'EnforceDataRetention',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled enforcement of the data-retention policies (NFR-PRV-01). Runs on the
 * shared queue; a no-op unless retention is enabled and policies are configured.
 * Unique so overlapping ticks never double-process a cohort. The run is audited by
 * the service.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldBeUnique',
      1 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Bus\\Queueable',
      3 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'retention' => 
          array (
            'name' => 'retention',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Privacy\\Services\\RetentionService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 28,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Jobs',
        'declaringClassName' => 'App\\Domain\\Privacy\\Jobs\\EnforceDataRetention',
        'implementingClassName' => 'App\\Domain\\Privacy\\Jobs\\EnforceDataRetention',
        'currentClassName' => 'App\\Domain\\Privacy\\Jobs\\EnforceDataRetention',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));