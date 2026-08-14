<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Audit\Services\AuditQueryService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Audit\Services\AuditQueryService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-0d298634dab32a1067f64eeb593fb019ea086de1a7215ad709c6d4dab5765c24',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Audit/Services/AuditQueryService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Audit\\Services',
    'name' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
    'shortName' => 'AuditQueryService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * READ side of the immutable audit log (FR-AUD-01), for the administration console\'s
 * Audit & Security section. It only ever SELECTs — writing stays with
 * {@see AuditLogger} and the Auditable trait, so there is no second logging path.
 *
 * PII SAFETY: the projection never returns the `before`/`after` payloads. Those are
 * already scrubbed at write time (secrets redacted, PII masked — SECURITY.md §6), but
 * this surface goes further and exposes only the CHANGED FIELD NAMES, so a reviewer can
 * see what changed without any value ever reaching the client. The values remain in the
 * tamper-evident log for forensic access through the database/CLI.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 213,
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
      'CATEGORIES' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'name' => 'CATEGORIES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    // Authentication + account security (Phase 1).
    \'security\' => [\'auth.login\', \'auth.login_failed\', \'auth.logout\', \'auth.account_locked\', \'mfa.enrolled\', \'mfa.disabled\', \'mfa.challenge_failed\', \'user.mfa_reset\', \'user.password_reset_forced\'],
    // Permission / access-grant changes (Phase 1 + cross-MDA sharing).
    \'permission\' => [\'cross_mda.granted\', \'cross_mda.revoked\', \'beneficiary.access_granted\', \'beneficiary.access_revoked\', \'role.created\', \'role.updated\', \'role.deleted\'],
    // Request-to-serve decisions (Phase 3 ownership → Phase 5 coordination).
    \'service_request\' => [\'service_request.created\', \'service_request.accepted\', \'service_request.declined\', \'ownership_transfer.approved\', \'ownership_transfer.rejected\'],
    // Data access + egress (Phase 6 exports, document downloads).
    \'data_access\' => [\'beneficiary.exported\', \'beneficiary_document.downloaded\', \'dashboard.exported\', \'report.generated\', \'report.downloaded\', \'audit_log.exported\'],
]',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 57,
            'startTokenPos' => 63,
            'startFilePos' => 1248,
            'endTokenPos' => 190,
            'endFilePos' => 2441,
          ),
        ),
        'docComment' => '/**
 * Action taxonomy for the console\'s filters. Anything unlisted is "activity"
 * (ordinary create/update/delete on a domain entity).
 *
 * @var array<string, list<string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'DEFAULT_CATEGORY' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'name' => 'DEFAULT_CATEGORY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'activity\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 201,
            'startFilePos' => 2481,
            'endTokenPos' => 201,
            'endFilePos' => 2490,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'paginate' => 
      array (
        'name' => 'paginate',
        'parameters' => 
        array (
          'filters' => 
          array (
            'name' => 'filters',
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 30,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '25',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 223,
                'startFilePos' => 2784,
                'endTokenPos' => 223,
                'endFilePos' => 2785,
              ),
            ),
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 46,
            'endColumn' => 62,
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
            'name' => 'Illuminate\\Contracts\\Pagination\\LengthAwarePaginator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filtered, paginated audit entries, newest first.
 *
 * @param  array<string, mixed>  $filters  category, action, actor_id, entity_type, from, to, q
 * @return LengthAwarePaginator<int, AuditLog>
 */',
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'forExport' => 
      array (
        'name' => 'forExport',
        'parameters' => 
        array (
          'filters' => 
          array (
            'name' => 'filters',
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
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 31,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'limit' => 
          array (
            'name' => 'limit',
            'default' => 
            array (
              'code' => '5000',
              'attributes' => 
              array (
                'startLine' => 78,
                'endLine' => 78,
                'startTokenPos' => 267,
                'startFilePos' => 3101,
                'endTokenPos' => 267,
                'endFilePos' => 3104,
              ),
            ),
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
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 47,
            'endColumn' => 63,
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
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The same filtered set, capped, for export.
 *
 * @param  array<string, mixed>  $filters
 * @return Collection<int, AuditLog>
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'query' => 
      array (
        'name' => 'query',
        'parameters' => 
        array (
          'filters' => 
          array (
            'name' => 'filters',
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 28,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $filters
 * @return Builder<AuditLog>
 */',
        'startLine' => 87,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'present' => 
      array (
        'name' => 'present',
        'parameters' => 
        array (
          'entries' => 
          array (
            'name' => 'entries',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 29,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Project entries for the client: the envelope plus the NAMES of changed fields.
 * Values are deliberately absent (see the class docblock).
 *
 * @param  iterable<int, AuditLog>  $entries
 * @return list<array<string, mixed>>
 */',
        'startLine' => 135,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'categoryFor' => 
      array (
        'name' => 'categoryFor',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
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
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 33,
            'endColumn' => 46,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 169,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'knownActions' => 
      array (
        'name' => 'knownActions',
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
 * The distinct actions present in the log — powers the console\'s filter list
 * without hard-coding a taxonomy the data might outgrow.
 *
 * @return list<string>
 */',
        'startLine' => 186,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'changedFields' => 
      array (
        'name' => 'changedFields',
        'parameters' => 
        array (
          'entry' => 
          array (
            'name' => 'entry',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Audit\\Models\\AuditLog',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 36,
            'endColumn' => 50,
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
 * Names of the fields an entry touched, taken from the scrubbed snapshots.
 *
 * @return list<string>
 */',
        'startLine' => 196,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'aliasName' => NULL,
      ),
      'allCategorisedActions' => 
      array (
        'name' => 'allCategorisedActions',
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
        'startLine' => 209,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Services',
        'declaringClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'implementingClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
        'currentClassName' => 'App\\Domain\\Audit\\Services\\AuditQueryService',
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