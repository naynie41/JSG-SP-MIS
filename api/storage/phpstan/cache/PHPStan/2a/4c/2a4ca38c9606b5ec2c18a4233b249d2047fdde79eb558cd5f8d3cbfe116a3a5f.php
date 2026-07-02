<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/BeneficiaryDocument.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\BeneficiaryDocument
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-d170b6181fe2aab6cae7df0836504401d1695927cddce77b5af670c8f617336f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'filename' => '/var/www/html/app/Domain/Registry/Models/BeneficiaryDocument.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
    'shortName' => 'BeneficiaryDocument',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A supporting document attached to a beneficiary (PRD FR-REG-07). MDA-scoped via
 * the denormalised `owner_mda_id`; upload/delete are audited via Auditable. The
 * file itself is stored on a private disk (outside the web root) and only ever
 * streamed through the authorized download endpoint — never served statically.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $owner_mda_id
 * @property string|null $uploaded_by
 * @property DocumentType $document_type
 * @property string $original_filename
 * @property string $stored_path
 * @property string $mime_type
 * @property int $size_bytes
 * @property string $checksum_sha256
 * @property-read Beneficiary $beneficiary
 * @property-read Mda|null $ownerMda
 * @property-read User|null $uploadedBy
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 38,
    'endLine' => 102,
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
      3 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'beneficiary_documents\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 101,
            'startFilePos' => 1446,
            'endTokenPos' => 101,
            'endFilePos' => 1468,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'beneficiary_id\', \'owner_mda_id\', \'uploaded_by\', \'document_type\', \'original_filename\', \'stored_path\', \'mime_type\', \'size_bytes\', \'checksum_sha256\']',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 57,
            'startTokenPos' => 112,
            'startFilePos' => 1539,
            'endTokenPos' => 141,
            'endFilePos' => 1765,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'stored_path\']',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 66,
            'startTokenPos' => 152,
            'startFilePos' => 1920,
            'endTokenPos' => 157,
            'endFilePos' => 1949,
          ),
        ),
        'docComment' => '/**
 * The stored path is an internal filesystem location — never expose it.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 66,
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
        'startLine' => 71,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'aliasName' => NULL,
      ),
      'beneficiary' => 
      array (
        'name' => 'beneficiary',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<Beneficiary, $this>
 */',
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'aliasName' => NULL,
      ),
      'ownerMda' => 
      array (
        'name' => 'ownerMda',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<Mda, $this>
 */',
        'startLine' => 90,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'aliasName' => NULL,
      ),
      'uploadedBy' => 
      array (
        'name' => 'uploadedBy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\BeneficiaryDocument',
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