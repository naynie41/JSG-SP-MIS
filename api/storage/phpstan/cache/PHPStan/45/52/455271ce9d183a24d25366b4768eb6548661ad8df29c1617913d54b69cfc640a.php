<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Logging\CorrelationIdProcessor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Logging\CorrelationIdProcessor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a4da65ad898dc596306101bccec1c6689fcd09ab50588b480523ffda91bb8b96',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Logging\\CorrelationIdProcessor',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Logging/CorrelationIdProcessor.php',
      ),
    ),
    'namespace' => 'App\\Logging',
    'name' => 'App\\Logging\\CorrelationIdProcessor',
    'shortName' => 'CorrelationIdProcessor',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Enriches every log record with the request correlation id (set by
 * {@see AssignCorrelationId}) and the acting user/MDA, so
 * structured logs can be traced end-to-end across nodes and correlated with the
 * audit trail (NFR-AVAIL-01, SECURITY.md §8). Never logs PII — only opaque ids.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 39,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      '__invoke' => 
      array (
        'name' => '__invoke',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Monolog\\LogRecord',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 30,
            'endColumn' => 46,
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
            'name' => 'Monolog\\LogRecord',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Logging',
        'declaringClassName' => 'App\\Logging\\CorrelationIdProcessor',
        'implementingClassName' => 'App\\Logging\\CorrelationIdProcessor',
        'currentClassName' => 'App\\Logging\\CorrelationIdProcessor',
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