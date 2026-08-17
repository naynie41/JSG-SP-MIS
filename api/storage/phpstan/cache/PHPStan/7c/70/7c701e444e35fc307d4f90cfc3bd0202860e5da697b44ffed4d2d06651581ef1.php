<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\Imports\ReferenceDatasetException.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\Imports\ReferenceDatasetException
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-dc9c30addd1e297e80e5f8df1c067fcf24c6553a9ea86a9d63077a54b29a784f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/Imports/ReferenceDatasetException.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference\\Imports',
    'name' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
    'shortName' => 'ReferenceDatasetException',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Raised when the authoritative administrative-divisions dataset is absent or is not
 * credibly a Jigawa dataset.
 *
 * Every message here is written for a maintainer standing at a terminal: it says what
 * was wrong, where the file was looked for, and where a real one is sourced. The
 * alternative — seeding placeholder wards — is the failure this class exists to
 * prevent: a fabricated ward list is worse than free text, because free text is
 * visibly unverified while a populated lookup table looks authoritative.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 126,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'RuntimeException',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'SOURCES' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'name' => 'SOURCES',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '<<<\'TXT\'
Source an authoritative dataset from one of:
  - OCHA HDX      "Nigeria - Administrative Boundaries" (admin 1-3), data.humdata.org
  - GRID3 Nigeria operational LGA + Ward boundaries, grid3.org
  - Jigawa State administrative records (the State\'s own ward register)
Filter to Jigawa State, then export the columns described in
app/Domain/Reference/README.md.
TXT',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 29,
            'startTokenPos' => 42,
            'startFilePos' => 811,
            'endTokenPos' => 44,
            'endFilePos' => 1237,
          ),
        ),
        'docComment' => '/** Where a maintainer obtains a real dataset. Appended to the absent-file message. */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 12,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'fileMissing' => 
      array (
        'name' => 'fileMissing',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 40,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'unreadable' => 
      array (
        'name' => 'unreadable',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 41,
            'endLine' => 41,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 41,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'empty' => 
      array (
        'name' => 'empty',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 34,
            'endColumn' => 45,
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
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'unsupportedFormat' => 
      array (
        'name' => 'unsupportedFormat',
        'parameters' => 
        array (
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 46,
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
        'docComment' => NULL,
        'startLine' => 55,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'malformed' => 
      array (
        'name' => 'malformed',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'detail' => 
          array (
            'name' => 'detail',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 52,
            'endColumn' => 65,
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
        'docComment' => NULL,
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'missingColumns' => 
      array (
        'name' => 'missingColumns',
        'parameters' => 
        array (
          'missing' => 
          array (
            'name' => 'missing',
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'found' => 
          array (
            'name' => 'found',
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 59,
            'endColumn' => 71,
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
 * @param  list<string>  $missing
 */',
        'startLine' => 71,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'unknownLgas' => 
      array (
        'name' => 'unknownLgas',
        'parameters' => 
        array (
          'unknown' => 
          array (
            'name' => 'unknown',
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 40,
            'endColumn' => 53,
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
 * The dataset names LGAs that are not Jigawa\'s — almost always the wrong file, or
 * the national file left unfiltered.
 *
 * @param  list<string>  $unknown
 */',
        'startLine' => 87,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'incompleteLgas' => 
      array (
        'name' => 'incompleteLgas',
        'parameters' => 
        array (
          'missing' => 
          array (
            'name' => 'missing',
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
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 43,
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
 * The dataset covers only part of the state. Loading it would produce a lookup
 * table that silently omits real places — the "partial list that looks
 * authoritative" case.
 *
 * @param  list<string>  $missing
 */',
        'startLine' => 106,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'aliasName' => NULL,
      ),
      'conflictingWard' => 
      array (
        'name' => 'conflictingWard',
        'parameters' => 
        array (
          'lgaCode' => 
          array (
            'name' => 'lgaCode',
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 44,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'wardCode' => 
          array (
            'name' => 'wardCode',
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 61,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 79,
            'endColumn' => 91,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 94,
            'endColumn' => 107,
            'parameterIndex' => 3,
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
        'docComment' => NULL,
        'startLine' => 118,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reference\\Imports',
        'declaringClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'implementingClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
        'currentClassName' => 'App\\Domain\\Reference\\Imports\\ReferenceDatasetException',
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