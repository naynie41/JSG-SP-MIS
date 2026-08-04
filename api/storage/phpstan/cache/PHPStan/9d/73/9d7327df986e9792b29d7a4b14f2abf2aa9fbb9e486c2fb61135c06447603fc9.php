<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\DashboardScopeResolver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\DashboardScopeResolver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c4cbb84397a27f4e03d30da91c3375d1acc98302e92bf4b18d9dd09821e2e9bc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\DashboardScopeResolver',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/DashboardScopeResolver.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\DashboardScopeResolver',
    'shortName' => 'DashboardScopeResolver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Resolves the {@see DashboardScope} for a user (PRD FR-DSH-01):
 *
 *  - oversight (`cross-mda.view`) → state-wide;
 *  - Development Partner → their FUNDED programmes only (Phase 6P);
 *  - any other MDA user → their accessible MDAs (own + active cross-MDA grants).
 *
 * The partner branch is checked before the MDA fallback; partners never hold
 * `cross-mda.view`, so oversight and partner are mutually exclusive.
 *
 * A partner\'s funded scope is derived from `activities.funding_partner_id` (the
 * queryable attribution, Phase 6P) — the distinct programmes of the activities they
 * fund — so scope, budget and delivery always agree, and a partner sees ONLY their
 * funded data (SECURITY.md — Development Partner: funded programmes only).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 51,
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
    ),
    'immediateMethods' => 
    array (
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 29,
            'endColumn' => 38,
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
            'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\DashboardScopeResolver',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\DashboardScopeResolver',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\DashboardScopeResolver',
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