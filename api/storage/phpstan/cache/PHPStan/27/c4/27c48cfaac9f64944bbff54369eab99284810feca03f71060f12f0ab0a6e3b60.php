<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/Coordinate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PhpOffice\PhpSpreadsheet\Cell\Coordinate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0584794077285900bf378339c6d125990fdbf78fe4573255b4d33034e5591e5e-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'filename' => '/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Cell/Coordinate.php',
      ),
    ),
    'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
    'name' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
    'shortName' => 'Coordinate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Helper class to manipulate cell coordinates.
 *
 * Columns indexes and rows are always based on 1, **not** on 0. This match the behavior
 * that Excel users are used to, and also match the Excel functions `COLUMN()` and `ROW()`.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 795,
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
      'A1_COORDINATE_REGEX' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'name' => 'A1_COORDINATE_REGEX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^(?<col>\\$?[A-Z]{1,3})(?<row>\\$?\\d{1,7})$/i\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 45,
            'startFilePos' => 549,
            'endTokenPos' => 45,
            'endFilePos' => 594,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 86,
      ),
      'FULL_REFERENCE_REGEX' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'name' => 'FULL_REFERENCE_REGEX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^(?:(?<worksheet>[^!]*)!)?(?<localReference>(?<firstCoordinate>[$]?[A-Z]{1,3}[$]?\\d{1,7})(?:\\:(?<secondCoordinate>[$]?[A-Z]{1,3}[$]?\\d{1,7}))?)$/i\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 56,
            'startFilePos' => 637,
            'endTokenPos' => 56,
            'endFilePos' => 785,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 190,
      ),
      'DEFAULT_RANGE' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'name' => 'DEFAULT_RANGE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'A1:A1\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 67,
            'startFilePos' => 897,
            'endTokenPos' => 67,
            'endFilePos' => 903,
          ),
        ),
        'docComment' => '/**
 * Default range variable constant.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'LOOKUP_CACHE' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'name' => 'LOOKUP_CACHE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\' ABCDEFGHIJKLMNOPQRSTUVWXYZ\'',
          'attributes' => 
          array (
            'startLine' => 453,
            'endLine' => 453,
            'startTokenPos' => 2904,
            'startFilePos' => 16968,
            'endTokenPos' => 2904,
            'endFilePos' => 16996,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 453,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'coordinateFromString' => 
      array (
        'name' => 'coordinateFromString',
        'parameters' => 
        array (
          'cellAddress' => 
          array (
            'name' => 'cellAddress',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 49,
            'endColumn' => 67,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert string coordinate to [0 => int column index, 1 => int row index].
 *
 * @param string $cellAddress eg: \'A1\'
 *
 * @return array{0: string, 1: string} Array containing column and row (indexes 0 and 1)
 */',
        'startLine' => 35,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'indexesFromString' => 
      array (
        'name' => 'indexesFromString',
        'parameters' => 
        array (
          'coordinates' => 
          array (
            'name' => 'coordinates',
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 46,
            'endColumn' => 64,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert string coordinate to [0 => int column index, 1 => int row index, 2 => string column string].
 *
 * @param string $coordinates eg: \'A1\', \'$B$12\'
 *
 * @return array{0: int, 1: int, 2: string} Array containing column and row index, and column string
 */',
        'startLine' => 59,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'coordinateIsRange' => 
      array (
        'name' => 'coordinateIsRange',
        'parameters' => 
        array (
          'cellAddress' => 
          array (
            'name' => 'cellAddress',
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
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 46,
            'endColumn' => 64,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if a Cell Address represents a range of cells.
 *
 * @param string $cellAddress eg: \'A1\' or \'A1:A2\' or \'A1:A2,C1:C2\'
 *
 * @return bool Whether the coordinate represents a range of cells
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'absoluteReference' => 
      array (
        'name' => 'absoluteReference',
        'parameters' => 
        array (
          'cellAddress' => 
          array (
            'name' => 'cellAddress',
            'default' => NULL,
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
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 46,
            'endColumn' => 68,
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
        'docComment' => '/**
 * Make string row, column or cell coordinate absolute.
 *
 * @param int|string $cellAddress e.g. \'A\' or \'1\' or \'A1\'
 *                    Note that this value can be a row or column reference as well as a cell reference
 *
 * @return string Absolute coordinate        e.g. \'$A\' or \'$1\' or \'$A$1\'
 */',
        'startLine' => 91,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'absoluteCoordinate' => 
      array (
        'name' => 'absoluteCoordinate',
        'parameters' => 
        array (
          'cellAddress' => 
          array (
            'name' => 'cellAddress',
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
            'startLine' => 122,
            'endLine' => 122,
            'startColumn' => 47,
            'endColumn' => 65,
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
        'docComment' => '/**
 * Make string coordinate absolute.
 *
 * @param string $cellAddress e.g. \'A1\'
 *
 * @return string Absolute coordinate        e.g. \'$A$1\'
 */',
        'startLine' => 122,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'splitRange' => 
      array (
        'name' => 'splitRange',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 39,
            'endColumn' => 51,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Split range into coordinate strings, using comma for union
 * and ignoring intersection (space).
 *
 * @param string $range e.g. \'B4:D9\' or \'B4:D9,H2:O11\' or \'B4\'
 *
 * @return array<array<string>> Array containing one or more arrays containing one or two coordinate strings
 *                                e.g. [\'B4\',\'D9\'] or [[\'B4\',\'D9\'], [\'H2\',\'O11\']]
 *                                        or [\'B4\']
 */',
        'startLine' => 152,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'allRanges' => 
      array (
        'name' => 'allRanges',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unionIsComma' => 
          array (
            'name' => 'unionIsComma',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 179,
                'endLine' => 179,
                'startTokenPos' => 817,
                'startFilePos' => 6542,
                'endTokenPos' => 817,
                'endFilePos' => 6545,
              ),
            ),
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
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 53,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Split range into coordinate strings, resolving unions and intersections.
 *
 * @param string $range e.g. \'B4:D9\' or \'B4:D9,H2:O11\' or \'B4\'
 * @param bool $unionIsComma true=comma is union, space is intersection
 *                           false=space is union, comma is intersection
 *
 * @return array<array<string>> Array containing one or more arrays containing one or two coordinate strings
 *                                e.g. [\'B4\',\'D9\'] or [[\'B4\',\'D9\'], [\'H2\',\'O11\']]
 *                                        or [\'B4\']
 */',
        'startLine' => 179,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'buildRange' => 
      array (
        'name' => 'buildRange',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 197,
            'endLine' => 197,
            'startColumn' => 39,
            'endColumn' => 50,
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
        'docComment' => '/**
 * Build range from coordinate strings.
 *
 * @param array<array<string>> $range Array containing one or more arrays containing one or two coordinate strings
 *
 * @return string String representation of $pRange
 */',
        'startLine' => 197,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'rangeBoundaries' => 
      array (
        'name' => 'rangeBoundaries',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 44,
            'endColumn' => 56,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculate range boundaries.
 *
 * @param string $range Cell range, Single Cell, Row/Column Range (e.g. A1:A1, B2, B:C, 2:3)
 *
 * @return array{array{int, int}, array{int, int}} Range coordinates [Start Cell, End Cell]
 *                    where Start Cell and End Cell are arrays (Column Number, Row Number)
 */',
        'startLine' => 225,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'rangeDimension' => 
      array (
        'name' => 'rangeDimension',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 272,
            'endLine' => 272,
            'startColumn' => 43,
            'endColumn' => 55,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculate range dimension.
 *
 * @param string $range Cell range, Single Cell, Row/Column Range (e.g. A1:A1, B2, B:C, 2:3)
 *
 * @return array{int, int} Range dimension (width, height)
 */',
        'startLine' => 272,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'getRangeBoundaries' => 
      array (
        'name' => 'getRangeBoundaries',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 47,
            'endColumn' => 59,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculate range boundaries.
 *
 * @param string $range Cell range, Single Cell, Row/Column Range (e.g. A1:A1, B2, B:C, 2:3)
 *
 * @return array{array{string, int}, array{string, int}} Range coordinates [Start Cell, End Cell]
 *                    where Start Cell and End Cell are arrays [Column ID, Row Number]
 */',
        'startLine' => 288,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'validateReferenceAndGetData' => 
      array (
        'name' => 'validateReferenceAndGetData',
        'parameters' => 
        array (
          'reference' => 
          array (
            'name' => 'reference',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 306,
            'endLine' => 306,
            'startColumn' => 57,
            'endColumn' => 66,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if cell or range reference is valid and return an array with type of reference (cell or range), worksheet (if it was given)
 * and the coordinate or the first coordinate and second coordinate if it is a range.
 *
 * @param string $reference Coordinate or Range (e.g. A1:A1, B2, B:C, 2:3)
 *
 * @return array{type: string, firstCoordinate?: string, secondCoordinate?: string, coordinate?: string, worksheet?: string, localReference?: string} reference data
 */',
        'startLine' => 306,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'coordinateIsInsideRange' => 
      array (
        'name' => 'coordinateIsInsideRange',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
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
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'coordinate' => 
          array (
            'name' => 'coordinate',
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
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 67,
            'endColumn' => 84,
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
 * Check if coordinate is inside a range.
 *
 * @param string $range Cell range, Single Cell, Row/Column Range (e.g. A1:A1, B2, B:C, 2:3)
 * @param string $coordinate Cell coordinate (e.g. A1)
 *
 * @return bool true if coordinate is inside range
 */',
        'startLine' => 342,
        'endLine' => 387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'columnIndexFromString' => 
      array (
        'name' => 'columnIndexFromString',
        'parameters' => 
        array (
          'columnAddress' => 
          array (
            'name' => 'columnAddress',
            'default' => NULL,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 50,
            'endColumn' => 71,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Column index from string.
 *
 * @param ?string $columnAddress eg \'A\'
 *
 * @return int Column index (A = 1)
 */',
        'startLine' => 396,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'stringFromColumnIndex' => 
      array (
        'name' => 'stringFromColumnIndex',
        'parameters' => 
        array (
          'columnIndex' => 
          array (
            'name' => 'columnIndex',
            'default' => NULL,
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
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 50,
            'endColumn' => 72,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tolerateZero' => 
          array (
            'name' => 'tolerateZero',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 2930,
                'startFilePos' => 17218,
                'endTokenPos' => 2930,
                'endFilePos' => 17222,
              ),
            ),
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
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 75,
            'endColumn' => 100,
            'parameterIndex' => 1,
            'isOptional' => true,
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
        'docComment' => '/**
 * String from column index.
 *
 * @param int|numeric-string $columnIndex Column index (A = 1)
 */',
        'startLine' => 460,
        'endLine' => 485,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'extractAllCellReferencesInRange' => 
      array (
        'name' => 'extractAllCellReferencesInRange',
        'parameters' => 
        array (
          'cellRange' => 
          array (
            'name' => 'cellRange',
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
            'startLine' => 494,
            'endLine' => 494,
            'startColumn' => 60,
            'endColumn' => 76,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Extract all cell references in range, which may be comprised of multiple cell ranges.
 *
 * @param string $cellRange Range: e.g. \'A1\' or \'A1:C10\' or \'A1:E10,A20:E25\' or \'A1:E5 C3:G7\' or \'A1:C1,A3:C3 B1:C3\'
 *
 * @return string[] Array containing single cell references
 */',
        'startLine' => 494,
        'endLine' => 534,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'processRangeSetOperators' => 
      array (
        'name' => 'processRangeSetOperators',
        'parameters' => 
        array (
          'operators' => 
          array (
            'name' => 'operators',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cells' => 
          array (
            'name' => 'cells',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 72,
            'endColumn' => 83,
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
 * @param mixed[] $operators
 * @param string[][] $cells
 *
 * @return mixed[]
 */',
        'startLine' => 542,
        'endLine' => 560,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'sortCellReferenceArray' => 
      array (
        'name' => 'sortCellReferenceArray',
        'parameters' => 
        array (
          'cellList' => 
          array (
            'name' => 'cellList',
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
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 52,
            'endColumn' => 66,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string[] $cellList
 *
 * @return string[]
 */',
        'startLine' => 567,
        'endLine' => 582,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'resolveUnionAndIntersection' => 
      array (
        'name' => 'resolveUnionAndIntersection',
        'parameters' => 
        array (
          'cellBlock' => 
          array (
            'name' => 'cellBlock',
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
            'startLine' => 593,
            'endLine' => 593,
            'startColumn' => 56,
            'endColumn' => 72,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'implodeCharacter' => 
          array (
            'name' => 'implodeCharacter',
            'default' => 
            array (
              'code' => '\',\'',
              'attributes' => 
              array (
                'startLine' => 593,
                'endLine' => 593,
                'startTokenPos' => 3804,
                'startFilePos' => 21824,
                'endTokenPos' => 3804,
                'endFilePos' => 21826,
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
            'startLine' => 593,
            'endLine' => 593,
            'startColumn' => 75,
            'endColumn' => 104,
            'parameterIndex' => 1,
            'isOptional' => true,
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
        'docComment' => '/**
 * Get all cell references applying union and intersection.
 *
 * @param string $cellBlock A cell range e.g. A1:B5,D1:E5 B2:C4
 *
 * @return string A string without intersection operator.
 *   If there was no intersection to begin with, return original argument.
 *   Otherwise, return cells and/or cell ranges in that range separated by comma.
 */',
        'startLine' => 593,
        'endLine' => 620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'getReferencesForCellBlock' => 
      array (
        'name' => 'getReferencesForCellBlock',
        'parameters' => 
        array (
          'cellBlock' => 
          array (
            'name' => 'cellBlock',
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
            'startLine' => 629,
            'endLine' => 629,
            'startColumn' => 55,
            'endColumn' => 71,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all cell references for an individual cell block.
 *
 * @param string $cellBlock A cell range e.g. A4:B5
 *
 * @return string[] All individual cells in that range
 */',
        'startLine' => 629,
        'endLine' => 676,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'mergeRangesInCollection' => 
      array (
        'name' => 'mergeRangesInCollection',
        'parameters' => 
        array (
          'coordinateCollection' => 
          array (
            'name' => 'coordinateCollection',
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
            'startLine' => 695,
            'endLine' => 695,
            'startColumn' => 52,
            'endColumn' => 78,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert an associative array of single cell coordinates to values to an associative array
 * of cell ranges to values.  Only adjacent cell coordinates with the same
 * value will be merged.  If the value is an object, it must implement the method getHashCode().
 *
 * For example, this function converts:
 *
 *    [ \'A1\' => \'x\', \'A2\' => \'x\', \'A3\' => \'x\', \'A4\' => \'y\' ]
 *
 * to:
 *
 *    [ \'A1:A3\' => \'x\', \'A4\' => \'y\' ]
 *
 * @param array<string, mixed> $coordinateCollection associative array mapping coordinates to values
 *
 * @return array<string, mixed> associative array mapping coordinate ranges to values
 */',
        'startLine' => 695,
        'endLine' => 762,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'getCellBlocksFromRangeString' => 
      array (
        'name' => 'getCellBlocksFromRangeString',
        'parameters' => 
        array (
          'rangeString' => 
          array (
            'name' => 'rangeString',
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
            'startLine' => 770,
            'endLine' => 770,
            'startColumn' => 58,
            'endColumn' => 76,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the individual cell blocks from a range string, removing any $ characters.
 *      then splitting by operators and returning an array with ranges and operators.
 *
 * @return mixed[][]
 */',
        'startLine' => 770,
        'endLine' => 781,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'aliasName' => NULL,
      ),
      'validateRange' => 
      array (
        'name' => 'validateRange',
        'parameters' => 
        array (
          'cellBlock' => 
          array (
            'name' => 'cellBlock',
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
            'startLine' => 789,
            'endLine' => 789,
            'startColumn' => 43,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'startColumnIndex' => 
          array (
            'name' => 'startColumnIndex',
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
            'startLine' => 789,
            'endLine' => 789,
            'startColumn' => 62,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'endColumnIndex' => 
          array (
            'name' => 'endColumnIndex',
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
            'startLine' => 789,
            'endLine' => 789,
            'startColumn' => 85,
            'endColumn' => 103,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'currentRow' => 
          array (
            'name' => 'currentRow',
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
            'startLine' => 789,
            'endLine' => 789,
            'startColumn' => 106,
            'endColumn' => 120,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'endRow' => 
          array (
            'name' => 'endRow',
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
            'startLine' => 789,
            'endLine' => 789,
            'startColumn' => 123,
            'endColumn' => 133,
            'parameterIndex' => 4,
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
 * Check that the given range is valid, i.e. that the start column and row are not greater than the end column and
 * row.
 *
 * @param string $cellBlock The original range, for displaying a meaningful error message
 */',
        'startLine' => 789,
        'endLine' => 794,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Cell',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate',
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