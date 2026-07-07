<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Events/ReferralSlaBreached.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Events\ReferralSlaBreached
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-518592a26bf11981637361d65fe98f69bf2906105dc1d205e1111f65edad920a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'filename' => '/var/www/html/app/Domain/Referral/Events/ReferralSlaBreached.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Events',
    'name' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
    'shortName' => 'ReferralSlaBreached',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Fired when a referral is overdue against its status SLA (PRD FR-REF-04/05). The
 * scheduled job escalates (bumping `escalation_level`) and dispatches this at each
 * new level; the notification subscriber alerts both MDAs + the escalation tier.
 * Nothing is auto-closed.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 24,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Events\\Dispatchable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'referral' => 
      array (
        'declaringClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'implementingClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'name' => 'referral',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Referral\\Models\\Referral',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 9,
        'endColumn' => 42,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'level' => 
      array (
        'declaringClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'implementingClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'name' => 'level',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 9,
        'endColumn' => 34,
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
          'referral' => 
          array (
            'name' => 'referral',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Referral\\Models\\Referral',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 9,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Events',
        'declaringClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'implementingClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
        'currentClassName' => 'App\\Domain\\Referral\\Events\\ReferralSlaBreached',
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