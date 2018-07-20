<?php

$hook_version = 1; 
$hook_array = Array(); 

$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(13, 'Gen Invoice Name', 'custom/hooks/invoice_hook.php','invoice_hook', 'gen_name');
