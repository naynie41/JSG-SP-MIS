<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Privacy/Services/AnonymizationService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Privacy\Services\AnonymizationService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1462464ef7efaf5e39e15a91f16f841b8fceb8168fa182f4e39488a3081b9074',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'filename' => '/var/www/html/app/Domain/Privacy/Services/AnonymizationService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Privacy\\Services',
    'name' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
    'shortName' => 'AnonymizationService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Irreversibly de-identifies a beneficiary (NFR-PRV-01) while PRESERVING everything
 * that must survive: the row and its id, all operational history (enrollments,
 * benefit ledger — their FKs are untouched), and the append-only audit trail.
 *
 * Direct identifiers are always removed; quasi identifiers are removed on a full
 * anonymize but kept on an `aggregate` (so de-identified statistics remain
 * possible). NIN/BVN and their lookup hashes are always cleared, so an anonymized
 * record can never again be matched or looked up. Which fields are direct vs quasi
 * is configuration (config/privacy.php), never hard-coded.
 *
 * The write is quiet (no model events) so it neither re-derives the identifier
 * hashes nor emits a PII-bearing "updated" audit diff; a single, metadata-only
 * `beneficiary.anonymized` entry is written instead.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
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
      'NOT_NULL_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'name' => 'NOT_NULL_FIELDS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'first_name\', \'last_name\']',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 68,
            'startFilePos' => 1352,
            'endTokenPos' => 73,
            'endFilePos' => 1378,
          ),
        ),
        'docComment' => '/** Beneficiary identity columns that are NOT NULL — redacted, not nulled. */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
    ),
    'immediateProperties' => 
    array (
      'audit' => 
      array (
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
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
        'startLine' => 35,
        'endLine' => 35,
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
            'startLine' => 35,
            'endLine' => 35,
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
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'aliasName' => NULL,
      ),
      'anonymize' => 
      array (
        'name' => 'anonymize',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keepQuasi' => 
          array (
            'name' => 'keepQuasi',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 42,
                'endLine' => 42,
                'startTokenPos' => 114,
                'startFilePos' => 1650,
                'endTokenPos' => 114,
                'endFilePos' => 1654,
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 9,
            'endColumn' => 31,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'policyKey' => 
          array (
            'name' => 'policyKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 124,
                'startFilePos' => 1686,
                'endTokenPos' => 124,
                'endFilePos' => 1689,
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
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 9,
            'endColumn' => 33,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'actor' => 
          array (
            'name' => 'actor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 134,
                'startFilePos' => 1715,
                'endTokenPos' => 134,
                'endFilePos' => 1718,
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'reason' => 
          array (
            'name' => 'reason',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 45,
                'endLine' => 45,
                'startTokenPos' => 144,
                'startFilePos' => 1747,
                'endTokenPos' => 144,
                'endFilePos' => 1750,
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
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 4,
            'isOptional' => true,
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
 * @param  bool  $keepQuasi  true for `aggregate` (keep de-identified quasi fields)
 */',
        'startLine' => 40,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'aliasName' => NULL,
      ),
      'redactStagingRows' => 
      array (
        'name' => 'redactStagingRows',
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
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 40,
            'endColumn' => 63,
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
 * Redact the person out of the import rows that created them.
 *
 * Every beneficiary here arrived through an import, and the staging row keeps the
 * payload it was built from — name, NIN, BVN, phone, address, as the MDA supplied
 * them. Clearing the registry row while that payload survives de-identifies nobody:
 * the values are still queryable and still joined to the same person by
 * `beneficiary_id`. Worse, the record now REPORTS itself as anonymized, so every
 * downstream check believes an erasure that did not happen.
 *
 * The row itself is kept and only its identifying keys are removed. It records that
 * this batch produced this record; deleting it would rewrite provenance and silently
 * change import tallies that were already reported. Both the direct and the quasi
 * lists go, whatever the mode: `aggregate` keeps quasi fields on the REGISTRY row so
 * statistics stay possible, and statistics never read the staging table.
 *
 * `original_record_id` is deliberately left: it is the source system\'s own reference
 * and the per-MDA idempotency key, so clearing it would let a re-import recreate the
 * person this erasure just removed.
 */',
        'startLine' => 107,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'aliasName' => NULL,
      ),
      'purgeDocuments' => 
      array (
        'name' => 'purgeDocuments',
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
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 37,
            'endColumn' => 60,
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
 * Remove attached documents — they are PII files. The stored files are deleted
 * from the private disk and the rows are hard-deleted (their filenames can
 * themselves be identifying). Controlled by config; on by default.
 */',
        'startLine' => 130,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\AnonymizationService',
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