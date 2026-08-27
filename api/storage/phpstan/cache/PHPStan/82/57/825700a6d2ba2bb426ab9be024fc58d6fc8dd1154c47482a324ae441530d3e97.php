<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Segments/SegmentDefinition.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Segments\SegmentDefinition
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-ee10ec165833217f6033782ec04c205521f5542b033bd3a0b287fb06a43ca9dc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'filename' => '/var/www/html/app/Domain/Reporting/Segments/SegmentDefinition.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Segments',
    'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
    'shortName' => 'SegmentDefinition',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * A composed segment query (FR-RPT-03): a set of filters, plus an optional breakdown
 * dimension for the chart.
 *
 * Composition is AND across dimensions, OR within one — "female AND aged 20-25 AND in
 * Dutse", where a multi-select of LGAs means any of them. That is the shape people
 * actually reason in, and it is also the only shape that cannot widen a scope: every
 * clause added can only remove rows.
 *
 * The definition is persisted with the export run, so an auditor can reconstruct
 * exactly which population was pulled, by whom, under which scope.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 150,
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
      'filters' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'name' => 'filters',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 49,
            'startFilePos' => 1006,
            'endTokenPos' => 50,
            'endFilePos' => 1007,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 9,
        'endColumn' => 34,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'breakdown' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'name' => 'breakdown',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 62,
            'startFilePos' => 1046,
            'endTokenPos' => 62,
            'endFilePos' => 1049,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 9,
        'endColumn' => 40,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'filters' => 
          array (
            'name' => 'filters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 28,
                'endLine' => 28,
                'startTokenPos' => 49,
                'startFilePos' => 1006,
                'endTokenPos' => 50,
                'endFilePos' => 1007,
              ),
            ),
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'breakdown' => 
          array (
            'name' => 'breakdown',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 29,
                'endLine' => 29,
                'startTokenPos' => 62,
                'startFilePos' => 1046,
                'endTokenPos' => 62,
                'endFilePos' => 1049,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 9,
            'endColumn' => 40,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, array{op: string, values: list<string>}>  $filters
 * @param  string|null  $breakdown  dimension key for the chart, null for none
 */',
        'startLine' => 27,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'aliasName' => NULL,
      ),
      'fromArray' => 
      array (
        'name' => 'fromArray',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'registry' => 
          array (
            'name' => 'registry',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 54,
            'endColumn' => 87,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse and validate a client payload against the dimension catalogue.
 *
 * Anything not in the catalogue is REFUSED rather than ignored. A silently dropped
 * filter is the dangerous failure here: the caller believes they narrowed the
 * population and exports a wider one, which on an aggregate tier is exactly the
 * mistake the cell-size guard exists to prevent.
 *
 * @param  array<string, mixed>  $payload
 */',
        'startLine' => 42,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'aliasName' => NULL,
      ),
      'operatorFor' => 
      array (
        'name' => 'operatorFor',
        'parameters' => 
        array (
          'dimension' => 
          array (
            'name' => 'dimension',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimension',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 41,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'requested' => 
          array (
            'name' => 'requested',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 70,
            'endColumn' => 86,
            'parameterIndex' => 1,
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
        'docComment' => '/** Range kinds take a two-ended range; everything else is a multi-select. */',
        'startLine' => 89,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'aliasName' => NULL,
      ),
      'assertAllowedValues' => 
      array (
        'name' => 'assertAllowedValues',
        'parameters' => 
        array (
          'dimension' => 
          array (
            'name' => 'dimension',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimension',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 49,
            'endColumn' => 75,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 78,
            'endColumn' => 90,
            'parameterIndex' => 1,
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
        'docComment' => '/**
 * An enum dimension accepts only its declared values. Without this a caller could
 * pass an arbitrary string and get a silently empty segment, which reads as "nobody
 * matches" rather than "you asked for something that does not exist".
 *
 * @param  list<string>  $values
 */',
        'startLine' => 111,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array<string, mixed> */',
        'startLine' => 126,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'aliasName' => NULL,
      ),
      'label' => 
      array (
        'name' => 'label',
        'parameters' => 
        array (
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
        'docComment' => '/** A short human summary for the run label and the audit trail. */',
        'startLine' => 135,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDefinition',
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