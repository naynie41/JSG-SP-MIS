<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Models/ReferralSlaPolicy.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Models\ReferralSlaPolicy
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-89b50fd438ca63152c6c1931228fcf9ddc721d897999a0e2957b50c0ffdb23d4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'filename' => '/var/www/html/app/Domain/Referral/Models/ReferralSlaPolicy.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Models',
    'name' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
    'shortName' => 'ReferralSlaPolicy',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An admin-editable SLA window for a referral status (PRD FR-REF-04/05): how many
 * hours a referral may sit in `status` before it is overdue. Global config (not
 * MDA-scoped); changes are audited.
 *
 * @property string $id
 * @property string $status
 * @property int $hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 46,
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
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'referral_sla_policies\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 61,
            'startFilePos' => 692,
            'endTokenPos' => 61,
            'endFilePos' => 714,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\', \'hours\']',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 35,
            'startTokenPos' => 72,
            'startFilePos' => 785,
            'endTokenPos' => 80,
            'endFilePos' => 826,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 35,
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
        'startLine' => 40,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Referral\\Models',
        'declaringClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'implementingClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
        'currentClassName' => 'App\\Domain\\Referral\\Models\\ReferralSlaPolicy',
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