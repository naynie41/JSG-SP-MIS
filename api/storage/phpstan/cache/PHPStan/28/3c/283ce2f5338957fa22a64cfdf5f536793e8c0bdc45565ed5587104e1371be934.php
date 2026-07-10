<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ServiceRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ServiceRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-68d0c757d35761a76552ff59fe51789d44b2eb69a2b17a4a933611303f480883',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'filename' => '/var/www/html/app/Domain/Registry/Models/ServiceRequest.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
    'shortName' => 'ServiceRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A Service Request (PRD §12, FR-OWN-06): the requesting MDA (`from_mda_id`) asks
 * the owner MDA (`to_mda_id`) for permission to serve an existing beneficiary.
 * State machine: pending → accepted | declined. Ownership
 * (`beneficiaries.owner_mda_id`) is NEVER changed. Distinct from a Referral.
 *
 * Not globally MDA-scoped because it is a two-party record (requester + owner);
 * visibility is enforced by {@see OwnerMdaPolicy}
 * and the explicit inbox/outbox queries. Audited via Auditable — the decision
 * transitions are recorded explicitly (service_request.accepted/declined) so
 * status changes are excluded from the generic update audit.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property string|null $activity_id
 * @property ServiceRequestStatus $status
 * @property string|null $reason
 * @property string|null $import_row_id
 * @property string|null $requested_by
 * @property string|null $decided_by
 * @property Carbon|null $decided_at
 * @property string|null $decision_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $fromMda
 * @property-read Mda $toMda
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 46,
    'endLine' => 114,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'service_requests\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 81,
            'startFilePos' => 1795,
            'endTokenPos' => 81,
            'endFilePos' => 1812,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'from_mda_id\', \'to_mda_id\', \'activity_id\', \'status\', \'reason\', \'import_row_id\', \'requested_by\', \'decided_by\', \'decided_at\', \'decision_reason\']',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 67,
            'startTokenPos' => 92,
            'startFilePos' => 1883,
            'endTokenPos' => 127,
            'endFilePos' => 2138,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 6,
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
        'startLine' => 72,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
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
 * The decision transition is audited explicitly; keep it out of the generic
 * update audit to avoid a duplicate, less-meaningful entry.
 *
 * @return list<string>
 */',
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'aliasName' => NULL,
      ),
      'beneficiary' => 
      array (
        'name' => 'beneficiary',
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
 * @return BelongsTo<Beneficiary, $this>
 */',
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'aliasName' => NULL,
      ),
      'fromMda' => 
      array (
        'name' => 'fromMda',
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
 * @return BelongsTo<Mda, $this>
 */',
        'startLine' => 102,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'aliasName' => NULL,
      ),
      'toMda' => 
      array (
        'name' => 'toMda',
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
 * @return BelongsTo<Mda, $this>
 */',
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ServiceRequest',
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