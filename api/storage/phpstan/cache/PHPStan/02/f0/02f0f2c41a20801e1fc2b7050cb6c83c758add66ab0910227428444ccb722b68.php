<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Style/Style.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PhpOffice\PhpSpreadsheet\Style\Style
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b8dea0401e11209be91e943662931a687c1409fd08dde58d5b881a82d3a5216d-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'filename' => '/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/Style/Style.php',
      ),
    ),
    'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
    'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
    'shortName' => 'Style',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 790,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Supervisor',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'REGEX_WHOLE_COLUMN' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'REGEX_WHOLE_COLUMN',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^[A-Z]+1:[A-Z]+\' . \\PhpOffice\\PhpSpreadsheet\\Cell\\AddressRange::MAX_ROW . \'$/\'',
          'attributes' => 
          array (
            'startLine' => 136,
            'endLine' => 138,
            'startTokenPos' => 516,
            'startFilePos' => 4193,
            'endTokenPos' => 526,
            'endFilePos' => 4257,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 136,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 15,
      ),
      'REGEX_WHOLE_ROW' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'REGEX_WHOLE_ROW',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^A\\d+:\' . \\PhpOffice\\PhpSpreadsheet\\Cell\\AddressRange::MAX_COLUMN . \'\\d+$/\'',
          'attributes' => 
          array (
            'startLine' => 139,
            'endLine' => 141,
            'startTokenPos' => 537,
            'startFilePos' => 4296,
            'endTokenPos' => 547,
            'endFilePos' => 4357,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 139,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 18,
      ),
    ),
    'immediateProperties' => 
    array (
      'font' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'font',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Font',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Font.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fill' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'fill',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Fill',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Fill.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'borders' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'borders',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Borders',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Borders.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'alignment' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'alignment',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Alignment',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Alignment.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'numberFormat' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'numberFormat',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\NumberFormat',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Number Format.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'protection' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'protection',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Protection',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Protection.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'index' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'index',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Index of style in collection. Only used for real style.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'quotePrefix' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'quotePrefix',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 130,
            'startFilePos' => 1100,
            'endTokenPos' => 130,
            'endFilePos' => 1104,
          ),
        ),
        'docComment' => '/**
 * Use Quote Prefix when displaying in cell editor. Only used for real style.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'checkBox' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'checkBox',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 141,
            'startFilePos' => 1139,
            'endTokenPos' => 141,
            'endFilePos' => 1143,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cachedStyles' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'name' => 'cachedStyles',
        'modifiers' => 20,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 75,
            'endLine' => 75,
            'startTokenPos' => 157,
            'startFilePos' => 1936,
            'endTokenPos' => 157,
            'endFilePos' => 1939,
          ),
        ),
        'docComment' => '/**
 * Internal cache for styles
 * Used when applying style on range of cells (column or row) and cleared when
 * all cells in range is styled.
 *
 * PhpSpreadsheet will always minimize the amount of styles used. So cells with
 * same styles will reference the same Style instance. To check if two styles
 * are similar Style::getHashCode() is used. This call is expensive. To minimize
 * the need to call this method we can cache the internal PHP object id of the
 * Style in the range. Style::getHashCode() will then only be called when we
 * encounter a unique style.
 *
 * @see Style::applyFromArray()
 * @see Style::getHashCode()
 *
 * @var null|array<string, mixed[]>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
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
          'isSupervisor' => 
          array (
            'name' => 'isSupervisor',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 174,
                'startFilePos' => 2432,
                'endTokenPos' => 174,
                'endFilePos' => 2436,
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 33,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'isConditional' => 
          array (
            'name' => 'isConditional',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 183,
                'startFilePos' => 2461,
                'endTokenPos' => 183,
                'endFilePos' => 2465,
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 61,
            'endColumn' => 87,
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
 * Create a new Style.
 *
 * @param bool $isSupervisor Flag indicating if this is a supervisor or not
 *         Leave this value at default unless you understand exactly what
 *    its ramifications are
 * @param bool $isConditional Flag indicating if this is a conditional style or not
 *       Leave this value at default unless you understand exactly what
 *    its ramifications are
 */',
        'startLine' => 87,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getSharedComponent' => 
      array (
        'name' => 'getSharedComponent',
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
 * Get the shared style component for the currently active cell in currently active sheet.
 * Only used for style supervisor.
 */',
        'startLine' => 114,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getParent' => 
      array (
        'name' => 'getParent',
        'parameters' => 
        array (
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
 * Get parent. Only used for style supervisor.
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getStyleArray' => 
      array (
        'name' => 'getStyleArray',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
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
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 35,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build style array from subcomponents.
 *
 * @param mixed[] $array
 *
 * @return array{quotePrefix: mixed[]}
 */',
        'startLine' => 150,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'applyFromArray' => 
      array (
        'name' => 'applyFromArray',
        'parameters' => 
        array (
          'styleArray' => 
          array (
            'name' => 'styleArray',
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'advancedBorders' => 
          array (
            'name' => 'advancedBorders',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 200,
                'endLine' => 200,
                'startTokenPos' => 600,
                'startFilePos' => 6163,
                'endTokenPos' => 600,
                'endFilePos' => 6166,
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 55,
            'endColumn' => 82,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply styles from array.
 *
 * <code>
 * $spreadsheet->getActiveSheet()->getStyle(\'B2\')->applyFromArray(
 *     [
 *         \'font\' => [
 *             \'name\' => \'Arial\',
 *             \'bold\' => true,
 *             \'italic\' => false,
 *             \'underline\' => Font::UNDERLINE_DOUBLE,
 *             \'strikethrough\' => false,
 *             \'color\' => [
 *                 \'rgb\' => \'808080\'
 *             ]
 *         ],
 *         \'borders\' => [
 *             \'bottom\' => [
 *                 \'borderStyle\' => Border::BORDER_DASHDOT,
 *                 \'color\' => [
 *                     \'rgb\' => \'808080\'
 *                 ]
 *             ],
 *             \'top\' => [
 *                 \'borderStyle\' => Border::BORDER_DASHDOT,
 *                 \'color\' => [
 *                     \'rgb\' => \'808080\'
 *                 ]
 *             ]
 *         ],
 *         \'alignment\' => [
 *             \'horizontal\' => Alignment::HORIZONTAL_CENTER,
 *             \'vertical\' => Alignment::VERTICAL_CENTER,
 *             \'wrapText\' => true,
 *         ],
 *         \'quotePrefix\'    => true
 *     ]
 * );
 * </code>
 *
 * @param mixed[] $styleArray Array containing style information
 * @param bool $advancedBorders advanced mode for setting borders
 *
 * @return $this
 */',
        'startLine' => 200,
        'endLine' => 533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getOldXfIndexes' => 
      array (
        'name' => 'getOldXfIndexes',
        'parameters' => 
        array (
          'selectionType' => 
          array (
            'name' => 'selectionType',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 38,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rangeStart' => 
          array (
            'name' => 'rangeStart',
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
            'startColumn' => 61,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rangeEnd' => 
          array (
            'name' => 'rangeEnd',
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
            'startColumn' => 80,
            'endColumn' => 94,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'columnStart' => 
          array (
            'name' => 'columnStart',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 97,
            'endColumn' => 115,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'columnEnd' => 
          array (
            'name' => 'columnEnd',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 118,
            'endColumn' => 134,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'styleArray' => 
          array (
            'name' => 'styleArray',
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
            'startColumn' => 137,
            'endColumn' => 153,
            'parameterIndex' => 5,
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
 * @param mixed[] $rangeStart
 * @param mixed[] $rangeEnd
 * @param mixed[] $styleArray
 *
 * @return mixed[]
 */',
        'startLine' => 542,
        'endLine' => 597,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getFill' => 
      array (
        'name' => 'getFill',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Fill',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Fill.
 */',
        'startLine' => 602,
        'endLine' => 605,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getFont' => 
      array (
        'name' => 'getFont',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Font',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Font.
 */',
        'startLine' => 610,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'setFont' => 
      array (
        'name' => 'setFont',
        'parameters' => 
        array (
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Font',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 620,
            'endLine' => 620,
            'startColumn' => 29,
            'endColumn' => 38,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set font.
 *
 * @return $this
 */',
        'startLine' => 620,
        'endLine' => 625,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getBorders' => 
      array (
        'name' => 'getBorders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Borders',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Borders.
 */',
        'startLine' => 630,
        'endLine' => 633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getAlignment' => 
      array (
        'name' => 'getAlignment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Alignment',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Alignment.
 */',
        'startLine' => 638,
        'endLine' => 641,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getNumberFormat' => 
      array (
        'name' => 'getNumberFormat',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\NumberFormat',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Number Format.
 */',
        'startLine' => 646,
        'endLine' => 649,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getConditionalStyles' => 
      array (
        'name' => 'getConditionalStyles',
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
        'docComment' => '/**
 * Get Conditional Styles. Only used on supervisor.
 *
 * @return Conditional[]
 */',
        'startLine' => 656,
        'endLine' => 659,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'setConditionalStyles' => 
      array (
        'name' => 'setConditionalStyles',
        'parameters' => 
        array (
          'conditionalStyleArray' => 
          array (
            'name' => 'conditionalStyleArray',
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
            'startLine' => 668,
            'endLine' => 668,
            'startColumn' => 42,
            'endColumn' => 69,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set Conditional Styles. Only used on supervisor.
 *
 * @param Conditional[] $conditionalStyleArray Array of conditional styles
 *
 * @return $this
 */',
        'startLine' => 668,
        'endLine' => 673,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getProtection' => 
      array (
        'name' => 'getProtection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'PhpOffice\\PhpSpreadsheet\\Style\\Protection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Protection.
 */',
        'startLine' => 678,
        'endLine' => 681,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getQuotePrefix' => 
      array (
        'name' => 'getQuotePrefix',
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
 * Get quote prefix.
 */',
        'startLine' => 686,
        'endLine' => 693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'setQuotePrefix' => 
      array (
        'name' => 'setQuotePrefix',
        'parameters' => 
        array (
          'quotePrefix' => 
          array (
            'name' => 'quotePrefix',
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
            'startLine' => 700,
            'endLine' => 700,
            'startColumn' => 36,
            'endColumn' => 52,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set quote prefix.
 *
 * @return $this
 */',
        'startLine' => 700,
        'endLine' => 715,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getCheckBox' => 
      array (
        'name' => 'getCheckBox',
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
        'startLine' => 717,
        'endLine' => 724,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'setCheckBox' => 
      array (
        'name' => 'setCheckBox',
        'parameters' => 
        array (
          'checkBox' => 
          array (
            'name' => 'checkBox',
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
            'startLine' => 726,
            'endLine' => 726,
            'startColumn' => 33,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 726,
        'endLine' => 738,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getHashCode' => 
      array (
        'name' => 'getHashCode',
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
        'docComment' => '/**
 * Get hash code.
 *
 * @return string Hash code
 */',
        'startLine' => 745,
        'endLine' => 758,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'getIndex' => 
      array (
        'name' => 'getIndex',
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
        'docComment' => '/**
 * Get own index in style collection.
 */',
        'startLine' => 763,
        'endLine' => 766,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'setIndex' => 
      array (
        'name' => 'setIndex',
        'parameters' => 
        array (
          'index' => 
          array (
            'name' => 'index',
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
            'startLine' => 771,
            'endLine' => 771,
            'startColumn' => 30,
            'endColumn' => 39,
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
        'docComment' => '/**
 * Set own index in style collection.
 */',
        'startLine' => 771,
        'endLine' => 774,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'aliasName' => NULL,
      ),
      'exportArray1' => 
      array (
        'name' => 'exportArray1',
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
        'docComment' => '/** @return mixed[] */',
        'startLine' => 777,
        'endLine' => 789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\Style',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\Style\\Style',
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