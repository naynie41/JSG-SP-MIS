<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Authorization/DeliveryAuthorizer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Authorization\DeliveryAuthorizer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-4a323f20eb1f1baa8c6d1dc1d3614f4c360a6720079da404dce67c48584ce0ae',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
        'filename' => '/var/www/html/app/Domain/Benefit/Authorization/DeliveryAuthorizer.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Authorization',
    'name' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
    'shortName' => 'DeliveryAuthorizer',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A single kind of authorization that lets a NON-OWNER MDA record an intervention
 * for a beneficiary (PRD FR-BEN-06). Each concrete authorizer represents one
 * explicit, accepted authorization type — an accepted Service Request
 * (request-to-serve, R2.3) now; an accepted Referral to the MDA (Phase 5) when it
 * ships. This is deliberately NOT a generic serve seam: only these explicit,
 * per-beneficiary authorizations count.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 24,
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
        'docComment' => '/** Whether this authorization currently entitles the MDA to deliver to the beneficiary. */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Authorization',
        'declaringClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
        'implementingClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
        'currentClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
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
        'docComment' => '/** Short identifier of the authorization type, recorded in the audit trail. */',
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Authorization',
        'declaringClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
        'implementingClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
        'currentClassName' => 'App\\Domain\\Benefit\\Authorization\\DeliveryAuthorizer',
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