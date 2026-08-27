<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Enums/ImportRowResolution.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Enums\ImportRowResolution
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-433a30945300562aeb4f43c15427b10e7210ef01367b074e4a69d4a1cc0af57b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'filename' => '/var/www/html/app/Domain/Registry/Enums/ImportRowResolution.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Enums',
    'name' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
    'shortName' => 'ImportRowResolution',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => true,
    'isBackedEnum' => true,
    'modifiers' => 0,
    'docComment' => '/**
 * How the importing officer resolved a flagged import row (PRD FR-DUP-05, §9).
 *
 * - New  — adjudicate as a DISTINCT person and create a new beneficiary (requires a
 *          justification). Valid only for a **probable** (fuzzy) match; an exact
 *          match is definitive and is never adjudicated as new.
 * - Link — provide-service: do not create; raise a Service Request against the
 *          matched existing beneficiary. Available at every band, and ONLY against a
 *          record owned by ANOTHER MDA.
 * - Own  — the match is a beneficiary this MDA ALREADY OWNS: a re-upload of its own
 *          data. Do not create a second record, and do not raise a Service Request —
 *          an MDA does not ask permission to serve its own beneficiary. The existing
 *          person stays and receives a NEW INTERVENTION under this batch\'s
 *          programme/activity, because re-uploading own data usually means delivering
 *          again. The duplicate ROW is blocked; the person is not.
 * - Skip — discard this row. Available at every band.
 *
 * A null resolution means "unresolved"; a non-flagged row defaults to New at commit.
 *
 * Link and Own are not interchangeable labels for one act — which applies is decided by
 * OWNERSHIP of the matched record, so {@see ImportCommitter} re-derives it from the data
 * at commit instead of trusting what a client stored.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 50,
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
      'name' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'name' => 'name',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'value' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'name' => 'value',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
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
      'againstExisting' => 
      array (
        'name' => 'againstExisting',
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
 * The two resolutions that act on an EXISTING matched record rather than creating
 * one. Both need `resolved_beneficiary_id`, and the committer decides between them
 * by ownership.
 *
 * @return list<self>
 */',
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'aliasName' => NULL,
      ),
      'cases' => 
      array (
        'name' => 'cases',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
            'name' => 'static',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'aliasName' => NULL,
      ),
      'tryFrom' => 
      array (
        'name' => 'tryFrom',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
                  'name' => 'static',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Enums',
        'declaringClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'implementingClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
        'currentClassName' => 'App\\Domain\\Registry\\Enums\\ImportRowResolution',
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
    'backingType' => 
    array (
      'name' => 'string',
      'isIdentifier' => true,
    ),
    'cases' => 
    array (
      'New' => 
      array (
        'name' => 'New',
        'value' => 
        array (
          'code' => '\'new\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 37,
            'startFilePos' => 1584,
            'endTokenPos' => 37,
            'endFilePos' => 1588,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Link' => 
      array (
        'name' => 'Link',
        'value' => 
        array (
          'code' => '\'link\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 46,
            'startFilePos' => 1607,
            'endTokenPos' => 46,
            'endFilePos' => 1612,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 23,
      ),
      'Own' => 
      array (
        'name' => 'Own',
        'value' => 
        array (
          'code' => '\'own\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 55,
            'startFilePos' => 1630,
            'endTokenPos' => 55,
            'endFilePos' => 1634,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Skip' => 
      array (
        'name' => 'Skip',
        'value' => 
        array (
          'code' => '\'skip\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 64,
            'startFilePos' => 1653,
            'endTokenPos' => 64,
            'endFilePos' => 1658,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 23,
      ),
    ),
  ),
));