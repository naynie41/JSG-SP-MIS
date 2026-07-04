<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Benefit/Services/VerifierRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Benefit\Services\VerifierRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f1c4d4bfced43a4b2e3e887ab70c21a49a2aa3cb93052c25cf7bb1f4e3dc9f5f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'filename' => '/var/www/html/app/Domain/Benefit/Services/VerifierRegistry.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Benefit\\Services',
    'name' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
    'shortName' => 'VerifierRegistry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Resolves the {@see BenefitVerifier} for a verification method (PRD FR-BEN-04).
 * Register a method\'s implementation here; enabling a stubbed method later means
 * swapping its binding, with no change to callers.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 49,
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
      'verifiers' => 
      array (
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'name' => 'verifiers',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 48,
            'startFilePos' => 530,
            'endTokenPos' => 49,
            'endFilePos' => 531,
          ),
        ),
        'docComment' => '/** @var array<string, BenefitVerifier> */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
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
          'verifiers' => 
          array (
            'name' => 'verifiers',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 66,
                'startFilePos' => 658,
                'endTokenPos' => 67,
                'endFilePos' => 659,
              ),
            ),
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 33,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  iterable<BenefitVerifier>  $verifiers
 */',
        'startLine' => 24,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'aliasName' => NULL,
      ),
      'for' => 
      array (
        'name' => 'for',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 25,
            'endColumn' => 50,
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
            'name' => 'App\\Domain\\Benefit\\Contracts\\BenefitVerifier',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'aliasName' => NULL,
      ),
      'availableMethods' => 
      array (
        'name' => 'availableMethods',
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
 * Verification methods that can actually run right now (for surfacing to the UI).
 *
 * @return list<string>
 */',
        'startLine' => 42,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Benefit\\Services',
        'declaringClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'implementingClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
        'currentClassName' => 'App\\Domain\\Benefit\\Services\\VerifierRegistry',
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