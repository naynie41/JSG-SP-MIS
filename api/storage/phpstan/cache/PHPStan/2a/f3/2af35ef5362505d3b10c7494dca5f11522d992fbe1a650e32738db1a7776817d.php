<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Imports\SpreadsheetReader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\SpreadsheetReader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-e1e84caf5f9ff88dd84766a9c658e7716149185a72a151fe2556635d4969bb3f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Imports/SpreadsheetReader.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports',
    'name' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
    'shortName' => 'SpreadsheetReader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Reads an uploaded Excel/CSV file into normalised, header-keyed rows
 * (PRD FR-REG-02). Header names are canonicalised (lower snake_case) so the file
 * may use "First Name", "NIN", "Date of Birth", etc. Values are returned as
 * trimmed strings; numeric cells are stringified without scientific notation so
 * long identifiers (NIN/BVN) survive intact.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 143,
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
      'HEADER_SCAN_ROWS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'name' => 'HEADER_SCAN_ROWS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '10',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 43,
            'startFilePos' => 826,
            'endTokenPos' => 43,
            'endFilePos' => 827,
          ),
        ),
        'docComment' => '/**
 * How far to look for the header row. Deep enough for a letterhead of a few title
 * lines plus a spacer, shallow enough that a genuinely header-less file cannot have
 * a data row deep in the sheet mistaken for its headers.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'read' => 
      array (
        'name' => 'read',
        'parameters' => 
        array (
          'absolutePath' => 
          array (
            'name' => 'absolutePath',
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
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 26,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extension' => 
          array (
            'name' => 'extension',
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
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * @return array{headers: list<string>, rows: list<array{number: int, values: array<string, string>}>}
 */',
        'startLine' => 29,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'aliasName' => NULL,
      ),
      'headerRowIndex' => 
      array (
        'name' => 'headerRowIndex',
        'parameters' => 
        array (
          'matrix' => 
          array (
            'name' => 'matrix',
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 37,
            'endColumn' => 49,
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
 * Which row holds the column headers.
 *
 * Government spreadsheets routinely open with a letterhead: a merged title cell, a
 * department line, an "Activity: …" note, a blank row, and only THEN the headers.
 * Taking row 1 on faith turned such a file into a single column named after the
 * ministry, and the mapping screen offered nothing to map.
 *
 * The rule: within the first {@see self::HEADER_SCAN_ROWS} rows, take the row with
 * the most non-empty cells, earliest wins on a tie. A banner has one filled cell (the
 * merge leaves the rest null) and a spacer has none, so both lose to the real header
 * row; an ordinary file\'s row 1 has the most and ties are broken back to it.
 *
 * This is a heuristic, and it is allowed to be one because it cannot pass unnoticed:
 * the headers it picks are shown on the mapping screen with real sample values, and a
 * person confirms every identity field against them (CLAUDE.md §11). A wrong guess
 * here is visible before anything is parsed, never silent.
 *
 * @param  list<list<mixed>>  $matrix
 */',
        'startLine' => 99,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'aliasName' => NULL,
      ),
      'canonicalHeader' => 
      array (
        'name' => 'canonicalHeader',
        'parameters' => 
        array (
          'header' => 
          array (
            'name' => 'header',
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
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 38,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 121,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'aliasName' => NULL,
      ),
      'stringify' => 
      array (
        'name' => 'stringify',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 32,
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
        'startLine' => 128,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Imports',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\SpreadsheetReader',
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