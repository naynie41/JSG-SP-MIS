<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Access\Services\RolePermissionService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Services\RolePermissionService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-cbcaa351d397a644c052d6c50a92d45fdaf664b255a2aec88d6af64a7283e8d7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Access/Services/RolePermissionService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Services',
    'name' => 'App\\Domain\\Access\\Services\\RolePermissionService',
    'shortName' => 'RolePermissionService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Writes the role → permission matrix (FR-UAM-05). It edits the EXISTING
 * `role_permission` pivot that {@see User::permissionKeys()} reads, so a change takes
 * effect on the next request — there is no second permission store to drift.
 *
 * Two invariants from docs/SECURITY.md are enforced here, not merely in the UI:
 *
 *  1. **The System Administrator role is not editable.** It holds every registered
 *     permission implicitly (RolesAndPermissionsSeeder); allowing an edit would let an
 *     administrator lock every administrator out of the console.
 *  2. **`export.reveal_pii` is never bundled into a role.** Unmasking NIN/BVN stays a
 *     System-Administrator-only capability; granting it to a role is a Data Protection
 *     Officer decision under NDPA/NDPR, not a console toggle.
 *
 * Grants that SECURITY.md flags as DPO-reviewable (`beneficiary.export` to a junior
 * role) are permitted but recorded distinctly in the audit entry, so a periodic review
 * can find them.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 33,
    'endLine' => 105,
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
      'NEVER_ROLE_GRANTABLE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'name' => 'NEVER_ROLE_GRANTABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Registry\\Export\\BeneficiaryListExport::REVEAL_PERMISSION]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 68,
            'startFilePos' => 1488,
            'endTokenPos' => 72,
            'endFilePos' => 1529,
          ),
        ),
        'docComment' => '/** Never assignable to any role — see docs/SECURITY.md §3. */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 83,
      ),
      'SENSITIVE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'name' => 'SENSITIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'beneficiary.export\', \'beneficiary.access_request\', \'cross-mda.view\']',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 85,
            'startFilePos' => 1653,
            'endTokenPos' => 93,
            'endFilePos' => 1722,
          ),
        ),
        'docComment' => '/** Grants that carry a DPO/sign-off obligation; allowed, but flagged in the audit. */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 100,
      ),
    ),
    'immediateProperties' => 
    array (
      'audit' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
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
        'startLine' => 41,
        'endLine' => 41,
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
            'startLine' => 41,
            'endLine' => 41,
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
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'currentClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'aliasName' => NULL,
      ),
      'isEditable' => 
      array (
        'name' => 'isEditable',
        'parameters' => 
        array (
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\Role',
                'isIdentifier' => false,
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
            'startColumn' => 32,
            'endColumn' => 41,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Whether this role\'s permissions may be edited at all. */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'currentClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'aliasName' => NULL,
      ),
      'sync' => 
      array (
        'name' => 'sync',
        'parameters' => 
        array (
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\Role',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 26,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keys' => 
          array (
            'name' => 'keys',
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 38,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'actor' => 
          array (
            'name' => 'actor',
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 51,
            'endColumn' => 61,
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
            'name' => 'App\\Domain\\Access\\Models\\Role',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replace a role\'s permission set with `$keys`.
 *
 * @param  list<string>  $keys
 *
 * @throws RuntimeException when the role is locked or a key is not grantable
 */',
        'startLine' => 56,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Services',
        'declaringClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'implementingClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
        'currentClassName' => 'App\\Domain\\Access\\Services\\RolePermissionService',
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