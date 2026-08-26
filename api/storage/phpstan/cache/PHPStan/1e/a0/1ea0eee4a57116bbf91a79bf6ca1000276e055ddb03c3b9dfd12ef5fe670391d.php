<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Support\DescribesConstraint.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\DescribesConstraint
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-6f3712e2cc790eecb9c32536d2897c628f88b65e6c7c7e2a75520e3531d79dc7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\DescribesConstraint',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Support/DescribesConstraint.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\DescribesConstraint',
    'shortName' => 'DescribesConstraint',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A rule object that can state the shape it enforces as a readable token.
 *
 * The administration console publishes the registry rules READ-ONLY so an admin can see
 * what ingestion actually enforces (FR-REG-04/05). A rule object rendered by class name
 * — `NationalIdentifier` — satisfies that page structurally while telling the reader
 * nothing: the whole point is the SHAPE, and "11 digits" is the shape. A rule that
 * carries its own token keeps the console honest when a string rule is replaced by an
 * object, which is exactly when the description would otherwise silently degrade.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 21,
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
      'constraintToken' => 
      array (
        'name' => 'constraintToken',
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
        'docComment' => '/** e.g. `digits:11`, `nigerian_phone:11`. */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\DescribesConstraint',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\DescribesConstraint',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\DescribesConstraint',
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