<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Enums\RegistrationSource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Enums\RegistrationSource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-15b895792449734e89431d891132d0fac3db2e3aa1e3573426905c67173d9387',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Enums/RegistrationSource.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Enums',
    'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
    'shortName' => 'RegistrationSource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => true,
    'isBackedEnum' => true,
    'modifiers' => 0,
    'docComment' => '/**
 * Where a beneficiary/household record came from (PRD §6.1 provenance).
 *
 * Ingestion is bulk/source-only (CLAUDE.md §8) — every record enters through a file
 * import, the REST intake, a connector sync, or an offline batch, and each of those
 * knows its own source. There is no path by which a record\'s origin is unknown.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 59,
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
      'name' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'name' => 'name',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'value' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'name' => 'value',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
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
      'isAssignable' => 
      array (
        'name' => 'isAssignable',
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
        'docComment' => '/** Whether a NEW record may be written with this provenance. */',
        'startLine' => 37,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'aliasName' => NULL,
      ),
      'assignable' => 
      array (
        'name' => 'assignable',
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
 * The provenances a new record may carry.
 *
 * @return list<self>
 */',
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'aliasName' => NULL,
      ),
      'assignableValues' => 
      array (
        'name' => 'assignableValues',
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
 * @return list<string>
 */',
        'startLine' => 55,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'aliasName' => NULL,
      ),
      'cases' => 
      array (
        'name' => 'cases',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'aliasName' => NULL,
      ),
      'tryFrom' => 
      array (
        'name' => 'tryFrom',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'static',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
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
    'backingType' => 
    array (
      'name' => 'string',
      'isIdentifier' => true,
    ),
    'cases' => 
    array (
      'Manual' => 
      array (
        'name' => 'Manual',
        'value' => 
        array (
          'code' => '\'manual\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 34,
            'startFilePos' => 1010,
            'endTokenPos' => 34,
            'endFilePos' => 1017,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Historical only. Manual single-record entry was removed (CLAUDE.md §8),
 * so no NEW record may carry this value — a record tagged `manual` today would be
 * claiming an origin that cannot occur, which is a lineage the audit trail cannot
 * account for.
 *
 * The case remains ONLY so rows written before the removal still cast and display.
 * Deleting it would break reading that history, which is worse than keeping a value
 * that {@see self::isAssignable()} refuses for writes.
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'Excel' => 
      array (
        'name' => 'Excel',
        'value' => 
        array (
          'code' => '\'excel\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 43,
            'startFilePos' => 1038,
            'endTokenPos' => 43,
            'endFilePos' => 1044,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 25,
      ),
      'Csv' => 
      array (
        'name' => 'Csv',
        'value' => 
        array (
          'code' => '\'csv\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 52,
            'startFilePos' => 1062,
            'endTokenPos' => 52,
            'endFilePos' => 1066,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Kobo' => 
      array (
        'name' => 'Kobo',
        'value' => 
        array (
          'code' => '\'kobo\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 61,
            'startFilePos' => 1085,
            'endTokenPos' => 61,
            'endFilePos' => 1090,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 23,
      ),
      'Odk' => 
      array (
        'name' => 'Odk',
        'value' => 
        array (
          'code' => '\'odk\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 70,
            'startFilePos' => 1108,
            'endTokenPos' => 70,
            'endFilePos' => 1112,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Api' => 
      array (
        'name' => 'Api',
        'value' => 
        array (
          'code' => '\'api\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 79,
            'startFilePos' => 1130,
            'endTokenPos' => 79,
            'endFilePos' => 1134,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Socu' => 
      array (
        'name' => 'Socu',
        'value' => 
        array (
          'code' => '\'socu\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 88,
            'startFilePos' => 1153,
            'endTokenPos' => 88,
            'endFilePos' => 1158,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 23,
      ),
      'GovernmentSystem' => 
      array (
        'name' => 'GovernmentSystem',
        'value' => 
        array (
          'code' => '\'government_system\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 97,
            'startFilePos' => 1189,
            'endTokenPos' => 97,
            'endFilePos' => 1207,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
    ),
  ),
));