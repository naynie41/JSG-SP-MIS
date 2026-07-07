<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Services/DeliveryAuthorization.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Services\DeliveryAuthorization
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-e1d6a3eb3bb6e40998dd97e6d38a771e94074bdff414c38a8331ebd6f21ad4e2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'filename' => '/var/www/html/app/Domain/Benefit/Services/DeliveryAuthorization.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Services',
    'name' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
    'shortName' => 'DeliveryAuthorization',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Decides whether a delivering MDA may record an intervention for a beneficiary
 * (PRD FR-BEN-06). The owner is always authorized; a NON-OWNER is authorized only
 * by an explicit, accepted authorization — checked through the registered
 * {@see DeliveryAuthorizer}s (Service Request now; Referral added in Phase 5
 * without touching the recorder). It never consults a generic serve seam.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 46,
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
      'TAG' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'name' => 'TAG',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'benefit.delivery_authorizers\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 43,
            'startFilePos' => 699,
            'endTokenPos' => 43,
            'endFilePos' => 728,
          ),
        ),
        'docComment' => '/** Container tag each domain registers its authorizer under. */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
    ),
    'immediateProperties' => 
    array (
      'authorizers' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'name' => 'authorizers',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'iterable',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 33,
        'endColumn' => 70,
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
          'authorizers' => 
          array (
            'name' => 'authorizers',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 33,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  iterable<DeliveryAuthorizer>  $authorizers
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 74,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'aliasName' => NULL,
      ),
      'basisFor' => 
      array (
        'name' => 'basisFor',
        'parameters' => 
        array (
          'deliveringMdaId' => 
          array (
            'name' => 'deliveringMdaId',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 30,
            'endColumn' => 52,
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 55,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'string',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The basis on which the delivering MDA may serve this beneficiary, or null if
 * unauthorized. Returns \'owner\' for the owning MDA, otherwise the authorizing
 * type\'s `source()` (e.g. \'service_request\', \'referral\').
 */',
        'startLine' => 32,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\DeliveryAuthorization',
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