<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Ops\Backup\BackupCipher.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Ops\Backup\BackupCipher
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-947b3edbc3468b1d753f1458ded982dc65afafcb8491471077454ef50da9388f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Ops/Backup/BackupCipher.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Ops\\Backup',
    'name' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
    'shortName' => 'BackupCipher',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Authenticated encryption for backup artifacts (NFR-AVAIL-01, SECURITY.md §4).
 * AES-256-CBC with a random IV, wrapped in an encrypt-then-MAC envelope
 * (HMAC-SHA256) so a tampered or wrongly-keyed artifact fails loudly on restore
 * instead of yielding garbage. Keys are derived from the configured backup key via
 * SHA-256, so any sufficiently random key string works; the enc and MAC keys are
 * domain-separated.
 *
 * Envelope layout: [ mac(32) | iv(16) | ciphertext ].
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 90,
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
      'CIPHER' => 
      array (
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'name' => 'CIPHER',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'aes-256-cbc\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 36,
            'startFilePos' => 627,
            'endTokenPos' => 36,
            'endFilePos' => 639,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'MAGIC' => 
      array (
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'name' => 'MAGIC',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"SPMISBK1\\x00"',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 47,
            'startFilePos' => 669,
            'endTokenPos' => 47,
            'endFilePos' => 680,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
    ),
    'immediateProperties' => 
    array (
      'key' => 
      array (
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'name' => 'key',
        'modifiers' => 132,
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 33,
        'endColumn' => 61,
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
          'key' => 
          array (
            'name' => 'key',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 33,
            'endColumn' => 61,
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
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'isConfigured' => 
      array (
        'name' => 'isConfigured',
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
        'startLine' => 27,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'encrypt' => 
      array (
        'name' => 'encrypt',
        'parameters' => 
        array (
          'plaintext' => 
          array (
            'name' => 'plaintext',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 29,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'decrypt' => 
      array (
        'name' => 'decrypt',
        'parameters' => 
        array (
          'envelope' => 
          array (
            'name' => 'envelope',
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
            'startLine' => 45,
            'endLine' => 45,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 45,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'encKey' => 
      array (
        'name' => 'encKey',
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
        'docComment' => NULL,
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'macKey' => 
      array (
        'name' => 'macKey',
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
        'docComment' => NULL,
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'aliasName' => NULL,
      ),
      'requireKey' => 
      array (
        'name' => 'requireKey',
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
        'docComment' => NULL,
        'startLine' => 82,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Ops\\Backup',
        'declaringClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'implementingClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
        'currentClassName' => 'App\\Domain\\Ops\\Backup\\BackupCipher',
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