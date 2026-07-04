<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Resources/BeneficiaryRevealResource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Resources\BeneficiaryRevealResource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-2db79a83890cad14c931c638c13a0e96a895d2aaabbe25a06b2325547dda2461',
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
 * name + id, owner MDA, source, registration date, LGA/Ward, status, and the
 * programme(s) + benefits-received sections. Never exposes NIN/BVN/phone/address/DOB.
 *
 * The programme/benefit sections read real enrolment + ledger data (FR-DUP-04),
 * respecting visibility: exact monetary values are shown only to the beneficiary\'s
 * owner MDA or oversight (see BeneficiaryRevealPresenter).
 *
 * @mixin Beneficiary
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 46,
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
            'startLine' => 29,
            'endLine' => 29,
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
        'startLine' => 29,
        'endLine' => 45,
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