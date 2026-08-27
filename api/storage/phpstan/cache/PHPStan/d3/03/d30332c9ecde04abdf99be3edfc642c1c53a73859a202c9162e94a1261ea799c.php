<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Models/Activity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Models\Activity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-cf685db77b182d8ddf848ee1686606eb6180d03a26921bf55cc95d9c4d18d908',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Programme\\Models\\Activity',
        'filename' => '/var/www/html/app/Domain/Programme/Models/Activity.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Programme\\Models',
    'name' => 'App\\Domain\\Programme\\Models\\Activity',
    'shortName' => 'Activity',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An MDA-owned unit of work that runs a global catalog {@see Programme} (PRD §10,
 * ARCH §12.4, FR-PRG-02). `owner_mda_id` is the CREATING MDA — its own MdaScope
 * column — so one programme can be run by many MDAs, each through its own activity.
 * The MDA-specific execution details (budget, funding source, schedule, period)
 * live here, not on the programme. Auditable; money is integer minor units (kobo,
 * NGN). A PostGIS `geom` column exists on PostgreSQL for later GIS work.
 *
 * @property string $id
 * @property string $programme_id
 * @property string $owner_mda_id
 * @property bool $involves_beneficiaries
 * @property string $name
 * @property string|null $description
 * @property int|null $target_beneficiaries
 * @property string|null $location_description
 * @property array<string, mixed>|null $schedule
 * @property Carbon|null $starts_on
 * @property Carbon|null $ends_on
 * @property int|null $budget_amount
 * @property string|null $funding_source
 * @property string|null $funding_partner_id
 * @property ActivityStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Programme $programme
 * @property-read Mda $ownerMda
 * @property-read User|null $fundingPartner
 * @property-read Collection<int, ActivityLocation> $locations
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 56,
    'endLine' => 204,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'activities\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 141,
            'startFilePos' => 2349,
            'endTokenPos' => 141,
            'endFilePos' => 2360,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'programme_id\', \'owner_mda_id\', \'involves_beneficiaries\', \'name\', \'description\', \'target_beneficiaries\', \'location_description\', \'schedule\', \'starts_on\', \'ends_on\', \'budget_amount\', \'funding_source\', \'funding_partner_id\', \'status\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 82,
            'startTokenPos' => 152,
            'startFilePos' => 2431,
            'endTokenPos' => 199,
            'endFilePos' => 2803,
          ),
        ),
        'docComment' => '/**
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 82,
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
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \\App\\Domain\\Programme\\Enums\\ActivityStatus::Draft->value]',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 89,
            'startTokenPos' => 210,
            'startFilePos' => 2884,
            'endTokenPos' => 223,
            'endFilePos' => 2940,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 87,
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
        'startLine' => 94,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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
            'name' => 'Database\\Factories\\ActivityFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 107,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'programme' => 
      array (
        'name' => 'programme',
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
 * @return BelongsTo<Programme, $this>
 */',
        'startLine' => 115,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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
        'startLine' => 123,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'creator' => 
      array (
        'name' => 'creator',
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
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'fundingPartner' => 
      array (
        'name' => 'fundingPartner',
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
 * The Development Partner funding this activity (Phase 6P), or null when it is
 * state-funded / not partner-attributed. Drives the partner-funding scope + metrics.
 *
 * @return BelongsTo<User, $this>
 */',
        'startLine' => 142,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'locations' => 
      array (
        'name' => 'locations',
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
 * The DECLARED location set: many LGAs, many wards per LGA, a null `ward_id`
 * meaning the whole LGA. Replaces the old single `lga`/`ward` pair.
 *
 * Descriptive only — it states where the activity plans to operate and is never
 * checked against the beneficiaries uploaded under it.
 *
 * @return HasMany<ActivityLocation, $this>
 */',
        'startLine' => 156,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeDeclaredIn' => 
      array (
        'name' => 'scopeDeclaredIn',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'lga' => 
          array (
            'name' => 'lga',
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
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 53,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ward' => 
          array (
            'name' => 'ward',
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
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 67,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Narrows to activities that DECLARE the given area, replacing the old exact-string
 * match on `activities.lga` / `.ward`.
 *
 * Values arrive as free text from the dashboard filter (the LGA/ward values seen on
 * beneficiary records), so they are slugged to `lgas.code` / `wards.code` — the same
 * slug GEO.1 stores.
 *
 * A ward filter also matches an activity that declared the WHOLE LGA containing that
 * ward. Declaring a whole LGA is a claim to cover every ward in it, so excluding
 * those activities would under-report coverage for exactly the activities with the
 * broadest declared reach.
 *
 * @param  Builder<Activity>  $query
 * @return Builder<Activity>
 */',
        'startLine' => 177,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'areaSlug' => 
      array (
        'name' => 'areaSlug',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 38,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** The registry slug shared by `lgas.code` and `wards.code`. */',
        'startLine' => 200,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Programme\\Models',
        'declaringClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'implementingClassName' => 'App\\Domain\\Programme\\Models\\Activity',
        'currentClassName' => 'App\\Domain\\Programme\\Models\\Activity',
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