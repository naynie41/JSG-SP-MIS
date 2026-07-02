<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Imports/Adapters/RegistrationSourceAdapter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Imports\Adapters\RegistrationSourceAdapter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-93c718a46d1679a527b06a84c854f53cb2ac3a6162abc49167c63a570f4563e6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'filename' => '/var/www/html/app/Domain/Registry/Imports/Adapters/RegistrationSourceAdapter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
    'name' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
    'shortName' => 'RegistrationSourceAdapter',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A registration SOURCE adapter (PRD FR-REG-02, §6.1). Each inbound channel —
 * Excel/CSV, Kobo Collect, ODK, an existing government system, or a future
 * source — maps its own record shape onto the canonical beneficiary field set,
 * and declares the provenance to stamp.
 *
 * This is the ONLY seam a new source needs to implement: the parse → validate →
 * commit pipeline and the shared registration rules never change. Register the
 * adapter in {@see SourceAdapterRegistry} (see app/Domain/Registry/README.md).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 36,
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
      'source' => 
      array (
        'name' => 'source',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** The provenance stamped on records ingested through this adapter. */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'aliasName' => NULL,
      ),
      'map' => 
      array (
        'name' => 'map',
        'parameters' => 
        array (
          'raw' => 
          array (
            'name' => 'raw',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 25,
            'endColumn' => 34,
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
 * Map one raw source record to canonical beneficiary fields plus
 * `original_record_id` (the caller\'s/source\'s own id, for traceability).
 * Unknown source fields are ignored; missing fields are simply absent.
 *
 * Canonical keys: first_name, middle_name, last_name, nin, bvn, phone,
 * date_of_birth, gender, address, lga, ward, original_record_id.
 *
 * @param  array<string, mixed>  $raw
 * @return array<string, mixed>
 */',
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Imports\\Adapters',
        'declaringClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'implementingClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
        'currentClassName' => 'App\\Domain\\Registry\\Imports\\Adapters\\RegistrationSourceAdapter',
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