<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Graduation\Services\GraduationProgressService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\Services\GraduationProgressService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-66e957c93405a3bc6e74d30391034de88e9b2db0416cbe460d8e5070ffb13867',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Graduation\\Services\\GraduationProgressService',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Graduation/Services/GraduationProgressService.php',
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
    'startLine' => 23,
    'endLine' => 122,
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
            'startLine' => 28,
            'endLine' => 28,
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
        'startLine' => 28,
        'endLine' => 91,
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
            'startLine' => 93,
            'endLine' => 93,
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
        'startLine' => 93,
        'endLine' => 102,
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
            'startLine' => 109,
            'endLine' => 109,
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
 * Full-ledger totals for the beneficiary in this programme (cross-MDA history).
 *
 * @return array{0: int, 1: int}
 */',
        'startLine' => 109,
        'endLine' => 121,
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