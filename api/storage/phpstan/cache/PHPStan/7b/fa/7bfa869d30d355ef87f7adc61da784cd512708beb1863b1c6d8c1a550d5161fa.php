<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Programme/Concerns/ValidatesLocationSet.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Programme\Concerns\ValidatesLocationSet
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-a6eb8ba2a790f6dd6d9f721d36bd4ba669e406dcd1fdac88bfde45254d9c65cb',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'filename' => '/var/www/html/app/Http/Requests/Programme/Concerns/ValidatesLocationSet.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Programme\\Concerns',
    'name' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
    'shortName' => 'ValidatesLocationSet',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The activity location-set rules, shared by every request that accepts one so the
 * three entry points cannot drift apart.
 *
 * Submitted shape — one entry per LGA:
 *
 *   "locations": [
 *     { "lga_id": "…", "ward_ids": ["…", "…"] },   // specific wards
 *     { "lga_id": "…", "whole_lga": true }          // the whole LGA
 *   ]
 *
 * Enforced here:
 *  - every `lga_id` and `ward_id` exists in the GEO.1 lookups;
 *  - each ward belongs to the LGA it was submitted under (the rule that matters —
 *    ward codes repeat across Jigawa, so a ward id under the wrong LGA is a real and
 *    silent way to mis-target an activity);
 *  - an LGA appears at most once, so its wards are unambiguous;
 *  - `whole_lga` and `ward_ids` are not both given.
 *
 * NOT enforced, deliberately: anything about the beneficiaries uploaded under the
 * activity. The set is a plan, not a constraint on the people.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 109,
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
      'locationSetRules' => 
      array (
        'name' => 'locationSetRules',
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
 * @return array<string, mixed>
 */',
        'startLine' => 37,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Requests\\Programme\\Concerns',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'aliasName' => NULL,
      ),
      'locationSetMessages' => 
      array (
        'name' => 'locationSetMessages',
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
 * @return array<string, string>
 */',
        'startLine' => 51,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Requests\\Programme\\Concerns',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'aliasName' => NULL,
      ),
      'validateLocationSet' => 
      array (
        'name' => 'validateLocationSet',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Validation\\Validator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 44,
            'endColumn' => 63,
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
        'docComment' => '/**
 * Cross-field checks that need the database, run only once the per-field rules
 * above have passed — otherwise a nonexistent ward id would produce two errors
 * saying different things.
 */',
        'startLine' => 64,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Requests\\Programme\\Concerns',
        'declaringClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'implementingClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
        'currentClassName' => 'App\\Http\\Requests\\Programme\\Concerns\\ValidatesLocationSet',
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