<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Models/Beneficiary.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Models\Beneficiary
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-4be27e541df03cc5712e86b97672e0a22b9f40d512e9300d8f557d140fae361f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'filename' => '/var/www/html/app/Domain/Registry/Models/Beneficiary.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Models',
    'name' => 'App\\Domain\\Registry\\Models\\Beneficiary',
    'shortName' => 'Beneficiary',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A beneficiary — the registry\'s core record (PRD FR-REG-01/03/04, FR-OWN-01).
 * Owned by an MDA and MDA-scoped via `owner_mda_id`; audited via Auditable.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property RegistrationSource $registration_source
 * @property Carbon $registration_date
 * @property string|null $import_batch_id
 * @property string|null $original_record_id
 * @property string|null $nin
 * @property string|null $bvn
 * @property string|null $phone
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $address
 * @property string|null $lga
 * @property string|null $ward
 * @property BeneficiaryStatus $status
 * @property-read Mda|null $ownerMda
 * @property-read HouseholdMembership|null $currentMembership
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 50,
    'endLine' => 199,
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
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
      3 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
      4 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'beneficiaries\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 141,
            'startFilePos' => 1924,
            'endTokenPos' => 141,
            'endFilePos' => 1938,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'owner_mda_id\', \'registration_source\', \'registration_date\', \'import_batch_id\', \'original_record_id\', \'nin\', \'bvn\', \'phone\', \'first_name\', \'middle_name\', \'last_name\', \'date_of_birth\', \'gender\', \'address\', \'lga\', \'ward\', \'status\']',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 78,
            'startTokenPos' => 152,
            'startFilePos' => 2009,
            'endTokenPos' => 205,
            'endFilePos' => 2380,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 78,
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
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'nin\', \'bvn\']',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 89,
            'startTokenPos' => 216,
            'startFilePos' => 2613,
            'endTokenPos' => 224,
            'endFilePos' => 2649,
          ),
        ),
        'docComment' => '/**
 * NIN/BVN are national identifiers — never exposed by default serialization;
 * screens reveal them (masked) through a permission-gated resource.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Registry\\Enums\\BeneficiaryStatus::Active->value]',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 96,
            'startTokenPos' => 235,
            'startFilePos' => 2730,
            'endTokenPos' => 248,
            'endFilePos' => 2790,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 96,
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
        'startLine' => 101,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'auditMask' => 
      array (
        'name' => 'auditMask',
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
 * A beneficiary\'s name is PII — masked in audit snapshots (SECURITY.md §6).
 * NIN/BVN/phone/address/DOB are masked by the global audit config.
 *
 * @return list<string>
 */',
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
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
        'startLine' => 123,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'normalizeDigits' => 
      array (
        'name' => 'normalizeDigits',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 44,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Strip all non-digits; return null when nothing remains (so the column is
 * NULL, not an empty string — required for the partial unique indexes).
 */',
        'startLine' => 154,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'fullName' => 
      array (
        'name' => 'fullName',
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
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
        'startLine' => 172,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'householdMemberships' => 
      array (
        'name' => 'householdMemberships',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<HouseholdMembership, $this>
 */',
        'startLine' => 180,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'currentMembership' => 
      array (
        'name' => 'currentMembership',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The beneficiary\'s current (open) household membership, if any.
 *
 * @return HasOne<HouseholdMembership, $this>
 */',
        'startLine' => 190,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Database\\Factories\\BeneficiaryFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 195,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Registry\\Models',
        'declaringClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'implementingClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
        'currentClassName' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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