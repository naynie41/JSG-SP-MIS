<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Registry/Models/Household.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '16b7557dad3047e3553627da295ebbd3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '536c9ecae42ba5d4f2e1d87e8d211e24' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '8bc61a4d33f56e104b3e45bfcb25eb0c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '020506dc4442bbbcef2006e5133534b8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'b6fcfcf47e38212e1ccb733b9bbe91e2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '37467b58738e924be47fa289836bfa00' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'fbd26bd220b832ccf665dd1ef8a388ff' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'e982f1d115d5c4c71a79c154a1c68176' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'fcb0dbd2c3fee6fa866fc0f66fcd8ac9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'c06687371b9ee61e38acb8450b20cada' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '3348b923789ba07a2899842ca4cbd590' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '5127920c4d2671cb655b1881d89fc939' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'c458351a828dcd1571b150e178370259' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'e451b6f95263b65d2ecd651461a2276f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'f5c2157828f2dde19d415b3d7b3ef839' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '24495557d75a680c5f815624d20a37cf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '2be122ab267887d515642bcf070c8224' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '318e1239d2740550e1a08d88e17590f9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'cff0c4f22ae34f1ef5d0b89887376cb7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '66517ffc82176ed562f698cf9109a978' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '525019e2f2803d12081aee6a2a190ff4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'dce195b9e2561e234c11032f8006d19a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '0a0107e5dcb7c0fd7359bb4cd927ece8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '9a6ea21c2830339e7736304fe51d8bd1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'e6ccca924f7fa9a8225d3142cd49658d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '5950dddf38e6323d585586b566f49d69' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'b1adb48e56900abb04006eb1a2e4e62b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '8804200bca86274381ed6c72f8919dbc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '6688b846ae7615b9f3945f79208f30b3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '399ed1abacc39a01da12e7a7c5948e9e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '4ca009b1fbc4a6e8ea796ac2038a846a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '3e9dfc1ed9bdcd4b1cb6499a43881140' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'cae0802a0be0727d7f2b2e1a512dd41b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '22d78048b8de7d53be4a5eb606644038' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '2180cd4f9f7acd37ba2a826ca5a2c127' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'd50e3f0e70541e91fda510ce6aa70771' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'facc9312dbea060908ff3cf156197847' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '8ac3f168cbd428c8adcfaeae19281af3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'be78eb8d1eb2394b39cf1745787181fb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'c6041a03244ff0b2f716a1a228e42d27' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '1f96a89133f42a4eccf790561fc5d07f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'c0f7375a2f886477686b10bac95ff030' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'c4771611422ee00824ce74936ca2233b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '235620f7b49c80994913cd9217391da1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '3579892882bcdd140351cae1cc93fde9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '9a03b6c24faf39c8ab4784ec994130b5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      '48ccc7f19dcc4c5e3b1ef2615919a9c3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'b1d4217d901863868302834d9f03aca2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'a24131ac5c9df2f5908d9b4287bab98b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
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
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
          0 => '/var/www/html/app/Domain/Registry/Models/Household.php',
          1 => 'App\\Domain\\Registry\\Models\\Household',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<HouseholdFactory> */',
        ),
      )),
      'e0e4c694c8331447d507742347f53952' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'casts',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      'bc3935842379d74c552baf2c453a884d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'booted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      'c3a274244ce847a859408a7dfef803bd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'ownerMda',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '0aeb89e5bdc666e4f99e1d29cbd1ae04' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'head',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '778034c773a4d2c4ff5360930fe8435d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'memberships',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '84ff08cb1a5543e1c49be5951d168367' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'currentMemberships',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '2a54fde3e16382aecd08a1b3a98acb43' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'householdfactory' => 'Database\\Factories\\HouseholdFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Household',
         'functionName' => 'newFactory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Registry\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'householdfactory' => 'Database\\Factories\\HouseholdFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Household',
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
      '/var/www/html/app/Domain/Registry/Models/Household.php' => '44045b38617a97d3984d2bb0aa47f68b8fdf856d60812bde157062fc109300e0',
      '/var/www/html/app/Domain/Audit/Concerns/Auditable.php' => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUuids.php' => 'f75b8db33aafd61f17652a5e4bb5b8989e62197b306e9f7ae60bb3ac2c34d534',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUniqueStringIds.php' => '3d5612d3c0a56c6c9f19e628b02085d4d68a64d9d07656742725cec78d4a79c5',
      '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php' => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/SoftDeletes.php' => 'da1b0c13d78ba2f62e97e5627c3149f4e81b9cf9b6092d4ca7f02ca5e5bbcfec',
    ),
  ),
));