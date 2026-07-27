<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Export\ExecutiveExportBuilder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Export\ExecutiveExportBuilder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-6676312622a2668775414d44ef25fcb32009cfb6f6407f1f0684c40e60b5472d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Export\\ExecutiveExportBuilder',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Export/ExecutiveExportBuilder.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Export',
    'name' => 'App\\Domain\\Reporting\\Export\\ExecutiveExportBuilder',
    'shortName' => 'ExecutiveExportBuilder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Turns the executive dashboard metric bundle into an AGGREGATE {@see ReportData} for
 * CSV/Excel/PDF export (PRD FR-RPT-03). Every row is a section/metric/value tuple built
 * from de-identified aggregates — there is NO beneficiary-level data and NO PII column,
 * so an executive export can never leak identities (SECURITY.md). The same builder feeds
 * all three formats, so they always agree.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 98,
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
      'build' => 
      array (
        'name' => 'build',
        'parameters' => 
        array (
          'm' => 
          array (
            'name' => 'm',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scopeLabel' => 
          array (
            'name' => 'scopeLabel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 37,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'subtitle' => 
          array (
            'name' => 'subtitle',
            'default' => 
            array (
              'code' => '\'Aggregate metrics · de-identified\'',
              'attributes' => 
              array (
                'startLine' => 21,
                'endLine' => 21,
                'startTokenPos' => 52,
                'startFilePos' => 725,
                'endTokenPos' => 52,
                'endFilePos' => 760,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 57,
            'endColumn' => 111,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Export\\ReportData',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $m  the dashboard metric bundle (scoped + filtered)
 */',
        'startLine' => 21,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Export',
        'declaringClassName' => 'App\\Domain\\Reporting\\Export\\ExecutiveExportBuilder',
        'implementingClassName' => 'App\\Domain\\Reporting\\Export\\ExecutiveExportBuilder',
        'currentClassName' => 'App\\Domain\\Reporting\\Export\\ExecutiveExportBuilder',
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