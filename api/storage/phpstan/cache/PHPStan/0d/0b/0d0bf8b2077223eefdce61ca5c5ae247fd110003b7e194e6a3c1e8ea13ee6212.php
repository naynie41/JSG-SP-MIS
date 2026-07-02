<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Audit/Support/AuditScrubber.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Audit\Support\AuditScrubber
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-450d4882876ee6f46e74c731ee46cb3beb13efbcfe09ee4865142e7d9f9f1a14',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'filename' => '/var/www/html/app/Domain/Audit/Support/AuditScrubber.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Audit\\Support',
    'name' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
    'shortName' => 'AuditScrubber',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Removes secrets and masks PII from audit before/after snapshots so the audit
 * log never stores raw secrets or personal data (SECURITY.md §6).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 62,
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
      'REDACTED' => 
      array (
        'declaringClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'implementingClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'name' => 'REDACTED',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'[redacted]\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 31,
            'startFilePos' => 277,
            'endTokenPos' => 31,
            'endFilePos' => 288,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'scrub' => 
      array (
        'name' => 'scrub',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 27,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extraOmit' => 
          array (
            'name' => 'extraOmit',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 21,
                'endLine' => 21,
                'startTokenPos' => 53,
                'startFilePos' => 594,
                'endTokenPos' => 54,
                'endFilePos' => 595,
              ),
            ),
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 46,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extraMask' => 
          array (
            'name' => 'extraMask',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 21,
                'endLine' => 21,
                'startTokenPos' => 63,
                'startFilePos' => 617,
                'endTokenPos' => 64,
                'endFilePos' => 618,
              ),
            ),
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 69,
            'endColumn' => 89,
            'parameterIndex' => 2,
            'isOptional' => true,
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
 * @param  array<string, mixed>  $attributes
 * @param  list<string>  $extraOmit  model-specific secret fields
 * @param  list<string>  $extraMask  model-specific PII fields
 * @return array<string, mixed>
 */',
        'startLine' => 21,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Audit\\Support',
        'declaringClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'implementingClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'currentClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'aliasName' => NULL,
      ),
      'mask' => 
      array (
        'name' => 'mask',
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
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 27,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 43,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Audit\\Support',
        'declaringClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'implementingClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
        'currentClassName' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
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