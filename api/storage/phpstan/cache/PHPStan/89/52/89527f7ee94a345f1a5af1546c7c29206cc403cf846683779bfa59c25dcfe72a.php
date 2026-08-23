<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Segments\CellSizeGuard.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Segments\CellSizeGuard
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a72c28c16c2ce9fd6e8e54e65526281017bc12474f8c7031de57dc714af24b88',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Segments/CellSizeGuard.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Segments',
    'name' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
    'shortName' => 'CellSizeGuard',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Small-cell suppression for aggregate output (NFR-PRV-01, NDPA/NDPR).
 *
 * A count is not automatically anonymous. "Female, aged 20–25, Dutse ward 3: 1" names
 * a person to anyone who knows the neighbourhood, and a filter builder makes that
 * trivially reachable — narrow until the number is small, and the aggregate has become
 * a disclosure. The standard defence in official statistics is a minimum cell size:
 * below N, publish nothing for that group.
 *
 * The threshold is CONFIGURABLE (`reporting.min_cell_size`) because it is a
 * stakeholder decision, not an engineering one — different data-protection postures
 * pick different N — and CLAUDE.md §8 forbids hard-coding those.
 *
 * Suppressed groups are not silently dropped. They are kept, with the count replaced by
 * a marker and rolled into a "suppressed" total, because a vanishing row is itself a
 * signal: a reader comparing two runs would learn that a group exists and is small.
 * Saying "withheld" says less than an absence does.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 91,
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
      'SUPPRESSED' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'name' => 'SUPPRESSED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'—\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 31,
            'startFilePos' => 1153,
            'endTokenPos' => 31,
            'endFilePos' => 1157,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'DEFAULT_MINIMUM' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'name' => 'DEFAULT_MINIMUM',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '5',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 42,
            'startFilePos' => 1196,
            'endTokenPos' => 42,
            'endFilePos' => 1196,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'minimum' => 
      array (
        'name' => 'minimum',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'aliasName' => NULL,
      ),
      'apply' => 
      array (
        'name' => 'apply',
        'parameters' => 
        array (
          'groups' => 
          array (
            'name' => 'groups',
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
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'enabled' => 
          array (
            'name' => 'enabled',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 42,
            'endColumn' => 54,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply suppression to a breakdown.
 *
 * @param  list<array{key: string, label: string, count: int}>  $groups
 * @return array{
 *     groups: list<array{key: string, label: string, count: int|null, suppressed: bool}>,
 *     suppressed_groups: int,
 *     suppressed_total: int,
 *     minimum: int,
 * }
 */',
        'startLine' => 47,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'aliasName' => NULL,
      ),
      'totalIsSuppressed' => 
      array (
        'name' => 'totalIsSuppressed',
        'parameters' => 
        array (
          'total' => 
          array (
            'name' => 'total',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 39,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'enabled' => 
          array (
            'name' => 'enabled',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 51,
            'endColumn' => 63,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Whether a whole-segment total may be shown.
 *
 * The grand total gets the same treatment as a group. Without this, a caller could
 * narrow the filters until one person matched and read the answer straight off the
 * total — every suppressed breakdown underneath it notwithstanding.
 */',
        'startLine' => 85,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\CellSizeGuard',
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