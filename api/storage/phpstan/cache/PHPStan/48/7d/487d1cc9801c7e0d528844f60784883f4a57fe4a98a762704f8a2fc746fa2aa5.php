<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Graduation/Services/GraduationProgressService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\Services\GraduationProgressService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b1cf488932ea20578fdd35611e0d865bbf32468bb882d7e0e0e684e0f0640f1d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'filename' => '/var/www/html/app/Domain/Graduation/Services/GraduationProgressService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Graduation\\Services',
    'name' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
    'shortName' => 'GraduationProgressService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Tracks a beneficiary/household\'s progress toward graduation (FR-GRD-02): it evaluates
 * the enrolling MDA\'s active {@see GraduationCriteria} for the enrolment\'s programme
 * against real data — benefits delivered, months enrolled, total value — and reports
 * per-rule and overall progress. Automatic rules are computed; the manual rule is only
 * ever met by an explicit officer decision (never auto-satisfied).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 142,
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
      'forEnrollment' => 
      array (
        'name' => 'forEnrollment',
        'parameters' => 
        array (
          'enrollment' => 
          array (
            'name' => 'enrollment',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Enrollment',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 35,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 29,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Services',
        'declaringClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'implementingClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'currentClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'aliasName' => NULL,
      ),
      'activeCriteria' => 
      array (
        'name' => 'activeCriteria',
        'parameters' => 
        array (
          'enrollment' => 
          array (
            'name' => 'enrollment',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Enrollment',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 36,
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
                  'name' => 'App\\Domain\\Graduation\\Models\\GraduationCriteria',
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
        'startLine' => 94,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation\\Services',
        'declaringClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'implementingClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'currentClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'aliasName' => NULL,
      ),
      'ledgerTotals' => 
      array (
        'name' => 'ledgerTotals',
        'parameters' => 
        array (
          'enrollment' => 
          array (
            'name' => 'enrollment',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Enrollment',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 122,
            'endLine' => 122,
            'startColumn' => 35,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Full-ledger totals for this enrolment\'s subject, in this programme (cross-MDA
 * history).
 *
 * A HOUSEHOLD enrolment has no `beneficiary_id`, and `benefits` has no household
 * column — a benefit is always delivered to a person. So a household\'s ledger is its
 * members\' ledger, and this reads it that way. It previously returned zero for a
 * household, which is the dangerous answer rather than an error: a household with a
 * year of support showed no progress, and under `all` logic could never become
 * eligible, with nothing to say why.
 *
 * Only CURRENT members count (`left_at is null`). Support follows the person: someone
 * who has left is no longer part of this household, and crediting it for what they
 * received would graduate it on the strength of help it no longer contains.
 *
 * @return array{0: int, 1: int}
 */',
        'startLine' => 122,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Graduation\\Services',
        'declaringClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'implementingClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'currentClassName' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
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