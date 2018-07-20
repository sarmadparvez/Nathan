<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(13, 'Check email', 'custom/hooks/calls_intake_hook.php','calls_intake_hook', 'check_email');
$hook_array['process_record']=Array();
$hook_array['process_record'][]=Array(999, 'do sum' , 'custom/modules/calls_IntakeForm/hook_sum.php', 'Hook_sum','do_sum');