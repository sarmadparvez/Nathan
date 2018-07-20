<?php 



include_once 'custom/modules/Configurator/asteriskConfig.php';
$response = checklicence();

if($response=="active")
{
    $redirect_action = "configurator&status=success";
   
}
else
{
    $redirect_action = "asteriskUserInfo&status=1";
}

$admin_options_defs=array();
$admin_options_defs['Administration']['AsteriskConfiguration']=array(       
        'ASTERISKPANELSETTINGS',
        'LBL_ASTERISK_CONFIGURATION_TITLE',
        'LBL_CONFIGURATION_DESC',
        './index.php?module=Configurator&action='.$redirect_action.''
        );
        
$admin_options_defs['Administration']['AsteriskActivation']=array(       
        'ASTERISKPANELACTIVATION',
        'LBL_ACTIVATION_TITLE',
        'LBL_ACTIVATION_DESC',
        './index.php?module=Configurator&action=asteriskUserInfo'
        );        
$admin_group_header[]=array(
    'LBL_ASTERISKGROUP_TITLE',
    'LBL_ASTERISKGROUP_DESC',
    false,
    $admin_options_defs,
);        

      




?>
