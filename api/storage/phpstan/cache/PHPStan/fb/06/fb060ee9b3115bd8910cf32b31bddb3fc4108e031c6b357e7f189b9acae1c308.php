<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Registry/Support/OriginalInput.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Registry\Support\OriginalInput
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-054d501f263f8bf16172a50cbc68723a0edf58a67647f52026ce5ce98f4d5f0a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Registry\\Support\\OriginalInput',
        'filename' => '/var/www/html/app/Domain/Registry/Support/OriginalInput.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Registry\\Support',
    'name' => 'App\\Domain\\Registry\\Support\\OriginalInput',
    'shortName' => 'OriginalInput',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Where the untouched source row is carried alongside the normalized one during
 * validation, so an error message can quote what the file actually said (FR-REG-16).
 *
 * A class rather than a constant on {@see QuotesOriginalInput}: PHP does not allow a
 * trait constant to be read through the trait\'s name, and both the rules and the
 * importer that fills it need one shared spelling of the key.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 22,
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
      'KEY' => 
      array (
        'declaringClassName' => 'App\\Domain\\Registry\\Support\\OriginalInput',
        'implementingClassName' => 'App\\Domain\\Registry\\Support\\OriginalInput',
        'name' => 'KEY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'__original\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 35,
            'startFilePos' => 725,
            'endTokenPos' => 35,
            'endFilePos' => 736,
          ),
        ),
        'docComment' => '/**
 * Deliberately underscore-prefixed: it shares a namespace with canonical field names
 * in the validated array, and no canonical field is or will be spelled this way.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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