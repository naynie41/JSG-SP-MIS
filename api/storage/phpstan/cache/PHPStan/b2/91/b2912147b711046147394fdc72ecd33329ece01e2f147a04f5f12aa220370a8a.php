<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Authorization/ReferralAuthorizer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Authorization\ReferralAuthorizer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b02050e5b9bd3ffd30b9117d2a0d4b242f5ccfee4fdbf6f6ebd490ef690a8472',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'filename' => '/var/www/html/app/Domain/Referral/Authorization/ReferralAuthorizer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Authorization',
    'name' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
    'shortName' => 'ReferralAuthorizer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Authorizes delivery when the MDA holds an accepted Referral for the beneficiary
 * (PRD FR-REF-02, FR-BEN-06). This is DISTINCT from the request-to-serve Service
 * Request — it consults the `referrals` table only, never a BeneficiaryServiceGrant,
 * so the two authorization paths stay separate. Ownership is never involved (the
 * owner MDA consented by referring).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
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
      'authorizes' => 
      array (
        'name' => 'authorizes',
        'parameters' => 
        array (
          'mdaId' => 
          array (
            'name' => 'mdaId',
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
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 32,
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
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 47,
            'endColumn' => 70,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Authorization',
        'declaringClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'implementingClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'currentClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'aliasName' => NULL,
      ),
      'source' => 
      array (
        'name' => 'source',
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
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Authorization',
        'declaringClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'implementingClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
        'currentClassName' => 'App\\Domain\\Referral\\Authorization\\ReferralAuthorizer',
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