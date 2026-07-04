<?php declare(strict_types = 1);

// ftm-/var/www/html/app/Domain/Benefit/Models/Benefit.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      '71d73e88827b0567c95828db0ffcb817' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      '0297fe29316a1baddd7929f8077edc61' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '7baf0a5d08383ad1901cebf85e0fa626' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'c8bc890c4327e18efaa72d89c542547d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '2aebad461ce5427b1de0cea8146955f9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '7aca89fec21b3ff844b11b5480c3c8af' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '8bb7363202284443300f22a8c6d8a583' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '93cc26839ef060b1947fd5dcaeef730c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '49f0e014f309e78b4144e0718e719915' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '7ec86b69e84922da271271b3fc862a1f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '1d83b17109c3d490ac095118d8f771bd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'ac3b95ab6515eb8afc905373ba997e0d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '6a26fd6486bf43e6580049d7f8c0c80c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '057b9ba7a77f26b4a79a98f7bd82bd8d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'ae21889e89127dd55ce59e97bc10b96c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'd0e3f86ea75cbe9e88d5a570e2757c1d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'edd1e806890b334a4b901ec86261cea0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '5cd2040ee2be99b7ac89a0190c74c708' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'e5ef32daf918eaf05382c75409c762df' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '1f59c7273aa3e8dde7a9281f8fa2e080' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '46ce61e8177da950e37ff0644f0b8e5b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'e2094e76891024a9a72b7e7521821a0e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'b7bb3d17f297055ed58142cf08ce473f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '4a684c73b47ee3e6ccddec2102e23efb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '7a158dbead0fabeb851b4919dbdb4c96' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '11a8bec889b1650b6bf5cfdae9296316' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'ebccee501683d3aec5b630a1b4366ce5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '8b258a5143eb3bcabeeb0270b4fc8e54' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '363e777107127345d8bacd41a5385802' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'a2eb4243136aaae559c2523b93d0e54f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'e0ab99e71e8ad2f7da016f64b9605210' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '166c1c86022d1306df7e7e6523340837' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '9ea7aa56c15def26786aa12b4d2ff1d7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '084cc973c0766cf455a3e32a409813cf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '727a9df7535d2c34b95749c267807cd4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'c1d9bb6d4bf49f2a7cbac041602033cb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'a2f87b04cb2bb632d01fe68e40f265a9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'e1cbe8c7392773903a1dfcd9e5c8798d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '0ffc850addf4eaea089e5af7558bfee9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'f13264d7db955ba3a4c41d3b1d8523a7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '035de755743dacb5375dd3ec6f8fa2e1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '7daa28628fea5732dda2d20c8b0e631b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'f87cc7a89dce666c0a57e924d1ee7b0c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '569e8b85db3b78f46f613761850c9e23' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '2321d0e80fea87dcd2097360db15e90c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'f7df6b09dcb2aa559cecaca77b59f24a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '31d47e072d71caaf1ecb7ff882c58927' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '14810a896dfbd2c9b6cc4e146569cd9b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      'e80d7c4ced0782b59f40f84f8e4ffc6d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
          0 => '/var/www/html/app/Domain/Benefit/Models/Benefit.php',
          1 => 'App\\Domain\\Benefit\\Models\\Benefit',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BenefitFactory> */',
        ),
      )),
      '052735cf3d789ed13e047ffe422e62d0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      '0ebfae3e44c805241cff0d9d7b48995f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      'aa2d3d168aee421e940ea17d8d8db731' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      'ddbae2491b59d20b4f2dea3d73deba40' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
         'functionName' => 'beneficiary',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      '8d2679b0ccd4b624b2243c1e04b9d7fa' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      'eda9d06f5bf774109f60ae5ca08d951d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      'c24ade2e37c6c89665c207fc7a03ede9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
         'functionName' => 'enrollment',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      '9e34ae41bf0342118e92043f77a6303f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Benefit\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
          'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
          'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
          'activity' => 'App\\Domain\\Programme\\Models\\Activity',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'programme' => 'App\\Domain\\Programme\\Models\\Programme',
          'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
          'benefitfactory' => 'Database\\Factories\\BenefitFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
            'benefitstatus' => 'App\\Domain\\Benefit\\Enums\\BenefitStatus',
            'benefittype' => 'App\\Domain\\Benefit\\Enums\\BenefitType',
            'verificationmethod' => 'App\\Domain\\Benefit\\Enums\\VerificationMethod',
            'activity' => 'App\\Domain\\Programme\\Models\\Activity',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'programme' => 'App\\Domain\\Programme\\Models\\Programme',
            'beneficiary' => 'App\\Domain\\Registry\\Models\\Beneficiary',
            'benefitfactory' => 'Database\\Factories\\BenefitFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Domain\\Benefit\\Models\\Benefit',
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
      '/var/www/html/app/Domain/Benefit/Models/Benefit.php' => '564535f42ca329420820ed8e0e402e226d374dbd19b2768211ad217235b9e729',
      '/var/www/html/app/Domain/Audit/Concerns/Auditable.php' => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUuids.php' => 'f75b8db33aafd61f17652a5e4bb5b8989e62197b306e9f7ae60bb3ac2c34d534',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUniqueStringIds.php' => '3d5612d3c0a56c6c9f19e628b02085d4d68a64d9d07656742725cec78d4a79c5',
      '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php' => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
      '/var/www/html/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/SoftDeletes.php' => 'da1b0c13d78ba2f62e97e5627c3149f4e81b9cf9b6092d4ca7f02ca5e5bbcfec',
    ),
  ),
));