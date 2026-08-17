<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reference\ReferenceServiceProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reference\ReferenceServiceProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-3e7d962fd00de9622dcb44a4bf4b23d78a2b32707bec7ffd93156713d00078c0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reference\\ReferenceServiceProvider',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reference/ReferenceServiceProvider.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reference',
    'name' => 'App\\Domain\\Reference\\ReferenceServiceProvider',
    'shortName' => 'ReferenceServiceProvider',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Wires the Reference domain: shared, non-PII lookup data (Jigawa LGAs and wards).
 *
 * It registers NO permission. The lookups are read by every authenticated role to
 * render a cascading LGA → Ward selector, so a `reference.view` permission would be
 * granted to every role at once — a permission that can never deny anything makes the
 * RBAC set describe a distinction the system does not draw. The routes are gated by
 * authentication alone, and deliberately so (see routes/api.php).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 28,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\ServiceProvider',
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
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
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
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reference',
        'declaringClassName' => 'App\\Domain\\Reference\\ReferenceServiceProvider',
        'implementingClassName' => 'App\\Domain\\Reference\\ReferenceServiceProvider',
        'currentClassName' => 'App\\Domain\\Reference\\ReferenceServiceProvider',
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