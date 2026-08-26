<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Segments\SegmentAccess.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Segments\SegmentAccess
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8fd4cc2384f0411781da4c3834ba20b36bd64a9d47e113f54289a7ff321f73ab',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Segments/SegmentAccess.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Segments',
    'name' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
    'shortName' => 'SegmentAccess',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * What a caller may get OUT of the segment builder (SECURITY.md §3 export matrix).
 *
 * The builder composes filters; it does not decide entitlement. Three questions are
 * answered here, once, so that the preview endpoint, the export endpoint and the queued
 * job cannot drift apart — the classic way a "report tool" becomes the hole in an export
 * policy is that one of those three forgets to ask.
 *
 *  1. May they see ROWS at all, or only counts?
 *     System Administrator, SP Coordination and MDA Admin export beneficiary data.
 *     Development Partners and Executives do NOT — aggregates only, never the registry.
 *     This is a TIER, derived from the export permission, not from the scope: a partner
 *     with a wide funded-programme scope still gets counts, and an MDA Admin with a
 *     narrow one still gets its own rows.
 *
 *  2. May identifiers be shown in the clear?
 *     Only with `export.reveal_pii`, which SECURITY.md reserves to the System
 *     Administrator by default. Everything else is masked, including for an MDA
 *     exporting the people it owns.
 *
 *  3. Does the small-cell guard apply?
 *     It applies wherever the output describes people the caller does not own — the
 *     aggregate tiers, and cross-MDA aggregates. It does NOT apply to an MDA
 *     segmenting its OWN beneficiaries: re-identification is not a risk against a
 *     population you already hold the records for, and suppressing there would break
 *     ordinary operational work like "which two women in this ward are still pending".
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 38,
    'endLine' => 108,
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
      'TIER_ROWS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'TIER_ROWS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'rows\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 50,
            'startFilePos' => 1850,
            'endTokenPos' => 50,
            'endFilePos' => 1855,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'TIER_AGGREGATE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'TIER_AGGREGATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'aggregate\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 61,
            'startFilePos' => 1893,
            'endTokenPos' => 61,
            'endFilePos' => 1903,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'EXPORT_PERMISSION' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'EXPORT_PERMISSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'beneficiary.export\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 74,
            'startFilePos' => 2019,
            'endTokenPos' => 74,
            'endFilePos' => 2038,
          ),
        ),
        'docComment' => '/** The permission that lets a caller pull beneficiary rows at all. */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
    ),
    'immediateProperties' => 
    array (
      'tier' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'tier',
        'modifiers' => 2049,
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
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 9,
        'endColumn' => 27,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'revealPii' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'revealPii',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 9,
        'endColumn' => 30,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cellSizeGuard' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'cellSizeGuard',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 9,
        'endColumn' => 34,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'scope' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'name' => 'scope',
        'modifiers' => 2049,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 9,
        'endColumn' => 36,
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
          'tier' => 
          array (
            'name' => 'tier',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'revealPii' => 
          array (
            'name' => 'revealPii',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'cellSizeGuard' => 
          array (
            'name' => 'cellSizeGuard',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 9,
            'endColumn' => 36,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'aliasName' => NULL,
      ),
      'forUser' => 
      array (
        'name' => 'forUser',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 36,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 48,
            'endColumn' => 68,
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
        'startLine' => 54,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'aliasName' => NULL,
      ),
      'fromParams' => 
      array (
        'name' => 'fromParams',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 54,
            'endColumn' => 74,
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
 * Rebuild from what was persisted on the run, so a queued job renders exactly the
 * entitlement resolved at request time — never a re-resolution against a user whose
 * roles may have changed since.
 *
 * @param  array<string, mixed>  $params
 */',
        'startLine' => 84,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'aliasName' => NULL,
      ),
      'showsRows' => 
      array (
        'name' => 'showsRows',
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
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'aliasName' => NULL,
      ),
      'toParams' => 
      array (
        'name' => 'toParams',
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
        'docComment' => '/** @return array<string, mixed> */',
        'startLine' => 100,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentAccess',
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