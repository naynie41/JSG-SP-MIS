<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Reporting/Support/DashboardScope.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Reporting\Support\DashboardScope
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-b52c0b828d2c06e49788a53c42a88c93b03787ff3b7ab3e810ece8ff0d01d914',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'filename' => '/var/www/html/app/Domain/Reporting/Support/DashboardScope.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Reporting\\Support',
    'name' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
    'shortName' => 'DashboardScope',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * A resolved dashboard scope (PRD FR-DSH-01). It expresses WHICH data a caller may
 * see, independent of the implicit request-time MdaScope, so the reporting layer can
 * apply the same constraint whether it runs in a request or on the (unauthenticated)
 * scheduler/queue. Three kinds:
 *
 *  - state_wide — oversight (cross-mda.view): all MDAs, all programmes.
 *  - mda        — an MDA user: limited to `mdaIds` (own MDA + any cross-MDA grants).
 *  - partner    — a Development Partner: limited to their funded `programmeIds`.
 *
 * `mdaIds`/`programmeIds` are `null` when that axis is unconstrained (state-wide).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 180,
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
      'KIND_STATE_WIDE' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'KIND_STATE_WIDE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'state_wide\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 33,
            'startFilePos' => 772,
            'endTokenPos' => 33,
            'endFilePos' => 783,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'KIND_MDA' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'KIND_MDA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mda\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 44,
            'startFilePos' => 815,
            'endTokenPos' => 44,
            'endFilePos' => 819,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'KIND_PARTNER' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'KIND_PARTNER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'partner\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 55,
            'startFilePos' => 855,
            'endTokenPos' => 55,
            'endFilePos' => 863,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
      'kind' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'kind',
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
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 9,
        'endColumn' => 36,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mdaIds' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'mdaIds',
        'modifiers' => 2177,
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 9,
        'endColumn' => 38,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'programmeIds' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'programmeIds',
        'modifiers' => 2177,
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 9,
        'endColumn' => 44,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'partnerId' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'partnerId',
        'modifiers' => 2177,
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 9,
        'endColumn' => 42,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'label' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'label',
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
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 9,
        'endColumn' => 37,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'governance' => 
      array (
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'name' => 'governance',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 125,
            'startFilePos' => 1979,
            'endTokenPos' => 125,
            'endFilePos' => 1983,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 9,
        'endColumn' => 48,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'kind' => 
          array (
            'name' => 'kind',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 9,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'mdaIds' => 
          array (
            'name' => 'mdaIds',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'programmeIds' => 
          array (
            'name' => 'programmeIds',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 9,
            'endColumn' => 44,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'partnerId' => 
          array (
            'name' => 'partnerId',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 9,
            'endColumn' => 42,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'label' => 
          array (
            'name' => 'label',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 9,
            'endColumn' => 37,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'governance' => 
          array (
            'name' => 'governance',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 45,
                'endLine' => 45,
                'startTokenPos' => 125,
                'startFilePos' => 1979,
                'endTokenPos' => 125,
                'endFilePos' => 1983,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 9,
            'endColumn' => 48,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  list<string>|null  $mdaIds  null = all MDAs
 * @param  list<string>|null  $programmeIds  null = all programmes; set for partner
 * @param  string|null  $partnerId  the funding partner\'s user id (partner scope only)
 * @param  bool  $governance  whether GOVERNANCE data (users, audit, imports, the
 *                            administrative datasets) is in scope. This is a second,
 *                            independent axis: state-wide answers "how much of the
 *                            PROGRAMME data may you see", governance answers "may you
 *                            see who did what". Executive/SP Coordination are
 *                            state-wide but NOT governance; only a System
 *                            Administrator is both.
 */',
        'startLine' => 39,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 8,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'stateWide' => 
      array (
        'name' => 'stateWide',
        'parameters' => 
        array (
          'governance' => 
          array (
            'name' => 'governance',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 48,
                'endLine' => 48,
                'startTokenPos' => 147,
                'startFilePos' => 2052,
                'endTokenPos' => 147,
                'endFilePos' => 2056,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 38,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'mda' => 
      array (
        'name' => 'mda',
        'parameters' => 
        array (
          'mdaIds' => 
          array (
            'name' => 'mdaIds',
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 32,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'label' => 
          array (
            'name' => 'label',
            'default' => 
            array (
              'code' => '\'My MDA\'',
              'attributes' => 
              array (
                'startLine' => 56,
                'endLine' => 56,
                'startTokenPos' => 205,
                'startFilePos' => 2286,
                'endTokenPos' => 205,
                'endFilePos' => 2293,
              ),
            ),
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 47,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  list<string>  $mdaIds
 */',
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'partner' => 
      array (
        'name' => 'partner',
        'parameters' => 
        array (
          'programmeIds' => 
          array (
            'name' => 'programmeIds',
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 36,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'partnerId' => 
          array (
            'name' => 'partnerId',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 267,
                'startFilePos' => 2823,
                'endTokenPos' => 267,
                'endFilePos' => 2826,
              ),
            ),
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 57,
            'endColumn' => 81,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'label' => 
          array (
            'name' => 'label',
            'default' => 
            array (
              'code' => '\'Funded programmes\'',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 276,
                'startFilePos' => 2845,
                'endTokenPos' => 276,
                'endFilePos' => 2863,
              ),
            ),
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 84,
            'endColumn' => 118,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A Development Partner scope. `$programmeIds` are the partner\'s FUNDED programmes
 * (the distinct programmes of the activities they fund, `activities.funding_partner_id`);
 * `$partnerId` identifies the partner so activity-precise funding metrics resolve.
 *
 * @param  list<string>  $programmeIds
 */',
        'startLine' => 70,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'rehydrate' => 
      array (
        'name' => 'rehydrate',
        'parameters' => 
        array (
          'kind' => 
          array (
            'name' => 'kind',
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'mdaIds' => 
          array (
            'name' => 'mdaIds',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 52,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'programmeIds' => 
          array (
            'name' => 'programmeIds',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 68,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'label' => 
          array (
            'name' => 'label',
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 90,
            'endColumn' => 102,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'governance' => 
          array (
            'name' => 'governance',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 354,
                'startFilePos' => 3392,
                'endTokenPos' => 354,
                'endFilePos' => 3396,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 105,
            'endColumn' => 128,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reconstruct a scope from its persisted parts (e.g. a captured report run), so a
 * queued job applies the exact scope resolved at request time.
 *
 * @param  list<string>|null  $mdaIds
 * @param  list<string>|null  $programmeIds
 */',
        'startLine' => 84,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'isStateWide' => 
      array (
        'name' => 'isStateWide',
        'parameters' => 
        array (
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
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'tier' => 
      array (
        'name' => 'tier',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * The oversight TIER this scope represents (PRD role tiering). Governor/Executive
 * (and SP Coordination) resolve to state-wide; a Development Partner to their funded
 * programmes; every other MDA user to an operational/own-MDA tier. Purely a label —
 * the actual enforcement is the scope\'s `mdaIds`/`programmeIds` applied in every query.
 */',
        'startLine' => 104,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'isPartner' => 
      array (
        'name' => 'isPartner',
        'parameters' => 
        array (
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
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'includesCoordinationMetrics' => 
      array (
        'name' => 'includesCoordinationMetrics',
        'parameters' => 
        array (
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
        'docComment' => '/** Coordination metrics (referrals/grievances/duplicates) apply to MDA/state scopes, not partners. */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'includesGovernanceData' => 
      array (
        'name' => 'includesGovernanceData',
        'parameters' => 
        array (
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
 * Whether the administrative/governance datasets (users, MDAs, programmes,
 * duplicates, audit, imports) are in scope. State-wide oversight is NOT enough —
 * an Executive sees all programme data but not who did what.
 */',
        'startLine' => 129,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'covers' => 
      array (
        'name' => 'covers',
        'parameters' => 
        array (
          'other' => 
          array (
            'name' => 'other',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 28,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Whether this scope is entitled to at least everything `$other` shows (PRD
 * FR-RPT-04). Used to check a recipient (this = recipient\'s scope) may receive a
 * report scoped to `$other` — so a schedule can never deliver out-of-scope data:
 *
 *  - a GOVERNANCE report is covered only by a governance scope — state-wide is not
 *    enough, so an Executive can never be sent an admin (user/audit/import) report
 *    even though they out-rank the MDA axis;
 *  - state-wide covers everything else;
 *  - an MDA scope covers another MDA scope only if its MDAs are a superset;
 *  - a partner scope covers another partner scope only if its programmes are a superset;
 *  - the axes never cross (an MDA scope cannot cover a partner/state-wide report).
 */',
        'startLine' => 147,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'aliasName' => NULL,
      ),
      'key' => 
      array (
        'name' => 'key',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * A stable key identifying this scope — the primary key of its snapshot row and
 * the cache key. Two callers with the same effective scope share one snapshot.
 */',
        'startLine' => 170,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Reporting\\Support',
        'declaringClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'implementingClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
        'currentClassName' => 'App\\Domain\\Reporting\\Support\\DashboardScope',
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