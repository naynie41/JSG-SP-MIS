<?php declare(strict_types = 1);

// ftm-C:\Users\ACER\Desktop\JSG-SP-MIS\JSG-SP-MIS\api\app\Domain\Registry\Models\Beneficiary.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v5-2.3.2',
   'data' => 
  array (
    0 => 
    array (
      'cd4737527a1b6d542daf52ee5535ef59' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '46132b4b25f8eab708a04081819ff012' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'ac0458a92f4d9c520a72fec0ffb78554' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '61e0ce6aa5398f95383ac63a5b65074a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '9c8264aa7a190a12f6d7f2f7a8aded0b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'fd3a90ed52ec6a2e41b98d8026a22a61' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'f236ca86b35ff37407a94af7b81c881b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'c80dbcbe177a1b8acfd116ebd311ad87' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '52134647aa8868809c1ed3b531fa207c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '0512be3c8b58724c303a174238d933e3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '0e7c46d5b2eb5f14c2a3742e1270555e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Audit\\Concerns',
         'uses' => 
        array (
          'auditlogger' => 'App\\Domain\\Audit\\Services\\AuditLogger',
          'auditscrubber' => 'App\\Domain\\Audit\\Support\\AuditScrubber',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Audit\\Concerns\\Auditable',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '8a1b66028a9585610931805405c43e56' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'e68570a5664f532cfdef041aedcb2df0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '1d1571175467c76a62d18a7e482e47e9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '1a09af3563de224403d1be7af15b3e2c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '657b8c8328faee63fef073de73c173bf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'e369977c6374fb118e03e095ce0e6d1e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '620eede49aaae654bb0087cdeb88aa08' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '3c4777d1342dd780e77b006cb797a448' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'e66aca1c4e02d88b99d10ed9011cd929' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '90da042b7153a082b74e5c38a3af56cc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '26a465aec1d61de79a76d2721f9a1b81' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '5ef61708077d028edd2b59d45693bed5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '08a8e1011a5ada6edfe26028d9d2d107' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      'db94c2ba652c487c5fd038305f443e0c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'modelnotfoundexception' => 'Illuminate\\Database\\Eloquent\\ModelNotFoundException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds',
          3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          4 => NULL,
        ),
      )),
      '1a5c11b0765ec0dd2944fc2f0a5c2932' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '9a599fd28e911f232262c8bb62ef8ebd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
         'uses' => 
        array (
          'str' => 'Illuminate\\Support\\Str',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '3cf180c3bb9cd9d41dbf743a78ab075d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '01abac84ba452cfdb86690f2a922117c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '52168010f85effd2ed758618a2893d52' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Access\\Concerns',
         'uses' => 
        array (
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'ea70a78ed742a7da27ffaae8222a2f90' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '875f68a4f69b0a95db5e8b5de716480b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '16efd55d9c2e71c9911e7518f0932fc6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'e4f76bce946f52eafbf82e8af3b8b7c0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '23a4411642ccd86f53e3b6345af03166' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '8766bc3271605e8a1e5a12d15a895967' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'afd6f6897916e0b0e5cb42c912e96a53' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'a75b5952b6e141800f39c4701dba69ac' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '3365d3cd4631698894fbdde6927a71dd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '0a82ed25b2972c42b00621ab3bcfaf4a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '7d5476a2fc23a26a8b04d390f97a8692' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '52042bb1a9ebd437184f798457e818f2' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '7907c5edb35b543ca75cedf9c668fad9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '339173a80d44e0913c6ccd6f1c2e4a59' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '5cd804355effceef8a86a763b8301056' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'daed12e8a9c48841004e58b7cf0296b1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      '1f73013f4640930b64b588a319a01dbd' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'd8b310dfa2416fa36c15b1ce7685e4b8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'a4bbc04f3a6e2c7e2f713275b887b9cf' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent',
         'uses' => 
        array (
          'eloquentcollection' => 'Illuminate\\Database\\Eloquent\\Collection',
          'basecollection' => 'Illuminate\\Support\\Collection',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
          0 => 'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php',
          1 => 'App\\Domain\\Registry\\Models\\Beneficiary',
          2 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          3 => NULL,
          4 => '/** @use HasFactory<BeneficiaryFactory> */',
        ),
      )),
      'b9b836006d9ef1eed5fc056b7d1a495f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'b94f85ca3ee3183f01475bf6f134ab3d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'isAnonymized',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '256cfab274802ce62bce2fac43685c04' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'auditMask',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '69d25e1984a5e2ddfc497ddb4669ec73' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'auditExcluded',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'b96a6b1d97dd5e5c2168be3a7661d329' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'ca9cc636583e4cadbf3a17bf1fb71666' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'normalizeDigits',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'f3332244fc75ff17d387e20e22e6664b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'blockNameDobFor',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '6cba220370bbdd7a30af6ad59f6c253e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'fullName',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '02b64224483b3781b0f206674f9a0026' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'hasSharingConsent',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'fab972147523c2c7a8adcf3fe5b41fa0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'consents',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'b754bdb95a8d7a58b408b89797c616f7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'd67c618054160640b8732bba272fd8c3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'householdMemberships',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '13d9dddb81808ba1ee6f51eb12a1f40e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'benefits',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '626539306668cb2693bfeffa1f04fef9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'enrollments',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'ce7d3aa9f41819f1c82b3549f670508d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'documents',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'f4d66dc4d8de239187a19776ba07d483' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
         'functionName' => 'currentMembership',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      '5dad3b8c517dc83412ce8a0534985657' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Domain\\Registry\\Models',
         'uses' => 
        array (
          'mdascoped' => 'App\\Domain\\Access\\Concerns\\MdaScoped',
          'scopedtomda' => 'App\\Domain\\Access\\Concerns\\ScopedToMda',
          'mda' => 'App\\Domain\\Access\\Models\\Mda',
          'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
          'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
          'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
          'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
          'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
          'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
          'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
          'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
          'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
          'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
          'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
          'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'carbon' => 'Illuminate\\Support\\Carbon',
          'invalidargumentexception' => 'InvalidArgumentException',
        ),
         'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
            'mdascope' => 'App\\Domain\\Access\\Scopes\\MdaScope',
            'auditable' => 'App\\Domain\\Audit\\Concerns\\Auditable',
            'benefit' => 'App\\Domain\\Benefit\\Models\\Benefit',
            'phoneticencoder' => 'App\\Domain\\Matching\\Support\\PhoneticEncoder',
            'enrollment' => 'App\\Domain\\Programme\\Models\\Enrollment',
            'beneficiarystatus' => 'App\\Domain\\Registry\\Enums\\BeneficiaryStatus',
            'consentstatus' => 'App\\Domain\\Registry\\Enums\\ConsentStatus',
            'gender' => 'App\\Domain\\Registry\\Enums\\Gender',
            'registrationsource' => 'App\\Domain\\Registry\\Enums\\RegistrationSource',
            'identifierhasher' => 'App\\Domain\\Registry\\Support\\IdentifierHasher',
            'beneficiaryfactory' => 'Database\\Factories\\BeneficiaryFactory',
            'hasuuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'hasone' => 'Illuminate\\Database\\Eloquent\\Relations\\HasOne',
            'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'carbon' => 'Illuminate\\Support\\Carbon',
            'invalidargumentexception' => 'InvalidArgumentException',
          ),
           'className' => 'App\\Domain\\Registry\\Models\\Beneficiary',
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
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Registry\\Models\\Beneficiary.php' => 'b7d9b1765de983ce16d6811bc1fad8a077e0300e64873b0e4e67b3d203e05205',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Audit\\Concerns\\Auditable.php' => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Factories\\HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\HasUuids.php' => 'f75b8db33aafd61f17652a5e4bb5b8989e62197b306e9f7ae60bb3ac2c34d534',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueStringIds.php' => '3d5612d3c0a56c6c9f19e628b02085d4d68a64d9d07656742725cec78d4a79c5',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\app\\Domain\\Access\\Concerns\\ScopedToMda.php' => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
      'C:\\Users\\ACER\\Desktop\\JSG-SP-MIS\\JSG-SP-MIS\\api\\vendor\\composer\\..\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\SoftDeletes.php' => 'da1b0c13d78ba2f62e97e5627c3149f4e81b9cf9b6092d4ca7f02ca5e5bbcfec',
    ),
  ),
));