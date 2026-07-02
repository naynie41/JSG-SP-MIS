<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Http/Requests/Access/Concerns/ValidatesUserAssignment.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Http\Requests\Access\Concerns\ValidatesUserAssignment
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-40b10566425852eee9be53a458d6692834db12e86769be4b53d1afcf1be12d82',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'filename' => '/var/www/html/app/Http/Requests/Access/Concerns/ValidatesUserAssignment.php',
      ),
    ),
    'namespace' => 'App\\Http\\Requests\\Access\\Concerns',
    'name' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
    'shortName' => 'ValidatesUserAssignment',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Validation rules that keep user creation/editing within the actor\'s authority
 * (SECURITY.md, least privilege): an actor may only place users in MDAs they can
 * access, and may only assign roles whose permissions they themselves hold (no
 * privilege escalation). Holders of cross-mda.view bypass both.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 58,
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
      'accessibleMdaRule' => 
      array (
        'name' => 'accessibleMdaRule',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Closure',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Requests\\Access\\Concerns',
        'declaringClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'implementingClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'currentClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'aliasName' => NULL,
      ),
      'assignableRoleRule' => 
      array (
        'name' => 'assignableRoleRule',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Closure',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 33,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Http\\Requests\\Access\\Concerns',
        'declaringClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'implementingClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
        'currentClassName' => 'App\\Http\\Requests\\Access\\Concerns\\ValidatesUserAssignment',
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