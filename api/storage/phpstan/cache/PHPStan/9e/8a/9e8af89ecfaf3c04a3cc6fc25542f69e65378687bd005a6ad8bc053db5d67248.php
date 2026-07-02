<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Contracts/BeneficiaryRouter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Contracts\BeneficiaryRouter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-c51820b21d4a2b5b79e46c7eb12e85656b27b65f8ca4745d66685de3efd7c4dc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
        'filename' => '/var/www/html/app/Domain/Registry/Contracts/BeneficiaryRouter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Contracts',
    'name' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
    'shortName' => 'BeneficiaryRouter',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Auto-route/assign extension point (PRD FR-OWN-04): decide the MDA a beneficiary
 * should be routed to based on an identified need (e.g. a programme match).
 *
 * Phase 2 ships a no-op implementation (NullBeneficiaryRouter). Programme
 * matching lands in Phase 4 by binding a real implementation to this contract —
 * no caller changes required.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 25,
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
      'suggestMdaFor' => 
      array (
        'name' => 'suggestMdaFor',
        'parameters' => 
        array (
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
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 35,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'need' => 
          array (
            'name' => 'need',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 48,
                'startFilePos' => 834,
                'endTokenPos' => 48,
                'endFilePos' => 837,
              ),
            ),
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 61,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Suggest the MDA id a beneficiary should be routed to for a given need,
 * or null when no routing applies. Never changes ownership by itself; the
 * caller decides whether to act (subject to ownership/consent rules).
 */',
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 91,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Contracts',
        'declaringClassName' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
        'implementingClassName' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
        'currentClassName' => 'App\\Domain\\Registry\\Contracts\\BeneficiaryRouter',
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