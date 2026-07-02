<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Resources/BeneficiaryRevealResource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Resources\BeneficiaryRevealResource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-3ce4523b26b598ac27bfa3a760bfdca66323d99e4e39d02e9a0e527d111031b6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
        'filename' => '/var/www/html/app/Http/Resources/BeneficiaryRevealResource.php',
      ),
    ),
    'namespace' => 'App\\Http\\Resources',
    'name' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
    'shortName' => 'BeneficiaryRevealResource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The minimal cross-MDA "reveal" projection (PRD FR-OWN-03 / FR-DUP-04): just
 * enough for another MDA to recognise an existing beneficiary and coordinate —
 * name + id, owner MDA, source, registration date, LGA/Ward, status. Never
 * exposes NIN/BVN/phone/address/DOB.
 *
 * @mixin Beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 37,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Http\\Resources\\Json\\JsonResource',
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
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
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
            'startColumn' => 29,
            'endColumn' => 44,
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
 * @return array<string, mixed>
 */',
        'startLine' => 24,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Resources',
        'declaringClassName' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
        'implementingClassName' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
        'currentClassName' => 'App\\Http\\Resources\\BeneficiaryRevealResource',
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