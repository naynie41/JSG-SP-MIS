<?php declare(strict_types = 1);

// odsl-/var/www/html/app/Domain/Graduation/GraduationServiceProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Domain\Graduation\GraduationServiceProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3.31-f568d09aaf4f24018ad8cf6972925c6663ffabc4cc18cb35a78fd1c2d1faf6b4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Domain\\Graduation\\GraduationServiceProvider',
        'filename' => '/var/www/html/app/Domain/Graduation/GraduationServiceProvider.php',
      ),
    ),
    'namespace' => 'App\\Domain\\Graduation',
    'name' => 'App\\Domain\\Graduation\\GraduationServiceProvider',
    'shortName' => 'GraduationServiceProvider',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Graduation management domain (FR-GRD-01, FR-GRD-02). Registers the graduation
 * permissions: `graduation.view` (see criteria, progress and history) and
 * `graduation.edit` (configure criteria and record a graduation). The progress and
 * graduation services are plain container-resolved services; the notification for a
 * recorded graduation is wired through the existing NotificationSubscriber.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 27,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\ServiceProvider',
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
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Domain\\Graduation',
        'declaringClassName' => 'App\\Domain\\Graduation\\GraduationServiceProvider',
        'implementingClassName' => 'App\\Domain\\Graduation\\GraduationServiceProvider',
        'currentClassName' => 'App\\Domain\\Graduation\\GraduationServiceProvider',
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