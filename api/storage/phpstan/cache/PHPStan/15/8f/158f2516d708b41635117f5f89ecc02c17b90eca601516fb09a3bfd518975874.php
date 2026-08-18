<?php declare(strict_types = 1);

// odsl-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Support\NameSplitter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\NameSplitter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-27cfa70d43fad955f35149df5cd783a9a546ec9b80c87319083c5c8f37500a34',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'filename' => 'C:/Users/ACER/Desktop/JSG-SP-MIS/JSG-SP-MIS/api/app/Domain/Registry/Support/NameSplitter.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\NameSplitter',
    'shortName' => 'NameSplitter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Splits a single full-name column into first/last name.
 *
 * Many MDA exports carry one `Name` column rather than separate given/surname fields.
 * Mapping such a column to BOTH `first_name` and `last_name` is what produced records
 * reading "Rekiya Bagwai Rekiya Bagwai"; mapping it to one leaves the other empty and
 * the row fails validation. So the column is mapped once, to `full_name`, and split here.
 *
 * The rule (stakeholder decision, not inferred):
 *
 *   "Amina"                → first: Amina        last: (none)
 *   "Rekiya Bagwai"        → first: Rekiya       last: Bagwai
 *   "Barira Sadau Barde"   → first: Barira       last: Sadau Barde
 *   "A B C D"              → first: A            last: B C D
 *
 * The first token is the first name; EVERYTHING after it is the last name. Note that
 * `middle_name` is deliberately never populated by the split — the instruction is that a
 * middle token belongs to the surname, and inferring otherwise would silently disagree.
 *
 * A single token yields no last name rather than a guessed one. `last_name` is required
 * (and is the fuzzy blocking key), so such a row is rejected by validation — which is
 * the honest outcome: SP-MIS cannot invent a surname it was not given.
 *
 * Explicit `first_name` / `last_name` columns always win; this only fills what they left
 * empty. See {@see ColumnMapper::apply()}.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 81,
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
      'split' => 
      array (
        'name' => 'split',
        'parameters' => 
        array (
          'fullName' => 
          array (
            'name' => 'fullName',
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 34,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array{first_name: string|null, last_name: string|null}
 */',
        'startLine' => 40,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'aliasName' => NULL,
      ),
      'tokenise' => 
      array (
        'name' => 'tokenise',
        'parameters' => 
        array (
          'fullName' => 
          array (
            'name' => 'fullName',
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
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 38,
            'endColumn' => 54,
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
 * Name tokens, with punctuation-free whitespace collapsed.
 *
 * Splits on any whitespace run (files arrive with double spaces, tabs and non-breaking
 * spaces from spreadsheet exports), and drops empty tokens so " Ada  Okoye " does not
 * yield a blank first name.
 *
 * @return list<string>
 */',
        'startLine' => 67,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Domain\\Registry\\Support',
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
        'currentClassName' => 'App\\Domain\\Registry\\Support\\NameSplitter',
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