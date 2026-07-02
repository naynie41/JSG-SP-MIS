<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Services/HouseholdMembershipService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Services\HouseholdMembershipService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d2c9965d76df8330a34adb742b6f0416d1d4ee396b7b8bfaa8b3bbb78cca6364',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'filename' => '/var/www/html/app/Domain/Registry/Services/HouseholdMembershipService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Services',
    'name' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
    'shortName' => 'HouseholdMembershipService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Household membership lifecycle (PRD FR-REG-01, §9 history decision).
 *
 * Memberships are never deleted — a beneficiary\'s household history is preserved
 * by closing (`left_at`) the current open membership and opening a new one. The
 * single-open-membership rule is enforced here (the partial unique index is the
 * DB backstop); every change is audited at the household level.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 160,
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
      'audit' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
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
        'startLine' => 27,
        'endLine' => 27,
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
            'startLine' => 27,
            'endLine' => 27,
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'household' => 
          array (
            'name' => 'household',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Household',
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
            'startColumn' => 25,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 47,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Enums\\HouseholdRole',
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
            'startColumn' => 73,
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
            'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Add a beneficiary to a household as a new open membership. */',
        'startLine' => 30,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'move' => 
      array (
        'name' => 'move',
        'parameters' => 
        array (
          'toHousehold' => 
          array (
            'name' => 'toHousehold',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Household',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 26,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 50,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Enums\\HouseholdRole',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 76,
            'endColumn' => 94,
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
            'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Move a beneficiary into a household: close the current open membership
 * (wherever it is) and open a new one — full history retained.
 */',
        'startLine' => 53,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'remove' => 
      array (
        'name' => 'remove',
        'parameters' => 
        array (
          'household' => 
          array (
            'name' => 'household',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Household',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 28,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 50,
            'endColumn' => 73,
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
            'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Remove a beneficiary from a household by closing their open membership. */',
        'startLine' => 85,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'designateHead' => 
      array (
        'name' => 'designateHead',
        'parameters' => 
        array (
          'household' => 
          array (
            'name' => 'household',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Household',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 35,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 57,
            'endColumn' => 80,
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
 * Designate/replace the household head. The head must be a current open member;
 * their membership role is set to Head.
 */',
        'startLine' => 112,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'openMembership' => 
      array (
        'name' => 'openMembership',
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
            'startLine' => 132,
            'endLine' => 132,
            'startColumn' => 37,
            'endColumn' => 60,
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
                  'name' => 'App\\Domain\\Registry\\Models\\HouseholdMembership',
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
        'docComment' => NULL,
        'startLine' => 132,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'assertNoOpenMembership' => 
      array (
        'name' => 'assertNoOpenMembership',
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
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 45,
            'endColumn' => 68,
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
        'startLine' => 140,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'aliasName' => NULL,
      ),
      'clearHeadIfMember' => 
      array (
        'name' => 'clearHeadIfMember',
        'parameters' => 
        array (
          'householdId' => 
          array (
            'name' => 'householdId',
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
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 40,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'beneficiaryId' => 
          array (
            'name' => 'beneficiaryId',
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
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 62,
            'endColumn' => 82,
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
        'docComment' => '/** If the departing beneficiary headed their old household, vacate that role. */',
        'startLine' => 148,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Services',
        'declaringClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'implementingClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
        'currentClassName' => 'App\\Domain\\Registry\\Services\\HouseholdMembershipService',
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