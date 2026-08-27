<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Services/ConsentService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Services\ConsentService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-041a688db7b3d725e7cfe6a92c483295d3c1b264680e107523b9226c0651872f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'filename' => '/var/www/html/app/Domain/Registry/Services/ConsentService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Services',
    'name' => 'App\\Domain\\Registry\\Services\\ConsentService',
    'shortName' => 'ConsentService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Captures and enforces beneficiary consent per PURPOSE (NFR-PRV-01, FR-DSH-01). A
 * change appends an immutable history row and audits the change — so consent has a
 * full, auditable lifecycle (capture, update, withdraw). For the cross-MDA sharing
 * purpose the current status is also denormalised onto the beneficiary so the
 * sharing gate ({@see DataSharingGuard}) stays a cheap read.
 * Other purposes (e.g. processing) resolve their current status from the history.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 93,
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
      'PURPOSE_CROSS_MDA' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'name' => 'PURPOSE_CROSS_MDA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cross_mda_sharing\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 71,
            'startFilePos' => 946,
            'endTokenPos' => 71,
            'endFilePos' => 964,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'PURPOSE_PROCESSING' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'name' => 'PURPOSE_PROCESSING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'data_processing\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 82,
            'startFilePos' => 1006,
            'endTokenPos' => 82,
            'endFilePos' => 1022,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
    ),
    'immediateProperties' => 
    array (
      'audit' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'name' => 'audit',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 33,
        'endColumn' => 67,
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
          'audit' => 
          array (
            'name' => 'audit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Audit\\Services\\AuditLogger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 33,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'aliasName' => NULL,
      ),
      'record' => 
      array (
        'name' => 'record',
        'parameters' => 
        array (
          'beneficiary' => 
          array (
            'name' => 'beneficiary',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Beneficiary',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'status' => 
          array (
            'name' => 'status',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 9,
            'endColumn' => 29,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'actor' => 
          array (
            'name' => 'actor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 35,
                'endLine' => 35,
                'startTokenPos' => 127,
                'startFilePos' => 1215,
                'endTokenPos' => 127,
                'endFilePos' => 1218,
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
                      'name' => 'App\\Domain\\Access\\Models\\User',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'basis' => 
          array (
            'name' => 'basis',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 36,
                'endLine' => 36,
                'startTokenPos' => 137,
                'startFilePos' => 1246,
                'endTokenPos' => 137,
                'endFilePos' => 1249,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 9,
            'endColumn' => 29,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'source' => 
          array (
            'name' => 'source',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 37,
                'endLine' => 37,
                'startTokenPos' => 147,
                'startFilePos' => 1278,
                'endTokenPos' => 147,
                'endFilePos' => 1281,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'note' => 
          array (
            'name' => 'note',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 157,
                'startFilePos' => 1308,
                'endTokenPos' => 157,
                'endFilePos' => 1311,
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 9,
            'endColumn' => 28,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'purpose' => 
          array (
            'name' => 'purpose',
            'default' => 
            array (
              'code' => 'self::PURPOSE_CROSS_MDA',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 166,
                'startFilePos' => 1340,
                'endTokenPos' => 168,
                'endFilePos' => 1362,
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
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 9,
            'endColumn' => 49,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryConsent',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'aliasName' => NULL,
      ),
      'currentStatus' => 
      array (
        'name' => 'currentStatus',
        'parameters' => 
        array (
          'beneficiary' => 
          array (
            'name' => 'beneficiary',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Beneficiary',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 35,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'purpose' => 
          array (
            'name' => 'purpose',
            'default' => 
            array (
              'code' => 'self::PURPOSE_CROSS_MDA',
              'attributes' => 
              array (
                'startLine' => 79,
                'endLine' => 79,
                'startTokenPos' => 464,
                'startFilePos' => 3069,
                'endTokenPos' => 466,
                'endFilePos' => 3091,
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
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 61,
            'endColumn' => 101,
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
            'name' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The beneficiary\'s current consent status for a purpose. The cross-MDA purpose
 * reads the denormalised column; any other purpose reads the latest history row
 * (defaulting to Unknown — no consent is ever assumed, NFR-PRV-01).
 */',
        'startLine' => 79,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\ConsentService',
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