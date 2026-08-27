<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Segments/SegmentDimensionRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Segments\SegmentDimensionRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-579039f14e82e2a523231449a7c44362208e2ae87016fede0c5f6d117a01a64c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'filename' => '/var/www/html/app/Domain/Reporting/Segments/SegmentDimensionRegistry.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Segments',
    'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
    'shortName' => 'SegmentDimensionRegistry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * The filter catalogue for the segment builder (FR-RPT-03), assembled from data rather
 * than hand-listed.
 *
 * Two sources, and the distinction is real:
 *
 *  - CANONICAL dimensions come from {@see CanonicalSchema::segmentableFields()} — the
 *    fields an MDA\'s file actually carries. Adding a segmentable field to the schema
 *    (disability, vulnerability tier, anything later) makes it a filter here with NO
 *    change to this class, the API, or the UI. That is the point: a schema field that
 *    is filterable in one report and missing from another is how a segment silently
 *    stops meaning what people think it means.
 *
 *  - SYSTEM dimensions are attributes SP-MIS stamps rather than receives: which
 *    programme or activity a person is enrolled in, where their record came from, when
 *    it was registered, its status, whether they sit in a household. They cannot come
 *    from the canonical schema because no source file supplies them.
 *
 * What is NOT here is as important. Identity fields — NIN, BVN, phone, name — are
 * excluded structurally by `segmentableFields()`, not by omission from a list somebody
 * has to remember to keep correct. A filter on an identifier is not a segment; it is a
 * lookup of one named person dressed as a report, and it would turn an aggregate-tier
 * user into someone who can confirm whether a specific individual is in the registry.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 37,
    'endLine' => 176,
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
      'SYSTEM_DIMENSIONS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'name' => 'SYSTEM_DIMENSIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    \'registration_source\' => [\'label\' => \'Registration source\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_ENUM, \'column\' => \'registration_source\', \'values\' => \\App\\Domain\\Registry\\Enums\\RegistrationSource::class],
    \'status\' => [\'label\' => \'Status\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_ENUM, \'column\' => \'status\', \'values\' => \\App\\Domain\\Registry\\Enums\\BeneficiaryStatus::class],
    \'registration_date\' => [\'label\' => \'Registration date\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_DATE, \'column\' => \'registration_date\'],
    \'owner_mda\' => [\'label\' => \'Owner MDA\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_LOOKUP, \'column\' => \'owner_mda_id\'],
    // Relationship dimensions. The column is resolved by the query builder through
    // an enrollment subquery, not by a direct comparison — see SegmentQueryBuilder.
    \'programme\' => [\'label\' => \'Programme\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_LOOKUP, \'column\' => \'enrollments.programme_id\'],
    \'activity\' => [\'label\' => \'Activity\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_LOOKUP, \'column\' => \'enrollments.activity_id\'],
    \'household\' => [\'label\' => \'Household or individual\', \'kind\' => \\App\\Domain\\Reporting\\Segments\\SegmentDimension::KIND_ENUM, \'column\' => \'household_membership\', \'options\' => [[\'value\' => \'household\', \'label\' => \'In a household\'], [\'value\' => \'individual\', \'label\' => \'Individual (no household)\']]],
]',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 88,
            'startTokenPos' => 60,
            'startFilePos' => 1910,
            'endTokenPos' => 349,
            'endFilePos' => 3618,
          ),
        ),
        'docComment' => '/**
 * Attributes SP-MIS stamps on a record, which no source file provides.
 *
 * @var array<string, array<string, mixed>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'RELATIONAL' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'name' => 'RELATIONAL',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'programme\', \'activity\', \'household\', \'household_role\']',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 362,
            'startFilePos' => 3899,
            'endTokenPos' => 373,
            'endFilePos' => 3954,
          ),
        ),
        'docComment' => '/**
 * Filter keys resolved through a RELATIONSHIP (an enrollment or a household
 * membership) rather than a column on `beneficiaries`. They filter correctly; they
 * cannot be grouped by.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 88,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'all' => 
      array (
        'name' => 'all',
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
 * Every dimension the builder offers, keyed by filter key.
 *
 * @return array<string, SegmentDimension>
 */',
        'startLine' => 104,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 25,
            'endColumn' => 35,
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
                  'name' => 'App\\Domain\\Reporting\\Segments\\SegmentDimension',
                  'isIdentifier' => false,
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
        'docComment' => NULL,
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'aliasName' => NULL,
      ),
      'keys' => 
      array (
        'name' => 'keys',
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
        'docComment' => '/** @return list<string> */',
        'startLine' => 142,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'aliasName' => NULL,
      ),
      'optionsFrom' => 
      array (
        'name' => 'optionsFrom',
        'parameters' => 
        array (
          'spec' => 
          array (
            'name' => 'spec',
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
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 34,
            'endColumn' => 44,
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
 * Enum options, from the declared backed enum or a literal list.
 *
 * @param  array<string, mixed>  $spec
 * @return list<array{value: string, label: string}>
 */',
        'startLine' => 153,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Segments',
        'declaringClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Segments\\SegmentDimensionRegistry',
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