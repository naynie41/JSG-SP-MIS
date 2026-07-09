<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Programme/Models/Activity.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '0c2df76e66534dd0d76327b1fe811688' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '2b9e06d56a86f3c29615a02aeb3b3d7d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '78e8c47bccd9fafc4b4213971dc9a01b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'bootAuditable',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '3db1f352a9562ac63ee239c34e1e8db6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'auditExcluded',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '6856995e2c38d5261cc507d1add8b0d7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'auditEntityName',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'e04a24dcbfa48ff18536a4201ab8b527' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'auditOmit',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '5c59691579df709fcc7d50129c975fbd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'auditMask',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'b27c24d91899d524fbab684aadca56b9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'scrubAttributes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'ada0cdf575fa6f540c720173a0bae088' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'allAuditExcluded',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '0ac36fa1ff17cf0315cc9f81a23eaba5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'auditSnapshot',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'ac31f521bfb84826ad1c2ffb2cf1b52e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'writeAudit',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Audit\\Concerns',
           'uses' => 
          array (
            'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
            'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
            'str' => 'Illuminate\\Support\\Str',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Audit\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '78d22b2b5abb43f4a7a912347e6f2a34' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
          'TFactory' => 
          array (
            0 => '@template',
            1 => 
            \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
               'name' => 'TFactory',
               'bound' => 
              \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                 'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
               'default' => NULL,
               'lowerBound' => NULL,
               'description' => '',
               'attributes' => 
              array (
                'startLine' => 2,
                'endLine' => 2,
              ),
            )),
          ),
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '370cbceee89b40080940267b1777fa6b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'factory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '0de160f3fa810c1084a59c1a161b223f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'newFactory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '0ad90f869d7ac2b1bbab0a668cce8ce1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'getUseFactoryAttribute',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'b6a75d79cbfff0fc748a62e0d5c19798' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '9eac13e34451906c4b1a1866544be4c7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '7d426cb44d4fb73d649e4fe6a25d6791' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'newUniqueId',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'f926fcc3ac79e5798339601bb5b518bc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'isValidUniqueId',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '3c6586b929ff436f79af78f9ed420b08' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'initializeHasUniqueStringIds',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '1650cb3bdb080cd0d758d82cd29ed367' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'uniqueIds',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '962f96984f15435c71acdfa9ae936956' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'resolveRouteBindingQuery',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'acc7e1261b7e7b0b4a8606370ad7f459' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'getKeyType',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '5c26a2e038bf085e6d0701dfce8ac8f8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'getIncrementing',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'ae959182ce88a0ca892e8b6f5e4d415b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'handleInvalidUniqueId',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '0cf00770600bcb850a9521be379750eb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'newUniqueId',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '1633c1d6265669a72accbd46818ecfd0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'isValidUniqueId',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '755af63271dd81a0628774f00d9e6d99' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '4d299019e030c80e7636504383cc5a44' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'bootScopedToMda',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Access\\Concerns',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '8f8a6f3986ee1d37ed213d2a1f9505a9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'mdaOwnershipColumn',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Access\\Concerns',
           'uses' => 
          array (
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '0b51dec904ff47730ea6b35c4d68579e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '1259c86e389ae1d024a55282f3d5089c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'bootSoftDeletes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '7dde74895df41705bbd2e3e0a0c17df9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'initializeSoftDeletes',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '4a5478f7a82b425b608eb62fa57de00b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'forceDelete',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'fdaa87d49c4fad973060dd8887651321' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'forceDeleteQuietly',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'ae2a6752361c9f0387786b7bab76f0bc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'forceDestroy',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '51059f13d38b2f9b02ecbacad1f2c2d3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'performDeleteOnModel',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '2f974fa025269e6b839baa672dd6520a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'runSoftDelete',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '0520c2fc674097d38c6958ad638892db' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'restore',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'd27f3da6fd50fba8c541053acb43329d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'restoreQuietly',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '03acee5c90e59c04ab2018d51a30fb0c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'trashed',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '5963c10ba179564b3289e42ef48df922' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'softDeleted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '9c060c9cf788e289a13f9e3b6d78cb9a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'restoring',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '8217e69445f4f09b041b4d3c3b6c9396' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'restored',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '8516670c59af3497fe3550900bcdaf2a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'forceDeleting',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      'abe586cbd217dd0cac00353330804886' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'forceDeleted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '3eccf240a234b035af512f3169c4a3ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'isForceDeleting',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '2992619d6a1f0775752cda06f1636f6d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'getDeletedAtColumn',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '86e93813a667ee28aeec58505e5f092d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'getQualifiedDeletedAtColumn',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent',
           'uses' => 
          array (
            'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'basecollection' => 'Illuminate\\Support\\Collection',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
         'traitData' => 
        array (
          0 => '/var/www/html/app/Domain/Programme/Models/Activity.php',
          1 => 'App\\Domain\\Programme\\Models\\Activity',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<ActivityFactory> */',
        ),
      )),
      '8a4b82e81860ca0247d2cd0eedcf744f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'casts',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Programme\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activityfactory' => 'Database\\Factories\\ActivityFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'a2edae52e07beb61aeddc77a5c5b9224' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'newFactory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Programme\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activityfactory' => 'Database\\Factories\\ActivityFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '0be89278180a7ce74b2c944f2e6881ea' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'programme',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Programme\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activityfactory' => 'Database\\Factories\\ActivityFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'f934aa7d19bab106091716132144fd5b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'ownerMda',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Programme\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activityfactory' => 'Database\\Factories\\ActivityFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'a24635fdbc508111d922f8cc926fe516' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Programme\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'user' => 'App\\Domain\\Access\\Models\\User',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
          'activityfactory' => 'Database\\Factories\\ActivityFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Programme\\Models\\Activity',
         'functionName' => 'creator',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Programme\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'user' => 'App\\Domain\\Access\\Models\\User',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activitystatus' => 'App\\Domain\\Programme\\Enums\\ActivityStatus',
            'activityfactory' => 'Database\\Factories\\ActivityFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Programme\\Models\\Activity',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      '/var/www/html/app/Domain/Programme/Models/Activity.php' => '03f0b4950c66e6cfcc0f65692ea000ebfa47b76648d1e6a551653c1c50153b92',
      '/var/www/html/app/Domain/Audit/Concerns/Auditable.php' => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUuids.php' => 'f75b8db33aafd61f17652a5e4bb5b8989e62197b306e9f7ae60bb3ac2c34d534',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUniqueStringIds.php' => '3d5612d3c0a56c6c9f19e628b02085d4d68a64d9d07656742725cec78d4a79c5',
      '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php' => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/SoftDeletes.php' => 'da1b0c13d78ba2f62e97e5627c3149f4e81b9cf9b6092d4ca7f02ca5e5bbcfec',
    ),
  ),
));