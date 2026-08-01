<?php declare(strict_types = 1);

// osfsl-C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/database/seeders/RolesAndPermissionsSeeder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Database\Seeders\RolesAndPermissionsSeeder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e8a497046dd21291ebf3d36af1a21378171fd960fce6fec1b37e9918532d20a8-8.3.31-6.70.0.1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/database/seeders/RolesAndPermissionsSeeder.php',
      ),
    ),
    'namespace' => 'Database\\Seeders',
    'name' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
    'shortName' => 'RolesAndPermissionsSeeder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Persists the registered module permissions (via PermissionSynchronizer), seeds
 * the seven predefined roles (PRD FR-UAM-01), and assigns each role its
 * permission bundle. Idempotent: safe to re-run.
 *
 * Permissions themselves are declared by each module in its service provider
 * (see PermissionRegistry); this seeder only owns the role → permission mapping.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 129,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Seeder',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ROLE_PERMISSIONS' => 
      array (
        'declaringClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'implementingClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'name' => 'ROLE_PERMISSIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Access\\Enums\\RoleKey::SpCoordination->value => [
    \'cross-mda.view\',
    \'mda.view\',
    \'user.view\',
    \'role.view\',
    \'permission.view\',
    \'beneficiary.view\',
    \'beneficiary.export\',
    \'beneficiary-lookup.view\',
    // Programme catalog is administered centrally (§10) — SP Coordination
    // co-administers it alongside the System Administrator.
    \'programme.view\',
    \'programme.create\',
    \'programme.edit\',
    \'activity.view\',
    \'enrollment.view\',
    \'benefit.view\',
    \'double-dipping.view\',
    \'double-dipping.edit\',
    \'referral.view\',
    \'referral-sla.edit\',
    \'grievance.view\',
    \'grievance-sla.edit\',
    \'graduation.view\',
    \'dashboard.view\',
    \'reporting.view\',
    \'reporting.export\',
], \\App\\Domain\\Access\\Enums\\RoleKey::MneOfficer->value => [\'cross-mda.view\', \'mda.view\', \'user.view\', \'beneficiary.view\', \'beneficiary.export\', \'beneficiary-lookup.view\', \'programme.view\', \'activity.view\', \'enrollment.view\', \'benefit.view\', \'referral.view\', \'grievance.view\', \'graduation.view\', \'dashboard.view\', \'reporting.view\', \'reporting.export\'], \\App\\Domain\\Access\\Enums\\RoleKey::MdaAdmin->value => [
    \'mda.view\',
    \'user.view\',
    \'user.create\',
    \'user.edit\',
    \'role.view\',
    // MDA Admin may export beneficiary data — scoped to their own MDA
    // (no cross-mda.view). SECURITY.md — Export of beneficiary data.
    \'beneficiary.view\',
    \'beneficiary.create\',
    \'beneficiary.edit\',
    \'beneficiary.approve\',
    \'beneficiary.export\',
    // Right-of-access (DSAR): the owner MDA is the data controller (NFR-PRV-01).
    \'beneficiary.access_request\',
    \'beneficiary-lookup.view\',
    \'household.view\',
    \'household.create\',
    \'household.edit\',
    // Programmes are a global catalog (§10) — MDAs read but never create/edit them;
    // they run programmes through their own MDA-owned activities.
    \'programme.view\',
    \'activity.view\',
    \'activity.create\',
    \'activity.edit\',
    \'enrollment.view\',
    \'enrollment.create\',
    \'enrollment.edit\',
    \'benefit.view\',
    \'benefit.create\',
    \'benefit.approve\',
    \'referral.view\',
    \'referral.create\',
    \'referral.edit\',
    \'grievance.view\',
    \'grievance.create\',
    \'grievance.edit\',
    \'graduation.view\',
    \'graduation.edit\',
    \'dashboard.view\',
    \'reporting.view\',
    \'reporting.export\',
], \\App\\Domain\\Access\\Enums\\RoleKey::MdaOfficer->value => [\'mda.view\', \'user.view\', \'beneficiary.view\', \'beneficiary.create\', \'beneficiary.edit\', \'beneficiary-lookup.view\', \'household.view\', \'household.create\', \'household.edit\', \'programme.view\', \'activity.view\', \'activity.create\', \'activity.edit\', \'enrollment.view\', \'enrollment.create\', \'enrollment.edit\', \'benefit.view\', \'benefit.create\', \'benefit.approve\', \'referral.view\', \'referral.create\', \'referral.edit\', \'grievance.view\', \'grievance.create\', \'grievance.edit\', \'graduation.view\', \'graduation.edit\', \'dashboard.view\', \'reporting.view\', \'reporting.export\'], \\App\\Domain\\Access\\Enums\\RoleKey::DevelopmentPartner->value => [\'mda.view\', \'beneficiary.view\', \'programme.view\', \'activity.view\', \'enrollment.view\', \'benefit.view\', \'dashboard.view\', \'reporting.view\', \'reporting.export\'], \\App\\Domain\\Access\\Enums\\RoleKey::Executive->value => [
    // Executive sees state-wide AGGREGATES only — no beneficiary-data export.
    // (Aggregate reporting export lives under reporting.export.) SECURITY.md.
    \'cross-mda.view\',
    \'mda.view\',
    \'user.view\',
    \'beneficiary.view\',
    \'beneficiary-lookup.view\',
    \'programme.view\',
    \'activity.view\',
    \'enrollment.view\',
    \'benefit.view\',
    \'referral.view\',
    \'grievance.view\',
    \'graduation.view\',
    \'dashboard.view\',
    \'reporting.view\',
    \'reporting.export\',
]]',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 89,
            'startTokenPos' => 62,
            'startFilePos' => 925,
            'endTokenPos' => 550,
            'endFilePos' => 4854,
          ),
        ),
        'docComment' => '/**
 * Role => permission keys. System Administrator implicitly receives every
 * registered permission and is handled separately.
 *
 * @var array<string, list<string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'run' => 
      array (
        'name' => 'run',
        'parameters' => 
        array (
          'synchronizer' => 
          array (
            'name' => 'synchronizer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Services\\PermissionSynchronizer',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 25,
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
        'docComment' => NULL,
        'startLine' => 91,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Database\\Seeders',
        'declaringClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'implementingClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'currentClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'aliasName' => NULL,
      ),
      'seedRoles' => 
      array (
        'name' => 'seedRoles',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 98,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Database\\Seeders',
        'declaringClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'implementingClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
        'currentClassName' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
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