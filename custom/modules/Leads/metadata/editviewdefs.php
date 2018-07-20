<?php
$viewdefs ['Leads'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="autoconvert" value="0">',
          1 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          2 => '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          3 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          4 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ),
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
          2 => 
          array (
            'sugar_html' => 
            array (
              'type' => 'button',
              'value' => '{$MOD.LBL_SAVE_AND_CONVERT_BUTTON_LABEL}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_SAVE_AND_CONVERT_BUTTON_TITLE}',
                'accessKey' => '{$MOD.LBL_SAVE_AND_CONVERT_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'saveautoconvert();',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ),
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ),
          ),
        ),
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
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim}

	  {literal}
	  function saveautoconvert(){
		var _form = document.getElementById("EditView");
		_form.autoconvert.value="1";
		$("#SAVE_HEADER").click();
	  }
	  {/literal}

	  </script>',
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
      ),
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'LBL_CONTACT_INFORMATION' => 
      array (
        0 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 'last_name',
          1 => '',
        ),
        2 => 
        array (
          0 => 'account_name',
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
          1 => '',
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
          1 => '',
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
          1 => '',
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'types_of_protection_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPES_OF_PROTECTION',
          ),
          1 => '',
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
          0 => 
          array (
            'name' => 'status_description',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'lead_source',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE',
            'customCode' => '{if $LEAD_SOURCE == "readOnly"}<span>{$fields.lead_source.value}</span>{else}{html_options name="lead_source" id="lead_source" options=$fields.lead_source.options selected=$fields.lead_source.value}{/if}',
          ),
          1 => 
          array (
            'name' => 'referred_by_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_REFERRED_BY_CONTACT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'lead_source_description',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 'campaign_name',
          1 => 
          array (
            'name' => 'keywords_c',
            'studio' => 'visible',
            'label' => 'LBL_KEYWORDS',
          ),
        ),
      ),
    ),
  ),
);
?>
