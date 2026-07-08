<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Export/PdfExporter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Export\PdfExporter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c3caa78c1ec591b9a5490ea85ed376cc467ac8684e07f3cb99bc47b049058e63',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
        'filename' => '/var/www/html/app/Domain/Reporting/Export/PdfExporter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Export',
    'name' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
    'shortName' => 'PdfExporter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Branded PDF export (PRD FR-RPT-03) via Dompdf, rendering the design-system report
 * template (`resources/views/reports/pdf.blade.php`) — forest letterhead with a crest
 * slot, lime accent, and a readable table. DejaVu Sans is used so the ₦ symbol and
 * the masking glyph render.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 40,
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
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
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
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\PdfExporter',
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