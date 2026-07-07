<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Models/Referral.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Models\Referral
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c75c3d6a23222975227fc88bf5a976082750a044708e5129b6268380babfb429',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Models\\Referral',
        'filename' => '/var/www/html/app/Domain/Referral/Models/Referral.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Models',
    'name' => 'App\\Domain\\Referral\\Models\\Referral',
    'shortName' => 'Referral',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A referral of a beneficiary from one MDA to another for an identified need
 * (PRD FR-REF-01/02/04, §8.2). Two-party visible (see {@see ReferralScope}) and
 * Auditable; the transition columns are excluded from the generic update audit
 * because each transition is recorded explicitly (referral.accepted, …).
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property string $need
 * @property string|null $notes
 * @property ReferralStatus $status
 * @property string|null $outcome
 * @property string|null $reason
 * @property string|null $info_request
 * @property string|null $info_response
 * @property string|null $created_by
 * @property Carbon|null $accepted_at
 * @property Carbon|null $rejected_at
 * @property Carbon|null $info_requested_at
 * @property Carbon|null $info_responded_at
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $fromMda
 * @property-read Mda $toMda
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 48,
    'endLine' => 137,
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
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'referrals\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 86,
            'startFilePos' => 1726,
            'endTokenPos' => 86,
            'endFilePos' => 1736,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'from_mda_id\', \'to_mda_id\', \'need\', \'notes\', \'status\', \'outcome\', \'reason\', \'info_request\', \'info_response\', \'created_by\', \'accepted_at\', \'rejected_at\', \'info_requested_at\', \'info_responded_at\', \'started_at\', \'completed_at\', \'closed_at\']',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 76,
            'startTokenPos' => 97,
            'startFilePos' => 1807,
            'endTokenPos' => 153,
            'endFilePos' => 2213,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 76,
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
        'docComment' => NULL,
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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
        'startLine' => 86,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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
 * Transitions are audited explicitly; keep them out of the generic update audit.
 *
 * @return list<string>
 */',
        'startLine' => 105,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\Referral',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\Referral',
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