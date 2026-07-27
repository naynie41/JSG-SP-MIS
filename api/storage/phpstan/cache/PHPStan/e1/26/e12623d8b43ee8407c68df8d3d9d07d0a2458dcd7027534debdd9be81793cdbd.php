<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Access\Models\User.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Access\Models\User
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-fff53d6fcd4c37c7b9be357e0b475702957b85cf3233aed363cd267e7fda02c6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Access\\Models\\User',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Access/Models/User.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Access\\Models',
    'name' => 'App\\Domain\\Access\\Models\\User',
    'shortName' => 'User',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $mda_id
 * @property string|null $role_id
 * @property UserStatus $status
 * @property string|null $mfa_secret
 * @property list<string>|null $mfa_recovery_codes
 * @property bool $mfa_enabled
 * @property int $failed_login_attempts
 * @property Carbon|null $locked_until
 * @property Carbon|null $last_login_at
 * @property-read Mda|null $mda
 * @property-read Role|null $role
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 40,
    'endLine' => 341,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Auth\\User',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Laravel\\Sanctum\\HasApiTokens',
      2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      4 => 'Illuminate\\Notifications\\Notifiable',
      5 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
      6 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'email\', \'password\', \'mda_id\', \'role_id\', \'status\', \'mfa_enabled\']',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 98,
            'startTokenPos' => 229,
            'startFilePos' => 2661,
            'endTokenPos' => 252,
            'endFilePos' => 2798,
          ),
        ),
        'docComment' => '/**
 * Mass-assignable attributes. The MFA secret and lockout counters are
 * intentionally excluded — they are managed by application logic, never
 * by mass assignment (SECURITY.md).
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Access\\Enums\\UserStatus::Active->value, \'mfa_enabled\' => false, \'failed_login_attempts\' => 0]',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 110,
            'startTokenPos' => 263,
            'startFilePos' => 3009,
            'endTokenPos' => 290,
            'endFilePos' => 3132,
          ),
        ),
        'docComment' => '/**
 * Model-level defaults mirroring the database defaults so freshly
 * instantiated models match what is persisted.
 *
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'password\', \'remember_token\', \'mfa_secret\']',
          'attributes' => 
          array (
            'startLine' => 117,
            'endLine' => 121,
            'startTokenPos' => 301,
            'startFilePos' => 3286,
            'endTokenPos' => 312,
            'endFilePos' => 3360,
          ),
        ),
        'docComment' => '/**
 * Attributes hidden from serialization. The MFA secret is never exposed.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 117,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'permissionKeysCache' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'name' => 'permissionKeysCache',
        'modifiers' => 4,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 256,
            'endLine' => 256,
            'startTokenPos' => 941,
            'startFilePos' => 7515,
            'endTokenPos' => 941,
            'endFilePos' => 7518,
          ),
        ),
        'docComment' => '/**
 * Cached list of permission keys for this instance (the Gate resolves every
 * ability check through here, so avoid reloading the role each time).
 *
 * @var list<string>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 256,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'accessibleMdaIdsCache' => 
      array (
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'name' => 'accessibleMdaIdsCache',
        'modifiers' => 4,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 291,
            'endLine' => 291,
            'startTokenPos' => 1068,
            'startFilePos' => 8505,
            'endTokenPos' => 1068,
            'endFilePos' => 8508,
          ),
        ),
        'docComment' => '/**
 * Cached list of MDA ids this user may access: their own MDA plus any MDA
 * with an active (non-expired) cross-MDA grant.
 *
 * @var list<string>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 291,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 49,
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
      'mdaOwnershipColumn' => 
      array (
        'name' => 'mdaOwnershipColumn',
        'parameters' => 
        array (
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
 * Users are MDA-scoped on their `mda_id` column (not the Phase 2 default).
 */',
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'auditExcluded' => 
      array (
        'name' => 'auditExcluded',
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
 * Operational/auth-state columns excluded from audit (changes to only these
 * produce no audit entry). The semantic auth events (login, lockout, MFA)
 * are audited explicitly instead.
 *
 * @return list<string>
 */',
        'startLine' => 60,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'auditMask' => 
      array (
        'name' => 'auditMask',
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
 * A user\'s full name is PII and is masked in audit snapshots (SECURITY.md).
 *
 * @return list<string>
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'casts' => 
      array (
        'name' => 'casts',
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
 * @return array<string, string>
 */',
        'startLine' => 126,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'mfaRequired' => 
      array (
        'name' => 'mfaRequired',
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
        'docComment' => '/**
 * Whether MFA is mandatory for this user (driven by the role, FR-UAM-04).
 *
 * A local/dev testing escape hatch (config `security.mfa.enforce`, env
 * `MFA_ENFORCE`) can disable this — but ONLY outside production. In production
 * the control is always on regardless of the flag, so it can never be weakened
 * (SECURITY.md §2/§12).
 */',
        'startLine' => 149,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'isLocked' => 
      array (
        'name' => 'isLocked',
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
        'docComment' => '/**
 * Whether the account is currently locked out (FR-UAM-06).
 */',
        'startLine' => 161,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'registerFailedAttempt' => 
      array (
        'name' => 'registerFailedAttempt',
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
        'docComment' => '/**
 * Record a failed login/MFA attempt and apply lockout with exponential
 * backoff once the configured threshold is reached. Returns true if this
 * attempt caused (or extended) a lock.
 */',
        'startLine' => 171,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'clearLockout' => 
      array (
        'name' => 'clearLockout',
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
        'docComment' => '/**
 * Clear lockout state after a successful authentication.
 */',
        'startLine' => 196,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'consumeRecoveryCode' => 
      array (
        'name' => 'consumeRecoveryCode',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
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
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 41,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Consume a one-time recovery code if it matches. Returns true and removes
 * the code on success; false otherwise (constant-time comparison).
 */',
        'startLine' => 212,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'mda' => 
      array (
        'name' => 'mda',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The MDA this user belongs to (nullable for non-MDA-bound roles).
 *
 * @return BelongsTo<Mda, $this>
 */',
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'role' => 
      array (
        'name' => 'role',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The user\'s role (single role per user, PRD FR-UAM-02). Permissions are
 * resolved through the role; authorization always checks permissions.
 *
 * @return BelongsTo<Role, $this>
 */',
        'startLine' => 245,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'permissionKeys' => 
      array (
        'name' => 'permissionKeys',
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
 * The permission keys granted to this user via their role.
 *
 * @return list<string>
 */',
        'startLine' => 263,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'hasPermission' => 
      array (
        'name' => 'hasPermission',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
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
            'startLine' => 271,
            'endLine' => 271,
            'startColumn' => 35,
            'endColumn' => 45,
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
        'docComment' => '/**
 * Whether the user has a given `module.action` permission.
 */',
        'startLine' => 271,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'canAccessAllMdas' => 
      array (
        'name' => 'canAccessAllMdas',
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
        'docComment' => '/**
 * Whether the user may see data across all MDAs (FR-UAM-03 bypass). Granted
 * by the `cross-mda.view` permission to oversight roles.
 */',
        'startLine' => 280,
        'endLine' => 283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'accessibleMdaIds' => 
      array (
        'name' => 'accessibleMdaIds',
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
 * @return list<string>
 */',
        'startLine' => 296,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'mdaAccessGrants' => 
      array (
        'name' => 'mdaAccessGrants',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Explicit grants giving this user access to MDAs other than their own.
 *
 * @return HasMany<MdaAccessGrant, $this>
 */',
        'startLine' => 319,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Database\\Factories\\UserFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 324,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
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
        'docComment' => '/**
 * Invalidate all access tokens whenever the password changes
 * (SECURITY.md §2: invalidate tokens on password change).
 */',
        'startLine' => 333,
        'endLine' => 340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Access\\Models',
        'declaringClassName' => 'App\\Domain\\Access\\Models\\User',
        'implementingClassName' => 'App\\Domain\\Access\\Models\\User',
        'currentClassName' => 'App\\Domain\\Access\\Models\\User',
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