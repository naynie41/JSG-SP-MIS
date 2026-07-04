<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'd28f5b549cee269434229d69d0653b42' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'b40c08fcaf250a936c23bdfd0ea26434' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '1c02a07aadb7f0bd0dccb5b1f4a7bfac' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'c4dab2970edb4b5df0d57a17fa75a356' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '0b1054e1eef062cd2ee67c141b11b4a7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'c1786df1e809a15f36937cc2ed284884' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'd2c717f3947d783775389e79b7429084' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'be2f7146cd7004b5baaa676b8d4ef62d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'd40aaeffb4859247893a90bc9e649321' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '2723bd6899e462b4871219b3d42126a8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '2072c9cd21dd722f4b0197876e5dee1d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'a640aa5f016dbcf6cf653f33e6329c0d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '54c82605d0d402171a220797047f4ecb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '757b7591440b10935e104ab2c08d96f0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '93e97dd50f04e60b6e34b266d8a33e63' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '15f7ac07f0d2da12a97033287955d399' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '80b6cd891cb03e55ea021160d0976c3a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '27e46aaddf23f233d2a047691838feaa' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '4b0b5a4867281c4207444249e3870c7e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'aaa90ca486317cfd87b81aa1616a1687' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '79b3a0189ba1f04c5f6bd74235e3b1f2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '83b6e9604fb3ee3788f20615f0b139d7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '8e146d235124247224c31e8bb5123ece' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '478084bba1e133f96eb7f6dba14de746' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '45a19960d20e6484dd5edf16a2e2511a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'ac8b517568bab1bc3b51dddb0ebde851' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '0c8f0a6675b4210fd4885c9af3243232' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'd59507d452bdd683b253765639afb11e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      'b680aa6d50352e15576c157f729cabf9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '6d9ecf408d4cfd0d7e5b108d149aa2a6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php',
          1 => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitImportBatchFactory> */',
        ),
      )),
      '18925fd976f9ca607a6c3556c321f63f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'mdaOwnershipColumn',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'c749db62ada81783ab140a05142353f7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'auditExcluded',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'd853eac21372d3c4cdb136e14bcd92ba' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'casts',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      '32140e67de5b6ede6efb379d7cdc7dce' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'newFactory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'cbd5f34d61b9e8acd0e2c869375fc338' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'activity',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'e3d746f01d8afaa1da5a46f09019b14d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'programme',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      'e3feae9928f64b43e11b487f8a3977df' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'mda',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      '3099acb568607ffbad9917ea15301e65' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
          'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
          'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
         'functionName' => 'rows',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Domain\\Benefit\\Models',
           'uses' => 
          array (
            'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
            'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
            'mda' => 'App\\Domain\\Access\\Models\\Mda',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'importstatus' => 'App\\Domain\\Registry\\Enums\\ImportStatus',
            'benefitimportbatchfactory' => 'Database\\Factories\\BenefitImportBatchFactory',
            'collection' => 'Illuminate\\Database\\Eloquent\\Collection',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\BenefitImportBatch',
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
      '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php' => 'ab0350f31226f879a93debe2a0daf5bde93fabb2f916626b5d1c96fdd2e8be1c',
      '/var/www/html/app/Domain/Audit/Concerns/Auditable.php' => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUuids.php' => 'f75b8db33aafd61f17652a5e4bb5b8989e62197b306e9f7ae60bb3ac2c34d534',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUniqueStringIds.php' => '3d5612d3c0a56c6c9f19e628b02085d4d68a64d9d07656742725cec78d4a79c5',
      '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php' => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
    ),
  ),
));