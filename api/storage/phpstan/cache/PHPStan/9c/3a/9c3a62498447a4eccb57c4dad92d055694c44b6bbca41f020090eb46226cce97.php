<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Audit/Concerns/Auditable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Audit\Concerns\Auditable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'filename' => '/var/www/html/app/Domain/Audit/Concerns/Auditable.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Audit\\Concerns',
    'name' => 'App\\Domain\\Audit\\Concerns\\Auditable',
    'shortName' => 'Auditable',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Records create/update/delete of a model to the audit log (FR-AUD-01) with
 * scrubbed before/after snapshots. Add this trait to any model that should be
 * audited — future modules get consistent auditing for free.
 *
 * Override auditExcluded() for operational columns that should not generate
 * audit noise (e.g. last_login_at). Secrets/PII are handled globally by the
 * AuditScrubber (config/audit.php).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 136,
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
      'bootAuditable' => 
      array (
        'name' => 'bootAuditable',
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
        'startLine' => 22,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
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
 * Columns that never produce an audit entry on change.
 *
 * @return list<string>
 */',
        'startLine' => 55,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'auditEntityName' => 
      array (
        'name' => 'auditEntityName',
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
 * The entity name used in the action and entity_type (e.g. "user").
 */',
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'auditOmit' => 
      array (
        'name' => 'auditOmit',
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
 * Model-specific secret fields to redact (merged with the global list).
 *
 * @return list<string>
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
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
 * Model-specific PII fields to mask (merged with the global list).
 *
 * @return list<string>
 */',
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'scrubAttributes' => 
      array (
        'name' => 'scrubAttributes',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 37,
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
 * @param  array<string, mixed>  $attributes
 * @return array<string, mixed>
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'allAuditExcluded' => 
      array (
        'name' => 'allAuditExcluded',
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
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'auditSnapshot' => 
      array (
        'name' => 'auditSnapshot',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 36,
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
 * Filter out excluded columns, then scrub secrets/PII.
 *
 * @param  array<string, mixed>  $attributes
 * @return array<string, mixed>
 */',
        'startLine' => 111,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'aliasName' => NULL,
      ),
      'writeAudit' => 
      array (
        'name' => 'writeAudit',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
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
            'startLine' => 127,
            'endLine' => 127,
            'startColumn' => 33,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'before' => 
          array (
            'name' => 'before',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 127,
            'endLine' => 127,
            'startColumn' => 48,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'after' => 
          array (
            'name' => 'after',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 127,
            'endLine' => 127,
            'startColumn' => 64,
            'endColumn' => 76,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>|null  $before
 * @param  array<string, mixed>|null  $after
 */',
        'startLine' => 127,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Concerns',
        'declaringClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'implementingClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
        'currentClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
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