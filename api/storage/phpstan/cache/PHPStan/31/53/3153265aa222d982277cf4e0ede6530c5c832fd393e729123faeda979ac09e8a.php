<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Programme/Services/ActivityLocationService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Programme\Services\ActivityLocationService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-6eb143ddf3f96f1c08fd437bae6ccd71ba17bdd224e807cdb42a81083197a6b0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'filename' => '/var/www/html/app/Domain/Programme/Services/ActivityLocationService.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Programme\\Services',
    'name' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
    'shortName' => 'ActivityLocationService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Turns a submitted location set into `activity_locations` rows.
 *
 * The submitted shape is one entry per LGA — `{ lga_id, ward_ids: [...] }`, or
 * `{ lga_id, whole_lga: true }` — because that is how the set is chosen in the UI and
 * how it reads back. It is flattened to one row per ward here, so the storage shape
 * stays queryable by coverage/GIS aggregations.
 *
 * Validation lives in {@see ValidatesLocationSet}
 * (it must produce field-level 422s); this service assumes a validated set and owns
 * only the flatten + replace.
 *
 * DESCRIPTIVE ONLY: nothing here — and nothing anywhere — checks the beneficiaries
 * uploaded under the activity against these places.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 202,
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
      'sync' => 
      array (
        'name' => 'sync',
        'parameters' => 
        array (
          'activity' => 
          array (
            'name' => 'activity',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Activity',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 26,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'set' => 
          array (
            'name' => 'set',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 46,
            'endColumn' => 55,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replaces an activity\'s entire location set.
 *
 * Replace rather than merge: the set is edited as a whole in the UI, so a submitted
 * set is the complete intended statement, and removing an LGA has to remove its
 * wards with it. Wrapped in a transaction so a failure never leaves an activity
 * with half a set.
 *
 * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
 */',
        'startLine' => 41,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'aliasName' => NULL,
      ),
      'flatten' => 
      array (
        'name' => 'flatten',
        'parameters' => 
        array (
          'set' => 
          array (
            'name' => 'set',
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
            'startLine' => 69,
            'endLine' => 69,
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
 * One (lga_id, ward_id) pair per row; ward_id null for a whole-LGA declaration.
 *
 * An entry with no wards is a whole-LGA row — selecting an LGA and no wards means
 * the same thing as ticking "whole LGA", and storing it as one null-ward row keeps
 * a single representation of that idea instead of two.
 *
 * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
 * @return list<array{0: string, 1: string|null}>
 */',
        'startLine' => 69,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'aliasName' => NULL,
      ),
      'present' => 
      array (
        'name' => 'present',
        'parameters' => 
        array (
          'activity' => 
          array (
            'name' => 'activity',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Domain\\Programme\\Models\\Activity',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 29,
            'endColumn' => 46,
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
 * The set as the API returns it: grouped by LGA, wards nested, whole-LGA flagged.
 *
 * Built from the loaded relation so a caller that eager-loaded pays no extra query.
 *
 * @return list<array<string, mixed>>
 */',
        'startLine' => 100,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'aliasName' => NULL,
      ),
      'misplacedWards' => 
      array (
        'name' => 'misplacedWards',
        'parameters' => 
        array (
          'set' => 
          array (
            'name' => 'set',
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
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 36,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wards that do not belong to the LGA they were submitted under, keyed by ward id.
 *
 * Shared by the form requests so the "ward belongs to its LGA" rule has exactly one
 * implementation. Returns the offenders rather than a bool so the caller can name
 * them in the error.
 *
 * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
 * @return array<string, string> ward id => the lga id it was wrongly submitted under
 */',
        'startLine' => 158,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'aliasName' => NULL,
      ),
      'duplicateLgas' => 
      array (
        'name' => 'duplicateLgas',
        'parameters' => 
        array (
          'set' => 
          array (
            'name' => 'set',
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
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 35,
            'endColumn' => 44,
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
 * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
 * @return list<string> lga ids submitted more than once
 */',
        'startLine' => 188,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'aliasName' => NULL,
      ),
      'lgaNames' => 
      array (
        'name' => 'lgaNames',
        'parameters' => 
        array (
          'ids' => 
          array (
            'name' => 'ids',
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
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * @return array<string, string> lga id => name, for error messages
 */',
        'startLine' => 198,
        'endLine' => 201,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Programme\\Services',
        'declaringClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'implementingClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
        'currentClassName' => 'App\\Domain\\Programme\\Services\\ActivityLocationService',
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