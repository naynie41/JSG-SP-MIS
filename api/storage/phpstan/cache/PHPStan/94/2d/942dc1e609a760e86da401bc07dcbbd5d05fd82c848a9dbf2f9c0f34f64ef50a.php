<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Scopes/ReferralScope.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Scopes\ReferralScope
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-be7944937d272bfb96a3c8e3578b88ed50ec8f487ba3d55af0e4149432a017ee',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Scopes\\ReferralScope',
        'filename' => '/var/www/html/app/Domain/Referral/Scopes/ReferralScope.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Scopes',
    'name' => 'App\\Domain\\Referral\\Scopes\\ReferralScope',
    'shortName' => 'ReferralScope',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Two-party MDA scoping for referrals (PRD FR-REF, FR-UAM-03). A referral is
 * visible when the user\'s accessible MDAs include EITHER side (`from_mda_id` or
 * `to_mda_id`) — so both the originating and receiving MDA see it. Mirrors
 * {@see MdaScope}; oversight (`cross-mda.view`) sees all.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 45,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Scope',
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
      'apply' => 
      array (
        'name' => 'apply',
        'parameters' => 
        array (
          'builder' => 
          array (
            'name' => 'builder',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 27,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 45,
            'endColumn' => 56,
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
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Scopes',
        'declaringClassName' => 'App\\Domain\\Referral\\Scopes\\ReferralScope',
        'implementingClassName' => 'App\\Domain\\Referral\\Scopes\\ReferralScope',
        'currentClassName' => 'App\\Domain\\Referral\\Scopes\\ReferralScope',
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