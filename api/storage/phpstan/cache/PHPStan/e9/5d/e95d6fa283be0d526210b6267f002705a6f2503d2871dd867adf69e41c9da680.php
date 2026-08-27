<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/ImportMappingTemplate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\ImportMappingTemplate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b66f4ba9d0d5230f3598a92ce268fa59431e8c7a2e57f9b0bf424e23ccd76567',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'filename' => '/var/www/html/app/Domain/Registry/Models/ImportMappingTemplate.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
    'shortName' => 'ImportMappingTemplate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A saved column mapping for one MDA\'s recurring file shape (CLAUDE.md §11).
 *
 * Keyed by (MDA, source, source signature) so it is offered only for a file with the
 * same columns — when an MDA changes its export the signature changes, the template no
 * longer matches, and the officer re-maps rather than having a stale mapping applied to
 * moved columns.
 *
 * A template PRE-FILLS a proposal. It never satisfies the identity-field confirmation:
 * NIN, BVN, name and phone are re-confirmed on every import regardless, because the
 * cost of a wrong identity mapping is a false duplicate merge, not a validation error.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property RegistrationSource $source
 * @property string $source_signature
 * @property string $name
 * @property array<string, string|null> $column_map
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 37,
    'endLine' => 70,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'App\\Domain\\Access\\Concerns\\MdaScoped',
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Domain\\Audit\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'import_mapping_templates\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 83,
            'startFilePos' => 1469,
            'endTokenPos' => 83,
            'endFilePos' => 1494,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'owner_mda_id\', \'source\', \'source_signature\', \'name\', \'column_map\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 53,
            'startTokenPos' => 94,
            'startFilePos' => 1565,
            'endTokenPos' => 114,
            'endFilePos' => 1701,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'casts' => 
      array (
        'name' => 'casts',
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
 * @return array<string, string>
 */',
        'startLine' => 58,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'aliasName' => NULL,
      ),
      'mdaOwnershipColumn' => 
      array (
        'name' => 'mdaOwnershipColumn',
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
        'docComment' => NULL,
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\ImportMappingTemplate',
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