<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\AdminOrganizationService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\AdminOrganizationService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-fbbc683ac0238fc6a9469de724693eac55ded042b0716c6619933678de9e1c40',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/AdminOrganizationService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
    'shortName' => 'AdminOrganizationService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * ORGANIZATION roll-up for the administration console (FR-UAM-01, FR-PRG-05).
 *
 * A read-only aggregate over data that already exists: MDAs (Phase 1), the users
 * allocated to them (Phase 1), and the activities they own (Phase 4). It answers the
 * questions the console\'s Organization section asks — how many users and MDA
 * administrators each organization has, and how much programme delivery it runs —
 * WITHOUT duplicating any organization logic: creating, editing, activating and
 * deactivating an MDA all remain the existing `/mdas` endpoints and policies.
 *
 * Development Partners are users holding the Development Partner role; their footprint
 * is the activities they FUND (`activities.funding_partner_id`, Phase 6P).
 *
 * Counts only — no PII beyond the staff names/emails an administrator already manages.
 * Every query bypasses the MDA scope EXPLICITLY: the administrator\'s remit is platform-wide.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 33,
    'endLine' => 126,
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
        'startLine' => 38,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'aliasName' => NULL,
      ),
      'partners' => 
      array (
        'name' => 'partners',
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
 * Development Partners with the delivery they fund (Phase 6P attribution).
 *
 * @return array<int, array<string, mixed>>
 */',
        'startLine' => 91,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
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