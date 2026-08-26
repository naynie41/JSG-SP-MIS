<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Reporting\Services\MdaActionRequiredService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Services\MdaActionRequiredService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-88a07c2fbbadd2468da3a73624f1917257a4ce5e4783ecdfe533ab6b0f0ec530',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Reporting/Services/MdaActionRequiredService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Services',
    'name' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
    'shortName' => 'MdaActionRequiredService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The MDA console\'s action-required counters: work waiting on THIS MDA right now.
 *
 * These are deliberately NOT part of the Phase 6 dashboard metrics, for two reasons:
 *
 *  1. **They must be live.** An unfiltered `/dashboard` serves a snapshot refreshed on
 *     a 15-minute cycle ({@see DashboardService}). A work queue that reports two
 *     approvals outstanding after the officer already cleared them is worse than no
 *     counter at all, so these are computed per request.
 *  2. **They are DIRECTIONAL.** The dashboard\'s referral block counts everything the
 *     MDA is party to, in either direction. "Awaiting me" is specifically the INBOUND
 *     side — a referral this MDA received, or a request-to-serve on a beneficiary this
 *     MDA owns. That distinction cannot be derived from `referrals.by_status`.
 *
 * Both are COUNTS only. Reading the underlying lists to size them would pull
 * beneficiary records into a page that just renders a number (SECURITY.md — minimise
 * PII on screen).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 34,
    'endLine' => 114,
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
      'REFERRAL_OPEN' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'name' => 'REFERRAL_OPEN',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Referral\\Enums\\ReferralStatus::Created->value, \\App\\Domain\\Referral\\Enums\\ReferralStatus::MoreInfoRequested->value]',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 45,
            'startTokenPos' => 73,
            'startFilePos' => 1721,
            'endTokenPos' => 89,
            'endFilePos' => 1817,
          ),
        ),
        'docComment' => '/**
 * Referral states that still need the receiving MDA to act. `more_info_requested`
 * counts because the ball is back with the receiver.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'GRIEVANCE_OPEN' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'name' => 'GRIEVANCE_OPEN',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Grievance\\Enums\\GrievanceStatus::Open->value, \\App\\Domain\\Grievance\\Enums\\GrievanceStatus::Assigned->value, \\App\\Domain\\Grievance\\Enums\\GrievanceStatus::InProgress->value]',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 56,
            'startTokenPos' => 102,
            'startFilePos' => 1984,
            'endTokenPos' => 125,
            'endFilePos' => 2114,
          ),
        ),
        'docComment' => '/**
 * Grievance states still on this MDA\'s desk. Resolved and Closed are done.
 *
 * @var list<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'forUser' => 
      array (
        'name' => 'forUser',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Access\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 29,
            'endColumn' => 38,
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
 * @return array{pending_referrals: int, pending_service_requests: int, open_grievances: int, breached_grievances: int, mda_id: string|null}
 */',
        'startLine' => 61,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Services',
        'declaringClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'implementingClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
        'currentClassName' => 'App\\Domain\\Reporting\\Services\\MdaActionRequiredService',
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