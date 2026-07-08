<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Reports/AdHoc/AdHocDatasetRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-da058c66e37aa027f0aa82588ee7bc10c9ae511ecb7a1db2172035949642e1e2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'filename' => '/var/www/html/app/Domain/Reporting/Reports/AdHoc/AdHocDatasetRegistry.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
    'name' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
    'shortName' => 'AdHocDatasetRegistry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * The whitelist of ad-hoc datasets (PRD FR-RPT-03). Each dataset exposes ONLY
 * aggregate dimensions (group-by), measures (count/sum), and filters — there is no
 * row-level or identifier column anywhere, so an ad-hoc report is always
 * de-identified and PII can never be selected. Coordination datasets
 * (referrals/grievances) are hidden from a partner\'s funded-programme scope.
 *
 * This registry is the single source of truth: the builder validates every
 * definition against it, and the API catalogue is derived from it.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 185,
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
      'DATASETS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'name' => 'DATASETS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'benefits\' => [\'label\' => \'Benefits (ledger)\', \'coordination\' => false, \'model\' => \\App\\Domain\\Benefit\\Models\\Benefit::class, \'exclude_reversed\' => true, \'dimensions\' => [\'programme\' => [\'label\' => \'Programme\', \'column\' => \'programme_id\', \'render\' => \'programme\'], \'mda\' => [\'label\' => \'Delivering MDA\', \'column\' => \'mda_id\', \'render\' => \'mda\'], \'lga\' => [\'label\' => \'LGA\', \'column\' => \'lga\', \'render\' => \'title\'], \'ward\' => [\'label\' => \'Ward\', \'column\' => \'ward\', \'render\' => \'title\'], \'benefit_type\' => [\'label\' => \'Benefit type\', \'column\' => \'benefit_type\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Deliveries\', \'sql\' => \'count(*)\', \'render\' => \'int\'], \'total_value\' => [\'label\' => \'Value (₦)\', \'sql\' => \'coalesce(sum(monetary_value), 0)\', \'render\' => \'naira\'], \'total_quantity\' => [\'label\' => \'Quantity\', \'sql\' => \'coalesce(sum(quantity), 0)\', \'render\' => \'string\']], \'filters\' => [\'programme_id\' => [\'column\' => \'programme_id\', \'kind\' => \'equals\'], \'mda_id\' => [\'column\' => \'mda_id\', \'kind\' => \'equals\'], \'lga\' => [\'column\' => \'lga\', \'kind\' => \'equals\'], \'ward\' => [\'column\' => \'ward\', \'kind\' => \'equals\'], \'benefit_type\' => [\'column\' => \'benefit_type\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'delivery_date\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'delivery_date\', \'kind\' => \'date_to\']]], \'beneficiaries\' => [\'label\' => \'Beneficiaries (registry)\', \'coordination\' => false, \'model\' => \\App\\Domain\\Registry\\Models\\Beneficiary::class, \'exclude_reversed\' => false, \'dimensions\' => [\'owner_mda\' => [\'label\' => \'Owner MDA\', \'column\' => \'owner_mda_id\', \'render\' => \'mda\'], \'lga\' => [\'label\' => \'LGA\', \'column\' => \'lga\', \'render\' => \'title\'], \'ward\' => [\'label\' => \'Ward\', \'column\' => \'ward\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'registration_source\' => [\'label\' => \'Source\', \'column\' => \'registration_source\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Beneficiaries\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'owner_mda_id\', \'kind\' => \'equals\'], \'lga\' => [\'column\' => \'lga\', \'kind\' => \'equals\'], \'ward\' => [\'column\' => \'ward\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'registration_source\' => [\'column\' => \'registration_source\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'registration_date\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'registration_date\', \'kind\' => \'date_to\']]], \'referrals\' => [\'label\' => \'Referrals\', \'coordination\' => true, \'model\' => \\App\\Domain\\Referral\\Models\\Referral::class, \'exclude_reversed\' => false, \'dimensions\' => [\'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'from_mda\' => [\'label\' => \'From MDA\', \'column\' => \'from_mda_id\', \'render\' => \'mda\'], \'to_mda\' => [\'label\' => \'To MDA\', \'column\' => \'to_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Referrals\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => null, \'kind\' => \'mda_two_party\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]], \'grievances\' => [\'label\' => \'Grievances\', \'coordination\' => true, \'model\' => \\App\\Domain\\Grievance\\Models\\Grievance::class, \'exclude_reversed\' => false, \'dimensions\' => [\'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'category\' => [\'label\' => \'Category\', \'column\' => \'category\', \'render\' => \'title\'], \'channel\' => [\'label\' => \'Channel\', \'column\' => \'channel\', \'render\' => \'title\'], \'handling_mda\' => [\'label\' => \'Handling MDA\', \'column\' => \'handling_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Grievances\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'handling_mda_id\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'category\' => [\'column\' => \'category\', \'kind\' => \'equals\'], \'channel\' => [\'column\' => \'channel\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]]]',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 126,
            'startTokenPos' => 60,
            'startFilePos' => 966,
            'endTokenPos' => 1474,
            'endFilePos' => 6616,
          ),
        ),
        'docComment' => '/**
 * @var array<string, array<string, mixed>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'dataset' => 
          array (
            'name' => 'dataset',
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
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 32,
            'endColumn' => 46,
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
                  'name' => 'array',
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
 * @return array<string, mixed>|null
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'aliasName' => NULL,
      ),
      'isCoordination' => 
      array (
        'name' => 'isCoordination',
        'parameters' => 
        array (
          'dataset' => 
          array (
            'name' => 'dataset',
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
            'startColumn' => 43,
            'endColumn' => 57,
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
            'name' => 'bool',
            'isIdentifier' => true,
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
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'aliasName' => NULL,
      ),
      'availableTo' => 
      array (
        'name' => 'availableTo',
        'parameters' => 
        array (
          'dataset' => 
          array (
            'name' => 'dataset',
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
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 57,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'startLine' => 141,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'aliasName' => NULL,
      ),
      'catalogueFor' => 
      array (
        'name' => 'catalogueFor',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
                'isIdentifier' => false,
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
            'startColumn' => 41,
            'endColumn' => 61,
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
 * The public catalogue for a scope: each available dataset with its selectable
 * dimensions, measures and filters (keys + labels only — no SQL).
 *
 * @return list<array<string, mixed>>
 */',
        'startLine' => 153,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'aliasName' => NULL,
      ),
      'optionList' => 
      array (
        'name' => 'optionList',
        'parameters' => 
        array (
          'items' => 
          array (
            'name' => 'items',
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
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 40,
            'endColumn' => 51,
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
 * @param  array<string, array<string, mixed>>  $items
 * @return list<array{key: string, label: string}>
 */',
        'startLine' => 176,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Reporting\\Reports\\AdHoc',
        'declaringClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'implementingClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'currentClassName' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
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