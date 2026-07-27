<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Privacy\Services\SubjectAccessAssembler.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Privacy\Services\SubjectAccessAssembler
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-587aebae5552b77a87b60d3cbb0b741082056b6d7dac3d2b4b97dc8a5b134b1b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Privacy/Services/SubjectAccessAssembler.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Privacy\\Services',
    'name' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
    'shortName' => 'SubjectAccessAssembler',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Assembles a Data Subject Access Request package (NFR-PRV-01, right of access): the
 * subject\'s FULL core record plus their complete benefit history, enrollments,
 * consent history, attached-document metadata, and the cross-MDA grants over their
 * record. Identifiers are UNMASKED here by design — this is the subject\'s own data
 * returned on an authorized request — so the endpoint is tightly permission-gated
 * and audited. Reads across all MDAs (the record belongs to the subject).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 121,
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
      'assemble' => 
      array (
        'name' => 'assemble',
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
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 30,
            'endColumn' => 53,
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
        'startLine' => 25,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'aliasName' => NULL,
      ),
      'coreRecord' => 
      array (
        'name' => 'coreRecord',
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
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 33,
            'endColumn' => 56,
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
        'startLine' => 79,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'aliasName' => NULL,
      ),
      'grants' => 
      array (
        'name' => 'grants',
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
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 29,
            'endColumn' => 52,
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
 * @return list<array<string, mixed>>
 */',
        'startLine' => 107,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Privacy\\Services',
        'declaringClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'implementingClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
        'currentClassName' => 'App\\Domain\\Privacy\\Services\\SubjectAccessAssembler',
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