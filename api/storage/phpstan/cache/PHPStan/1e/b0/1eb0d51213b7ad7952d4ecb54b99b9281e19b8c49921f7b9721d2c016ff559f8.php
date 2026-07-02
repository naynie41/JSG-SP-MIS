<?php declare(strict_types = 1);

// osfsl-/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/RichText/RichText.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PhpOffice\PhpSpreadsheet\RichText\RichText
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cadc6a12e1e0d751f20293d30a7496c774053d2736cedc6f746eec16e9acfb57-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'filename' => '/var/www/html/vendor/composer/../phpoffice/phpspreadsheet/src/PhpSpreadsheet/RichText/RichText.php',
      ),
    ),
    'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
    'name' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
    'shortName' => 'RichText',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 164,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'PhpOffice\\PhpSpreadsheet\\IComparable',
      1 => 'Stringable',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'richTextElements' => 
      array (
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'name' => 'richTextElements',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Rich text elements.
 *
 * @var ITextElement[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 36,
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
          'cell' => 
          array (
            'name' => 'cell',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 22,
                'endLine' => 22,
                'startTokenPos' => 64,
                'startFilePos' => 464,
                'endTokenPos' => 64,
                'endFilePos' => 467,
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
                      'name' => 'PhpOffice\\PhpSpreadsheet\\Cell\\Cell',
                      'isIdentifier' => false,
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new RichText instance.
 */',
        'startLine' => 22,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'addText' => 
      array (
        'name' => 'addText',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'PhpOffice\\PhpSpreadsheet\\RichText\\ITextElement',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 29,
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
        'docComment' => '/**
 * Add text.
 *
 * @param ITextElement $text Rich text element
 *
 * @return $this
 */',
        'startLine' => 48,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'createText' => 
      array (
        'name' => 'createText',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 60,
            'endLine' => 60,
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
            'name' => 'PhpOffice\\PhpSpreadsheet\\RichText\\TextElement',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create text.
 *
 * @param string $text Text
 */',
        'startLine' => 60,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'createTextRun' => 
      array (
        'name' => 'createTextRun',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 73,
            'endLine' => 73,
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
            'name' => 'PhpOffice\\PhpSpreadsheet\\RichText\\Run',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create text run.
 *
 * @param string $text Text
 */',
        'startLine' => 73,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'getPlainText' => 
      array (
        'name' => 'getPlainText',
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
 * Get plain text.
 */',
        'startLine' => 84,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
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
 * Convert to string.
 */',
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'getRichTextElements' => 
      array (
        'name' => 'getRichTextElements',
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
 * Get Rich Text elements.
 *
 * @return ITextElement[]
 */',
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      'setRichTextElements' => 
      array (
        'name' => 'setRichTextElements',
        'parameters' => 
        array (
          'textElements' => 
          array (
            'name' => 'textElements',
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
            'startLine' => 122,
            'endLine' => 122,
            'startColumn' => 41,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set Rich Text elements.
 *
 * @param ITextElement[] $textElements Array of elements
 *
 * @return $this
 */',
        'startLine' => 122,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
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
        'startLine' => 134,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'aliasName' => NULL,
      ),
      '__clone' => 
      array (
        'name' => '__clone',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Implement PHP __clone to create a deep clone, not just a shallow copy.
 */',
        'startLine' => 150,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PhpOffice\\PhpSpreadsheet\\RichText',
        'declaringClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'implementingClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
        'currentClassName' => 'PhpOffice\\PhpSpreadsheet\\RichText\\RichText',
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