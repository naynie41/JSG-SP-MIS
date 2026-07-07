<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Referral/Jobs/EscalateOverdueReferrals.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Referral\Jobs\EscalateOverdueReferrals
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-742c9d159f976fc035d78627c6db98bf7d09d6e9d4f2d7ba29fbcc6b3ff2f94e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'filename' => '/var/www/html/app/Domain/Referral/Jobs/EscalateOverdueReferrals.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Referral\\Jobs',
    'name' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
    'shortName' => 'EscalateOverdueReferrals',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Scheduled SLA sweep (PRD FR-REF-04/05). Flags overdue referrals and escalates
 * them one tier per elapsed window (capped at the configured chain length),
 * dispatching {@see ReferralSlaBreached} whenever the level increases. It NEVER
 * changes a referral\'s status — no auto-close; it only escalates + notifies.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 93,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Bus\\Queueable',
      3 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
      'ACTIVE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'implementingClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'name' => 'ACTIVE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\App\\Domain\\Referral\\Enums\\ReferralStatus::Created, \\App\\Domain\\Referral\\Enums\\ReferralStatus::MoreInfoRequested, \\App\\Domain\\Referral\\Enums\\ReferralStatus::Accepted, \\App\\Domain\\Referral\\Enums\\ReferralStatus::InProgress]',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 36,
            'startTokenPos' => 111,
            'startFilePos' => 1117,
            'endTokenPos' => 133,
            'endFilePos' => 1269,
          ),
        ),
        'docComment' => '/** Non-terminal statuses that can breach an SLA. */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'audit' => 
          array (
            'name' => 'audit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Audit\\Services\\AuditLogger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 28,
            'endColumn' => 45,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Referral\\Jobs',
        'declaringClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'implementingClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'currentClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'aliasName' => NULL,
      ),
      'enteredAt' => 
      array (
        'name' => 'enteredAt',
        'parameters' => 
        array (
          'referral' => 
          array (
            'name' => 'referral',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Referral\\Models\\Referral',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 32,
            'endColumn' => 49,
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
                  'name' => 'Illuminate\\Support\\Carbon',
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
        'docComment' => '/** When the referral entered its current status (the SLA clock start). */',
        'startLine' => 83,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Referral\\Jobs',
        'declaringClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'implementingClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
        'currentClassName' => 'App\\Domain\\Referral\\Jobs\\EscalateOverdueReferrals',
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