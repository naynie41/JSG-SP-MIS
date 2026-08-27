<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Controllers/Api/V1/Reporting/AdminOrganizationController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Controllers\Api\V1\Reporting\AdminOrganizationController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1f95596ead1e62f1a3c54bf488261511be4debaf842c6346391e9cb22543e7e2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'filename' => '/var/www/html/app/Http/Controllers/Api/V1/Reporting/AdminOrganizationController.php',
      ),
    ),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reporting',
    'name' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
    'shortName' => 'AdminOrganizationController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Organization roll-up for the administration console (FR-UAM-01, FR-PRG-05):
 * per-MDA user allocation, MDA administrators and owned activities, plus the
 * Development Partners and the delivery they fund.
 *
 * READ ONLY — organizations are created, edited, activated and deactivated through
 * the existing `/mdas` endpoints and their policies; this endpoint never duplicates
 * that logic. Gated to the System Administrator role, like the rest of the console.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Http\\Controllers\\Controller',
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
      'organizations' => 
      array (
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'name' => 'organizations',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 33,
        'endColumn' => 88,
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
          'organizations' => 
          array (
            'name' => 'organizations',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Services\\AdminOrganizationService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 33,
            'endColumn' => 88,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 92,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reporting',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'aliasName' => NULL,
      ),
      'index' => 
      array (
        'name' => 'index',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Http\\JsonResponse',
            'isIdentifier' => false,
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
        'namespace' => 'App\\Http\\Controllers\\Api\\V1\\Reporting',
        'declaringClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'implementingClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
        'currentClassName' => 'App\\Http\\Controllers\\Api\\V1\\Reporting\\AdminOrganizationController',
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