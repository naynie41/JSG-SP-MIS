<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Export/Contracts/ReportExporter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Export\Contracts\ReportExporter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b1b8f5a295a838b678dee7c9ee275a449ff3e046b0431196cd2cea3d030b5214',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'filename' => '/var/www/html/app/Domain/Reporting/Export/Contracts/ReportExporter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Export\\Contracts',
    'name' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
    'shortName' => 'ReportExporter',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Renders a {@see ReportData} to the bytes of one file format (PRD FR-RPT-03). Pure
 * data → bytes; the caller stores/streams the result. Sensitive cells are already
 * masked by {@see ReportData::cell()}.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 20,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
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
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Export\\ReportFormat',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export\\Contracts',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Export\\ReportData',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 28,
            'endColumn' => 43,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export\\Contracts',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
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