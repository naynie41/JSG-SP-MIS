<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Registry/BeneficiaryMatchSearchRequest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Registry\BeneficiaryMatchSearchRequest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-1b4290d5a5679151a5dad48eec13c2e0b08af0d2cb74a77a77023916479eb365',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'filename' => '/var/www/html/app/Http/Requests/Registry/BeneficiaryMatchSearchRequest.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Registry',
    'name' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
    'shortName' => 'BeneficiaryMatchSearchRequest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Ad-hoc duplicate/serve search (PRD FR-DUP-04, FR-OWN-03): partial identity
 * details fed to the SAME matching engine used by import screening. At least one
 * blocking-capable field (NIN/BVN/phone, or last name for the name+DOB block) is
 * required so the search never degrades into a full-table scan.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Http\\FormRequest',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'QUERY_FIELDS' => 
      array (
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'name' => 'QUERY_FIELDS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'nin\', \'bvn\', \'phone\', \'first_name\', \'middle_name\', \'last_name\', \'date_of_birth\', \'gender\', \'lga\', \'ward\']',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 21,
            'startTokenPos' => 42,
            'startFilePos' => 601,
            'endTokenPos' => 74,
            'endFilePos' => 730,
          ),
        ),
        'docComment' => '/** Canonical fields the engine understands; only these are forwarded. */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'authorize' => 
      array (
        'name' => 'authorize',
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
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'aliasName' => NULL,
      ),
      'rules' => 
      array (
        'name' => 'rules',
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
 * @return array<string, mixed>
 */',
        'startLine' => 31,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'aliasName' => NULL,
      ),
      'canonicalQuery' => 
      array (
        'name' => 'canonicalQuery',
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
 * The provided (non-empty) query fields in the engine\'s canonical shape.
 *
 * @return array<string, string>
 */',
        'startLine' => 53,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Http\\Requests\\Registry',
        'declaringClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'implementingClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
        'currentClassName' => 'App\\Http\\Requests\\Registry\\BeneficiaryMatchSearchRequest',
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