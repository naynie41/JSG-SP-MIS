<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Export/ExcelExporter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Export\ExcelExporter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ac43c4cef3a1a42090281b745f20858072d110a72fb59a2d74db02e5557889a3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
        'filename' => '/var/www/html/app/Domain/Reporting/Export/ExcelExporter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Export',
    'name' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
    'shortName' => 'ExcelExporter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Excel (.xlsx) export (PRD FR-RPT-03) via PhpSpreadsheet — the shared data export in
 * spreadsheet form. A title/scope/generated header block, then a bold column header
 * row and the masked data rows.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 75,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Reporting\\Export\\Contracts\\ReportExporter',
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
        'startLine' => 20,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
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
            'startLine' => 25,
            'endLine' => 25,
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
        'startLine' => 25,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\ExcelExporter',
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