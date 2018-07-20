<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(13, 'Copy Account Code', 'custom/hooks/policy_hook.php','policy_hook', 'copy_acc_code');
