<?php
$viewdefs ['Leads'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="autoconvert(\'{$fields.id.value}\');" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
            'sugar_html' => 
            array (
              'type' => 'button',
              'value' => '{$MOD.LBL_CONVERTLEAD}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'autoconvert(\'{$fields.id.value}\');',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ),
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ),
          ),
          4 => 'FIND_DUPLICATES',
          5 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => 
              array (
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';',
                'name' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              ),
            ),
          ),
          'BTN_SET_DEAD' => 
          array (
            'customCode' => '<input type="button" class="button" onClick="setDead(\'{$fields.id.value}\');" value="{$MOD.BTN_SET_DEAD}">',
          ),
          'AOS_GENLET' => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_GENERATE_LETTER}">',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Leads/Lead.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/Leads/autoconvert.js',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'LBL_CONTACT_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 'phone_work',
          1 => '',
        ),
        4 => 
        array (
          0 => 'phone_mobile',
          1 => '',
        ),
        5 => 
        array (
          0 => 'email1',
          1 => 
          array (
            'name' => 'zywave_c',
            'label' => 'LBL_ZYWAVE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'zywave_industry_c',
            'studio' => 'visible',
            'label' => 'LBL_ZYWAVE_INDUSTRY',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_state',
            'comment' => 'State for primary address',
            'label' => 'LBL_PRIMARY_ADDRESS_STATE',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'coverage_type_c',
            'studio' => 'visible',
            'label' => 'LBL_COVERAGE_TYPE',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'coverage_amount_c',
            'studio' => 'visible',
            'label' => 'LBL_COVERAGE_AMOUNT',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'pcategory_c',
            'studio' => 'visible',
            'label' => 'LBL_PCATEGORY',
          ),
          1 => 
          array (
            'name' => 'policy_g',
            'studio' => 'visible',
            'label' => 'LBL_POLICY_G',
          ),
        ),
        11 => 
        array (
          0 => 'description',
          1 => '',
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'types_of_insurance_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPES_OF_INSURANCE',
          ),
          1 => 
          array (
            'name' => 'types_of_protection_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPES_OF_PROTECTION',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'accept_status_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCEPT_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 'status',
          1 => '',
        ),
        3 => 
        array (
          0 => 'status_description',
          1 => '',
        ),
        4 => 
        array (
          0 => 'lead_source',
          1 => 
          array (
            'name' => 'referred_by_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_REFERRED_BY_CONTACT',
          ),
        ),
        5 => 
        array (
          0 => 'lead_source_description',
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
          1 => 
          array (
            'name' => 'keywords_c',
            'studio' => 'visible',
            'label' => 'LBL_KEYWORDS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'ip_address_c',
            'label' => 'LBL_IP_ADDRESS',
          ),
          1 => '',
        ),
      ),
      'lbl_detailview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'lead_cost_c',
            'label' => 'LBL_LEAD_COST',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'lead_value_c',
            'label' => 'LBL_LEAD_VALUE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'time2close_c',
            'label' => 'LBL_TIME2CLOSE',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'date_open_c',
            'label' => 'LBL_DATE_OPEN',
          ),
          1 => 
          array (
            'name' => 'date_close_c',
            'label' => 'LBL_DATE_CLOSE',
          ),
        ),
      ),
    ),
  ),
);
?>
