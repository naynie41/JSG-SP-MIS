<?php declare(strict_types = 1);

// osfsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\DashboardMetricsService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\DashboardMetricsService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c61dc49f28816204a309df10739414870f816702a5c2f3b49cdee765e9578d9a-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/DashboardMetricsService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
    'shortName' => 'DashboardMetricsService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Computes the consolidated dashboard metrics for a {@see DashboardScope} (PRD
 * FR-RPT-01/02). This is the COMPUTE side — it reads raw tables and is run OFF the
 * request path (by the snapshot refresh); the request path reads the precomputed
 * snapshot instead. Every query bypasses the request-time MdaScope and applies the
 * scope EXPLICITLY, so scoping is identical in a request or on the scheduler/queue.
 *
 * All output is de-identified aggregate data (counts/sums by non-PII dimensions) —
 * never a beneficiary field (SECURITY.md §1). Benefit figures reuse the Phase 4
 * {@see LedgerAggregator}. Coordination metrics (duplicates/referrals/grievances)
 * are MDA/state concepts and are null for a partner scope.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 45,
    'endLine' => 1870,
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
      'filter' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'name' => 'filter',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The active cross-cutting filter for this compute pass. Set at the top of
 * {@see compute()} and read by the base query builders + ledger calls; it only ever
 * NARROWS the scope (applied on top of it), so it can never widen visibility.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'ledger' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'name' => 'ledger',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 33,
        'endColumn' => 73,
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
          'ledger' => 
          array (
            'name' => 'ledger',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Services\\LedgerAggregator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 33,
            'endColumn' => 73,
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
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'compute' => 
      array (
        'name' => 'compute',
        'parameters' => 
        array (
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 29,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filter' => 
          array (
            'name' => 'filter',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 209,
                'startFilePos' => 2706,
                'endTokenPos' => 209,
                'endFilePos' => 2709,
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
                      'name' => 'App\\Domain\\Reporting\\Support\\DashboardFilter',
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 52,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Compute the metric bundle for a scope. When `$filter` is set (a filtered request),
 * every metric is recomputed live with the filter pushed into its queries; the
 * unfiltered snapshot path passes no filter.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 66,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'filterOptions' => 
      array (
        'name' => 'filterOptions',
        'parameters' => 
        array (
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
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 35,
            'endColumn' => 55,
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
 * The universe of filter options WITHIN a scope (unfiltered) — so the UI only ever
 * offers programmes/MDAs/areas the caller may actually see. Computed live (small
 * distinct lists); never widens the scope.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 108,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'registry' => 
      array (
        'name' => 'registry',
        'parameters' => 
        array (
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 31,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 151,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmes' => 
      array (
        'name' => 'programmes',
        'parameters' => 
        array (
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
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 33,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Programme counts in scope (headline "active programmes", FR-RPT-01). Programmes
 * are a GLOBAL catalog (§10), so an MDA "runs" the distinct catalog programmes it
 * has activities for; a partner sees its funded set; state-wide is the whole catalog.
 *
 * @return array<string, int>
 */',
        'startLine' => 179,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'duplicates' => 
      array (
        'name' => 'duplicates',
        'parameters' => 
        array (
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
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 33,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Matches surfaced during import + how staged rows were resolved (new vs served
 * vs skipped). Scoped by the importing MDA (`import_batches.owner_mda_id`).
 *
 * @return array<string, mixed>
 */',
        'startLine' => 224,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'benefits' => 
      array (
        'name' => 'benefits',
        'parameters' => 
        array (
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
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 31,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 243,
        'endLine' => 252,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'referrals' => 
      array (
        'name' => 'referrals',
        'parameters' => 
        array (
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
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 32,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 259,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'grievances' => 
      array (
        'name' => 'grievances',
        'parameters' => 
        array (
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
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 33,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 288,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'coverage' => 
      array (
        'name' => 'coverage',
        'parameters' => 
        array (
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
            'startLine' => 315,
            'endLine' => 315,
            'startColumn' => 31,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Coverage by LGA — beneficiaries plus delivered benefits per area. Feeds the
 * registry map/table; the GIS step joins these keys to boundaries.
 *
 * @return array<int, array<string, mixed>>
 */',
        'startLine' => 315,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'population' => 
      array (
        'name' => 'population',
        'parameters' => 
        array (
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
            'startLine' => 343,
            'endLine' => 343,
            'startColumn' => 33,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Population headline — NET-UNIQUE only. `total_individuals` is the deduplicated
 * registry count in scope; `net_unique_served` is distinct beneficiaries with a
 * delivery (never the gross delivery count, which is `benefits.disbursed.benefit_count`).
 *
 * @return array<string, mixed>
 */',
        'startLine' => 343,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'demographics' => 
      array (
        'name' => 'demographics',
        'parameters' => 
        array (
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
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 35,
            'endColumn' => 55,
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
 * Demographics from existing fields only (gender, DOB → age bands, household vs
 * individual). Percentages are over KNOWN values; "unspecified"/"unknown" is kept
 * explicit so partial coverage is visible.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 368,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'ageBands' => 
      array (
        'name' => 'ageBands',
        'parameters' => 
        array (
          'base' => 
          array (
            'name' => 'base',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 402,
            'endLine' => 402,
            'startColumn' => 31,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Age-band counts from date_of_birth using config cut points. Boundaries are
 * computed as DATES (today − age) so the comparison is driver-portable and the
 * band edges are exact; records without a DOB fall into "unknown".
 *
 * @param  Builder<Beneficiary>  $base
 * @return array<string, int>
 */',
        'startLine' => 402,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'householdSize' => 
      array (
        'name' => 'householdSize',
        'parameters' => 
        array (
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
            'startLine' => 429,
            'endLine' => 429,
            'startColumn' => 36,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Household-size distribution over the scoped households — a field we HAVE (active
 * memberships per household), banded into 1 / 2–3 / 4–6 / 7+. Partners own no
 * households, so this is empty for them. NOTE: poverty/disability/PWD/IDP/occupation
 * breakdowns are intentionally absent — those fields are not captured (deferred slot).
 *
 * @return array<string, mixed>
 */',
        'startLine' => 429,
        'endLine' => 460,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmePerformance' => 
      array (
        'name' => 'programmePerformance',
        'parameters' => 
        array (
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
            'startLine' => 472,
            'endLine' => 472,
            'startColumn' => 43,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Per-programme performance: status, implementing MDA(s), start/end (derived from
 * the scoped activities), target vs net-unique reached, completion, coverage
 * (absolute), budget allocated/spent/remaining, cost per beneficiary, a
 * configurable traffic-light score, and the activity-level drill-down. Everything
 * is scoped: a partner sees only its funded programmes\' activities, an MDA only its
 * own — so the drill-down never exposes activities outside the caller\'s remit.
 *
 * @return list<array<string, mixed>>
 */',
        'startLine' => 472,
        'endLine' => 577,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'registryQuality' => 
      array (
        'name' => 'registryQuality',
        'parameters' => 
        array (
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
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 38,
            'endColumn' => 58,
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
 * Registry quality/integrity. "verified/pending" map to the review status we hold
 * (active = clean, flagged = pending review); completeness is field presence
 * (NIN via the keyed hash, phone, DOB, gender, LGA). No PII values — presence only.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 586,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'matchesSurfaced' => 
      array (
        'name' => 'matchesSurfaced',
        'parameters' => 
        array (
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
            'startLine' => 615,
            'endLine' => 615,
            'startColumn' => 38,
            'endColumn' => 58,
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
        'docComment' => NULL,
        'startLine' => 615,
        'endLine' => 622,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'coordination' => 
      array (
        'name' => 'coordination',
        'parameters' => 
        array (
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
            'startLine' => 631,
            'endLine' => 631,
            'startColumn' => 35,
            'endColumn' => 55,
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
 * Coordination across agencies: active delivering MDAs, cross-MDA (joint, served
 * by a non-owner) beneficiaries — net-unique, referral throughput, request-to-serve,
 * partner contributions, and sync/API health.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 631,
        'endLine' => 716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'jointProgrammes' => 
      array (
        'name' => 'jointProgrammes',
        'parameters' => 
        array (
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
            'startLine' => 723,
            'endLine' => 723,
            'startColumn' => 38,
            'endColumn' => 58,
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
 * Programmes run jointly by two or more MDAs (activities owned by ≥2 MDAs), within
 * scope. State-wide covers the whole catalog; an MDA sees the joint programmes it
 * participates in.
 */',
        'startLine' => 723,
        'endLine' => 751,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerFunding' => 
      array (
        'name' => 'partnerFunding',
        'parameters' => 
        array (
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
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 37,
            'endColumn' => 57,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * PARTNER-FUNDING aggregates (Phase 6P) — activity-precise, over the activities a
 * partner actually funds (`activities.funding_partner_id`). Allocated = committed
 * funding on those activities; `delivered_value` = the recorded VALUE OF BENEFITS
 * DELIVERED under them (programme data) — **not treasury expenditure**; labelled so
 * downstream. Scoped to the partner, de-identified (counts + values only).
 *
 * @return array<string, mixed>|null
 */',
        'startLine' => 762,
        'endLine' => 837,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerReach' => 
      array (
        'name' => 'partnerReach',
        'parameters' => 
        array (
          'activityIds' => 
          array (
            'name' => 'activityIds',
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
            'startLine' => 847,
            'endLine' => 847,
            'startColumn' => 35,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reach demographics of the cohort SERVED through a partner\'s funded activities —
 * households, women (recorded female) and children (age band). CAPTURED fields only
 * (PWD/vulnerable are not held). Counts only, no PII.
 *
 * @param  list<string>  $activityIds
 * @return array<string, int>
 */',
        'startLine' => 847,
        'endLine' => 873,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerRegistry' => 
      array (
        'name' => 'partnerRegistry',
        'parameters' => 
        array (
          'activityIds' => 
          array (
            'name' => 'activityIds',
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
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 38,
            'endColumn' => 55,
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
 * FUNDED-PROGRAMME BENEFICIARIES (Phase 6P "Registry" tab) — the aggregate registry
 * for a partner\'s funded cohort: beneficiaries ENROLLED IN or SERVED BY the funded
 * activities (activity-precise, union). De-identified counts only — never the raw
 * registry, never a beneficiary field. KPIs, captured-field demographics, a REDUCED
 * targeting funnel (Registered → Enrolled → Receiving; the eligible→selected steps
 * are omitted — no eligibility denominator / selection model), and data quality.
 *
 * @param  list<string>  $activityIds
 * @return array<string, mixed>
 */',
        'startLine' => 886,
        'endLine' => 990,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'cohortHouseholdSize' => 
      array (
        'name' => 'cohortHouseholdSize',
        'parameters' => 
        array (
          'householdIds' => 
          array (
            'name' => 'householdIds',
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
            'startLine' => 1000,
            'endLine' => 1000,
            'startColumn' => 42,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Household-size distribution (banded 1 / 2–3 / 4–6 / 7+) over a set of household ids
 * — a field we HAVE (active memberships per household). Used for the partner cohort,
 * whose households come from its served/enrolled members (a partner owns no households).
 *
 * @param  list<string>  $householdIds
 * @return array<string, mixed>
 */',
        'startLine' => 1000,
        'endLine' => 1028,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerProgrammes' => 
      array (
        'name' => 'partnerProgrammes',
        'parameters' => 
        array (
          'activities' => 
          array (
            'name' => 'activities',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\Collection',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 40,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'activityIds' => 
          array (
            'name' => 'activityIds',
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
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 64,
            'endColumn' => 81,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'outputByProgramme' => 
          array (
            'name' => 'outputByProgramme',
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
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 84,
            'endColumn' => 107,
            'parameterIndex' => 2,
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
 * Per-FUNDED-programme results (Phase 6P "Programmes & Results"), ACTIVITY-PRECISE:
 * only the partner\'s funded activities count toward each programme\'s budget allocated,
 * value DELIVERED (recorded delivery value, NOT treasury expenditure), reach, coverage
 * (absolute), completion, interventions (benefit-record count), average benefit value,
 * cost per beneficiary, a monthly delivery-rate series and a four-state delivery status.
 * De-identified aggregates only; the ledger is constrained to the funded activities.
 *
 * @param  Collection<int, Activity>  $activities  the partner\'s funded activities
 * @param  list<string>  $activityIds
 * @param  array<string, array<int, array<string, int|string|null>>>  $outputByProgramme
 * @return array<int, array<string, mixed>>
 */',
        'startLine' => 1043,
        'endLine' => 1145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmeStatusLight' => 
      array (
        'name' => 'programmeStatusLight',
        'parameters' => 
        array (
          'completion' => 
          array (
            'name' => 'completion',
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
                      'name' => 'float',
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
            'startLine' => 1153,
            'endLine' => 1153,
            'startColumn' => 43,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'endDate' => 
          array (
            'name' => 'endDate',
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
            'startLine' => 1153,
            'endLine' => 1153,
            'startColumn' => 63,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'today' => 
          array (
            'name' => 'today',
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
            'startLine' => 1153,
            'endLine' => 1153,
            'startColumn' => 81,
            'endColumn' => 93,
            'parameterIndex' => 2,
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
        'docComment' => '/**
 * Four-state funded-programme delivery status (Phase 6P) from completion + timeline:
 * past the delivery end date → Completed (if completion ≥ completed_min) else Delayed;
 * still in timeline → On Track / At Risk / Delayed by completion band. Thresholds are
 * configurable (config/reporting.php programme_status). No target → "unrated".
 */',
        'startLine' => 1153,
        'endLine' => 1172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerOutputIndicators' => 
      array (
        'name' => 'partnerOutputIndicators',
        'parameters' => 
        array (
          'activityIds' => 
          array (
            'name' => 'activityIds',
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
            'startLine' => 1184,
            'endLine' => 1184,
            'startColumn' => 46,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * OUTPUT INDICATORS (Phase 6P) — counts of INTERVENTIONS (benefit records) delivered
 * under a partner\'s funded activities, by benefit TYPE and captured demographic
 * (gender, age). OUTPUTS ONLY — interventions delivered — never outcomes (poverty,
 * income, attendance), which require external evaluation data. Counts only, no PII.
 * Returns a per-programme map and a rolled-up total across all funded programmes.
 *
 * @param  list<string>  $activityIds
 * @return array{by_programme: array<string, array<int, array<string, int|string|null>>>, rolled_up: array<int, array<string, int|string|null>>}
 */',
        'startLine' => 1184,
        'endLine' => 1242,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'zeroFilledSeries' => 
      array (
        'name' => 'zeroFilledSeries',
        'parameters' => 
        array (
          'map' => 
          array (
            'name' => 'map',
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
            'startLine' => 1251,
            'endLine' => 1251,
            'startColumn' => 39,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'months' => 
          array (
            'name' => 'months',
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
            'startLine' => 1251,
            'endLine' => 1251,
            'startColumn' => 51,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Zero-fill a {\'YYYY-MM\' => value} map to the full last-N-months label list, so a
 * delivery series always has one point per month (gaps rendered as zero).
 *
 * @param  array<string, int>  $map
 * @return array<int, array{month: string, value: int}>
 */',
        'startLine' => 1251,
        'endLine' => 1257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerCoordination' => 
      array (
        'name' => 'partnerCoordination',
        'parameters' => 
        array (
          'partnerId' => 
          array (
            'name' => 'partnerId',
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
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 42,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fundedProgrammeIds' => 
          array (
            'name' => 'fundedProgrammeIds',
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
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 61,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'selfTotals' => 
          array (
            'name' => 'selfTotals',
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
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 88,
            'endColumn' => 104,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'callerActivityIds' => 
          array (
            'name' => 'callerActivityIds',
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
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 107,
            'endColumn' => 130,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * PARTNER COORDINATION (Phase 6P "Coordination" tab) — the actor landscape AROUND a
 * partner\'s funded programmes: the funding organisations, government agencies (MDAs)
 * and implementing agencies active in them; a funding-by-partner table (amounts for
 * the CALLER only — a partner never sees another funder\'s money); the MDA landscape;
 * and data-sharing / sync health for the implementing agencies. Programme overlap
 * (the tab\'s headline) is served by {@see programmeOverlap()} on the same block.
 *
 * @param  list<string>  $fundedProgrammeIds
 * @param  array{allocated:int,delivered_value:int,net_unique_reached:int,funded_programmes:int}  $selfTotals
 * @param  list<string>  $callerActivityIds
 * @return array<string, mixed>
 */',
        'startLine' => 1272,
        'endLine' => 1387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmeOverlap' => 
      array (
        'name' => 'programmeOverlap',
        'parameters' => 
        array (
          'partnerId' => 
          array (
            'name' => 'partnerId',
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
            'startLine' => 1399,
            'endLine' => 1399,
            'startColumn' => 39,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fundedProgrammeIds' => 
          array (
            'name' => 'fundedProgrammeIds',
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
            'startLine' => 1399,
            'endLine' => 1399,
            'startColumn' => 58,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'partnerActivities' => 
          array (
            'name' => 'partnerActivities',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1399,
            'endLine' => 1399,
            'startColumn' => 85,
            'endColumn' => 102,
            'parameterIndex' => 2,
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
 * PROGRAMME OVERLAP (Phase 6P) — where a partner\'s funded (catalog programme × LGA)
 * cell is ALSO served, in the same LGA, by a DIFFERENT funder or a DIFFERENT MDA.
 * A coordination signal only: it exposes the existence + count of other funders/MDAs,
 * never their amounts (a partner sees only their own money).
 *
 * @param  list<string>  $fundedProgrammeIds
 * @param  Collection<int, Activity>  $partnerActivities
 * @return array<string, mixed>
 */',
        'startLine' => 1399,
        'endLine' => 1439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'partnerContributions' => 
      array (
        'name' => 'partnerContributions',
        'parameters' => 
        array (
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
            'startLine' => 1449,
            'endLine' => 1449,
            'startColumn' => 43,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Partner (funder) contributions: distinct partners, funded programmes, net-unique
 * beneficiaries served through them, allocated funding, and a PER-PARTNER breakdown.
 * Every figure is scoped to funded programmes in view (a partner/MDA never sees
 * funding beyond its own remit); state-wide covers all funders.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 1449,
        'endLine' => 1493,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'coverageBands' => 
      array (
        'name' => 'coverageBands',
        'parameters' => 
        array (
          'coverageRows' => 
          array (
            'name' => 'coverageRows',
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
            'startLine' => 1502,
            'endLine' => 1502,
            'startColumn' => 36,
            'endColumn' => 54,
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
 * Coverage banding by ABSOLUTE beneficiaries per area against configurable
 * thresholds (green/yellow/red) — explicitly NOT a % of population.
 *
 * @param  array<int, array<string, mixed>>  $coverageRows
 * @return array<string, mixed>
 */',
        'startLine' => 1502,
        'endLine' => 1522,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'trends' => 
      array (
        'name' => 'trends',
        'parameters' => 
        array (
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
            'startLine' => 1531,
            'endLine' => 1531,
            'startColumn' => 29,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Periodised trends over the last N months: net-new registrations, cumulative
 * beneficiaries (from a pre-window baseline), disbursement value, and programme
 * growth. Months are zero-filled so the series is a complete, ordered timeline.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 1531,
        'endLine' => 1558,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'deferredSlots' => 
      array (
        'name' => 'deferredSlots',
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
 * Deferred aggregation slots (NFR — field/denominator-dependent). They exist with
 * a stable key so they can switch on later, but return null today and must not be
 * rendered. Not exposed as a live metric.
 *
 * @return array<string, null>
 */',
        'startLine' => 1567,
        'endLine' => 1576,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'monthLabels' => 
      array (
        'name' => 'monthLabels',
        'parameters' => 
        array (
          'months' => 
          array (
            'name' => 'months',
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
            'startLine' => 1583,
            'endLine' => 1583,
            'startColumn' => 34,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return list<string> the last N \'YYYY-MM\' month labels, oldest first
 */',
        'startLine' => 1583,
        'endLine' => 1593,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'monthCountSeries' => 
      array (
        'name' => 'monthCountSeries',
        'parameters' => 
        array (
          'base' => 
          array (
            'name' => 'base',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1601,
            'endLine' => 1601,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dateColumn' => 
          array (
            'name' => 'dateColumn',
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
            'startLine' => 1601,
            'endLine' => 1601,
            'startColumn' => 54,
            'endColumn' => 71,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'months' => 
          array (
            'name' => 'months',
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
            'startLine' => 1601,
            'endLine' => 1601,
            'startColumn' => 74,
            'endColumn' => 84,
            'parameterIndex' => 2,
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
 * Monthly row counts for a date column over the last N months.
 *
 * @param  Builder<covariant \\Illuminate\\Database\\Eloquent\\Model>  $base
 * @return array<string, int>
 */',
        'startLine' => 1601,
        'endLine' => 1613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmeGrowthSeries' => 
      array (
        'name' => 'programmeGrowthSeries',
        'parameters' => 
        array (
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
            'startLine' => 1620,
            'endLine' => 1620,
            'startColumn' => 44,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'months' => 
          array (
            'name' => 'months',
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
            'startLine' => 1620,
            'endLine' => 1620,
            'startColumn' => 67,
            'endColumn' => 77,
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
 * Programmes created per month over the last N months, within scope.
 *
 * @return array<string, int>
 */',
        'startLine' => 1620,
        'endLine' => 1637,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'programmeIdsInScope' => 
      array (
        'name' => 'programmeIdsInScope',
        'parameters' => 
        array (
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
            'startLine' => 1646,
            'endLine' => 1646,
            'startColumn' => 42,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The catalog programme ids in scope: partner = funded set; MDA = programmes it
 * runs (has activities for); state-wide = the whole catalog. A programme filter
 * intersects the result (never widens it); an MDA/area filter narrows the MDA set.
 *
 * @return list<string>
 */',
        'startLine' => 1646,
        'endLine' => 1666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'beneficiaryBase' => 
      array (
        'name' => 'beneficiaryBase',
        'parameters' => 
        array (
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
            'startLine' => 1676,
            'endLine' => 1676,
            'startColumn' => 38,
            'endColumn' => 58,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Beneficiaries in scope. For a partner this is the beneficiaries SERVED by their
 * funded programmes (via the ledger); otherwise the MDA-owned registry.
 *
 * @return Builder<Beneficiary>
 */',
        'startLine' => 1676,
        'endLine' => 1694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'applyBeneficiaryFilter' => 
      array (
        'name' => 'applyBeneficiaryFilter',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1703,
            'endLine' => 1703,
            'startColumn' => 45,
            'endColumn' => 58,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply the cross-cutting filter to a beneficiary query (registration date range,
 * area, owning MDA, and — for a programme filter — restrict to those served by it).
 *
 * @param  Builder<Beneficiary>  $query
 * @return Builder<Beneficiary>
 */',
        'startLine' => 1703,
        'endLine' => 1728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'applyActivityFilter' => 
      array (
        'name' => 'applyActivityFilter',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1736,
            'endLine' => 1736,
            'startColumn' => 42,
            'endColumn' => 55,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply the cross-cutting filter to an activity query (programme, owning MDA, area).
 *
 * @param  Builder<Activity>  $query
 * @return Builder<Activity>
 */',
        'startLine' => 1736,
        'endLine' => 1753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'applyBenefitFilter' => 
      array (
        'name' => 'applyBenefitFilter',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1762,
            'endLine' => 1762,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 1762,
                'endLine' => 1762,
                'startTokenPos' => 16478,
                'startFilePos' => 82416,
                'endTokenPos' => 16478,
                'endFilePos' => 82417,
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
            'startLine' => 1762,
            'endLine' => 1762,
            'startColumn' => 57,
            'endColumn' => 75,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply the cross-cutting filter to a benefit-ledger query (programme, delivering
 * MDA, area, delivery-date range). `$prefix` qualifies the columns for joined queries.
 *
 * @param  Builder<Benefit>  $query
 * @return Builder<Benefit>
 */',
        'startLine' => 1762,
        'endLine' => 1786,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'applyCoordinationFilter' => 
      array (
        'name' => 'applyCoordinationFilter',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1794,
            'endLine' => 1794,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fromCol' => 
          array (
            'name' => 'fromCol',
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
            'startLine' => 1794,
            'endLine' => 1794,
            'startColumn' => 62,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'toCol' => 
          array (
            'name' => 'toCol',
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
            'startLine' => 1794,
            'endLine' => 1794,
            'startColumn' => 79,
            'endColumn' => 91,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply the MDA + date parts of the filter to a coordination record query
 * (referrals / service requests) that has from/to MDA columns and a `created_at`.
 *
 * @param  Builder<covariant \\Illuminate\\Database\\Eloquent\\Model>  $query
 */',
        'startLine' => 1794,
        'endLine' => 1809,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'householdBase' => 
      array (
        'name' => 'householdBase',
        'parameters' => 
        array (
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
            'startLine' => 1816,
            'endLine' => 1816,
            'startColumn' => 36,
            'endColumn' => 56,
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
                  'name' => 'Illuminate\\Database\\Eloquent\\Builder',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Households in scope, or null for a partner (households are an owner concept).
 *
 * @return Builder<Household>|null
 */',
        'startLine' => 1816,
        'endLine' => 1846,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'aliasName' => NULL,
      ),
      'countBy' => 
      array (
        'name' => 'countBy',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1855,
            'endLine' => 1855,
            'startColumn' => 30,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
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
            'startLine' => 1855,
            'endLine' => 1855,
            'startColumn' => 46,
            'endColumn' => 59,
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
 * Grouped counts by a column (nulls bucketed as "unspecified"). Non-PII columns
 * only (status/source/lga/ward).
 *
 * @param  Builder<covariant \\Illuminate\\Database\\Eloquent\\Model>  $query
 * @return array<string, int>
 */',
        'startLine' => 1855,
        'endLine' => 1869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardMetricsService',
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