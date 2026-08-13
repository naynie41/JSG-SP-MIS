<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-8451f6b67936554e92032fd4a3bd9f5b43846705260de1b184d1ba408ebd1368',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Reports\\AdHoc\\AdHocDatasetRegistry',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Reports/AdHoc/AdHocDatasetRegistry.php',
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
 * Datasets flagged `admin` are the ADMINISTRATIVE/governance datasets behind the
 * System Administrator console\'s Reports section — users, organizations, the
 * programme catalogue, duplicate review, the audit log and import batches. They are
 * available only to a scope carrying `governance` (a System Administrator), never to
 * state-wide oversight: an Executive sees all programme data but not who did what.
 * Their dimensions are counts over administrative attributes only — a user report can
 * group by role or status but never by name or email, and an audit report never
 * exposes the before/after payload.
 *
 * This registry is the single source of truth: the builder validates every
 * definition against it, and the API catalogue is derived from it.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 422,
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
          'code' => '[
    \'benefits\' => [\'label\' => \'Benefits (ledger)\', \'coordination\' => false, \'model\' => \\App\\Domain\\Benefit\\Models\\Benefit::class, \'exclude_reversed\' => true, \'dimensions\' => [\'programme\' => [\'label\' => \'Programme\', \'column\' => \'programme_id\', \'render\' => \'programme\'], \'mda\' => [\'label\' => \'Delivering MDA\', \'column\' => \'mda_id\', \'render\' => \'mda\'], \'lga\' => [\'label\' => \'LGA\', \'column\' => \'lga\', \'render\' => \'title\'], \'ward\' => [\'label\' => \'Ward\', \'column\' => \'ward\', \'render\' => \'title\'], \'benefit_type\' => [\'label\' => \'Benefit type\', \'column\' => \'benefit_type\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Deliveries\', \'sql\' => \'count(*)\', \'render\' => \'int\'], \'total_value\' => [\'label\' => \'Value (₦)\', \'sql\' => \'coalesce(sum(monetary_value), 0)\', \'render\' => \'naira\'], \'total_quantity\' => [\'label\' => \'Quantity\', \'sql\' => \'coalesce(sum(quantity), 0)\', \'render\' => \'string\']], \'filters\' => [\'programme_id\' => [\'column\' => \'programme_id\', \'kind\' => \'equals\'], \'mda_id\' => [\'column\' => \'mda_id\', \'kind\' => \'equals\'], \'lga\' => [\'column\' => \'lga\', \'kind\' => \'equals\'], \'ward\' => [\'column\' => \'ward\', \'kind\' => \'equals\'], \'benefit_type\' => [\'column\' => \'benefit_type\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'delivery_date\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'delivery_date\', \'kind\' => \'date_to\']]],
    \'beneficiaries\' => [\'label\' => \'Beneficiaries (registry)\', \'coordination\' => false, \'model\' => \\App\\Domain\\Registry\\Models\\Beneficiary::class, \'exclude_reversed\' => false, \'dimensions\' => [\'owner_mda\' => [\'label\' => \'Owner MDA\', \'column\' => \'owner_mda_id\', \'render\' => \'mda\'], \'lga\' => [\'label\' => \'LGA\', \'column\' => \'lga\', \'render\' => \'title\'], \'ward\' => [\'label\' => \'Ward\', \'column\' => \'ward\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'registration_source\' => [\'label\' => \'Source\', \'column\' => \'registration_source\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Beneficiaries\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'owner_mda_id\', \'kind\' => \'equals\'], \'lga\' => [\'column\' => \'lga\', \'kind\' => \'equals\'], \'ward\' => [\'column\' => \'ward\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'registration_source\' => [\'column\' => \'registration_source\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'registration_date\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'registration_date\', \'kind\' => \'date_to\']]],
    /*
     * Activities — an MDA\'s own delivery vehicles under the shared programme
     * catalogue (§10). Scoped on `owner_mda_id`, the CREATING MDA, so an MDA\'s
     * activity report is about its own delivery and a programme dimension answers
     * "what are we running, and under which programme" without the catalogue itself
     * ever being reportable to a non-governance scope.
     *
     * `budget_amount` is an activity BUDGET figure, not expenditure — SP-MIS records
     * delivery, it does not move money.
     */
    \'activities\' => [\'label\' => \'Activities (delivery)\', \'coordination\' => false, \'model\' => \\App\\Domain\\Programme\\Models\\Activity::class, \'exclude_reversed\' => false, \'dimensions\' => [\'programme\' => [\'label\' => \'Programme\', \'column\' => \'programme_id\', \'render\' => \'programme\'], \'mda\' => [\'label\' => \'Owner MDA\', \'column\' => \'owner_mda_id\', \'render\' => \'mda\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'lga\' => [\'label\' => \'LGA\', \'column\' => \'lga\', \'render\' => \'title\'], \'ward\' => [\'label\' => \'Ward\', \'column\' => \'ward\', \'render\' => \'title\'], \'involves_beneficiaries\' => [\'label\' => \'Registers beneficiaries\', \'column\' => \'involves_beneficiaries\', \'render\' => \'bool\']], \'measures\' => [\'count\' => [\'label\' => \'Activities\', \'sql\' => \'count(*)\', \'render\' => \'int\'], \'target_beneficiaries\' => [\'label\' => \'Target beneficiaries\', \'sql\' => \'coalesce(sum(target_beneficiaries), 0)\', \'render\' => \'int\'], \'budget_amount\' => [\'label\' => \'Budget (₦)\', \'sql\' => \'coalesce(sum(budget_amount), 0)\', \'render\' => \'naira\']], \'filters\' => [\'programme_id\' => [\'column\' => \'programme_id\', \'kind\' => \'equals\'], \'mda_id\' => [\'column\' => \'owner_mda_id\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'lga\' => [\'column\' => \'lga\', \'kind\' => \'equals\'], \'ward\' => [\'column\' => \'ward\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'starts_on\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'ends_on\', \'kind\' => \'date_to\']]],
    \'referrals\' => [\'label\' => \'Referrals\', \'coordination\' => true, \'model\' => \\App\\Domain\\Referral\\Models\\Referral::class, \'exclude_reversed\' => false, \'dimensions\' => [\'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'from_mda\' => [\'label\' => \'From MDA\', \'column\' => \'from_mda_id\', \'render\' => \'mda\'], \'to_mda\' => [\'label\' => \'To MDA\', \'column\' => \'to_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Referrals\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => null, \'kind\' => \'mda_two_party\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    \'grievances\' => [\'label\' => \'Grievances\', \'coordination\' => true, \'model\' => \\App\\Domain\\Grievance\\Models\\Grievance::class, \'exclude_reversed\' => false, \'dimensions\' => [\'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'category\' => [\'label\' => \'Category\', \'column\' => \'category\', \'render\' => \'title\'], \'channel\' => [\'label\' => \'Channel\', \'column\' => \'channel\', \'render\' => \'title\'], \'handling_mda\' => [\'label\' => \'Handling MDA\', \'column\' => \'handling_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Grievances\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'handling_mda_id\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'category\' => [\'column\' => \'category\', \'kind\' => \'equals\'], \'channel\' => [\'column\' => \'channel\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    /* ------------------------------------------------------------------------
       | Administrative (governance) datasets — System Administrator console only.
       | Counts over administrative attributes; never a name, email, NIN or an
       | audit before/after payload.
       */
    \'users\' => [\'label\' => \'Users & access\', \'coordination\' => false, \'admin\' => true, \'model\' => \\App\\Domain\\Access\\Models\\User::class, \'exclude_reversed\' => false, \'dimensions\' => [\'role\' => [\'label\' => \'Role\', \'column\' => \'role_id\', \'render\' => \'role\'], \'mda\' => [\'label\' => \'MDA\', \'column\' => \'mda_id\', \'render\' => \'mda\'], \'status\' => [\'label\' => \'Account status\', \'column\' => \'status\', \'render\' => \'title\'], \'mfa_enabled\' => [\'label\' => \'MFA enrolled\', \'column\' => \'mfa_enabled\', \'render\' => \'bool\']], \'measures\' => [\'count\' => [\'label\' => \'Users\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'mda_id\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'role_id\' => [\'column\' => \'role_id\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    \'organizations\' => [\'label\' => \'Organizations (MDAs & partners)\', \'coordination\' => false, \'admin\' => true, \'model\' => \\App\\Domain\\Access\\Models\\Mda::class, \'exclude_reversed\' => false, \'dimensions\' => [\'type\' => [\'label\' => \'Type\', \'column\' => \'type\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Organizations\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'type\' => [\'column\' => \'type\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\']]],
    \'programme_catalogue\' => [\'label\' => \'Programme catalogue\', \'coordination\' => false, \'admin\' => true, \'model\' => \\App\\Domain\\Programme\\Models\\Programme::class, \'exclude_reversed\' => false, \'dimensions\' => [\'type\' => [\'label\' => \'Type\', \'column\' => \'type\', \'render\' => \'title\'], \'benefit_category\' => [\'label\' => \'Benefit category\', \'column\' => \'benefit_category\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Programmes\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'type\' => [\'column\' => \'type\', \'kind\' => \'equals\'], \'benefit_category\' => [\'column\' => \'benefit_category\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    /*
     * Duplicate review is BOTH governance data (platform-wide data quality) and each
     * MDA\'s own operational record — the same rows the MDA console\'s Duplicate
     * Resolution module already shows them. So it stays `admin` (it belongs to the
     * administration console\'s catalogue and to a governance scope unrestricted) and
     * additionally declares `mda_scopable`: an MDA scope may report on its OWN rows,
     * constrained through the owning import batch in AdHocReportBuilder::applyScope.
     *
     * State-wide-but-not-governance (Executive, SP Coordination) is deliberately NOT
     * admitted by that exception — the invariant that oversight sees programme data
     * but not the platform\'s own administrative records is unchanged.
     */
    \'duplicates\' => [\'label\' => \'Duplicate review\', \'coordination\' => false, \'admin\' => true, \'mda_scopable\' => true, \'model\' => \\App\\Domain\\Registry\\Models\\ImportRow::class, \'exclude_reversed\' => false, \'dimensions\' => [\'match_band\' => [\'label\' => \'Match band\', \'column\' => \'match_band\', \'render\' => \'title\'], \'resolution\' => [\'label\' => \'Resolution\', \'column\' => \'resolution\', \'render\' => \'title\']], \'measures\' => [\'count\' => [\'label\' => \'Rows\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'match_band\' => [\'column\' => \'match_band\', \'kind\' => \'equals\'], \'resolution\' => [\'column\' => \'resolution\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    \'audit\' => [\'label\' => \'Audit events\', \'coordination\' => false, \'admin\' => true, \'model\' => \\App\\Domain\\Audit\\Models\\AuditLog::class, \'exclude_reversed\' => false, \'dimensions\' => [\'action\' => [\'label\' => \'Action\', \'column\' => \'action\', \'render\' => \'action\'], \'entity_type\' => [\'label\' => \'Entity\', \'column\' => \'entity_type\', \'render\' => \'class\'], \'actor_mda\' => [\'label\' => \'Actor MDA\', \'column\' => \'actor_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Events\', \'sql\' => \'count(*)\', \'render\' => \'int\']], \'filters\' => [\'action\' => [\'column\' => \'action\', \'kind\' => \'equals\'], \'entity_type\' => [\'column\' => \'entity_type\', \'kind\' => \'equals\'], \'mda_id\' => [\'column\' => \'actor_mda_id\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
    \'imports\' => [\'label\' => \'Import batches\', \'coordination\' => false, \'admin\' => true, \'model\' => \\App\\Domain\\Registry\\Models\\ImportBatch::class, \'exclude_reversed\' => false, \'dimensions\' => [\'source\' => [\'label\' => \'Source\', \'column\' => \'source\', \'render\' => \'title\'], \'status\' => [\'label\' => \'Status\', \'column\' => \'status\', \'render\' => \'title\'], \'owner_mda\' => [\'label\' => \'Owner MDA\', \'column\' => \'owner_mda_id\', \'render\' => \'mda\']], \'measures\' => [\'count\' => [\'label\' => \'Batches\', \'sql\' => \'count(*)\', \'render\' => \'int\'], \'total_rows\' => [\'label\' => \'Rows\', \'sql\' => \'coalesce(sum(total_rows), 0)\', \'render\' => \'int\'], \'valid_rows\' => [\'label\' => \'Valid rows\', \'sql\' => \'coalesce(sum(valid_rows), 0)\', \'render\' => \'int\'], \'invalid_rows\' => [\'label\' => \'Invalid rows\', \'sql\' => \'coalesce(sum(invalid_rows), 0)\', \'render\' => \'int\'], \'committed_rows\' => [\'label\' => \'Committed rows\', \'sql\' => \'coalesce(sum(committed_rows), 0)\', \'render\' => \'int\']], \'filters\' => [\'mda_id\' => [\'column\' => \'owner_mda_id\', \'kind\' => \'equals\'], \'source\' => [\'column\' => \'source\', \'kind\' => \'equals\'], \'status\' => [\'column\' => \'status\', \'kind\' => \'equals\'], \'date_from\' => [\'column\' => \'created_at\', \'kind\' => \'date_from\'], \'date_to\' => [\'column\' => \'created_at\', \'kind\' => \'date_to\']]],
]',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 331,
            'startTokenPos' => 95,
            'startFilePos' => 1871,
            'endTokenPos' => 3658,
            'endFilePos' => 17978,
          ),
        ),
        'docComment' => '/**
 * @var array<string, array<string, mixed>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 331,
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
            'startLine' => 336,
            'endLine' => 336,
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
        'startLine' => 336,
        'endLine' => 339,
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
            'startLine' => 341,
            'endLine' => 341,
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
        'startLine' => 341,
        'endLine' => 344,
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
      'isAdmin' => 
      array (
        'name' => 'isAdmin',
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
            'startLine' => 347,
            'endLine' => 347,
            'startColumn' => 36,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** Whether a dataset is administrative/governance data (System Administrator only). */',
        'startLine' => 347,
        'endLine' => 350,
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
      'isMdaScopable' => 
      array (
        'name' => 'isMdaScopable',
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
            'startLine' => 359,
            'endLine' => 359,
            'startColumn' => 42,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Whether an administrative dataset also admits an MDA scope, restricted to that
 * MDA\'s own rows. Only `duplicates` does: it is simultaneously platform governance
 * data and the MDA\'s own operational record. Every such dataset MUST have a
 * corresponding scope clause in AdHocReportBuilder::applyScope — without one the
 * query would be platform-wide.
 */',
        'startLine' => 359,
        'endLine' => 362,
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
            'startLine' => 364,
            'endLine' => 364,
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
            'startLine' => 364,
            'endLine' => 364,
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
        'startLine' => 364,
        'endLine' => 381,
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
            'startLine' => 389,
            'endLine' => 389,
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
        'startLine' => 389,
        'endLine' => 407,
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
            'startLine' => 413,
            'endLine' => 413,
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
        'startLine' => 413,
        'endLine' => 421,
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