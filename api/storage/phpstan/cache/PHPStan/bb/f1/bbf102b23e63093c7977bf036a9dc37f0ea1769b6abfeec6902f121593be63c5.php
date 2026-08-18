<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Support\RepairDoubledNames.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\RepairDoubledNames
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-7c9cdfdee61432bd9238de1b79a4ed3ad1921e5ffcc7372f2261f867b708b1af',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Support/RepairDoubledNames.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
    'shortName' => 'RepairDoubledNames',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Repairs beneficiaries whose name was doubled by an early import.
 *
 *   php artisan registry:repair-doubled-names            # report only
 *   php artisan registry:repair-doubled-names --apply    # write the fix
 *
 * Before {@see NameSplitter} existed, a source file with one `Name` column could only be
 * mapped by pointing BOTH `first_name` and `last_name` at it, which stored the whole name
 * twice — "Rekiya Bagwai Rekiya Bagwai". New imports cannot produce this; these are the
 * records made before the fix.
 *
 * The repair re-splits by the same rule the importer now uses, so a record repaired here
 * is indistinguishable from one imported today.
 *
 * Three things make this safe to run against real data:
 *
 *  - **Narrow by construction.** Only rows where `first_name` is identical to `last_name`
 *    AND contains a space. A genuine "Musa Musa" has no space in either field and is left
 *    alone — the command cannot invent a correction for a name that was never doubled.
 *  - **Saved through the model.** `last_name` feeds `block_name_dob`, the fuzzy blocking
 *    key (FR-DUP-03). A bulk query update would leave that key pointing at the old
 *    surname and silently degrade duplicate detection; the model\'s `saving` hook
 *    recomputes it, and `Auditable` records the before/after per record.
 *  - **Idempotent.** After the repair `first_name !== last_name`, so a second run finds
 *    nothing.
 *
 * Reports by name SHAPE (token counts), never by name: this is PII and CLAUDE.md §8 says
 * it is never logged. The shape is also the more useful check — it shows the rule being
 * applied, which a list of names would not.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 41,
    'endLine' => 173,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'signature' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'registry:repair-doubled-names
        {--apply : Write the corrections. Without this the command only reports.}
        {--batch= : Limit to one import batch id.}\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 45,
            'startTokenPos' => 48,
            'startFilePos' => 1931,
            'endTokenPos' => 48,
            'endFilePos' => 2094,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Re-split beneficiary names doubled by an import made before the full-name split existed\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 57,
            'startFilePos' => 2127,
            'endTokenPos' => 57,
            'endFilePos' => 2215,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 119,
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
      'handle' => 
      array (
        'name' => 'handle',
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
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'aliasName' => NULL,
      ),
      'reportShapes' => 
      array (
        'name' => 'reportShapes',
        'parameters' => 
        array (
          'shapes' => 
          array (
            'name' => 'shapes',
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
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'skipped' => 
          array (
            'name' => 'skipped',
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
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 50,
            'endColumn' => 61,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<int, int>  $shapes
 */',
        'startLine' => 124,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'aliasName' => NULL,
      ),
      'recordSummary' => 
      array (
        'name' => 'recordSummary',
        'parameters' => 
        array (
          'repaired' => 
          array (
            'name' => 'repaired',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'skipped' => 
          array (
            'name' => 'skipped',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 51,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'shapes' => 
          array (
            'name' => 'shapes',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 65,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'batchId' => 
          array (
            'name' => 'batchId',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 80,
            'endColumn' => 95,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * One summary entry so the repair itself is on the record, separate from the
 * per-record `beneficiary.updated` entries the model wrote. Counts only — the names
 * are already in those per-record entries and must not be duplicated here.
 *
 * @param  array<int, int>  $shapes
 */',
        'startLine' => 152,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\RepairDoubledNames',
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