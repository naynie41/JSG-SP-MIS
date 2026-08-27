<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Export/BeneficiaryListExport.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Export\BeneficiaryListExport
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f5085a62440007f0ea5c484d34640592edd1a083fed6e71a3bad58dc6c17c4aa',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'filename' => '/var/www/html/app/Domain/Registry/Export/BeneficiaryListExport.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Export',
    'name' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
    'shortName' => 'BeneficiaryListExport',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Turns the beneficiary registry list into a {@see ReportData} for the shared Phase 6
 * exporters (PRD FR-REG-04 + FR-RPT-03). One place owns the filter logic and the
 * column spec so the export is byte-for-byte the same view the list endpoint returns —
 * same scope, same filters/search, same masking. NIN/BVN are marked sensitive (masked
 * by {@see ReportData::cell()}) UNLESS the caller may reveal them.
 *
 * Two entry points share everything below:
 *  - the controller streams a small export in-request (rows already MDA-scoped by the
 *    global {@see MdaScope});
 *  - {@see self::fromRun()} rebuilds the same query on the queue from a captured
 *    {@see DashboardScope}, so a large export renders exactly what the requester saw.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 231,
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
      'DEFAULT_SYNC_MAX' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'name' => 'DEFAULT_SYNC_MAX',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '2000',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 78,
            'startFilePos' => 1351,
            'endTokenPos' => 78,
            'endFilePos' => 1354,
          ),
        ),
        'docComment' => '/** Rows above this count are generated on the queue instead of streamed. */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'MAX_ROWS' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'name' => 'MAX_ROWS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '100000',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 91,
            'startFilePos' => 1446,
            'endTokenPos' => 91,
            'endFilePos' => 1452,
          ),
        ),
        'docComment' => '/** Hard ceiling to keep any single export bounded. */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'REVEAL_PERMISSION' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'name' => 'REVEAL_PERMISSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'export.reveal_pii\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 102,
            'startFilePos' => 1493,
            'endTokenPos' => 102,
            'endFilePos' => 1511,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'syncMax' => 
      array (
        'name' => 'syncMax',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'filtersFromRequest' => 
      array (
        'name' => 'filtersFromRequest',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 40,
            'endColumn' => 55,
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
 * The list filters, read with the SAME param names the list endpoint uses
 * (`search`, `filter[...]`), plus the source/batch axes.
 *
 * @return array{search: string, lga: ?string, ward: ?string, status: ?string, source: ?string, batch: ?string}
 */',
        'startLine' => 51,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'applyFilters' => 
      array (
        'name' => 'applyFilters',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filters' => 
          array (
            'name' => 'filters',
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 50,
            'endColumn' => 63,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Apply the list filters to a query. This is the SINGLE filter implementation
 * the list endpoint and the export both call, so they can never diverge.
 *
 * @param  Builder<Beneficiary>  $query
 * @param  array<string, mixed>  $filters
 * @return Builder<Beneficiary>
 */',
        'startLine' => 71,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'ordered' => 
      array (
        'name' => 'ordered',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 29,
            'endColumn' => 42,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The same order the list uses (newest registration first), so the exported
 * file matches the on-screen sequence.
 *
 * @param  Builder<Beneficiary>  $query
 * @return Builder<Beneficiary>
 */',
        'startLine' => 107,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'columns' => 
      array (
        'name' => 'columns',
        'parameters' => 
        array (
          'reveal' => 
          array (
            'name' => 'reveal',
            'default' => NULL,
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 29,
            'endColumn' => 40,
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
 * The export column spec, mirroring the visible list. NIN/BVN are sensitive
 * (masked to last-4 by the exporter) unless the caller may reveal them.
 *
 * @return list<ReportColumn>
 */',
        'startLine' => 118,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'toReportData' => 
      array (
        'name' => 'toReportData',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
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
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'reveal' => 
          array (
            'name' => 'reveal',
            'default' => NULL,
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
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 50,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'scopeLabel' => 
          array (
            'name' => 'scopeLabel',
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
            'startColumn' => 64,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Domain\\Reporting\\Export\\ReportData',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the {@see ReportData} from an already scoped + filtered query.
 *
 * @param  Builder<Beneficiary>  $query
 */',
        'startLine' => 141,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'fromRun' => 
      array (
        'name' => 'fromRun',
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
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 29,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
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
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 52,
            'endColumn' => 65,
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
            'name' => 'App\\Domain\\Reporting\\Export\\ReportData',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Rebuild the export on the queue from a captured scope + params (see
 * ReportService::queueBeneficiaryExport). Applies the SAME scope and filters
 * the request resolved, so a deferred large export honours exactly the
 * requester\'s entitlement — out-of-scope rows never appear.
 *
 * @param  array<string, mixed>|null  $params
 */',
        'startLine' => 167,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'scopedQuery' => 
      array (
        'name' => 'scopedQuery',
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
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 33,
            'endColumn' => 53,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The beneficiary query constrained to a resolved scope, WITHOUT the request
 * MdaScope (the queue has no auth session). Mirrors MdaScope exactly: state-wide
 * sees all; an MDA scope is limited to its owner MDAs; anything else sees nothing.
 *
 * @return Builder<Beneficiary>
 */',
        'startLine' => 186,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'row' => 
      array (
        'name' => 'row',
        'parameters' => 
        array (
          'beneficiary' => 
          array (
            'name' => 'beneficiary',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Registry\\Models\\Beneficiary',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 26,
            'endColumn' => 49,
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
 * One beneficiary → a row keyed by column key. Raw NIN/BVN are passed through;
 * masking happens at render via the sensitive column flag (never here), so a
 * reveal-less export can never leak an identifier.
 *
 * @return array<string, scalar|null>
 */',
        'startLine' => 204,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'aliasName' => NULL,
      ),
      'stringOrNull' => 
      array (
        'name' => 'stringOrNull',
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
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 35,
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
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 222,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Domain\\Registry\\Export',
        'declaringClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'implementingClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
        'currentClassName' => 'App\\Domain\\Registry\\Export\\BeneficiaryListExport',
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