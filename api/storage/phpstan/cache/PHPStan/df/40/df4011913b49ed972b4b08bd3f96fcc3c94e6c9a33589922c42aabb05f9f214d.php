<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\AdminSummaryService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\AdminSummaryService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1e8a1f648370d8c57357805e89a12fbb67e5a4e7668492183670d3b99fa14621',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/AdminSummaryService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
    'shortName' => 'AdminSummaryService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * GOVERNANCE aggregates for the System Administrator console (FR-UAM-01, FR-AUD-01).
 *
 * This is the administration read-model: who is provisioned, what is configured, and
 * what has recently been changed — deliberately NOT system health. Backup age, queue
 * depth, snapshot freshness, CPU/memory and other infrastructure telemetry stay out of
 * this console (they are an ops/CLI concern served by `/health/metrics`).
 *
 * Every figure is a COUNT over data the administrator may already see; no beneficiary
 * PII is read or returned, and audit `before`/`after` payloads are never exposed —
 * recent activity carries only actor, action, entity type and timestamp (SECURITY.md
 * §6: audit payloads may contain sensitive values).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 287,
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
      'TREND_MONTHS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'name' => 'TREND_MONTHS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '12',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 108,
            'startFilePos' => 1537,
            'endTokenPos' => 108,
            'endFilePos' => 1538,
          ),
        ),
        'docComment' => '/** Months of user-adoption history to return. */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'RECENT_LIMIT' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'name' => 'RECENT_LIMIT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '10',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 121,
            'startFilePos' => 1636,
            'endTokenPos' => 121,
            'endFilePos' => 1637,
          ),
        ),
        'docComment' => '/** How many recent administrative events to surface. */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'build' => 
      array (
        'name' => 'build',
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
 * @return array<string, mixed>
 */',
        'startLine' => 47,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'aliasName' => NULL,
      ),
      'kpis' => 
      array (
        'name' => 'kpis',
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
 * Provisioning + catalog counts. Global by design: the System Administrator\'s remit
 * is the whole platform, so the MDA scope is bypassed EXPLICITLY (never implicitly).
 *
 * @return array<string, int>
 */',
        'startLine' => 68,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'aliasName' => NULL,
      ),
      'adoptionTrend' => 
      array (
        'name' => 'adoptionTrend',
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
 * USER ADOPTION over the last N months: accounts created per month plus the running
 * total, so the console can show uptake rather than a raw headcount.
 *
 * @return array<int, array{month: string, new_users: int, total_users: int}>
 */',
        'startLine' => 101,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'aliasName' => NULL,
      ),
      'registrySnapshot' => 
      array (
        'name' => 'registrySnapshot',
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
 * REGISTRY & DATA QUALITY snapshot: import throughput, row-level validation, and
 * duplicate resolution. Counts only — no row payloads, no PII.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 132,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'aliasName' => NULL,
      ),
      'alerts' => 
      array (
        'name' => 'alerts',
        'parameters' => 
        array (
          'kpis' => 
          array (
            'name' => 'kpis',
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
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 29,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'registry' => 
          array (
            'name' => 'registry',
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
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 42,
            'endColumn' => 56,
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
 * ADMINISTRATIVE alerts — governance conditions an administrator must act on
 * (provisioning, configuration, data quality). Never infrastructure warnings.
 *
 * @param  array<string, int>  $kpis
 * @param  array<string, mixed>  $registry
 * @return array<int, array<string, string>>
 */',
        'startLine' => 175,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'aliasName' => NULL,
      ),
      'recentActivity' => 
      array (
        'name' => 'recentActivity',
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
 * RECENT ADMINISTRATIVE ACTIVITY from the append-only audit log. Only the envelope
 * is exposed — actor, action, entity type and time. The `before`/`after` payloads
 * are deliberately NOT returned: they may carry sensitive values (SECURITY.md §6).
 *
 * @return array<int, array<string, string|null>>
 */',
        'startLine' => 244,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
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
            'startLine' => 276,
            'endLine' => 276,
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
        'startLine' => 276,
        'endLine' => 286,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminSummaryService',
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