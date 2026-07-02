<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/IReader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PhpOffice\PhpSpreadsheet\Reader\IReader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cffc70e4a2f5a812546b994ae1d985ef41b17ecbc47658eb89033ab3438e7613-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'filename' => '/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/IReader.php',
      ),
    ),
    'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
    'name' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
    'shortName' => 'IReader',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 175,
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
      'LOAD_WITH_CHARTS' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'LOAD_WITH_CHARTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 28,
            'startFilePos' => 263,
            'endTokenPos' => 28,
            'endFilePos' => 263,
          ),
        ),
        'docComment' => '/**
 * Flag used to load the charts.
 *
 * This flag is supported only for some formats.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'READ_DATA_ONLY' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'READ_DATA_ONLY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 41,
            'startFilePos' => 389,
            'endTokenPos' => 41,
            'endFilePos' => 389,
          ),
        ),
        'docComment' => '/**
 * Flag used to read data only, not style or structure information.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'IGNORE_EMPTY_CELLS' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'IGNORE_EMPTY_CELLS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '4',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 54,
            'startFilePos' => 558,
            'endTokenPos' => 54,
            'endFilePos' => 558,
          ),
        ),
        'docComment' => '/**
 * Flag used to ignore empty cells when reading.
 *
 * The ignored cells will not be instantiated.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'IGNORE_ROWS_WITH_NO_CELLS' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'IGNORE_ROWS_WITH_NO_CELLS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '8',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 67,
            'startFilePos' => 790,
            'endTokenPos' => 67,
            'endFilePos' => 790,
          ),
        ),
        'docComment' => '/**
 * Flag used to ignore rows without cells.
 *
 * This flag is supported only for some formats.
 * This can heavily improve performance for some files.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'ALLOW_EXTERNAL_IMAGES' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'ALLOW_EXTERNAL_IMAGES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '16',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 80,
            'startFilePos' => 1011,
            'endTokenPos' => 80,
            'endFilePos' => 1012,
          ),
        ),
        'docComment' => '/**
 * Allow external images. Use with caution.
 * Improper specification of these within a spreadsheet
 * can subject the caller to security exploits.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'DONT_ALLOW_EXTERNAL_IMAGES' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'DONT_ALLOW_EXTERNAL_IMAGES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '32',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 91,
            'startFilePos' => 1061,
            'endTokenPos' => 91,
            'endFilePos' => 1062,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'CREATE_BLANK_SHEET_IF_NONE_READ' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'name' => 'CREATE_BLANK_SHEET_IF_NONE_READ',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '64',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 102,
            'startFilePos' => 1117,
            'endTokenPos' => 102,
            'endFilePos' => 1118,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'canRead' => 
      array (
        'name' => 'canRead',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 29,
            'endColumn' => 44,
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
 * Can the current IReader read the file?
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getReadDataOnly' => 
      array (
        'name' => 'getReadDataOnly',
        'parameters' => 
        array (
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
 * Read data only?
 *        If this is true, then the Reader will only read data values for cells, it will not read any formatting
 *           or structural information (like merges).
 *        If false (the default) it will read data and formatting.
 */',
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setReadDataOnly' => 
      array (
        'name' => 'setReadDataOnly',
        'parameters' => 
        array (
          'readDataOnly' => 
          array (
            'name' => 'readDataOnly',
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
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 37,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set read data only
 *        Set to true, to advise the Reader only to read data values for cells, and to ignore any formatting
 *            or structural information (like merges).
 *        Set to false (the default) to advise the Reader to read both data and formatting for cells.
 *
 * @return $this
 */',
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getReadEmptyCells' => 
      array (
        'name' => 'getReadEmptyCells',
        'parameters' => 
        array (
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
 * Read empty cells?
 *        If this is true (the default), then the Reader will read data values for all cells, irrespective of value.
 *        If false it will not read data for cells containing a null value or an empty string.
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setReadEmptyCells' => 
      array (
        'name' => 'setReadEmptyCells',
        'parameters' => 
        array (
          'readEmptyCells' => 
          array (
            'name' => 'readEmptyCells',
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
            'startColumn' => 39,
            'endColumn' => 58,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set read empty cells
 *        Set to true (the default) to advise the Reader read data values for all cells, irrespective of value.
 *        Set to false to advise the Reader to ignore cells containing a null value or an empty string.
 *
 * @return $this
 */',
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 66,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getIncludeCharts' => 
      array (
        'name' => 'getIncludeCharts',
        'parameters' => 
        array (
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
 * Read charts in workbook?
 *      If this is true, then the Reader will include any charts that exist in the workbook.
 *         Note that a ReadDataOnly value of false overrides, and charts won\'t be read regardless of the IncludeCharts value.
 *      If false (the default) it will ignore any charts defined in the workbook file.
 */',
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setIncludeCharts' => 
      array (
        'name' => 'setIncludeCharts',
        'parameters' => 
        array (
          'includeCharts' => 
          array (
            'name' => 'includeCharts',
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 38,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set read charts in workbook
 *     Set to true, to advise the Reader to include any charts that exist in the workbook.
 *         Note that a ReadDataOnly value of false overrides, and charts won\'t be read regardless of the IncludeCharts value.
 *     Set to false (the default) to discard charts.
 *
 * @return $this
 */',
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 64,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getLoadSheetsOnly' => 
      array (
        'name' => 'getLoadSheetsOnly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'array',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get which sheets to load
 * Returns either an array of worksheet names (the list of worksheets that should be loaded), or a null
 *        indicating that all worksheets in the workbook should be loaded.
 *
 * @return null|string[]
 */',
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setLoadSheetsOnly' => 
      array (
        'name' => 'setLoadSheetsOnly',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 39,
            'endColumn' => 62,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set which sheets to load.
 *
 * @param null|string|string[] $value This should be either an array of worksheet names to be loaded,
 *          or a string containing a single worksheet name. If NULL, then it tells the Reader to
 *          read all worksheets in the workbook
 *
 * @return $this
 */',
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 70,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setLoadAllSheets' => 
      array (
        'name' => 'setLoadAllSheets',
        'parameters' => 
        array (
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
 * Set all sheets to load
 *        Tells the Reader to load all worksheets from the workbook.
 *
 * @return $this
 */',
        'startLine' => 131,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getReadFilter' => 
      array (
        'name' => 'getReadFilter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReadFilter',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Read filter.
 */',
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setReadFilter' => 
      array (
        'name' => 'setReadFilter',
        'parameters' => 
        array (
          'readFilter' => 
          array (
            'name' => 'readFilter',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReadFilter',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 35,
            'endColumn' => 57,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set read filter.
 *
 * @return $this
 */',
        'startLine' => 143,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setAllowExternalImages' => 
      array (
        'name' => 'setAllowExternalImages',
        'parameters' => 
        array (
          'allowExternalImages' => 
          array (
            'name' => 'allowExternalImages',
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
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 44,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allow external images. Use with caution.
 * Improper specification of these within a spreadsheet
 * can subject the caller to security exploits.
 */',
        'startLine' => 150,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 76,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'getAllowExternalImages' => 
      array (
        'name' => 'getAllowExternalImages',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 152,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'setCreateBlankSheetIfNoneRead' => 
      array (
        'name' => 'setCreateBlankSheetIfNoneRead',
        'parameters' => 
        array (
          'createBlankSheetIfNoneRead' => 
          array (
            'name' => 'createBlankSheetIfNoneRead',
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
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 51,
            'endColumn' => 82,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a blank sheet if none are read,
 * possibly due to a typo when using LoadSheetsOnly.
 */',
        'startLine' => 158,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 90,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'aliasName' => NULL,
      ),
      'load' => 
      array (
        'name' => 'load',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
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
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 26,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'flags' => 
          array (
            'name' => 'flags',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 174,
                'endLine' => 174,
                'startTokenPos' => 370,
                'startFilePos' => 6302,
                'endTokenPos' => 370,
                'endFilePos' => 6302,
              ),
            ),
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
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 44,
            'endColumn' => 57,
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
            'name' => 'PhpOffice\\PhpSpreadsheet\\Spreadsheet',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Loads PhpSpreadsheet from file.
 *
 * @param string $filename The name of the file to load
 * @param int $flags Flags that can change the behaviour of the Writer:
 *            self::LOAD_WITH_CHARTS    Load any charts that are defined (if the Reader supports Charts)
 *            self::READ_DATA_ONLY      Read only data, not style or structure information, from the file
 *            self::IGNORE_EMPTY_CELLS  Don\'t read empty cells (cells that contain a null value,
 *                                      empty string, or a string containing only whitespace characters)
 *            self::IGNORE_ROWS_WITH_NO_CELLS    Don\'t load any rows that contain no cells.
 *            self::ALLOW_EXTERNAL_IMAGES    Attempt to fetch images stored outside the spreadsheet.
 *            self::DONT_ALLOW_EXTERNAL_IMAGES    Don\'t attempt to fetch images stored outside the spreadsheet.
 *            self::CREATE_BLANK_SHEET_IF_NONE_READ    If no sheets are read, create a blank one.
 */',
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 72,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Reader',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Reader\\IReader',
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