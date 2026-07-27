<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Privacy\Retention\RetentionPolicyRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Privacy\Retention\RetentionPolicyRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d1e571fdcca080a2643ebfd9e3c838bc4f7617314336c8e59cb5f35c848d5275',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Privacy/Retention/RetentionPolicyRepository.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Privacy\\Retention',
    'name' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
    'shortName' => 'RetentionPolicyRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Reads retention policies from config/privacy.php (NFR-PRV-01). The engine consults
 * this rather than the raw config so policies validate to typed value objects in one
 * place and disabled/invalid entries are filtered out.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 52,
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
      'enabled' => 
      array (
        'name' => 'enabled',
        'parameters' => 
        array (
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
        'docComment' => '/** Whether the retention engine is switched on at all (DPO/config). */',
        'startLine' => 15,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Retention',
        'declaringClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'implementingClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'currentClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'aliasName' => NULL,
      ),
      'batchLimit' => 
      array (
        'name' => 'batchLimit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
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
        'namespace' => 'App\\Domain\\Privacy\\Retention',
        'declaringClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'implementingClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'currentClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'aliasName' => NULL,
      ),
      'deleteHard' => 
      array (
        'name' => 'deleteHard',
        'parameters' => 
        array (
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
        'docComment' => '/** Whether a `delete` action may hard-delete a history-free record. */',
        'startLine' => 26,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Retention',
        'declaringClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'implementingClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'currentClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'aliasName' => NULL,
      ),
      'enabledPolicies' => 
      array (
        'name' => 'enabledPolicies',
        'parameters' => 
        array (
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
 * The enabled, well-formed policies (a policy with no age period is skipped so a
 * misconfiguration can never sweep the whole registry).
 *
 * @return list<RetentionPolicy>
 */',
        'startLine' => 37,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Retention',
        'declaringClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'implementingClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
        'currentClassName' => 'App\\Domain\\Privacy\\Retention\\RetentionPolicyRepository',
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