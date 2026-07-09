<?php declare(strict_types = 1);

// odsl-/var/www/html/app
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v1-enums',
   'data' => 
  array (
    '/var/www/html/app/Console/Commands/SyncPermissions.php' => 
    array (
      0 => 'a15ce2880d7eee73801a299afa2d9b17692d99829027828c517350963cffe228',
      1 => 
      array (
        0 => 'app\\console\\commands\\syncpermissions',
      ),
      2 => 
      array (
        0 => 'app\\console\\commands\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/AccessServiceProvider.php' => 
    array (
      0 => 'b8f8c5900bc2153886950a36d8a699e521d1b2978759e6dfa6440116e0d8f420',
      1 => 
      array (
        0 => 'app\\domain\\access\\accessserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\register',
        1 => 'app\\domain\\access\\boot',
        2 => 'app\\domain\\access\\registerpermissions',
        3 => 'app\\domain\\access\\registergate',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Concerns/MdaScoped.php' => 
    array (
      0 => '2fadd691a2db6e606382ae75b14bc56fb148c7f30cb6627c117f29e986254836',
      1 => 
      array (
        0 => 'app\\domain\\access\\concerns\\mdascoped',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\concerns\\mdaownershipcolumn',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Concerns/ScopedToMda.php' => 
    array (
      0 => '71fc767929ce4cf3fedf8bf8371e29b9fd1102308666d911286f798b453ab3b6',
      1 => 
      array (
        0 => 'app\\domain\\access\\concerns\\scopedtomda',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\concerns\\bootscopedtomda',
        1 => 'app\\domain\\access\\concerns\\mdaownershipcolumn',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Enums/MdaStatus.php' => 
    array (
      0 => 'e9faac3708c3f9fbc1945297e817958bc2d35d5309d73163fb40623a6873271b',
      1 => 
      array (
        0 => 'app\\domain\\access\\enums\\mdastatus',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Enums/MdaType.php' => 
    array (
      0 => '2f2eac5be5df3d3e78bfa2fb247db4e72e2d878b2cff841ce227cd205888ab4b',
      1 => 
      array (
        0 => 'app\\domain\\access\\enums\\mdatype',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Enums/PermissionAction.php' => 
    array (
      0 => '0b5f8b6fa4ffbafe23ced98bd900648fb725b0d0bbd4773372d7a4abdbd5bc87',
      1 => 
      array (
        0 => 'app\\domain\\access\\enums\\permissionaction',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Enums/RoleKey.php' => 
    array (
      0 => 'aa800acebadff237b3c94d7c656afe6bb41249a9394e49867aae94dd38406890',
      1 => 
      array (
        0 => 'app\\domain\\access\\enums\\rolekey',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Enums/UserStatus.php' => 
    array (
      0 => 'dd2233f8e9ec3c328794b59602d7d49b02e9b7b745d221c91176b9e4e92566b7',
      1 => 
      array (
        0 => 'app\\domain\\access\\enums\\userstatus',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/AccountLocked.php' => 
    array (
      0 => '6a29d3eca8dbf739f869de93b8a568d5037a63503f89546132dade060171a98d',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\accountlocked',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/CrossMdaAccessGranted.php' => 
    array (
      0 => '04ece9def707b952cf591d09bc34d387d49e33f6006f2e643c1eb5329ff8f1d6',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\crossmdaaccessgranted',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/CrossMdaAccessRevoked.php' => 
    array (
      0 => '7eef2c7af90de99a2eb106983c061e4a9c7c4660702a72d4e4e4dc3007c1349b',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\crossmdaaccessrevoked',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/MfaChallengeFailed.php' => 
    array (
      0 => '6594ed4939885c3f4ad9ecfce9678f70e8ea9c9235a73a1bf5199506c782e798',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\mfachallengefailed',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/MfaDisabled.php' => 
    array (
      0 => '44ad54a153cdbc05a6ffa065bc9184bfe23dbc52c8e1487efd6bb6bd5be2c612',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\mfadisabled',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Events/MfaEnrolled.php' => 
    array (
      0 => '54bd3fb003c5ee5fc3ac649ad69aee0b2cb788a097da24ba49f4b148bb25f3cf',
      1 => 
      array (
        0 => 'app\\domain\\access\\events\\mfaenrolled',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Models/Mda.php' => 
    array (
      0 => 'f6259a074f9d66f3a1d70b6a00eb78cc70d713a12cedc5cf8128f26f0dcdfefd',
      1 => 
      array (
        0 => 'app\\domain\\access\\models\\mda',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\models\\mdaownershipcolumn',
        1 => 'app\\domain\\access\\models\\casts',
        2 => 'app\\domain\\access\\models\\users',
        3 => 'app\\domain\\access\\models\\accessgrants',
        4 => 'app\\domain\\access\\models\\newfactory',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Models/MdaAccessGrant.php' => 
    array (
      0 => '80454f5cca1295177440dad59922c69b175d5bf25e20ef46174a8e4a926daa17',
      1 => 
      array (
        0 => 'app\\domain\\access\\models\\mdaaccessgrant',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\models\\casts',
        1 => 'app\\domain\\access\\models\\user',
        2 => 'app\\domain\\access\\models\\mda',
        3 => 'app\\domain\\access\\models\\grantedby',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Models/Permission.php' => 
    array (
      0 => '10a3ce0147c4289ad1e25d1b39dbaee4a20a0abd50fcc9cdaba4123df82b5e8b',
      1 => 
      array (
        0 => 'app\\domain\\access\\models\\permission',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\models\\casts',
        1 => 'app\\domain\\access\\models\\roles',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Models/Role.php' => 
    array (
      0 => 'bd952984b7e25955c8dbbf0131d0cfb90debde15bc116b18e5caf338c07a6052',
      1 => 
      array (
        0 => 'app\\domain\\access\\models\\role',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\models\\casts',
        1 => 'app\\domain\\access\\models\\permissions',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Models/User.php' => 
    array (
      0 => 'ca09043f98e418ab889b12f7312deef7f00c09162d6de61ef5350c3a68c9fc2d',
      1 => 
      array (
        0 => 'app\\domain\\access\\models\\user',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\models\\mdaownershipcolumn',
        1 => 'app\\domain\\access\\models\\auditexcluded',
        2 => 'app\\domain\\access\\models\\auditmask',
        3 => 'app\\domain\\access\\models\\casts',
        4 => 'app\\domain\\access\\models\\mfarequired',
        5 => 'app\\domain\\access\\models\\islocked',
        6 => 'app\\domain\\access\\models\\registerfailedattempt',
        7 => 'app\\domain\\access\\models\\clearlockout',
        8 => 'app\\domain\\access\\models\\consumerecoverycode',
        9 => 'app\\domain\\access\\models\\mda',
        10 => 'app\\domain\\access\\models\\role',
        11 => 'app\\domain\\access\\models\\permissionkeys',
        12 => 'app\\domain\\access\\models\\haspermission',
        13 => 'app\\domain\\access\\models\\canaccessallmdas',
        14 => 'app\\domain\\access\\models\\accessiblemdaids',
        15 => 'app\\domain\\access\\models\\mdaaccessgrants',
        16 => 'app\\domain\\access\\models\\newfactory',
        17 => 'app\\domain\\access\\models\\booted',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Scopes/MdaScope.php' => 
    array (
      0 => '478e85b109874681106a1684c196f21c2dba4647cfa7797df0be396bd0b242d5',
      1 => 
      array (
        0 => 'app\\domain\\access\\scopes\\mdascope',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\scopes\\apply',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Services/AuthTokenIssuer.php' => 
    array (
      0 => 'a397c3e455de0cf57964b04e9301026d36aa0adadad89fe441de443434662e96',
      1 => 
      array (
        0 => 'app\\domain\\access\\services\\authtokenissuer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\services\\__construct',
        1 => 'app\\domain\\access\\services\\issuefull',
        2 => 'app\\domain\\access\\services\\issuechallenge',
        3 => 'app\\domain\\access\\services\\issuesetup',
        4 => 'app\\domain\\access\\services\\ttl',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Services/MfaService.php' => 
    array (
      0 => 'a461e4a67b29fa5edec85d8f0da273e64e8bf3adb0fe670b1055592433c4ebd9',
      1 => 
      array (
        0 => 'app\\domain\\access\\services\\mfaservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\services\\__construct',
        1 => 'app\\domain\\access\\services\\generatesecret',
        2 => 'app\\domain\\access\\services\\provisioninguri',
        3 => 'app\\domain\\access\\services\\verifycode',
        4 => 'app\\domain\\access\\services\\generaterecoverycodes',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Services/PermissionSynchronizer.php' => 
    array (
      0 => 'a533bcaaf7a6ec8c525f40b8237cb509e97afae3ad2702df976e2d016c108aad',
      1 => 
      array (
        0 => 'app\\domain\\access\\services\\permissionsynchronizer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\services\\__construct',
        1 => 'app\\domain\\access\\services\\sync',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Support/PasswordRules.php' => 
    array (
      0 => '317ee13fcbbfd9a9df5acc408f9089882c0ef520a7054682186e23cb08d05c78',
      1 => 
      array (
        0 => 'app\\domain\\access\\support\\passwordrules',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\support\\default',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Support/PermissionRegistry.php' => 
    array (
      0 => '20daabc96a55522ab4e4f813d5324aa53f9abe348f62d712fdfe17865616e788',
      1 => 
      array (
        0 => 'app\\domain\\access\\support\\permissionregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\access\\support\\register',
        1 => 'app\\domain\\access\\support\\all',
        2 => 'app\\domain\\access\\support\\keys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Access/Support/TokenAbility.php' => 
    array (
      0 => 'f8c1ef67e624053f707a6ca7ca5dff8c4b47a40260e8d9f696eceecf0f9a5cee',
      1 => 
      array (
        0 => 'app\\domain\\access\\support\\tokenability',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Audit/Concerns/Auditable.php' => 
    array (
      0 => '5ffa2245eaa31de5eade775b8b48dfcbbf9e33ae3b5651120046c5b92a6d4b7a',
      1 => 
      array (
        0 => 'app\\domain\\audit\\concerns\\auditable',
      ),
      2 => 
      array (
        0 => 'app\\domain\\audit\\concerns\\bootauditable',
        1 => 'app\\domain\\audit\\concerns\\auditexcluded',
        2 => 'app\\domain\\audit\\concerns\\auditentityname',
        3 => 'app\\domain\\audit\\concerns\\auditomit',
        4 => 'app\\domain\\audit\\concerns\\auditmask',
        5 => 'app\\domain\\audit\\concerns\\scrubattributes',
        6 => 'app\\domain\\audit\\concerns\\allauditexcluded',
        7 => 'app\\domain\\audit\\concerns\\auditsnapshot',
        8 => 'app\\domain\\audit\\concerns\\writeaudit',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Audit/Listeners/AuditEventSubscriber.php' => 
    array (
      0 => '6518033cd102eb7de41ee766f68b6b905cc3ff54f7392d3101b3a83c87310c7a',
      1 => 
      array (
        0 => 'app\\domain\\audit\\listeners\\auditeventsubscriber',
      ),
      2 => 
      array (
        0 => 'app\\domain\\audit\\listeners\\__construct',
        1 => 'app\\domain\\audit\\listeners\\handleaccountlocked',
        2 => 'app\\domain\\audit\\listeners\\handlemfaenrolled',
        3 => 'app\\domain\\audit\\listeners\\handlemfadisabled',
        4 => 'app\\domain\\audit\\listeners\\handlemfachallengefailed',
        5 => 'app\\domain\\audit\\listeners\\handlecrossmdaaccessgranted',
        6 => 'app\\domain\\audit\\listeners\\handlecrossmdaaccessrevoked',
        7 => 'app\\domain\\audit\\listeners\\subscribe',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Audit/Models/AuditLog.php' => 
    array (
      0 => '7990b6b04f64b90d3ea36b5735a22ea90fb3d906fdc2738ef33f7621234761ef',
      1 => 
      array (
        0 => 'app\\domain\\audit\\models\\auditlog',
      ),
      2 => 
      array (
        0 => 'app\\domain\\audit\\models\\casts',
        1 => 'app\\domain\\audit\\models\\booted',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Audit/Services/AuditLogger.php' => 
    array (
      0 => 'b7dd721cd0160cc94e7476c7e7a234701571466bcbc26b044a3710f54477c753',
      1 => 
      array (
        0 => 'app\\domain\\audit\\services\\auditlogger',
      ),
      2 => 
      array (
        0 => 'app\\domain\\audit\\services\\record',
        1 => 'app\\domain\\audit\\services\\entityname',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Audit/Support/AuditScrubber.php' => 
    array (
      0 => '450d4882876ee6f46e74c731ee46cb3beb13efbcfe09ee4865142e7d9f9f1a14',
      1 => 
      array (
        0 => 'app\\domain\\audit\\support\\auditscrubber',
      ),
      2 => 
      array (
        0 => 'app\\domain\\audit\\support\\scrub',
        1 => 'app\\domain\\audit\\support\\mask',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Authorization/DeliveryAuthorizer.php' => 
    array (
      0 => '4a323f20eb1f1baa8c6d1dc1d3614f4c360a6720079da404dce67c48584ce0ae',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\authorization\\deliveryauthorizer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\authorization\\authorizes',
        1 => 'app\\domain\\benefit\\authorization\\source',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Authorization/ServiceRequestAuthorizer.php' => 
    array (
      0 => '7ceb99540aea8ce741bd1551518e0226ee7c1829685e64e0aac1c491a86ec84a',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\authorization\\servicerequestauthorizer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\authorization\\authorizes',
        1 => 'app\\domain\\benefit\\authorization\\source',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/BenefitServiceProvider.php' => 
    array (
      0 => '54342bf4f9df217ab8d9e614dee2d2f58c0d59f7703e7888939eb225c895bb11',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\benefitserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\register',
        1 => 'app\\domain\\benefit\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Contracts/BenefitVerifier.php' => 
    array (
      0 => '2914f04c3916f269a43bb87644374e647d9ac331fbef598c974a7c8d88c4d308',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\contracts\\benefitverifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\contracts\\method',
        1 => 'app\\domain\\benefit\\contracts\\isavailable',
        2 => 'app\\domain\\benefit\\contracts\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Enums/BenefitFlagStatus.php' => 
    array (
      0 => '71a52154e6516472ff20f3e4402a6d9612558a5ee443e189fdc8de2f9f4699ff',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\enums\\benefitflagstatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Enums/BenefitStatus.php' => 
    array (
      0 => '627a35d7caf6a58013c56a9a88f1f2bab1b22b0ef211c24c7532cc783980f1f5',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\enums\\benefitstatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Enums/BenefitType.php' => 
    array (
      0 => '859686fa1a6b5a797ee1c3ad8f0cd1a4295fc5f30a339ad35b0e0cf9380834f9',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\enums\\benefittype',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Enums/VerificationMethod.php' => 
    array (
      0 => '8505d69a5622d4029d70c386d38d48b896283f72ecf7529a743042b67509b776',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\enums\\verificationmethod',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Exceptions/DeliveryNotAuthorizedException.php' => 
    array (
      0 => '98a16f3fda792efdc95c21401c3a66b59fede0a767bef62093c81f29b2a0fe35',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\exceptions\\deliverynotauthorizedexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Exceptions/NotEnrolledException.php' => 
    array (
      0 => '544aa1339ed92409a1916afc27441a1d6e9a48a336c375e582f53c876c26755d',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\exceptions\\notenrolledexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Exceptions/VerificationUnavailableException.php' => 
    array (
      0 => '7c123cef72df6699b8b2bb7871fa4c51fa54246ace53407b118d2a88a998aa0a',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\exceptions\\verificationunavailableexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Imports/BenefitDeliveryRowValidator.php' => 
    array (
      0 => '3c2ee5bdffc2e6f77ddb04d6b6a0593a8b16446940c46071edfa72f4f421fcb0',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\imports\\benefitdeliveryrowvalidator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\imports\\__construct',
        1 => 'app\\domain\\benefit\\imports\\validate',
        2 => 'app\\domain\\benefit\\imports\\resolvebeneficiary',
        3 => 'app\\domain\\benefit\\imports\\benefitfields',
        4 => 'app\\domain\\benefit\\imports\\parsedate',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Jobs/CommitBenefitImport.php' => 
    array (
      0 => 'e5aa258adc1375478ff09ba5235fa7fa5d5a4507ee9c3ec43185eee0fe88d46c',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\jobs\\commitbenefitimport',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\jobs\\__construct',
        1 => 'app\\domain\\benefit\\jobs\\handle',
        2 => 'app\\domain\\benefit\\jobs\\commitrow',
        3 => 'app\\domain\\benefit\\jobs\\failed',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Jobs/ParseBenefitImport.php' => 
    array (
      0 => '85b1a3d2f8eff2ff0cb82a93373fbd16c06ea0e9c5fc23fbbb03e4fb7240ba38',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\jobs\\parsebenefitimport',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\jobs\\__construct',
        1 => 'app\\domain\\benefit\\jobs\\handle',
        2 => 'app\\domain\\benefit\\jobs\\failed',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Models/Benefit.php' => 
    array (
      0 => '564535f42ca329420820ed8e0e402e226d374dbd19b2768211ad217235b9e729',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\models\\benefit',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\models\\mdaownershipcolumn',
        1 => 'app\\domain\\benefit\\models\\casts',
        2 => 'app\\domain\\benefit\\models\\newfactory',
        3 => 'app\\domain\\benefit\\models\\beneficiary',
        4 => 'app\\domain\\benefit\\models\\programme',
        5 => 'app\\domain\\benefit\\models\\activity',
        6 => 'app\\domain\\benefit\\models\\enrollment',
        7 => 'app\\domain\\benefit\\models\\mda',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Models/BenefitFlag.php' => 
    array (
      0 => '611280023203f333dc50f797d13717b72575efa9f63983a358d118d5e3227c8a',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\models\\benefitflag',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\models\\casts',
        1 => 'app\\domain\\benefit\\models\\beneficiary',
        2 => 'app\\domain\\benefit\\models\\frommda',
        3 => 'app\\domain\\benefit\\models\\othermda',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Models/BenefitImportBatch.php' => 
    array (
      0 => 'ab0350f31226f879a93debe2a0daf5bde93fabb2f916626b5d1c96fdd2e8be1c',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\models\\benefitimportbatch',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\models\\mdaownershipcolumn',
        1 => 'app\\domain\\benefit\\models\\auditexcluded',
        2 => 'app\\domain\\benefit\\models\\casts',
        3 => 'app\\domain\\benefit\\models\\newfactory',
        4 => 'app\\domain\\benefit\\models\\activity',
        5 => 'app\\domain\\benefit\\models\\programme',
        6 => 'app\\domain\\benefit\\models\\mda',
        7 => 'app\\domain\\benefit\\models\\rows',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Models/BenefitImportRow.php' => 
    array (
      0 => '45c6e24e2d5747581b0b72e68d5d5b0caf8563e1fabee0accbf13df9ee5010f5',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\models\\benefitimportrow',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\models\\casts',
        1 => 'app\\domain\\benefit\\models\\newfactory',
        2 => 'app\\domain\\benefit\\models\\batch',
        3 => 'app\\domain\\benefit\\models\\beneficiary',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Models/DoubleDippingRule.php' => 
    array (
      0 => 'ebc75dd07505f13868661f44d1c40d38be2b3c18051c597b98c936beadcfb282',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\models\\doubledippingrule',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\models\\casts',
        1 => 'app\\domain\\benefit\\models\\newfactory',
        2 => 'app\\domain\\benefit\\models\\appliesto',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Policies/BenefitFlagPolicy.php' => 
    array (
      0 => '2812db42916166d70b3c45810283bba041b21201f14b442e225899a3ac50250a',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\benefitflagpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\involves',
        1 => 'app\\domain\\benefit\\policies\\viewany',
        2 => 'app\\domain\\benefit\\policies\\view',
        3 => 'app\\domain\\benefit\\policies\\review',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Policies/BenefitImportPolicy.php' => 
    array (
      0 => '10a8ecfe0c8f26bc4d0c82a944cb72c69e758aa095a28f8273e966ce0a9090f2',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\benefitimportpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\viewany',
        1 => 'app\\domain\\benefit\\policies\\view',
        2 => 'app\\domain\\benefit\\policies\\create',
        3 => 'app\\domain\\benefit\\policies\\commit',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Policies/BenefitPolicy.php' => 
    array (
      0 => 'cc6fa6efa76f80374bb2df8e4e99d313efd1985f3df5acc12143fc1035146958',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\benefitpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\viewany',
        1 => 'app\\domain\\benefit\\policies\\view',
        2 => 'app\\domain\\benefit\\policies\\record',
        3 => 'app\\domain\\benefit\\policies\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Policies/DoubleDippingRulePolicy.php' => 
    array (
      0 => '672df5b5a5b34b1ce2064b4e74450d059c54c004a4b96dbfd8ce957765118f12',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\doubledippingrulepolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\policies\\viewany',
        1 => 'app\\domain\\benefit\\policies\\create',
        2 => 'app\\domain\\benefit\\policies\\update',
        3 => 'app\\domain\\benefit\\policies\\delete',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/BeneficiaryRevealPresenter.php' => 
    array (
      0 => '606e5f7450c76e5b2398af381b8308136037fee4ce11da84929cac0740e81594',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\beneficiaryrevealpresenter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\sections',
        1 => 'app\\domain\\benefit\\services\\programmes',
        2 => 'app\\domain\\benefit\\services\\benefits',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/BenefitRecorder.php' => 
    array (
      0 => 'e52587f2ab3e1325e4fb6d3094a3cb5a547dba2e4f834e53fa4f642840b282de',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\benefitrecorder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\__construct',
        1 => 'app\\domain\\benefit\\services\\record',
        2 => 'app\\domain\\benefit\\services\\verify',
        3 => 'app\\domain\\benefit\\services\\applyverification',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/DeliveryAuthorization.php' => 
    array (
      0 => 'e1d6a3eb3bb6e40998dd97e6d38a771e94074bdff414c38a8331ebd6f21ad4e2',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\deliveryauthorization',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\__construct',
        1 => 'app\\domain\\benefit\\services\\basisfor',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/DoubleDippingDetector.php' => 
    array (
      0 => '7f9b6d0217777eb739d596d5079284332f2510b2e343d5aeb97097dfee5a7e6e',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\doubledippingdetector',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\check',
        1 => 'app\\domain\\benefit\\services\\raiseflag',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/LedgerAggregator.php' => 
    array (
      0 => '34ac8a4569f1c973ad723a19fa9ce2c6e12b9c324b9bb5354a816caca9a89731',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\ledgeraggregator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\programmebudget',
        1 => 'app\\domain\\benefit\\services\\activitybudget',
        2 => 'app\\domain\\benefit\\services\\aggregate',
        3 => 'app\\domain\\benefit\\services\\scopedtotals',
        4 => 'app\\domain\\benefit\\services\\scopedbudget',
        5 => 'app\\domain\\benefit\\services\\scopedgroup',
        6 => 'app\\domain\\benefit\\services\\scopedledger',
        7 => 'app\\domain\\benefit\\services\\budget',
        8 => 'app\\domain\\benefit\\services\\totals',
        9 => 'app\\domain\\benefit\\services\\applyfilters',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/VerifierRegistry.php' => 
    array (
      0 => 'f1c4d4bfced43a4b2e3e887ab70c21a49a2aa3cb93052c25cf7bb1f4e3dc9f5f',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifierregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\__construct',
        1 => 'app\\domain\\benefit\\services\\for',
        2 => 'app\\domain\\benefit\\services\\availablemethods',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/Verifiers/BiometricVerifier.php' => 
    array (
      0 => '17c57ed3428c04e668f14a8c435d3184763aa7faeaa855013f4c85ee369f8100',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\biometricverifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\method',
        1 => 'app\\domain\\benefit\\services\\verifiers\\isavailable',
        2 => 'app\\domain\\benefit\\services\\verifiers\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/Verifiers/FieldConfirmationVerifier.php' => 
    array (
      0 => '373c313df25098b85dc3245877fe1097cfed10247579ad3cebf3e745a7d50ca5',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\fieldconfirmationverifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\method',
        1 => 'app\\domain\\benefit\\services\\verifiers\\isavailable',
        2 => 'app\\domain\\benefit\\services\\verifiers\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/Verifiers/OtpVerifier.php' => 
    array (
      0 => 'ecb6f708ce2a33a84675fd23c41dba534b2efb91951d1109fc2f4f5ec8d8007c',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\otpverifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\method',
        1 => 'app\\domain\\benefit\\services\\verifiers\\isavailable',
        2 => 'app\\domain\\benefit\\services\\verifiers\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Benefit/Services/Verifiers/SignatureVerifier.php' => 
    array (
      0 => '334014f32f9dff40140229cbd1b99511daede26fc65ee14eb5b3a26d1f7308ec',
      1 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\signatureverifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\benefit\\services\\verifiers\\method',
        1 => 'app\\domain\\benefit\\services\\verifiers\\isavailable',
        2 => 'app\\domain\\benefit\\services\\verifiers\\verify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Enums/GrievanceCategory.php' => 
    array (
      0 => '7b561af6b2522c67b443b8aa2e67d3647b09864d3bbdb43eae23c320768c199a',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\enums\\grievancecategory',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Enums/GrievanceChannel.php' => 
    array (
      0 => 'a36c2fbc1ae70fae976a63cdd187b2c94405facdf1480071769df02d9313c840',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\enums\\grievancechannel',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Enums/GrievanceStatus.php' => 
    array (
      0 => '8e998b38803b5187bb481a1c73665295dc12e307863355a295f429b819d04c66',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\enums\\grievancestatus',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\enums\\allowednext',
        1 => 'app\\domain\\grievance\\enums\\cantransitionto',
        2 => 'app\\domain\\grievance\\enums\\isterminal',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Events/GrievanceAssigned.php' => 
    array (
      0 => '918cda6950250411958218a6132c5e7e6dd361f46f40e60f5043e22e12f4b2a0',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\events\\grievanceassigned',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Events/GrievanceResolved.php' => 
    array (
      0 => 'd201afd898da5cb66182a1c06859c8cf524f07477b5167604cce1e33c28938d2',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\events\\grievanceresolved',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Events/GrievanceSlaBreached.php' => 
    array (
      0 => 'b9ed5e62bb9f41e2a61bb402eeefc7d0007c0d7dafeea05b870b16260ff32a76',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\events\\grievanceslabreached',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Exceptions/InvalidGrievanceTransitionException.php' => 
    array (
      0 => '53469c75787a06413efc003efb18498de3dc47259cd9b9225334c29a29aefdb6',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\exceptions\\invalidgrievancetransitionexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/GrievanceServiceProvider.php' => 
    array (
      0 => '029bad74913957a2ecbaf02b23df8277df65595bcc02a08ad938af61fc63e609',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\grievanceserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Jobs/EscalateOverdueGrievances.php' => 
    array (
      0 => '33ac339573028f109ccd54f33c04529cff633c870eeb8ab401742458c61ea106',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\jobs\\escalateoverduegrievances',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\jobs\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Models/Grievance.php' => 
    array (
      0 => '736416937988ff2b10560021b26ef9ce8a53e40e9d1570ced11cece9fa85caef',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\models\\grievance',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\models\\casts',
        1 => 'app\\domain\\grievance\\models\\mdaownershipcolumn',
        2 => 'app\\domain\\grievance\\models\\auditexcluded',
        3 => 'app\\domain\\grievance\\models\\handlingmda',
        4 => 'app\\domain\\grievance\\models\\beneficiary',
        5 => 'app\\domain\\grievance\\models\\assignee',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Models/GrievanceSlaPolicy.php' => 
    array (
      0 => '2910253196a52181630c5e82e196a910561b82529bf1e773670309e3843e7faa',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\models\\grievanceslapolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\models\\casts',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Policies/GrievancePolicy.php' => 
    array (
      0 => 'c7d9880f0af3bd08734036a6ce440076c3015e9836df2a308a577efe5c50f295',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\policies\\grievancepolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\policies\\viewany',
        1 => 'app\\domain\\grievance\\policies\\view',
        2 => 'app\\domain\\grievance\\policies\\create',
        3 => 'app\\domain\\grievance\\policies\\manage',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Grievance/Services/GrievanceService.php' => 
    array (
      0 => 'b463f8bade9327acac7967efaf0bbcba5d1ffb9bcd52c6861d6956155037bfd4',
      1 => 
      array (
        0 => 'app\\domain\\grievance\\services\\grievanceservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\grievance\\services\\__construct',
        1 => 'app\\domain\\grievance\\services\\create',
        2 => 'app\\domain\\grievance\\services\\assign',
        3 => 'app\\domain\\grievance\\services\\start',
        4 => 'app\\domain\\grievance\\services\\resolve',
        5 => 'app\\domain\\grievance\\services\\close',
        6 => 'app\\domain\\grievance\\services\\transition',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Contracts/MatchScorer.php' => 
    array (
      0 => '6a50d61d435858d12704ccce8ddd03866c7b7f03fb80dd7bde921fc99e7dd470',
      1 => 
      array (
        0 => 'app\\domain\\matching\\contracts\\matchscorer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\contracts\\score',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Engine/DeterministicMatcher.php' => 
    array (
      0 => '25fc4f37a1954d18b8089bb06b47f564ad2b37c1f468b7dc0b17c376efd6f035',
      1 => 
      array (
        0 => 'app\\domain\\matching\\engine\\deterministicmatcher',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\engine\\__construct',
        1 => 'app\\domain\\matching\\engine\\match',
        2 => 'app\\domain\\matching\\engine\\matchkeysets',
        3 => 'app\\domain\\matching\\engine\\firedkeysets',
        4 => 'app\\domain\\matching\\engine\\normalisecandidatekeys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Engine/DuplicateCascade.php' => 
    array (
      0 => '4d230988924c4d4ec820454c3893d4cbbfceda4c7c8cb3c82585263364ee75b7',
      1 => 
      array (
        0 => 'app\\domain\\matching\\engine\\duplicatecascade',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\engine\\__construct',
        1 => 'app\\domain\\matching\\engine\\evaluate',
        2 => 'app\\domain\\matching\\engine\\candidatehaskey',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Engine/MatchResult.php' => 
    array (
      0 => '177f277ebf6c6cfd5b06e57f8a399d6c0ce1aff2a936692670fc0c3288fa7f24',
      1 => 
      array (
        0 => 'app\\domain\\matching\\engine\\matchresult',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\engine\\__construct',
        1 => 'app\\domain\\matching\\engine\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Engine/MatchingEngine.php' => 
    array (
      0 => '50c2a21850f502653e9093a8aa73ae9be74cc2840fd577e73aba69d28d48a259',
      1 => 
      array (
        0 => 'app\\domain\\matching\\engine\\matchingengine',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\engine\\__construct',
        1 => 'app\\domain\\matching\\engine\\match',
        2 => 'app\\domain\\matching\\engine\\band',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Enums/ExactMatchBehaviour.php' => 
    array (
      0 => '678a2388a77f2072a77bbef6b5c963f86773123cd014d6ad8fddd66808cd029d',
      1 => 
      array (
        0 => 'app\\domain\\matching\\enums\\exactmatchbehaviour',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Enums/MatchBand.php' => 
    array (
      0 => 'c86d1270a2ef9b07037fa032a7842d5ccb34906203636c5d274f8399b8da7b93',
      1 => 
      array (
        0 => 'app\\domain\\matching\\enums\\matchband',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\enums\\rank',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Enums/MatchComparator.php' => 
    array (
      0 => '9000507548e87cb5ed6edd4a7c765d9e5c0a37bf6500ae8d2f41ecd0e648d331',
      1 => 
      array (
        0 => 'app\\domain\\matching\\enums\\matchcomparator',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Enums/MatchField.php' => 
    array (
      0 => '1683b93bb5a6c155bceb224a0e9ef3991c5217bb58ee097062eda8ed915ae50c',
      1 => 
      array (
        0 => 'app\\domain\\matching\\enums\\matchfield',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\enums\\isnumericidentifier',
        1 => 'app\\domain\\matching\\enums\\values',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/MatchingServiceProvider.php' => 
    array (
      0 => 'cb1fbe7c85528c14a00f02cbb3c7f33993c3815913ae68b49ea71d401af4cec9',
      1 => 
      array (
        0 => 'app\\domain\\matching\\matchingserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\register',
        1 => 'app\\domain\\matching\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Models/MatchingConfig.php' => 
    array (
      0 => '45ac8dcc882927dba2ca43f8a8db8ac53d0be89ef2273ca3c05d1abd7786a93c',
      1 => 
      array (
        0 => 'app\\domain\\matching\\models\\matchingconfig',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\models\\casts',
        1 => 'app\\domain\\matching\\models\\deterministickeysets',
        2 => 'app\\domain\\matching\\models\\fuzzyfields',
        3 => 'app\\domain\\matching\\models\\totalfuzzyweight',
        4 => 'app\\domain\\matching\\models\\author',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/Comparator.php' => 
    array (
      0 => '740856faeb5ea82308fd6e030d4c97c711f900baf143252e379fd3869833b8b2',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\comparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\compare',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/ComparatorRegistry.php' => 
    array (
      0 => '0b09da4939d73f4a49fac70f941cd234a548781475e41d2f2e33462570295971',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\comparatorregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\__construct',
        1 => 'app\\domain\\matching\\scoring\\comparators\\get',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/DateProximityComparator.php' => 
    array (
      0 => 'b41853fd80c0dabe9402bc9520db835d800587f0c32036d957745fefda2bdffe',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\dateproximitycomparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\compare',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/ExactComparator.php' => 
    array (
      0 => '2e854834baf1d4b2b0c27cab629287dff0243e803432b30bc8617650682b41c0',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\exactcomparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\compare',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/JaroWinklerComparator.php' => 
    array (
      0 => 'f6ab7f5b1d9902af627998b41e0149c93d4907c5d8b5a1046663b3ddcfb36eef',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\jarowinklercomparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\compare',
        1 => 'app\\domain\\matching\\scoring\\comparators\\jaro',
        2 => 'app\\domain\\matching\\scoring\\comparators\\commonprefix',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/LevenshteinComparator.php' => 
    array (
      0 => 'e5f7a7fd57a72fc4c7b0c93f1fa47bd723e96c26e4dc89dc6ce49532b9c2c41c',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\levenshteincomparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\compare',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/Comparators/PhoneticComparator.php' => 
    array (
      0 => '16e0f06a6a024ca27942b0f9c51ecce965235d7a76c71c077039dc89cc0dd2f6',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\phoneticcomparator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\comparators\\__construct',
        1 => 'app\\domain\\matching\\scoring\\comparators\\compare',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/FieldNormalizer.php' => 
    array (
      0 => '186242c59e4c3594b279d0b5aa84bb0fee5d9c7a25ef08b8e05d55541dbbc04c',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\fieldnormalizer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\normalize',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/MatchScore.php' => 
    array (
      0 => 'd10013b3839213a53ec095f90028cd01c498c90eb70d443d1be58b800cfc2f30',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\matchscore',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\__construct',
        1 => 'app\\domain\\matching\\scoring\\toarray',
        2 => 'app\\domain\\matching\\scoring\\matchedfields',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Scoring/RuleBasedMatchScorer.php' => 
    array (
      0 => '840d9653435479683a1072b915545761cb3e6ff7c032e2d855572eb0a343400b',
      1 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\rulebasedmatchscorer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\scoring\\__construct',
        1 => 'app\\domain\\matching\\scoring\\score',
        2 => 'app\\domain\\matching\\scoring\\keysetmatches',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Services/MatchingConfigService.php' => 
    array (
      0 => '2b4650d34729b72834d7269f49c58eb0150cfdf4bfb64ecc9ac14e10749543d7',
      1 => 
      array (
        0 => 'app\\domain\\matching\\services\\matchingconfigservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\services\\active',
        1 => 'app\\domain\\matching\\services\\activeornull',
        2 => 'app\\domain\\matching\\services\\publish',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Matching/Support/PhoneticEncoder.php' => 
    array (
      0 => 'c3bb4cf143a17feea7d3eabdab4c278640724f5d5df1674634109f08158e033f',
      1 => 
      array (
        0 => 'app\\domain\\matching\\support\\phoneticencoder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\matching\\support\\code',
        1 => 'app\\domain\\matching\\support\\block',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Channels/EmailChannel.php' => 
    array (
      0 => 'c3adfe2d2fe6ca8dd22dc1e021da4c0237090a357dbdef44ba6a712f4fceae3a',
      1 => 
      array (
        0 => 'app\\domain\\notification\\channels\\emailchannel',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\channels\\key',
        1 => 'app\\domain\\notification\\channels\\isavailable',
        2 => 'app\\domain\\notification\\channels\\send',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Channels/InAppChannel.php' => 
    array (
      0 => 'ad427d0ac3927c1cefcede52fd115f12e4fd633bd0c03730da86cd53cb960229',
      1 => 
      array (
        0 => 'app\\domain\\notification\\channels\\inappchannel',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\channels\\key',
        1 => 'app\\domain\\notification\\channels\\isavailable',
        2 => 'app\\domain\\notification\\channels\\send',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Channels/SmsChannel.php' => 
    array (
      0 => '0051a1f1cb5898eafa482d37e138c506334eb260fe4ec09f3a835a2646abdee7',
      1 => 
      array (
        0 => 'app\\domain\\notification\\channels\\smschannel',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\channels\\key',
        1 => 'app\\domain\\notification\\channels\\isavailable',
        2 => 'app\\domain\\notification\\channels\\send',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Channels/WhatsAppChannel.php' => 
    array (
      0 => 'd11a016804bf334d15841aa0e83cbf01b24dbfaa7678de0159ecebf8bd3fceab',
      1 => 
      array (
        0 => 'app\\domain\\notification\\channels\\whatsappchannel',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\channels\\key',
        1 => 'app\\domain\\notification\\channels\\isavailable',
        2 => 'app\\domain\\notification\\channels\\send',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Contracts/NotificationChannel.php' => 
    array (
      0 => '024de6263ac28837e31314ae4245d5c891989c33dda0e64fc0153f653ffe87db',
      1 => 
      array (
        0 => 'app\\domain\\notification\\contracts\\notificationchannel',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\contracts\\key',
        1 => 'app\\domain\\notification\\contracts\\isavailable',
        2 => 'app\\domain\\notification\\contracts\\send',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Exceptions/ChannelUnavailableException.php' => 
    array (
      0 => '0934b96fd5707456bbaddc64a4e3cafc98ce87af0f84f0cbae1408ea767f5deb',
      1 => 
      array (
        0 => 'app\\domain\\notification\\exceptions\\channelunavailableexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Listeners/NotificationSubscriber.php' => 
    array (
      0 => '86b29f126f6da5c75b69629b217f4a45178b2622b1a2e6e861f1a6653580c236',
      1 => 
      array (
        0 => 'app\\domain\\notification\\listeners\\notificationsubscriber',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\listeners\\__construct',
        1 => 'app\\domain\\notification\\listeners\\handleservicerequestraised',
        2 => 'app\\domain\\notification\\listeners\\handleservicerequestaccepted',
        3 => 'app\\domain\\notification\\listeners\\handleservicerequestdeclined',
        4 => 'app\\domain\\notification\\listeners\\handleownershiptransferrequested',
        5 => 'app\\domain\\notification\\listeners\\approversin',
        6 => 'app\\domain\\notification\\listeners\\requester',
        7 => 'app\\domain\\notification\\listeners\\handlereferralstatuschanged',
        8 => 'app\\domain\\notification\\listeners\\handlereferralslabreached',
        9 => 'app\\domain\\notification\\listeners\\bothparties',
        10 => 'app\\domain\\notification\\listeners\\escalationtier',
        11 => 'app\\domain\\notification\\listeners\\handlegrievanceassigned',
        12 => 'app\\domain\\notification\\listeners\\handlegrievanceresolved',
        13 => 'app\\domain\\notification\\listeners\\handlegrievanceslabreached',
        14 => 'app\\domain\\notification\\listeners\\grievanceescalationtier',
        15 => 'app\\domain\\notification\\listeners\\handlereportready',
        16 => 'app\\domain\\notification\\listeners\\subscribe',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Mail/NotificationMail.php' => 
    array (
      0 => '38e56bec06fccdad2e17e88696d69734407cd98d3cc1f87b74f4c5f32e3484a1',
      1 => 
      array (
        0 => 'app\\domain\\notification\\mail\\notificationmail',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\mail\\__construct',
        1 => 'app\\domain\\notification\\mail\\build',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Models/Notification.php' => 
    array (
      0 => '9fb0da8933a12a90a31c67434338e8e6f0e9548fd098afda44e9a35ccd5aa98b',
      1 => 
      array (
        0 => 'app\\domain\\notification\\models\\notification',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\models\\casts',
        1 => 'app\\domain\\notification\\models\\mdaownershipcolumn',
        2 => 'app\\domain\\notification\\models\\recipient',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Models/NotificationPreference.php' => 
    array (
      0 => '8e3f417b034240f0226a1dbd9d1a58bc06cc5000412c55df91fa951873333bc7',
      1 => 
      array (
        0 => 'app\\domain\\notification\\models\\notificationpreference',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\models\\casts',
        1 => 'app\\domain\\notification\\models\\user',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/NotificationServiceProvider.php' => 
    array (
      0 => '1d6f1323ac9eeebf0162d6ec3a050737cece4fd3b9b2192da08d45880ec789ef',
      1 => 
      array (
        0 => 'app\\domain\\notification\\notificationserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\register',
        1 => 'app\\domain\\notification\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Services/Notifier.php' => 
    array (
      0 => '19e985dce1330094cdf277be2579b042960cf1a64e1d0d1f94f1c7d3b0e966ab',
      1 => 
      array (
        0 => 'app\\domain\\notification\\services\\notifier',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\services\\__construct',
        1 => 'app\\domain\\notification\\services\\notify',
        2 => 'app\\domain\\notification\\services\\allows',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Notification/Support/NotificationMessage.php' => 
    array (
      0 => 'a136eb37ba2520d08b847859cfb44c85da68e4edaaeb5d17d7a98e7c09f2042e',
      1 => 
      array (
        0 => 'app\\domain\\notification\\support\\notificationmessage',
      ),
      2 => 
      array (
        0 => 'app\\domain\\notification\\support\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Enums/ActivityStatus.php' => 
    array (
      0 => '43f8da6856eaa64bdf694ad3deb25a535a3180c01aba72a3bd318d6026b9b895',
      1 => 
      array (
        0 => 'app\\domain\\programme\\enums\\activitystatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Enums/EnrollmentStatus.php' => 
    array (
      0 => '23c087bf0daac86688a3623d9011f65f29a54741ddc970324108ca661b66b230',
      1 => 
      array (
        0 => 'app\\domain\\programme\\enums\\enrollmentstatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Enums/ProgrammeStatus.php' => 
    array (
      0 => '78416a03f16f08f24491cb232a14fbd68fa9481efc3a7c00a835a50068510319',
      1 => 
      array (
        0 => 'app\\domain\\programme\\enums\\programmestatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Enums/ProgrammeType.php' => 
    array (
      0 => 'f7533f8bf155e48ab593cc811fb903d75a9185855694c0ec87ed7a057232a2dd',
      1 => 
      array (
        0 => 'app\\domain\\programme\\enums\\programmetype',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Models/Activity.php' => 
    array (
      0 => '03f0b4950c66e6cfcc0f65692ea000ebfa47b76648d1e6a551653c1c50153b92',
      1 => 
      array (
        0 => 'app\\domain\\programme\\models\\activity',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\models\\casts',
        1 => 'app\\domain\\programme\\models\\newfactory',
        2 => 'app\\domain\\programme\\models\\programme',
        3 => 'app\\domain\\programme\\models\\ownermda',
        4 => 'app\\domain\\programme\\models\\creator',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Models/Enrollment.php' => 
    array (
      0 => '933420c81e4fee74f76bbef3b28ef68f6a6e174f052b173ef9304e9d6621a09c',
      1 => 
      array (
        0 => 'app\\domain\\programme\\models\\enrollment',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\models\\mdaownershipcolumn',
        1 => 'app\\domain\\programme\\models\\casts',
        2 => 'app\\domain\\programme\\models\\newfactory',
        3 => 'app\\domain\\programme\\models\\programme',
        4 => 'app\\domain\\programme\\models\\activity',
        5 => 'app\\domain\\programme\\models\\mda',
        6 => 'app\\domain\\programme\\models\\beneficiary',
        7 => 'app\\domain\\programme\\models\\household',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Models/Programme.php' => 
    array (
      0 => 'fff0c83972f6c9a80cfba2df8a15f711fb7a6229f8b886273dfe93504bf936ad',
      1 => 
      array (
        0 => 'app\\domain\\programme\\models\\programme',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\models\\casts',
        1 => 'app\\domain\\programme\\models\\newfactory',
        2 => 'app\\domain\\programme\\models\\creator',
        3 => 'app\\domain\\programme\\models\\activities',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Models/ProgrammeFunder.php' => 
    array (
      0 => '0343903efd67d1aad21b50a87a951d79a433a860138f89103c09779f0219eee3',
      1 => 
      array (
        0 => 'app\\domain\\programme\\models\\programmefunder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\models\\programmeidsforuser',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Policies/ActivityPolicy.php' => 
    array (
      0 => '9dcb8187883abdaf0b8c0c697de490794cf22c2fe497ed2a8e45483e157ad618',
      1 => 
      array (
        0 => 'app\\domain\\programme\\policies\\activitypolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\policies\\owns',
        1 => 'app\\domain\\programme\\policies\\viewany',
        2 => 'app\\domain\\programme\\policies\\view',
        3 => 'app\\domain\\programme\\policies\\create',
        4 => 'app\\domain\\programme\\policies\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Policies/EnrollmentPolicy.php' => 
    array (
      0 => '969cae631ff37ce5a126a88619c00a0e460240bce7a2a66f3617a9eee8486dd6',
      1 => 
      array (
        0 => 'app\\domain\\programme\\policies\\enrollmentpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\policies\\viewany',
        1 => 'app\\domain\\programme\\policies\\view',
        2 => 'app\\domain\\programme\\policies\\create',
        3 => 'app\\domain\\programme\\policies\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Policies/ProgrammePolicy.php' => 
    array (
      0 => 'c98cbe08a0c8a7428013137c97dc5966dd2b069a8a79e0b6dc007bcc79bf914a',
      1 => 
      array (
        0 => 'app\\domain\\programme\\policies\\programmepolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\policies\\iscatalogadmin',
        1 => 'app\\domain\\programme\\policies\\viewany',
        2 => 'app\\domain\\programme\\policies\\view',
        3 => 'app\\domain\\programme\\policies\\create',
        4 => 'app\\domain\\programme\\policies\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/ProgrammeServiceProvider.php' => 
    array (
      0 => 'f1c9dcc2c540d23b4c8c81fdead36f64ae2e8202b08ba837c72d08d0f00ab864',
      1 => 
      array (
        0 => 'app\\domain\\programme\\programmeserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Services/EligibilityEvaluator.php' => 
    array (
      0 => 'd03fc48242bc0d11e9cf36e0f43ce3e21e2f3190e09052d97768cf16d638f25f',
      1 => 
      array (
        0 => 'app\\domain\\programme\\services\\eligibilityevaluator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\services\\evaluate',
        1 => 'app\\domain\\programme\\services\\satisfies',
        2 => 'app\\domain\\programme\\services\\matches',
        3 => 'app\\domain\\programme\\services\\subjectattributes',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Services/EnrollmentService.php' => 
    array (
      0 => 'a76def3ec97d20bfb8562e0f9ef0b467ec38585795851d0385c219dc71ef44c1',
      1 => 
      array (
        0 => 'app\\domain\\programme\\services\\enrollmentservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\services\\__construct',
        1 => 'app\\domain\\programme\\services\\canserve',
        2 => 'app\\domain\\programme\\services\\enroll',
        3 => 'app\\domain\\programme\\services\\hasopenenrollment',
        4 => 'app\\domain\\programme\\services\\outcome',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Services/ProgrammeMatcher.php' => 
    array (
      0 => '0be25975e5825c187bf3fde23452e8a38840f4c377a1da0ea25e1f6f886bc711',
      1 => 
      array (
        0 => 'app\\domain\\programme\\services\\programmematcher',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\services\\__construct',
        1 => 'app\\domain\\programme\\services\\suggest',
        2 => 'app\\domain\\programme\\services\\matchesneed',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Programme/Services/ProgrammeMatchingRouter.php' => 
    array (
      0 => '3d3cf7ca199d082af146a0b41b345f3316a32ab597b68b28d4692bd671934e32',
      1 => 
      array (
        0 => 'app\\domain\\programme\\services\\programmematchingrouter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\programme\\services\\__construct',
        1 => 'app\\domain\\programme\\services\\suggestmdafor',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Authorization/ReferralAuthorizer.php' => 
    array (
      0 => 'b02050e5b9bd3ffd30b9117d2a0d4b242f5ccfee4fdbf6f6ebd490ef690a8472',
      1 => 
      array (
        0 => 'app\\domain\\referral\\authorization\\referralauthorizer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\authorization\\authorizes',
        1 => 'app\\domain\\referral\\authorization\\source',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Enums/ReferralStatus.php' => 
    array (
      0 => '2627c0c52327fba85b43395182a215d912ab542f420b65fc26df96c876e4dc06',
      1 => 
      array (
        0 => 'app\\domain\\referral\\enums\\referralstatus',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\enums\\allowednext',
        1 => 'app\\domain\\referral\\enums\\cantransitionto',
        2 => 'app\\domain\\referral\\enums\\isterminal',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Events/ReferralSlaBreached.php' => 
    array (
      0 => '518592a26bf11981637361d65fe98f69bf2906105dc1d205e1111f65edad920a',
      1 => 
      array (
        0 => 'app\\domain\\referral\\events\\referralslabreached',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Events/ReferralStatusChanged.php' => 
    array (
      0 => '723c1005c8edf93979dd43fb71f68793bd73074c49ac6b5a480152b9b29ded65',
      1 => 
      array (
        0 => 'app\\domain\\referral\\events\\referralstatuschanged',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Exceptions/InvalidReferralTransitionException.php' => 
    array (
      0 => 'a1cc81cea2301c44313aa7c8ecf8d89fccd7be0fc98d063273678aaa6528498b',
      1 => 
      array (
        0 => 'app\\domain\\referral\\exceptions\\invalidreferraltransitionexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Jobs/EscalateOverdueReferrals.php' => 
    array (
      0 => '742c9d159f976fc035d78627c6db98bf7d09d6e9d4f2d7ba29fbcc6b3ff2f94e',
      1 => 
      array (
        0 => 'app\\domain\\referral\\jobs\\escalateoverduereferrals',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\jobs\\handle',
        1 => 'app\\domain\\referral\\jobs\\enteredat',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Models/Referral.php' => 
    array (
      0 => '63ff59e3e8b49fccb8e7e5b93f66b2ce3d261c54c0821697560001d497129088',
      1 => 
      array (
        0 => 'app\\domain\\referral\\models\\referral',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\models\\booted',
        1 => 'app\\domain\\referral\\models\\authorizesdelivery',
        2 => 'app\\domain\\referral\\models\\casts',
        3 => 'app\\domain\\referral\\models\\auditexcluded',
        4 => 'app\\domain\\referral\\models\\beneficiary',
        5 => 'app\\domain\\referral\\models\\frommda',
        6 => 'app\\domain\\referral\\models\\tomda',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Models/ReferralSlaPolicy.php' => 
    array (
      0 => '89b50fd438ca63152c6c1931228fcf9ddc721d897999a0e2957b50c0ffdb23d4',
      1 => 
      array (
        0 => 'app\\domain\\referral\\models\\referralslapolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\models\\casts',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Policies/ReferralPolicy.php' => 
    array (
      0 => '2d26d6f9202a66132332bc8aef7febbd7b9e7c33e842eba2f4d8d2c3a336b001',
      1 => 
      array (
        0 => 'app\\domain\\referral\\policies\\referralpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\policies\\viewany',
        1 => 'app\\domain\\referral\\policies\\view',
        2 => 'app\\domain\\referral\\policies\\create',
        3 => 'app\\domain\\referral\\policies\\receive',
        4 => 'app\\domain\\referral\\policies\\originate',
        5 => 'app\\domain\\referral\\policies\\isparty',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/ReferralServiceProvider.php' => 
    array (
      0 => 'a3cac3ab32360bd52e1d47226b870ea02003847f7d43489f344142b22b89a2de',
      1 => 
      array (
        0 => 'app\\domain\\referral\\referralserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\register',
        1 => 'app\\domain\\referral\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Scopes/ReferralScope.php' => 
    array (
      0 => 'be7944937d272bfb96a3c8e3578b88ed50ec8f487ba3d55af0e4149432a017ee',
      1 => 
      array (
        0 => 'app\\domain\\referral\\scopes\\referralscope',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\scopes\\apply',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Referral/Services/ReferralService.php' => 
    array (
      0 => '62d52278002e3862a2be4061a677f2dd754da0440ea001cfb520b90d5909d0b4',
      1 => 
      array (
        0 => 'app\\domain\\referral\\services\\referralservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\referral\\services\\__construct',
        1 => 'app\\domain\\referral\\services\\create',
        2 => 'app\\domain\\referral\\services\\accept',
        3 => 'app\\domain\\referral\\services\\reject',
        4 => 'app\\domain\\referral\\services\\requestinfo',
        5 => 'app\\domain\\referral\\services\\respondinfo',
        6 => 'app\\domain\\referral\\services\\start',
        7 => 'app\\domain\\referral\\services\\complete',
        8 => 'app\\domain\\referral\\services\\close',
        9 => 'app\\domain\\referral\\services\\reconciliation',
        10 => 'app\\domain\\referral\\services\\transition',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Contracts/BeneficiaryRouter.php' => 
    array (
      0 => 'c51820b21d4a2b5b79e46c7eb12e85656b27b65f8ca4745d66685de3efd7c4dc',
      1 => 
      array (
        0 => 'app\\domain\\registry\\contracts\\beneficiaryrouter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\contracts\\suggestmdafor',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Contracts/DuplicateChecker.php' => 
    array (
      0 => '07a38eba165f1ce7ca7cc6e0b0f418a834e27678da7ecd92825b273def8a8dcc',
      1 => 
      array (
        0 => 'app\\domain\\registry\\contracts\\duplicatechecker',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\contracts\\check',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/BeneficiaryStatus.php' => 
    array (
      0 => '7e1b800d5f869d042d11ac0ca74c755e2d1a4637c064faaee90822c18b9bef55',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\beneficiarystatus',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/DocumentType.php' => 
    array (
      0 => 'cf9aba8c72b97c2ecc84ca3cfe3afaf6a57c8b64e3f836108148546718de81f9',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\documenttype',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/Gender.php' => 
    array (
      0 => 'f204b0d5de690aaa15205fb62b4efaa6c482a7017f0574547d98b31f26c01210',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\gender',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/HouseholdRole.php' => 
    array (
      0 => '907a1b1a00c1261bc25cba7fed52177ddde817c7ac6b3fb85e5016bc2333fe40',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\householdrole',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/ImportRowResolution.php' => 
    array (
      0 => 'fe4032867c0c8dcbade681b08155378bd774b1fd53b7b00d46268dfdea34bbf2',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\importrowresolution',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/ImportStatus.php' => 
    array (
      0 => 'eba19626b5869f30aa8bb267c0aa2a97f16ffe33b3f0b9b278cf3c628f8b6f5a',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\importstatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/Lga.php' => 
    array (
      0 => 'd5ed56dc15e9c2198671ee106d6ce61fbc909d6b76a3ab0345305e72fea1b1c2',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\lga',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\enums\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/OwnershipTransferStatus.php' => 
    array (
      0 => 'd70f4176b76b4a5dfd2cf975fddcde3a3ae0da944e841009db7c02265b7da9db',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\ownershiptransferstatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/RegistrationSource.php' => 
    array (
      0 => '7740d9ad863728c6504681d12c7220cfec980f8f54643174903fc6cc95c204bb',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\registrationsource',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Enums/ServiceRequestStatus.php' => 
    array (
      0 => '052e133a68f874c1c45293f802b96434663df57c738c3eb8c1e2d7be725178bc',
      1 => 
      array (
        0 => 'app\\domain\\registry\\enums\\servicerequeststatus',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Events/OwnershipTransferRequested.php' => 
    array (
      0 => '9b83576610d447c14c1a2069e041e37a55f334e9cbe2224710d26efa11c68957',
      1 => 
      array (
        0 => 'app\\domain\\registry\\events\\ownershiptransferrequested',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Events/ServiceRequestAccepted.php' => 
    array (
      0 => 'aef5857106080d6c5558bdb1fa343cb3f2ec7757c9012ad52ee1ccaca0ea92f4',
      1 => 
      array (
        0 => 'app\\domain\\registry\\events\\servicerequestaccepted',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Events/ServiceRequestDeclined.php' => 
    array (
      0 => 'a4d326d469c8b456043577aa7aec19c2ddf99ca3ce5855e7719475365a82ec9f',
      1 => 
      array (
        0 => 'app\\domain\\registry\\events\\servicerequestdeclined',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Events/ServiceRequestRaised.php' => 
    array (
      0 => '56da30156582ae9023a3191ae88dea280eaf1bd1a4f418d9f1cb7e7adb7b7537',
      1 => 
      array (
        0 => 'app\\domain\\registry\\events\\servicerequestraised',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/DefaultImportAdapter.php' => 
    array (
      0 => '919074dd7218fdd7ccc3ac59c0b9c3264e013e3141ef090376874ac70e2fbbd8',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\defaultimportadapter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\__construct',
        1 => 'app\\domain\\registry\\imports\\adapters\\source',
        2 => 'app\\domain\\registry\\imports\\adapters\\idkeys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/FieldMappingAdapter.php' => 
    array (
      0 => '653404df6a2848ef67671b39f3828b5472ae5aaa5f5a02c9f44c4bd96598cd50',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\fieldmappingadapter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\map',
        1 => 'app\\domain\\registry\\imports\\adapters\\idkeys',
        2 => 'app\\domain\\registry\\imports\\adapters\\extraaliases',
        3 => 'app\\domain\\registry\\imports\\adapters\\aliases',
        4 => 'app\\domain\\registry\\imports\\adapters\\extractrecordid',
        5 => 'app\\domain\\registry\\imports\\adapters\\firstnonempty',
        6 => 'app\\domain\\registry\\imports\\adapters\\canonicalisekeys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/KoboAdapter.php' => 
    array (
      0 => 'e052ea1ddfc65f4cad8d116d951dcfc5addf48081ae92f987d3c7b5092a2db75',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\koboadapter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\source',
        1 => 'app\\domain\\registry\\imports\\adapters\\idkeys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/OdkAdapter.php' => 
    array (
      0 => 'c1984b8e0b779f1ca03c02890b02bf86deb344756bf59bd33b5684b52e7807e5',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\odkadapter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\source',
        1 => 'app\\domain\\registry\\imports\\adapters\\idkeys',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/RegistrationSourceAdapter.php' => 
    array (
      0 => '93c718a46d1679a527b06a84c854f53cb2ac3a6162abc49167c63a570f4563e6',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\registrationsourceadapter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\source',
        1 => 'app\\domain\\registry\\imports\\adapters\\map',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/Adapters/SourceAdapterRegistry.php' => 
    array (
      0 => '18a85c06aa3ed13b221e2887adfcca14e88518c07c75fc943e74949ef76a36c9',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\sourceadapterregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\adapters\\register',
        1 => 'app\\domain\\registry\\imports\\adapters\\for',
        2 => 'app\\domain\\registry\\imports\\adapters\\has',
        3 => 'app\\domain\\registry\\imports\\adapters\\importablesources',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/ImportRowValidator.php' => 
    array (
      0 => '9d396786d1ddc150f07d8a959b6e2340fe49b9dbe397f289593986f61fdf08ed',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\importrowvalidator',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\validate',
        1 => 'app\\domain\\registry\\imports\\normalise',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Imports/SpreadsheetReader.php' => 
    array (
      0 => 'fcd2abdccef905df4648c583dc1187bcf5cff2fb9510ef7387dc600beeba5581',
      1 => 
      array (
        0 => 'app\\domain\\registry\\imports\\spreadsheetreader',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\imports\\read',
        1 => 'app\\domain\\registry\\imports\\canonicalheader',
        2 => 'app\\domain\\registry\\imports\\stringify',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Jobs/CommitImportBatch.php' => 
    array (
      0 => 'cbe231895a67d34232e6047bc73e0587ee765e37d61d575e0923c917d5e07821',
      1 => 
      array (
        0 => 'app\\domain\\registry\\jobs\\commitimportbatch',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\jobs\\__construct',
        1 => 'app\\domain\\registry\\jobs\\handle',
        2 => 'app\\domain\\registry\\jobs\\effectiveresolution',
        3 => 'app\\domain\\registry\\jobs\\recordintervention',
        4 => 'app\\domain\\registry\\jobs\\serve',
        5 => 'app\\domain\\registry\\jobs\\failed',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Jobs/ParseImportBatch.php' => 
    array (
      0 => '62156c677d92f573206d8d0f30c99dc5bb2a6ee38789e4ddded27648523f35aa',
      1 => 
      array (
        0 => 'app\\domain\\registry\\jobs\\parseimportbatch',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\jobs\\__construct',
        1 => 'app\\domain\\registry\\jobs\\handle',
        2 => 'app\\domain\\registry\\jobs\\failed',
        3 => 'app\\domain\\registry\\jobs\\tag',
        4 => 'app\\domain\\registry\\jobs\\istruthy',
        5 => 'app\\domain\\registry\\jobs\\autoresolve',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/Beneficiary.php' => 
    array (
      0 => '9ced46225f6fd13a36e79d5d039efb55a6bb7cfcaffa41aab28e212aa66fe8e5',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\beneficiary',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\auditmask',
        2 => 'app\\domain\\registry\\models\\auditexcluded',
        3 => 'app\\domain\\registry\\models\\booted',
        4 => 'app\\domain\\registry\\models\\normalizedigits',
        5 => 'app\\domain\\registry\\models\\blocknamedobfor',
        6 => 'app\\domain\\registry\\models\\fullname',
        7 => 'app\\domain\\registry\\models\\ownermda',
        8 => 'app\\domain\\registry\\models\\householdmemberships',
        9 => 'app\\domain\\registry\\models\\currentmembership',
        10 => 'app\\domain\\registry\\models\\newfactory',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/BeneficiaryDocument.php' => 
    array (
      0 => '57cc8abd59e4c5e20c688438068fdfad9102d6372de979bf6f124c437eb5fa14',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\beneficiarydocument',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\beneficiary',
        2 => 'app\\domain\\registry\\models\\ownermda',
        3 => 'app\\domain\\registry\\models\\uploadedby',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/BeneficiaryServiceGrant.php' => 
    array (
      0 => '608b14533256f27b69458646a4c02048c3b1ffeff446ae8ef72151671213fb20',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\beneficiaryservicegrant',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\mdaownershipcolumn',
        2 => 'app\\domain\\registry\\models\\beneficiary',
        3 => 'app\\domain\\registry\\models\\mda',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/Household.php' => 
    array (
      0 => 'c0206c14eedb9c7e08b70f8d0a52ddc9cfd94cfd0d9990031e1c402a6920aeb0',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\household',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\booted',
        2 => 'app\\domain\\registry\\models\\ownermda',
        3 => 'app\\domain\\registry\\models\\head',
        4 => 'app\\domain\\registry\\models\\memberships',
        5 => 'app\\domain\\registry\\models\\currentmemberships',
        6 => 'app\\domain\\registry\\models\\newfactory',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/HouseholdMembership.php' => 
    array (
      0 => 'd16869f3e73b469a4e1aeb88440bda81436f5a244cc987d5cbcb4618797c08ce',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\householdmembership',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\booted',
        2 => 'app\\domain\\registry\\models\\household',
        3 => 'app\\domain\\registry\\models\\beneficiary',
        4 => 'app\\domain\\registry\\models\\newfactory',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/ImportBatch.php' => 
    array (
      0 => '7fb973843b234cc03f56e6d1d34e164d797cc58a9046e2d3b0abaa7de2cd2823',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\importbatch',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\auditexcluded',
        2 => 'app\\domain\\registry\\models\\ownermda',
        3 => 'app\\domain\\registry\\models\\activity',
        4 => 'app\\domain\\registry\\models\\uploadedby',
        5 => 'app\\domain\\registry\\models\\rows',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/ImportRow.php' => 
    array (
      0 => 'aaad26e56a07ae6f82c0511c68061c99209b4a97158956b8a49a856ec10bd58e',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\importrow',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\batch',
        2 => 'app\\domain\\registry\\models\\beneficiary',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/OwnershipTransferRequest.php' => 
    array (
      0 => 'c75b4e5cd68c75b2e66c249f615968d32c2062b75b3696076e9b55f0540da63b',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\ownershiptransferrequest',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\beneficiary',
        2 => 'app\\domain\\registry\\models\\frommda',
        3 => 'app\\domain\\registry\\models\\tomda',
        4 => 'app\\domain\\registry\\models\\requestedby',
        5 => 'app\\domain\\registry\\models\\newfactory',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Models/ServiceRequest.php' => 
    array (
      0 => 'd8c397c11d9b8920bf128049a3dfa7e019ffd5fc78894fbdbf5bec0076d1b224',
      1 => 
      array (
        0 => 'app\\domain\\registry\\models\\servicerequest',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\models\\casts',
        1 => 'app\\domain\\registry\\models\\auditexcluded',
        2 => 'app\\domain\\registry\\models\\beneficiary',
        3 => 'app\\domain\\registry\\models\\frommda',
        4 => 'app\\domain\\registry\\models\\tomda',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Policies/BeneficiaryDocumentPolicy.php' => 
    array (
      0 => '9e23efa04bb22ffd3f3220edc27a1a0028a3b74d9430279ba7a6cedc6ac089ea',
      1 => 
      array (
        0 => 'app\\domain\\registry\\policies\\beneficiarydocumentpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\policies\\owns',
        1 => 'app\\domain\\registry\\policies\\view',
        2 => 'app\\domain\\registry\\policies\\delete',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Policies/BeneficiaryPolicy.php' => 
    array (
      0 => '05724e706f5bbb2fb9a397cd99990f83c5cfe362b8bc4d552a051c1d5e107ab2',
      1 => 
      array (
        0 => 'app\\domain\\registry\\policies\\beneficiarypolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\policies\\owns',
        1 => 'app\\domain\\registry\\policies\\viewany',
        2 => 'app\\domain\\registry\\policies\\view',
        3 => 'app\\domain\\registry\\policies\\hasservicegrant',
        4 => 'app\\domain\\registry\\policies\\create',
        5 => 'app\\domain\\registry\\policies\\update',
        6 => 'app\\domain\\registry\\policies\\delete',
        7 => 'app\\domain\\registry\\policies\\lookup',
        8 => 'app\\domain\\registry\\policies\\requesttransfer',
        9 => 'app\\domain\\registry\\policies\\decidetransfer',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Policies/HouseholdPolicy.php' => 
    array (
      0 => '410dd832e66f56c44fde79f1961801987574f5c0efa16a3b9de67eed45b10071',
      1 => 
      array (
        0 => 'app\\domain\\registry\\policies\\householdpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\policies\\owns',
        1 => 'app\\domain\\registry\\policies\\viewany',
        2 => 'app\\domain\\registry\\policies\\view',
        3 => 'app\\domain\\registry\\policies\\create',
        4 => 'app\\domain\\registry\\policies\\update',
        5 => 'app\\domain\\registry\\policies\\delete',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Policies/ImportBatchPolicy.php' => 
    array (
      0 => 'decd4c37aec185115f989b7700ec37dfa6ba3c9083c9579191d7da8438b86e0f',
      1 => 
      array (
        0 => 'app\\domain\\registry\\policies\\importbatchpolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\policies\\owns',
        1 => 'app\\domain\\registry\\policies\\viewany',
        2 => 'app\\domain\\registry\\policies\\view',
        3 => 'app\\domain\\registry\\policies\\create',
        4 => 'app\\domain\\registry\\policies\\commit',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Policies/OwnerMdaPolicy.php' => 
    array (
      0 => 'c46f38c984345bf6207d5a9c5d742f3c7e54993f7b6128daa1a8cb05e940433a',
      1 => 
      array (
        0 => 'app\\domain\\registry\\policies\\ownermdapolicy',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\policies\\view',
        1 => 'app\\domain\\registry\\policies\\decide',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/RegistryServiceProvider.php' => 
    array (
      0 => 'a6093c4b7fcf6308933ce49e15a4047636c0c5dda3a5590c2597f57657da88ea',
      1 => 
      array (
        0 => 'app\\domain\\registry\\registryserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\register',
        1 => 'app\\domain\\registry\\boot',
        2 => 'app\\domain\\registry\\registerpermissions',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/BatchDuplicateScreener.php' => 
    array (
      0 => 'ed23a96b20f70997f8a4541bd5ba077519843c5e745af50684c7bbd12f472e14',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\batchduplicatescreener',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\screen',
        2 => 'app\\domain\\registry\\services\\batchrecords',
        3 => 'app\\domain\\registry\\services\\remember',
        4 => 'app\\domain\\registry\\services\\blockkeys',
        5 => 'app\\domain\\registry\\services\\entry',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/BeneficiaryLookupService.php' => 
    array (
      0 => 'dedb58080e2291681f1ecbe4f2a3af76fe40c49f11d7eb9311b01355bf08a80b',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\beneficiarylookupservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\findbyidentity',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/BeneficiaryRegistrar.php' => 
    array (
      0 => '116b94b44493c69cbb5dd1a91c9cf0b47fb7fb9793ce4a015b1b1c15ec350fe9',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\beneficiaryregistrar',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\register',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/CandidateGatherer.php' => 
    array (
      0 => '632b8b5456fc44649577973373b5e46c005220dd95c0973d0c05e39f653af96c',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\candidategatherer',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\gather',
        2 => 'app\\domain\\registry\\services\\buildquery',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/DeterministicDuplicateFinder.php' => 
    array (
      0 => '9655e23a08143ca53687fc5e0b187c6772504829a171661de8dff1b10ec2f79e',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\deterministicduplicatefinder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\find',
        2 => 'app\\domain\\registry\\services\\buildquery',
        3 => 'app\\domain\\registry\\services\\torecord',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/FuzzyDuplicateFinder.php' => 
    array (
      0 => 'eda21affa7c5836adb860d4995a7a09f251d6fad3b6618578d9c086992789d77',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\fuzzyduplicatefinder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\find',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/HouseholdIngestionService.php' => 
    array (
      0 => '3d03b7f2989c95688c8c17571d6bd145622e5c4efb7d7c557ce770e4ee75e143',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\householdingestionservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\attach',
        2 => 'app\\domain\\registry\\services\\resolverole',
        3 => 'app\\domain\\registry\\services\\ensuremembership',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/HouseholdMembershipService.php' => 
    array (
      0 => 'd2c9965d76df8330a34adb742b6f0416d1d4ee396b7b8bfaa8b3bbb78cca6364',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\householdmembershipservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\add',
        2 => 'app\\domain\\registry\\services\\move',
        3 => 'app\\domain\\registry\\services\\remove',
        4 => 'app\\domain\\registry\\services\\designatehead',
        5 => 'app\\domain\\registry\\services\\openmembership',
        6 => 'app\\domain\\registry\\services\\assertnoopenmembership',
        7 => 'app\\domain\\registry\\services\\clearheadifmember',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/NullBeneficiaryRouter.php' => 
    array (
      0 => 'b8880cc7b6c8d8ff8216e903ff0ca2d0acfb6bc21e8e2c1fcdad97ee4872a157',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\nullbeneficiaryrouter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\suggestmdafor',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/NullDuplicateChecker.php' => 
    array (
      0 => 'e8376873eaf21f53c01f08a4a512d7dcaf0ca0abd7a4b8263768504cb742de8a',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\nullduplicatechecker',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\check',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/OwnershipTransferService.php' => 
    array (
      0 => '47722a58463a441d01c3af242357529a7f2ead2b0ec769f265daaf027807314d',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\ownershiptransferservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\request',
        2 => 'app\\domain\\registry\\services\\approve',
        3 => 'app\\domain\\registry\\services\\reject',
        4 => 'app\\domain\\registry\\services\\assertpending',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Services/ServiceRequestService.php' => 
    array (
      0 => 'ba821c3a0ccae645b246d1bce80c7ee5e0df3c3275513cdc68ac3718e9298902',
      1 => 
      array (
        0 => 'app\\domain\\registry\\services\\servicerequestservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\services\\__construct',
        1 => 'app\\domain\\registry\\services\\request',
        2 => 'app\\domain\\registry\\services\\accept',
        3 => 'app\\domain\\registry\\services\\decline',
        4 => 'app\\domain\\registry\\services\\hasactivegrant',
        5 => 'app\\domain\\registry\\services\\decide',
        6 => 'app\\domain\\registry\\services\\opengrant',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Registry/Support/BeneficiaryRules.php' => 
    array (
      0 => 'e8b474377cefbfb1f1c2f1b854667e5cbdbf891eedf01a3498998eb8ad381254',
      1 => 
      array (
        0 => 'app\\domain\\registry\\support\\beneficiaryrules',
      ),
      2 => 
      array (
        0 => 'app\\domain\\registry\\support\\isidentityfield',
        1 => 'app\\domain\\registry\\support\\forregistration',
        2 => 'app\\domain\\registry\\support\\messages',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Events/ReportReady.php' => 
    array (
      0 => '902e71f6dd7ff94c17fe80cfcb9bf59934f64595f3830594f78847ae558e78e5',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\events\\reportready',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\events\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/Contracts/ReportExporter.php' => 
    array (
      0 => 'b1b8f5a295a838b678dee7c9ee275a449ff3e046b0431196cd2cea3d030b5214',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\contracts\\reportexporter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\contracts\\format',
        1 => 'app\\domain\\reporting\\export\\contracts\\render',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/CsvExporter.php' => 
    array (
      0 => '8a6fc7d1bf9bfe842ec61d10cf53a5cd9ac505102f060c79647020cfde2efb3e',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\csvexporter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\format',
        1 => 'app\\domain\\reporting\\export\\render',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/ExcelExporter.php' => 
    array (
      0 => 'ac43c4cef3a1a42090281b745f20858072d110a72fb59a2d74db02e5557889a3',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\excelexporter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\format',
        1 => 'app\\domain\\reporting\\export\\render',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/PdfExporter.php' => 
    array (
      0 => 'c3caa78c1ec591b9a5490ea85ed376cc467ac8684e07f3cb99bc47b049058e63',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\pdfexporter',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\format',
        1 => 'app\\domain\\reporting\\export\\render',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/ReportColumn.php' => 
    array (
      0 => '29f2d7f91d9b29790971a916a941a84d6effe9f29ad029c814d4cb2dc4befd81',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\reportcolumn',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/ReportData.php' => 
    array (
      0 => 'd90275fcd0a36c7f30f1a2a0dafeddac140a7a3aac789a041d06752bc1fb2535',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\reportdata',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\__construct',
        1 => 'app\\domain\\reporting\\export\\rowcount',
        2 => 'app\\domain\\reporting\\export\\cell',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/ReportExporterRegistry.php' => 
    array (
      0 => '4c84e7dddb77e3f7cba758ea53fcb854604a141a8c5991811935f3380324cea8',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\reportexporterregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\__construct',
        1 => 'app\\domain\\reporting\\export\\for',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/ReportFormat.php' => 
    array (
      0 => '24c43f7e40bcd649458b91ba61f4fe28daeaf626b8ab4ef88863c888a6fc7fa6',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\reportformat',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\extension',
        1 => 'app\\domain\\reporting\\export\\mimetype',
        2 => 'app\\domain\\reporting\\export\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Export/SensitiveMasker.php' => 
    array (
      0 => 'eedeac00234b5f08d8aa71529d21b846cf438b0c90270c4937864d293a919d85',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\export\\sensitivemasker',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\export\\mask',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Jobs/GenerateReport.php' => 
    array (
      0 => 'fb96aed2b9204e84145cc68b574e231cbc500974290ead5739621efc39684957',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\generatereport',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\__construct',
        1 => 'app\\domain\\reporting\\jobs\\handle',
        2 => 'app\\domain\\reporting\\jobs\\requester',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Jobs/RefreshDashboardSnapshots.php' => 
    array (
      0 => '680f96566e406297a6d1c7e73dd4a9c9783fa95fc35a26a790bde03c29912537',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\refreshdashboardsnapshots',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Models/DashboardSnapshot.php' => 
    array (
      0 => '55dbd4dda223505288adfe32cbb8c8e78dddcee87b559575d2955b5f6084bb20',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\models\\dashboardsnapshot',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\models\\casts',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Models/ReportRun.php' => 
    array (
      0 => '2793228d889e663871b3371890daa77fd4a781d5da63c9bd78a4d0573243aeae',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\models\\reportrun',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\models\\casts',
        1 => 'app\\domain\\reporting\\models\\isaccessibleby',
        2 => 'app\\domain\\reporting\\models\\adhocdefinition',
        3 => 'app\\domain\\reporting\\models\\toscope',
        4 => 'app\\domain\\reporting\\models\\isready',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/ReportingServiceProvider.php' => 
    array (
      0 => '4984868596907509f65a7229d4b7abe7d46bff595cf0e48f93f59b93b5b9e7f3',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reportingserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Reports/ReportBuilder.php' => 
    array (
      0 => 'ec7249a25e50372348c3e99a21430e10de43688f544430d6cc9731c3c06daa58',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\reportbuilder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\__construct',
        1 => 'app\\domain\\reporting\\reports\\build',
        2 => 'app\\domain\\reporting\\reports\\beneficiariesbylga',
        3 => 'app\\domain\\reporting\\reports\\benefitsbyprogramme',
        4 => 'app\\domain\\reporting\\reports\\benefitsbymda',
        5 => 'app\\domain\\reporting\\reports\\benefitsbylga',
        6 => 'app\\domain\\reporting\\reports\\benefitgroupreport',
        7 => 'app\\domain\\reporting\\reports\\budgetutilization',
        8 => 'app\\domain\\reporting\\reports\\referralcompletion',
        9 => 'app\\domain\\reporting\\reports\\grievancesla',
        10 => 'app\\domain\\reporting\\reports\\data',
        11 => 'app\\domain\\reporting\\reports\\naira',
        12 => 'app\\domain\\reporting\\reports\\title',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Reports/ReportCatalogue.php' => 
    array (
      0 => '49907dee686c880b95dffa448ccbf81880004375f40220c8d666d2feaef4e33d',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\reportcatalogue',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\has',
        1 => 'app\\domain\\reporting\\reports\\label',
        2 => 'app\\domain\\reporting\\reports\\iscoordination',
        3 => 'app\\domain\\reporting\\reports\\availableto',
        4 => 'app\\domain\\reporting\\reports\\availablefor',
        5 => 'app\\domain\\reporting\\reports\\find',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/DashboardMetricsService.php' => 
    array (
      0 => 'f503d4ee20dc6f968f9d2f0871e8a52d8d01560b93111fd17a537d06372edc54',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\dashboardmetricsservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\__construct',
        1 => 'app\\domain\\reporting\\services\\compute',
        2 => 'app\\domain\\reporting\\services\\registry',
        3 => 'app\\domain\\reporting\\services\\programmes',
        4 => 'app\\domain\\reporting\\services\\duplicates',
        5 => 'app\\domain\\reporting\\services\\benefits',
        6 => 'app\\domain\\reporting\\services\\referrals',
        7 => 'app\\domain\\reporting\\services\\grievances',
        8 => 'app\\domain\\reporting\\services\\coverage',
        9 => 'app\\domain\\reporting\\services\\beneficiarybase',
        10 => 'app\\domain\\reporting\\services\\householdbase',
        11 => 'app\\domain\\reporting\\services\\countby',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/DashboardScopeResolver.php' => 
    array (
      0 => '99a54a3ab2d3153997e51052a21bdb40dc69d8ddb55c1d55932c71388f674407',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\dashboardscoperesolver',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\foruser',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/DashboardService.php' => 
    array (
      0 => '4b59768434b511fddcfe7e220f08951de58627628b4672d1f619505d1e13d5c9',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\dashboardservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\__construct',
        1 => 'app\\domain\\reporting\\services\\foruser',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/DashboardSnapshotService.php' => 
    array (
      0 => '716b2e27bdec0a372b59820091162ef722bf4d94e548b5597a9687340825cbb1',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\dashboardsnapshotservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\__construct',
        1 => 'app\\domain\\reporting\\services\\refreshall',
        2 => 'app\\domain\\reporting\\services\\refreshfor',
        3 => 'app\\domain\\reporting\\services\\read',
        4 => 'app\\domain\\reporting\\services\\partnerusers',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/ReportService.php' => 
    array (
      0 => 'bbe6f948c465a4ca4bb66fe7fe99050b6d7033caee9c32466ff5c90892e78a14',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\reportservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\__construct',
        1 => 'app\\domain\\reporting\\services\\request',
        2 => 'app\\domain\\reporting\\services\\requestadhoc',
        3 => 'app\\domain\\reporting\\services\\runfromschedule',
        4 => 'app\\domain\\reporting\\services\\schedulereportattributes',
        5 => 'app\\domain\\reporting\\services\\createrun',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Support/DashboardScope.php' => 
    array (
      0 => '28013976808af5b5a4905b12cb7e57030a19ab3c2e341fdbef2b92e0c111904d',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\support\\dashboardscope',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\support\\__construct',
        1 => 'app\\domain\\reporting\\support\\statewide',
        2 => 'app\\domain\\reporting\\support\\mda',
        3 => 'app\\domain\\reporting\\support\\partner',
        4 => 'app\\domain\\reporting\\support\\rehydrate',
        5 => 'app\\domain\\reporting\\support\\isstatewide',
        6 => 'app\\domain\\reporting\\support\\ispartner',
        7 => 'app\\domain\\reporting\\support\\includescoordinationmetrics',
        8 => 'app\\domain\\reporting\\support\\covers',
        9 => 'app\\domain\\reporting\\support\\key',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Access/AccessController.php' => 
    array (
      0 => '50e08fd003e95002e51e920225053678139ff7ff176e81700ecf1cc5d9165613',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\accesscontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\permissions',
        1 => 'app\\http\\controllers\\api\\v1\\access\\roles',
        2 => 'app\\http\\controllers\\api\\v1\\access\\matrix',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Access/MdaAccessGrantController.php' => 
    array (
      0 => 'f9c76533b24c0d12a84f8ce3bdb1a696b4d9357acfe4f888621b2b86bf2ab766',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\mdaaccessgrantcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\index',
        1 => 'app\\http\\controllers\\api\\v1\\access\\store',
        2 => 'app\\http\\controllers\\api\\v1\\access\\destroy',
        3 => 'app\\http\\controllers\\api\\v1\\access\\present',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Access/MdaController.php' => 
    array (
      0 => '7ec7474c87905b45c71323a128479fe5736073383d6b7137424282be6b83bb9a',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\mdacontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\index',
        1 => 'app\\http\\controllers\\api\\v1\\access\\store',
        2 => 'app\\http\\controllers\\api\\v1\\access\\show',
        3 => 'app\\http\\controllers\\api\\v1\\access\\update',
        4 => 'app\\http\\controllers\\api\\v1\\access\\deactivate',
        5 => 'app\\http\\controllers\\api\\v1\\access\\activate',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Access/UserController.php' => 
    array (
      0 => '43d44f96ff1713f4d921ab04efad2612e72dbe442c58709fa54c48e39c9726a6',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\usercontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\access\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\access\\index',
        2 => 'app\\http\\controllers\\api\\v1\\access\\store',
        3 => 'app\\http\\controllers\\api\\v1\\access\\show',
        4 => 'app\\http\\controllers\\api\\v1\\access\\update',
        5 => 'app\\http\\controllers\\api\\v1\\access\\suspend',
        6 => 'app\\http\\controllers\\api\\v1\\access\\deactivate',
        7 => 'app\\http\\controllers\\api\\v1\\access\\activate',
        8 => 'app\\http\\controllers\\api\\v1\\access\\forcepasswordreset',
        9 => 'app\\http\\controllers\\api\\v1\\access\\resetmfa',
        10 => 'app\\http\\controllers\\api\\v1\\access\\changestatus',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/AuthController.php' => 
    array (
      0 => '32b23f3f6e7c8b13457e68a81c3aad585b6e819d3bae83cc6921126f77ddfb18',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\authcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\login',
        2 => 'app\\http\\controllers\\api\\v1\\me',
        3 => 'app\\http\\controllers\\api\\v1\\logout',
        4 => 'app\\http\\controllers\\api\\v1\\changepassword',
        5 => 'app\\http\\controllers\\api\\v1\\fulltokenresponse',
        6 => 'app\\http\\controllers\\api\\v1\\credentialsarevalid',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Benefit/BenefitController.php' => 
    array (
      0 => 'c544b46befd919bb65b00612761aea798505d1a7e040e41638f8a5da3197f38d',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\benefitcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\benefit\\index',
        2 => 'app\\http\\controllers\\api\\v1\\benefit\\aggregate',
        3 => 'app\\http\\controllers\\api\\v1\\benefit\\store',
        4 => 'app\\http\\controllers\\api\\v1\\benefit\\show',
        5 => 'app\\http\\controllers\\api\\v1\\benefit\\verify',
        6 => 'app\\http\\controllers\\api\\v1\\benefit\\ledger',
        7 => 'app\\http\\controllers\\api\\v1\\benefit\\canviewledger',
        8 => 'app\\http\\controllers\\api\\v1\\benefit\\activitymismatch',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Benefit/BenefitFlagController.php' => 
    array (
      0 => 'e6e224288cfd68241756554f0c5a18ceb755abdb6acee25c46c494c7cac57f4d',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\benefitflagcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\index',
        1 => 'app\\http\\controllers\\api\\v1\\benefit\\review',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Benefit/BenefitImportController.php' => 
    array (
      0 => 'bd077b03e5a66c88830d07269fdfebd9c7797a9f8ac351f8f72f6493c80fe96f',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\benefitimportcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\index',
        1 => 'app\\http\\controllers\\api\\v1\\benefit\\store',
        2 => 'app\\http\\controllers\\api\\v1\\benefit\\show',
        3 => 'app\\http\\controllers\\api\\v1\\benefit\\confirm',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Benefit/DoubleDippingRuleController.php' => 
    array (
      0 => '359000777264b5d2490ef218e1616271d253634a8e0789e8577b68a64f642626',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\doubledippingrulecontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\benefit\\index',
        1 => 'app\\http\\controllers\\api\\v1\\benefit\\store',
        2 => 'app\\http\\controllers\\api\\v1\\benefit\\update',
        3 => 'app\\http\\controllers\\api\\v1\\benefit\\destroy',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Grievance/GrievanceController.php' => 
    array (
      0 => '81801990c4372d2f62a1f3fd0384091a232dfaa6059aa2dff5662a4dbc6cd808',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\grievance\\grievancecontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\grievance\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\grievance\\index',
        2 => 'app\\http\\controllers\\api\\v1\\grievance\\show',
        3 => 'app\\http\\controllers\\api\\v1\\grievance\\store',
        4 => 'app\\http\\controllers\\api\\v1\\grievance\\assign',
        5 => 'app\\http\\controllers\\api\\v1\\grievance\\start',
        6 => 'app\\http\\controllers\\api\\v1\\grievance\\resolve',
        7 => 'app\\http\\controllers\\api\\v1\\grievance\\close',
        8 => 'app\\http\\controllers\\api\\v1\\grievance\\act',
        9 => 'app\\http\\controllers\\api\\v1\\grievance\\run',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Grievance/GrievanceSlaPolicyController.php' => 
    array (
      0 => '9f75197d10383305661ad2b2313cbf4cbe3d5ae78f281e5f8dd9fee38c2aaa5f',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\grievance\\grievanceslapolicycontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\grievance\\index',
        1 => 'app\\http\\controllers\\api\\v1\\grievance\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/HealthController.php' => 
    array (
      0 => '26d690b2b0b2ae7d192487f87e9c6bda8ffe434a6aac9ada2ebf73cfa171000b',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\healthcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\show',
        1 => 'app\\http\\controllers\\api\\v1\\checkdatabase',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Matching/MatchingConfigController.php' => 
    array (
      0 => '8d07750299805cfc568c11baae20520ccfd1307ec17c02fbbe39b9f362e91c87',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\matching\\matchingconfigcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\matching\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\matching\\show',
        2 => 'app\\http\\controllers\\api\\v1\\matching\\update',
        3 => 'app\\http\\controllers\\api\\v1\\matching\\versions',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/MfaController.php' => 
    array (
      0 => '50ff7f80419d750c29cbe71aecf3e8b00903ede510b7309895254a182f87b427',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\mfacontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\enroll',
        2 => 'app\\http\\controllers\\api\\v1\\verify',
        3 => 'app\\http\\controllers\\api\\v1\\disable',
        4 => 'app\\http\\controllers\\api\\v1\\challenge',
        5 => 'app\\http\\controllers\\api\\v1\\verifyanycode',
        6 => 'app\\http\\controllers\\api\\v1\\fulltokenresponse',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Notification/NotificationController.php' => 
    array (
      0 => '4ec57035c07af373547ff8cfca3c6371762a9fd5949de27e08d5228fd9c09e87',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\notification\\notificationcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\notification\\index',
        1 => 'app\\http\\controllers\\api\\v1\\notification\\unreadcount',
        2 => 'app\\http\\controllers\\api\\v1\\notification\\markread',
        3 => 'app\\http\\controllers\\api\\v1\\notification\\markallread',
        4 => 'app\\http\\controllers\\api\\v1\\notification\\preferences',
        5 => 'app\\http\\controllers\\api\\v1\\notification\\updatepreferences',
        6 => 'app\\http\\controllers\\api\\v1\\notification\\mine',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Programme/ActivityController.php' => 
    array (
      0 => 'ce6dca3cb0429fbab50ff51707a055b7a4befa396c3ff0263e1e417759e332dc',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\activitycontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\index',
        1 => 'app\\http\\controllers\\api\\v1\\programme\\store',
        2 => 'app\\http\\controllers\\api\\v1\\programme\\show',
        3 => 'app\\http\\controllers\\api\\v1\\programme\\update',
        4 => 'app\\http\\controllers\\api\\v1\\programme\\budget',
        5 => 'app\\http\\controllers\\api\\v1\\programme\\archive',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Programme/EnrollmentController.php' => 
    array (
      0 => 'c067c167ebe9136f99b7340ad67aa47768f23167536a18cf72b74842eaf31c43',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\enrollmentcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\programme\\index',
        2 => 'app\\http\\controllers\\api\\v1\\programme\\store',
        3 => 'app\\http\\controllers\\api\\v1\\programme\\bulk',
        4 => 'app\\http\\controllers\\api\\v1\\programme\\update',
        5 => 'app\\http\\controllers\\api\\v1\\programme\\programme',
        6 => 'app\\http\\controllers\\api\\v1\\programme\\typemismatch',
        7 => 'app\\http\\controllers\\api\\v1\\programme\\activitymismatch',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Programme/ProgrammeController.php' => 
    array (
      0 => '7b25702b7c1c4ef724b7635e912114d299dd40337a9de520d3e8fb40688c0098',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\programmecontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\programme\\index',
        1 => 'app\\http\\controllers\\api\\v1\\programme\\store',
        2 => 'app\\http\\controllers\\api\\v1\\programme\\show',
        3 => 'app\\http\\controllers\\api\\v1\\programme\\update',
        4 => 'app\\http\\controllers\\api\\v1\\programme\\budget',
        5 => 'app\\http\\controllers\\api\\v1\\programme\\archive',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Referral/ReferralController.php' => 
    array (
      0 => 'fb08eae59c33341b099563c6e4c1fd14be5a12e4e5ed8580f855b299ec7df255',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\referral\\referralcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\referral\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\referral\\index',
        2 => 'app\\http\\controllers\\api\\v1\\referral\\show',
        3 => 'app\\http\\controllers\\api\\v1\\referral\\store',
        4 => 'app\\http\\controllers\\api\\v1\\referral\\accept',
        5 => 'app\\http\\controllers\\api\\v1\\referral\\reject',
        6 => 'app\\http\\controllers\\api\\v1\\referral\\requestinfo',
        7 => 'app\\http\\controllers\\api\\v1\\referral\\start',
        8 => 'app\\http\\controllers\\api\\v1\\referral\\complete',
        9 => 'app\\http\\controllers\\api\\v1\\referral\\close',
        10 => 'app\\http\\controllers\\api\\v1\\referral\\respondinfo',
        11 => 'app\\http\\controllers\\api\\v1\\referral\\receiving',
        12 => 'app\\http\\controllers\\api\\v1\\referral\\withledger',
        13 => 'app\\http\\controllers\\api\\v1\\referral\\run',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Referral/ReferralSlaPolicyController.php' => 
    array (
      0 => '4396a0787d6e22fbbcc432af9114f919de2165c9eeca0fa503808a7055129294',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\referral\\referralslapolicycontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\referral\\index',
        1 => 'app\\http\\controllers\\api\\v1\\referral\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryController.php' => 
    array (
      0 => '62cd49791682ee7f6cd8a0647bdb39a80e0be2d87c1c860d61945f7e5d25dff9',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\beneficiarycontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\index',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\show',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\update',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\destroy',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\lookup',
        5 => 'app\\http\\controllers\\api\\v1\\registry\\search',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryDocumentController.php' => 
    array (
      0 => '5bab416b085de642311defe5f1f603be28b27d4deb4de2a61d3946ba48a7a446',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\beneficiarydocumentcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\index',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\store',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\download',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\destroy',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryIntakeController.php' => 
    array (
      0 => 'dc259a5830642ebd8dfa8139aac5490fe3ab35ce31fd5b66ea8543758252f807',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\beneficiaryintakecontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\store',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/BeneficiaryRoutingController.php' => 
    array (
      0 => '4793751e2ca0383966a40c582bfaffed8df3862c21e24346cbccc3ed072391b3',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\beneficiaryroutingcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\suggestions',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\assign',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/HouseholdController.php' => 
    array (
      0 => '2ae1d606695a79016897c284e915fe115e03184884951ed9cb0c1f3990af6a91',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\householdcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\index',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\show',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\update',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\destroy',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\designatehead',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/HouseholdMemberController.php' => 
    array (
      0 => '7c459a526c7eefadfd7764607cbae8768694fcd9fc7bb5304a1de8b6922b6b76',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\householdmembercontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\store',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\move',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\destroy',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\authorizedhousehold',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/ImportBatchController.php' => 
    array (
      0 => 'ebb7358182fe121666e4733ee227d0edeea60ba99e2876cc33bf2a97fd81a0ca',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\importbatchcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\index',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\store',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\show',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\attachmatchreveals',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\registryreveal',
        5 => 'app\\http\\controllers\\api\\v1\\registry\\batchreveal',
        6 => 'app\\http\\controllers\\api\\v1\\registry\\resolverow',
        7 => 'app\\http\\controllers\\api\\v1\\registry\\confirm',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/OwnershipTransferController.php' => 
    array (
      0 => '20e098b5ca731a7bf7b20b3e3a1fa2cd2c5b04dd8d58f1e6b5b07a687ca479e9',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\ownershiptransfercontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\store',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\approve',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\reject',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\authorizedecision',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Registry/ServiceRequestController.php' => 
    array (
      0 => '2f1cee5ef853e3cc4b1b5edbd7c016749feebd065656d85c3418e4f767eb4316',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\servicerequestcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\registry\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\registry\\inbox',
        2 => 'app\\http\\controllers\\api\\v1\\registry\\outbox',
        3 => 'app\\http\\controllers\\api\\v1\\registry\\list',
        4 => 'app\\http\\controllers\\api\\v1\\registry\\store',
        5 => 'app\\http\\controllers\\api\\v1\\registry\\accept',
        6 => 'app\\http\\controllers\\api\\v1\\registry\\decline',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/DashboardController.php' => 
    array (
      0 => '7cbf8fc9c6c8570d32dc82e27ea127d9c849adc2894818e5861e8b5cb914b917',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\dashboardcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\index',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/ReportController.php' => 
    array (
      0 => 'ea2be210623b1dcafdf5cecf99e2df4c8c5dad523eef9b4f108a1574555845c2',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\reportcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\catalogue',
        2 => 'app\\http\\controllers\\api\\v1\\reporting\\index',
        3 => 'app\\http\\controllers\\api\\v1\\reporting\\store',
        4 => 'app\\http\\controllers\\api\\v1\\reporting\\show',
        5 => 'app\\http\\controllers\\api\\v1\\reporting\\download',
        6 => 'app\\http\\controllers\\api\\v1\\reporting\\mine',
        7 => 'app\\http\\controllers\\api\\v1\\reporting\\accessible',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Concerns/AuthResponses.php' => 
    array (
      0 => '7667385dda4e1e79049e0e5548d395e0bccb4835c3d5bb8f84700606f42c2237',
      1 => 
      array (
        0 => 'app\\http\\controllers\\concerns\\authresponses',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\concerns\\invalidcredentials',
        1 => 'app\\http\\controllers\\concerns\\accountlocked',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Controller.php' => 
    array (
      0 => 'd90b757ef4dfdb1146846db9d6d531024b5b2c0275f0832b9dbc5af1b4ae5091',
      1 => 
      array (
        0 => 'app\\http\\controllers\\controller',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Middleware/AssignCorrelationId.php' => 
    array (
      0 => 'd0f79e54d7c6e370dc251e9c69ec8d2128bedf2439c761f8d0d7248b0ffc916c',
      1 => 
      array (
        0 => 'app\\http\\middleware\\assigncorrelationid',
      ),
      2 => 
      array (
        0 => 'app\\http\\middleware\\handle',
        1 => 'app\\http\\middleware\\normalize',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Middleware/CheckPermission.php' => 
    array (
      0 => 'ee846aa86881be0e8842ebc9f0a6e8599049f9391cc35cf36b5f7198ffa53848',
      1 => 
      array (
        0 => 'app\\http\\middleware\\checkpermission',
      ),
      2 => 
      array (
        0 => 'app\\http\\middleware\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Middleware/EnforceIdleTimeout.php' => 
    array (
      0 => 'a3686556c842fd1c0ff83d7d2ad5c257d8acfb764938ef98a3e62b7d028ed5c7',
      1 => 
      array (
        0 => 'app\\http\\middleware\\enforceidletimeout',
      ),
      2 => 
      array (
        0 => 'app\\http\\middleware\\handle',
        1 => 'app\\http\\middleware\\isidle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Middleware/SecurityHeaders.php' => 
    array (
      0 => '8a55a32aeca228ea44bfc2f1d4fac4f5c2fef9316d3c02f4cea22db191091f77',
      1 => 
      array (
        0 => 'app\\http\\middleware\\securityheaders',
      ),
      2 => 
      array (
        0 => 'app\\http\\middleware\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/Concerns/ValidatesUserAssignment.php' => 
    array (
      0 => '40b10566425852eee9be53a458d6692834db12e86769be4b53d1afcf1be12d82',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\concerns\\validatesuserassignment',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\concerns\\accessiblemdarule',
        1 => 'app\\http\\requests\\access\\concerns\\assignablerolerule',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/StoreMdaAccessGrantRequest.php' => 
    array (
      0 => 'e6bc2252404ad6131f57dad8416b11eaef9111709831eee428f916a906014314',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\storemdaaccessgrantrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\authorize',
        1 => 'app\\http\\requests\\access\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/StoreMdaRequest.php' => 
    array (
      0 => '03ed5c60ecc16b84666a5a7418d6cade5a867388d7ed2594e02589b485dfe3c1',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\storemdarequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\authorize',
        1 => 'app\\http\\requests\\access\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/StoreUserRequest.php' => 
    array (
      0 => 'f349e6c1678140ef35ae42e36546aa277a72c7a116a68d23a63dfec63f1faf86',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\storeuserrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\authorize',
        1 => 'app\\http\\requests\\access\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/UpdateMdaRequest.php' => 
    array (
      0 => '52c9213c75f4ac6fd7fd8ec2a344f1db183482f5f213b98c4a28a0e1d00c0902',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\updatemdarequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\authorize',
        1 => 'app\\http\\requests\\access\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Access/UpdateUserRequest.php' => 
    array (
      0 => '4daa319b420138dc3ef25da6ae21217100885402c8ed4b1551356861bfd3b166',
      1 => 
      array (
        0 => 'app\\http\\requests\\access\\updateuserrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\access\\authorize',
        1 => 'app\\http\\requests\\access\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Auth/ChangePasswordRequest.php' => 
    array (
      0 => '8911eacd2e7f58fb84737e5289c3b08879ce73d6bbe9165dc0fe08e41088ec23',
      1 => 
      array (
        0 => 'app\\http\\requests\\auth\\changepasswordrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\auth\\authorize',
        1 => 'app\\http\\requests\\auth\\rules',
        2 => 'app\\http\\requests\\auth\\messages',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Auth/LoginRequest.php' => 
    array (
      0 => '7f58be90096c7569dbbeff52230a5257e88981cb85acaaee8816fba7a180ecad',
      1 => 
      array (
        0 => 'app\\http\\requests\\auth\\loginrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\auth\\authorize',
        1 => 'app\\http\\requests\\auth\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Auth/MfaCodeRequest.php' => 
    array (
      0 => '7edac0c330cecc44206e11611bd4f02b097ba11267b302eac37f373686073ae4',
      1 => 
      array (
        0 => 'app\\http\\requests\\auth\\mfacoderequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\auth\\authorize',
        1 => 'app\\http\\requests\\auth\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/AggregateBenefitsRequest.php' => 
    array (
      0 => '786d3d1fa451637ebf097f2347ae932676d4c01f399c5d143dc923b46ccc5227',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\aggregatebenefitsrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/RecordBenefitRequest.php' => 
    array (
      0 => '0edc35a02a258ff94b533d768a4b302bfb6c45d8759bc7c034b2f1e4157c3a04',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\recordbenefitrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/ReviewBenefitFlagRequest.php' => 
    array (
      0 => '76f1141c8dbde6018a359c7d955eb65719d30e96637e0c9753fa6a960a06b8d6',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\reviewbenefitflagrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/StoreDoubleDippingRuleRequest.php' => 
    array (
      0 => '41b2b01f79bdcf86f9ed0f80f5953fecf3929efd8d993b46df0fb61f328cfda0',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\storedoubledippingrulerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/UploadBenefitImportRequest.php' => 
    array (
      0 => 'b3471ffc12227b1197cebff60c27d730338974328505cd3306ccd31858749642',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\uploadbenefitimportrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Benefit/VerifyBenefitRequest.php' => 
    array (
      0 => 'b5526966e6ce6f64e9cc7d3607fdd10bd03a6f59dd4997a8992d3c369d370236',
      1 => 
      array (
        0 => 'app\\http\\requests\\benefit\\verifybenefitrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\benefit\\authorize',
        1 => 'app\\http\\requests\\benefit\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Grievance/AssignGrievanceRequest.php' => 
    array (
      0 => 'ea5e1f320e6fba87f2742a041d2ded68dca8d0df8219245ffa276e8b0a33ba86',
      1 => 
      array (
        0 => 'app\\http\\requests\\grievance\\assigngrievancerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\grievance\\authorize',
        1 => 'app\\http\\requests\\grievance\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Grievance/StoreGrievanceRequest.php' => 
    array (
      0 => '7e245db06c6c20c0aebf8296c320900b4cec4b60ed1c68965d430acfc1149501',
      1 => 
      array (
        0 => 'app\\http\\requests\\grievance\\storegrievancerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\grievance\\authorize',
        1 => 'app\\http\\requests\\grievance\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Grievance/TransitionGrievanceRequest.php' => 
    array (
      0 => 'eb00e1d5ea9ad69d4035e2bf27e4f370cc628ea891367c0970fb156318b4a3a4',
      1 => 
      array (
        0 => 'app\\http\\requests\\grievance\\transitiongrievancerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\grievance\\authorize',
        1 => 'app\\http\\requests\\grievance\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Grievance/UpdateGrievanceSlaRequest.php' => 
    array (
      0 => '26094338756bc71d5a6af2f920ffe35ad93b2b6f4ddec55ed842f4800bc94726',
      1 => 
      array (
        0 => 'app\\http\\requests\\grievance\\updategrievanceslarequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\grievance\\authorize',
        1 => 'app\\http\\requests\\grievance\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Matching/UpdateMatchingConfigRequest.php' => 
    array (
      0 => '9076295f5525ad7ea848a81fc75818efae74efae26389f559af0a36564bb8c65',
      1 => 
      array (
        0 => 'app\\http\\requests\\matching\\updatematchingconfigrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\matching\\authorize',
        1 => 'app\\http\\requests\\matching\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Notification/UpdateNotificationPreferencesRequest.php' => 
    array (
      0 => '461ec64be696bf74b83f66accf0fda554705e5fc45b4b7f7343e2c559cbff259',
      1 => 
      array (
        0 => 'app\\http\\requests\\notification\\updatenotificationpreferencesrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\notification\\authorize',
        1 => 'app\\http\\requests\\notification\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/BulkEnrollmentRequest.php' => 
    array (
      0 => '253d751c162b28e8bda1e2f201756ed095106ec5cbcdaf4533e7ffcf59fa18e1',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\bulkenrollmentrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
        2 => 'app\\http\\requests\\programme\\withvalidator',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/StoreActivityRequest.php' => 
    array (
      0 => 'ed673fe6d47aa5a4bc6bf159816c3adb839021b567f5fcc8ba34de071980a33d',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\storeactivityrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/StoreEnrollmentRequest.php' => 
    array (
      0 => 'a47a5dc911452a2404e25ae490422801531025b3d485c613539079cd16cb041c',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\storeenrollmentrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
        2 => 'app\\http\\requests\\programme\\withvalidator',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/StoreProgrammeRequest.php' => 
    array (
      0 => '4c3ae8e29b9f260ad79ef38f5734cf23010f1b358dbe5261955f9eb386cda53e',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\storeprogrammerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/UpdateActivityRequest.php' => 
    array (
      0 => 'a682d1ccff8a56dc049fed691e580ea7cd46ebf1db7ecddf8d92d3d69b2c83b5',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\updateactivityrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/UpdateEnrollmentRequest.php' => 
    array (
      0 => '6a9a7dbce1999bb954fee7c9ae19f8ab802e3156137938bf6605889fd017db61',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\updateenrollmentrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Programme/UpdateProgrammeRequest.php' => 
    array (
      0 => 'b622bceb7667afac58ab7ba057557927cc19111e9fddd0f9d90770d3f3566711',
      1 => 
      array (
        0 => 'app\\http\\requests\\programme\\updateprogrammerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\programme\\authorize',
        1 => 'app\\http\\requests\\programme\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Referral/StoreReferralRequest.php' => 
    array (
      0 => '861f12969a4ec2b60b34fae4e405e0da58e24bdcac1c214c54337f112a46b9e4',
      1 => 
      array (
        0 => 'app\\http\\requests\\referral\\storereferralrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\referral\\authorize',
        1 => 'app\\http\\requests\\referral\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Referral/TransitionReferralRequest.php' => 
    array (
      0 => 'f81a55c62b852b8d049b388dedb437c524731b3617a61c330f014d2096612628',
      1 => 
      array (
        0 => 'app\\http\\requests\\referral\\transitionreferralrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\referral\\authorize',
        1 => 'app\\http\\requests\\referral\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Referral/UpdateReferralSlaRequest.php' => 
    array (
      0 => '6d47696087e180bf287bc7291a5c5760fd047703a3df1d7c005ae49813c62ec3',
      1 => 
      array (
        0 => 'app\\http\\requests\\referral\\updatereferralslarequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\referral\\authorize',
        1 => 'app\\http\\requests\\referral\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/AcceptServiceRequestRequest.php' => 
    array (
      0 => 'fcab5bf23a640eda5dc45dd78edff55466bcbf0478406bbb7d07f184cfb0a2bc',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\acceptservicerequestrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/AddHouseholdMemberRequest.php' => 
    array (
      0 => '07564e306c93253e300dd32ed3c41618aa01ed517d59c01eaa45206eeda1a032',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\addhouseholdmemberrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/ApiRegistrationRequest.php' => 
    array (
      0 => 'ee2bfcf50e698747a2a225cd17ccf286326ef6b18a10bda85bed30249d171095',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\apiregistrationrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\prepareforvalidation',
        2 => 'app\\http\\requests\\registry\\rules',
        3 => 'app\\http\\requests\\registry\\messages',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/BeneficiaryLookupRequest.php' => 
    array (
      0 => 'f458080f9213b3b20bb550788e22e45a22577c12dac7700c349088025b436c70',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\beneficiarylookuprequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/BeneficiaryMatchSearchRequest.php' => 
    array (
      0 => '1b4290d5a5679151a5dad48eec13c2e0b08af0d2cb74a77a77023916479eb365',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\beneficiarymatchsearchrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
        2 => 'app\\http\\requests\\registry\\canonicalquery',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/DecideOwnershipTransferRequest.php' => 
    array (
      0 => '6e8351164a7aa8fe828a041a78270a9ad861883f1e81e65aa401daa017386b9b',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\decideownershiptransferrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/DeclineServiceRequestRequest.php' => 
    array (
      0 => 'da9f6e30e69cb7e58fd1719919f83e55fe6ae5db346ffdd23fb6928e32097205',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\declineservicerequestrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/DesignateHeadRequest.php' => 
    array (
      0 => '067b7d46eca10fb4c00f88dc4064d6f5893fb10497ac7c9f6f35bc5ce91d47f6',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\designateheadrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/MoveHouseholdMemberRequest.php' => 
    array (
      0 => 'b01dce9f46ca43e108387f9fec9177d6c1f99b7bd40d80ba405a9975b0aefd51',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\movehouseholdmemberrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/ResolveImportRowRequest.php' => 
    array (
      0 => 'd1d5edf7340532d674e91b35401b0b7fdf91e77e7b4f1d09cd120c9ac843a1e6',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\resolveimportrowrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/RouteAssignmentRequest.php' => 
    array (
      0 => 'c45ed110e8092630751cf3b91c3fa631ffd10d427cd32a3fc4a9efce7d2571f2',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\routeassignmentrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/StoreOwnershipTransferRequest.php' => 
    array (
      0 => 'f4219597e0c02f678cba8f45bf1a94243533afdfb5aaacfb646a036e3b20bee2',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\storeownershiptransferrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/StoreServiceRequestRequest.php' => 
    array (
      0 => 'ec60d130199b84d5f18582024fe7cffbec5c11754606467b1900d753c62ce9d9',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\storeservicerequestrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/UpdateBeneficiaryRequest.php' => 
    array (
      0 => 'f3176f882f98e6653566459523261677ae39c03810443ea2bc622818727fb34f',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\updatebeneficiaryrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\prepareforvalidation',
        2 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/UpdateHouseholdRequest.php' => 
    array (
      0 => '350d5386dd0150dfd1c0dcec975e78891c4d0dc69ed1f4da8441a7c31b5045d5',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\updatehouseholdrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/UploadDocumentRequest.php' => 
    array (
      0 => 'afb6739df07c937169649c6eed7595493ff9b7309ac9e9a5850ebb71d16af65f',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\uploaddocumentrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Registry/UploadImportRequest.php' => 
    array (
      0 => 'c95e59dc9e89819ffdcee0e177d04b687a443732c8116871ddedf894055a4a81',
      1 => 
      array (
        0 => 'app\\http\\requests\\registry\\uploadimportrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\registry\\authorize',
        1 => 'app\\http\\requests\\registry\\rules',
        2 => 'app\\http\\requests\\registry\\usableactivityrule',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/GenerateReportRequest.php' => 
    array (
      0 => '1dcabf5aafa9236bbf792347d584a7f72774ab8bb5c000ad705947377fe0b956',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\generatereportrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\authorize',
        1 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ActivityResource.php' => 
    array (
      0 => '63791b5a6f39ba9ca28ea0258d29a4b6232e3226111bff25f730cd033d733d72',
      1 => 
      array (
        0 => 'app\\http\\resources\\activityresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BeneficiaryDocumentResource.php' => 
    array (
      0 => '0d2261cfd0468528cdcec37bfc06d23090ccda08d6f24ac1b776fc6f4dfb6bea',
      1 => 
      array (
        0 => 'app\\http\\resources\\beneficiarydocumentresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BeneficiaryResource.php' => 
    array (
      0 => '0ec3c58a5fd56d855fcc69280c8af36167afa8afc4d37fa23a93bd8656d29a19',
      1 => 
      array (
        0 => 'app\\http\\resources\\beneficiaryresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
        1 => 'app\\http\\resources\\masktail',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BeneficiaryRevealResource.php' => 
    array (
      0 => '2db79a83890cad14c931c638c13a0e96a895d2aaabbe25a06b2325547dda2461',
      1 => 
      array (
        0 => 'app\\http\\resources\\beneficiaryrevealresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BenefitFlagResource.php' => 
    array (
      0 => '3a96530a9409f311be457d9ea65482bb6900b03f90b23e6193f3f4e85a132a0b',
      1 => 
      array (
        0 => 'app\\http\\resources\\benefitflagresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BenefitImportBatchResource.php' => 
    array (
      0 => '4206e5a4c0f5a5b0f551b2bb83d1bfa3573f980e22bfaf77146a7f99104897cb',
      1 => 
      array (
        0 => 'app\\http\\resources\\benefitimportbatchresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BenefitImportRowResource.php' => 
    array (
      0 => '67621425eab3b5b79e039e8b46a3a2832e2bcf41763883901caeb7315a900483',
      1 => 
      array (
        0 => 'app\\http\\resources\\benefitimportrowresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/BenefitResource.php' => 
    array (
      0 => '3e1e8b872055d5542f7c5c954b9202329ec003d429c02286b7fe4d8f4e14fc4c',
      1 => 
      array (
        0 => 'app\\http\\resources\\benefitresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/DoubleDippingRuleResource.php' => 
    array (
      0 => 'c63f9cd589be9d908020191ea67dbcc093dbdf9ff818303a6a8a91754a3d4bf8',
      1 => 
      array (
        0 => 'app\\http\\resources\\doubledippingruleresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/EnrollmentResource.php' => 
    array (
      0 => 'cb4cdaf67a78d03ffcb79afc54611185d3dc1117ab58a8c29b6dd70f461684ff',
      1 => 
      array (
        0 => 'app\\http\\resources\\enrollmentresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/GrievanceResource.php' => 
    array (
      0 => '7f4bc75cf43df99b86031e625046a1f5d3604846443c5bb55cf4283c71ed5e60',
      1 => 
      array (
        0 => 'app\\http\\resources\\grievanceresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/HouseholdMembershipResource.php' => 
    array (
      0 => '695d973fa43eb9c0171b1685c2f6e50d1ae10c84cabd8b44c2523ab15ea1de8b',
      1 => 
      array (
        0 => 'app\\http\\resources\\householdmembershipresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/HouseholdResource.php' => 
    array (
      0 => 'fa040c2b9cdeaaedaa7b9ae96651467b6041d40d793fc9802aa24b543d0a1770',
      1 => 
      array (
        0 => 'app\\http\\resources\\householdresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ImportBatchResource.php' => 
    array (
      0 => '10a897edc02a43b9dfee75878a981a0001e762b8b3b17df438940b852801ae49',
      1 => 
      array (
        0 => 'app\\http\\resources\\importbatchresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ImportRowResource.php' => 
    array (
      0 => '80a859a3e582d1662d3400383ba8ccc6a8e1d9ed18bb4e631da561903b5f3301',
      1 => 
      array (
        0 => 'app\\http\\resources\\importrowresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
        1 => 'app\\http\\resources\\masktail',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/MatchCandidateResource.php' => 
    array (
      0 => '313eaec58bab0c16e72ff27c5c7e69424cc86dc13f9d1a25fcb41ec7a3021549',
      1 => 
      array (
        0 => 'app\\http\\resources\\matchcandidateresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/MatchingConfigResource.php' => 
    array (
      0 => '38ecc1db7a62af5290cf7e98b7632f5f95c91b91992a06d98b35422b8d502dfa',
      1 => 
      array (
        0 => 'app\\http\\resources\\matchingconfigresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/MdaResource.php' => 
    array (
      0 => '5fa1ed647f5b13e5a2e48cc3c33f0c04f1e7b6ad29de9c82ae722e64631d0ebe',
      1 => 
      array (
        0 => 'app\\http\\resources\\mdaresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/NotificationResource.php' => 
    array (
      0 => '28149a6033202ddf5149685081afd1f671c0e89482c06640276b6c3243950658',
      1 => 
      array (
        0 => 'app\\http\\resources\\notificationresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/OwnershipTransferResource.php' => 
    array (
      0 => 'aea2958c7ac42e7559ec88b07559bf22bb6d14108d16daafe90233ff5112d08d',
      1 => 
      array (
        0 => 'app\\http\\resources\\ownershiptransferresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ProgrammeResource.php' => 
    array (
      0 => 'ae627dc82abf3182493704e29888f956676ee8236d93b206157a3e3f15d5f9ae',
      1 => 
      array (
        0 => 'app\\http\\resources\\programmeresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ReferralResource.php' => 
    array (
      0 => '80e14c1c0fa2d999b7e8841c5d6043e6a07a093f85b152ad0aaef6496aba852f',
      1 => 
      array (
        0 => 'app\\http\\resources\\referralresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ReportRunResource.php' => 
    array (
      0 => '7d5e4e676967eb4bbcd9226b86fca052ee87f736d9369a8bf8bb03e2a6785b1e',
      1 => 
      array (
        0 => 'app\\http\\resources\\reportrunresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ServiceRequestResource.php' => 
    array (
      0 => '9f4bad57c436c32d922362125dfc4372b37af2639e3a7494ad3e1a417f5d0662',
      1 => 
      array (
        0 => 'app\\http\\resources\\servicerequestresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/UserResource.php' => 
    array (
      0 => '5da0c98084da01f9c663dd20d1aacfa3f724546d6904d627e50ac354b387a8ad',
      1 => 
      array (
        0 => 'app\\http\\resources\\userresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Providers/AppServiceProvider.php' => 
    array (
      0 => 'a8411944932725e334fba8e64bd41812d047e3242b9674ac8ebed95aa12dd9a5',
      1 => 
      array (
        0 => 'app\\providers\\appserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\providers\\register',
        1 => 'app\\providers\\boot',
        2 => 'app\\providers\\configureratelimiters',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Support/ApiResponse.php' => 
    array (
      0 => 'e96784f149459b1ce7239487fc56a53b8ed4655417df742365cad9a3a929bc1d',
      1 => 
      array (
        0 => 'app\\support\\apiresponse',
      ),
      2 => 
      array (
        0 => 'app\\support\\success',
        1 => 'app\\support\\paginated',
        2 => 'app\\support\\error',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Exceptions/InvalidReportDefinitionException.php' => 
    array (
      0 => '0ccea7bfbece246cc3a96e9c77dedae8800b1a43d0f2c1c2414fce12e5460e11',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\exceptions\\invalidreportdefinitionexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Models/ReportDefinition.php' => 
    array (
      0 => '892a6ff86fa1b2af407f2ecaee50845fcc352aa2ab2af6da0ce4f6893835fb3a',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\models\\reportdefinition',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\models\\casts',
        1 => 'app\\domain\\reporting\\models\\toadhoc',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Reports/AdHoc/AdHocDatasetRegistry.php' => 
    array (
      0 => 'da058c66e37aa027f0aa82588ee7bc10c9ae511ecb7a1db2172035949642e1e2',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\adhocdatasetregistry',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\get',
        1 => 'app\\domain\\reporting\\reports\\adhoc\\iscoordination',
        2 => 'app\\domain\\reporting\\reports\\adhoc\\availableto',
        3 => 'app\\domain\\reporting\\reports\\adhoc\\cataloguefor',
        4 => 'app\\domain\\reporting\\reports\\adhoc\\optionlist',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Reports/AdHoc/AdHocDefinition.php' => 
    array (
      0 => 'a8afc8480f75a29d29f46ce98f7f17a2d5de7b13f9b27829b0a8e081097a6f72',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\adhocdefinition',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\__construct',
        1 => 'app\\domain\\reporting\\reports\\adhoc\\fromarray',
        2 => 'app\\domain\\reporting\\reports\\adhoc\\toarray',
        3 => 'app\\domain\\reporting\\reports\\adhoc\\label',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Reports/AdHoc/AdHocReportBuilder.php' => 
    array (
      0 => '07c16c51476e04e2ffa13aa7d912409b335cebbd5e6a617d31132f3ec0889417',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\adhocreportbuilder',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\reports\\adhoc\\validate',
        1 => 'app\\domain\\reporting\\reports\\adhoc\\build',
        2 => 'app\\domain\\reporting\\reports\\adhoc\\basequery',
        3 => 'app\\domain\\reporting\\reports\\adhoc\\applyscope',
        4 => 'app\\domain\\reporting\\reports\\adhoc\\applyfilters',
        5 => 'app\\domain\\reporting\\reports\\adhoc\\namemaps',
        6 => 'app\\domain\\reporting\\reports\\adhoc\\renderdimension',
        7 => 'app\\domain\\reporting\\reports\\adhoc\\rendermeasure',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/AdHocReportController.php' => 
    array (
      0 => 'f93a25f698ed95c2b626c4c34e94add4d3ed8f7ee1dfafaab4f02bb6aababdd1',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\adhocreportcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\datasets',
        2 => 'app\\http\\controllers\\api\\v1\\reporting\\preview',
        3 => 'app\\http\\controllers\\api\\v1\\reporting\\export',
        4 => 'app\\http\\controllers\\api\\v1\\reporting\\previewrows',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/ReportDefinitionController.php' => 
    array (
      0 => 'c1c5abdbe650d45685e2d1d8564694fba3e55aa4c8077c4a6a5c5967700e8bd4',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\reportdefinitioncontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\index',
        2 => 'app\\http\\controllers\\api\\v1\\reporting\\store',
        3 => 'app\\http\\controllers\\api\\v1\\reporting\\show',
        4 => 'app\\http\\controllers\\api\\v1\\reporting\\destroy',
        5 => 'app\\http\\controllers\\api\\v1\\reporting\\run',
        6 => 'app\\http\\controllers\\api\\v1\\reporting\\mine',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/AdHocReportRequest.php' => 
    array (
      0 => 'b1986f9cff82410ceab76031bd4b0abb2588f3201e5e9b9e7d156d0d6635c216',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\adhocreportrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\authorize',
        1 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/ExportAdHocRequest.php' => 
    array (
      0 => 'de6bb6ae6b0340eef743a72b5c2c2d00080a46beb74ed110c554c219e07e3aff',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\exportadhocrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/RunReportDefinitionRequest.php' => 
    array (
      0 => 'f827ea8c96fa5cc78d96c067011ef78527f9da5bc0dd07a1ac40cf8a7e2ec470',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\runreportdefinitionrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\authorize',
        1 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/SaveReportDefinitionRequest.php' => 
    array (
      0 => 'de44092302448ef2fccf91a6e2d25a3cacc339619684da46b1b63930d146ef90',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\savereportdefinitionrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ReportDefinitionResource.php' => 
    array (
      0 => '7f193b34adc6ccb36b90cfd57fc488b77fc60f86b0452383b79c15cca5cf7cdb',
      1 => 
      array (
        0 => 'app\\http\\resources\\reportdefinitionresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Exceptions/InvalidReportScheduleException.php' => 
    array (
      0 => '59791f4ac3c06475c2fc59aa480a2d9f5a7342b16df5244ada10e10b5d1a10db',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\exceptions\\invalidreportscheduleexception',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Jobs/RunDueReportSchedules.php' => 
    array (
      0 => '1207a2577d08685952dd409fcc8c374d94bc1b4ad6b07a04a46a1d7e2515cff0',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\runduereportschedules',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\jobs\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Listeners/DeliverScheduledReport.php' => 
    array (
      0 => '1d8f48c8f8e261f5464753c2695ceabbb3bee3a443fd10a1ee5afdc0fb266272',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\listeners\\deliverscheduledreport',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\listeners\\__construct',
        1 => 'app\\domain\\reporting\\listeners\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Mail/ScheduledReportMail.php' => 
    array (
      0 => '2b7284fc44f96c18cd6883ccbc5307c2a70ad530b454b468860e98a1c9b56db7',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\mail\\scheduledreportmail',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\mail\\__construct',
        1 => 'app\\domain\\reporting\\mail\\build',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Models/ReportSchedule.php' => 
    array (
      0 => '8a140ce5887a4b1bbb5a7ccddda9cc95d82a40551c908ad03dd26b30f86ce9a9',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\models\\reportschedule',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\models\\casts',
        1 => 'app\\domain\\reporting\\models\\definition',
        2 => 'app\\domain\\reporting\\models\\toscope',
        3 => 'app\\domain\\reporting\\models\\isactive',
        4 => 'app\\domain\\reporting\\models\\dueon',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Services/ReportScheduleService.php' => 
    array (
      0 => '17ec135fab548b24b6fb6f35406291da7fce3a25c2f1ee872304aefcc38a2f99',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\services\\reportscheduleservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\services\\__construct',
        1 => 'app\\domain\\reporting\\services\\create',
        2 => 'app\\domain\\reporting\\services\\update',
        3 => 'app\\domain\\reporting\\services\\delete',
        4 => 'app\\domain\\reporting\\services\\rundue',
        5 => 'app\\domain\\reporting\\services\\resolvereport',
        6 => 'app\\domain\\reporting\\services\\assertreportavailable',
        7 => 'app\\domain\\reporting\\services\\normalizerecipients',
        8 => 'app\\domain\\reporting\\services\\assertrecipientscoverscope',
        9 => 'app\\domain\\reporting\\services\\auditdata',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/ReportScheduleController.php' => 
    array (
      0 => '3c36a16b67c54863a0df1e30f38c862392e2e7f90b22b41fbe484c6865eddf7f',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\reportschedulecontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\index',
        2 => 'app\\http\\controllers\\api\\v1\\reporting\\store',
        3 => 'app\\http\\controllers\\api\\v1\\reporting\\show',
        4 => 'app\\http\\controllers\\api\\v1\\reporting\\update',
        5 => 'app\\http\\controllers\\api\\v1\\reporting\\destroy',
        6 => 'app\\http\\controllers\\api\\v1\\reporting\\mine',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/StoreReportScheduleRequest.php' => 
    array (
      0 => '85b4b4d7c02ade6dee1953bd2033c1cc9c1521106f07f36b3c4a3cd22e005d98',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\storereportschedulerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\authorize',
        1 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Requests/Reporting/UpdateReportScheduleRequest.php' => 
    array (
      0 => 'ecd17b5ce6f3fc584c14fa55f07fecdf00d374c91670b2eecd61c9a04affe0d2',
      1 => 
      array (
        0 => 'app\\http\\requests\\reporting\\updatereportschedulerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\reporting\\authorize',
        1 => 'app\\http\\requests\\reporting\\rules',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Resources/ReportScheduleResource.php' => 
    array (
      0 => 'ffaf5da7a18af438132cefbeee3415399d81cf434ed93f5d078beea79a52cc06',
      1 => 
      array (
        0 => 'app\\http\\resources\\reportscheduleresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Gis/BoundaryLoader.php' => 
    array (
      0 => '10054d0fd82098484831fcb3806a64ab23f547324a7e08dbfbe4faf8909d9b45',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\boundaryloader',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\load',
        1 => 'app\\domain\\reporting\\gis\\slug',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Gis/GeoBoundary.php' => 
    array (
      0 => 'b6efd1960ce962dcacecdc2e500223aa7c0cc0a54c17feeb3eac1665baffefca',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\geoboundary',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\casts',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Gis/GisCoverageService.php' => 
    array (
      0 => '118c26a15f3dec075ff7eaaad799d9db83f33edb111a34a9ee9dc4b1af515633',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\giscoverageservice',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\__construct',
        1 => 'app\\domain\\reporting\\gis\\coverage',
        2 => 'app\\domain\\reporting\\gis\\beneficiarycounts',
        3 => 'app\\domain\\reporting\\gis\\slug',
        4 => 'app\\domain\\reporting\\gis\\title',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Domain/Reporting/Gis/LoadGeoBoundaries.php' => 
    array (
      0 => '66b41c1dc83fbbf0107817c81a1f6a1015fcf40dbb64c49e09f734cae67a100a',
      1 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\loadgeoboundaries',
      ),
      2 => 
      array (
        0 => 'app\\domain\\reporting\\gis\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/html/app/Http/Controllers/Api/V1/Reporting/GisController.php' => 
    array (
      0 => 'bca3af064ef26c991cd54602756546185687ccc539720c71db81b77be60fdf4f',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\giscontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\v1\\reporting\\__construct',
        1 => 'app\\http\\controllers\\api\\v1\\reporting\\coverage',
        2 => 'app\\http\\controllers\\api\\v1\\reporting\\featurecollection',
      ),
      3 => 
      array (
      ),
    ),
  ),
));