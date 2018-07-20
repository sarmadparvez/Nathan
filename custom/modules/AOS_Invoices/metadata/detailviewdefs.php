<?php
$module_name = 'AOS_Invoices';
$_object_name = 'aos_invoices';
$viewdefs [$module_name] = 
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
          3 => 'FIND_DUPLICATES',
          4 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'pdf\');" value="{$MOD.LBL_PRINT_AS_PDF}">',
          ),
          5 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'emailpdf\');" value="{$MOD.LBL_EMAIL_PDF}">',
          ),
          6 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'email\');" value="{$MOD.LBL_EMAIL_INVOICE}">',
          ),
          7 => 
          array (
            'customCode' => '<input type="button" class="button" onclick="this.form.return_module.value=\'AOS_Invoices\'; 
			this.form.return_action.value=\'DetailView\';
			this.form.return_id.value=\'{$fields.id.value}\'; 
			this.form.action.value=\'qboexport\'; 
			this.form.module.value=\'AOS_Invoices\'; 
			" type="submit" value="{$MOD.LBL_EXPORT_TO_QBO}">',
          ),
          8 => 
          array (
            'customCode' => '<input type="button" class="button" onclick="this.form.return_module.value=\'AOS_Invoices\'; 
			this.form.return_action.value=\'DetailView\';
			this.form.return_id.value=\'{$fields.id.value}\'; 
			this.form.action.value=\'qboupdate\'; 
			this.form.module.value=\'tbe_qbo\'; 
			" type="submit" value="{$MOD.LBL_QBO_UPDATE}">',
          ),
          9 => 
          array (
            'customCode' => '<input type="button" class="button" onclick="this.form.return_module.value=\'AOS_Invoices\'; 
			this.form.return_action.value=\'DetailView\';
			this.form.return_id.value=\'{$fields.id.value}\'; 
			this.form.action.value=\'cancel\'; 
			this.form.module.value=\'AOS_Invoices\'; 
			" type="submit" value="Cancel">',
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_PANEL_OVERVIEW' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_INVOICE_TO' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'LBL_PANEL_OVERVIEW' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'invoice_no_c',
            'label' => 'LBL_INVOICE_NO',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'number',
            'label' => 'LBL_INVOICE_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'quote_number',
            'label' => 'LBL_QUOTE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'quote_date',
            'label' => 'LBL_QUOTE_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'due_date',
            'label' => 'LBL_DUE_DATE',
          ),
          1 => 
          array (
            'name' => 'invoice_date',
            'label' => 'LBL_INVOICE_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'aos_invoices_aos_contracts_1_name',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'insurer_c',
            'studio' => 'visible',
            'label' => 'LBL_INSURER',
          ),
          1 => '',
        ),
      ),
      'LBL_INVOICE_TO' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'label' => 'LBL_BILLING_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'acc_code_c',
            'label' => 'LBL_ACC_CODE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'billing_contact',
            'label' => 'LBL_BILLING_CONTACT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'label' => 'LBL_BILLING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
            ),
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'label' => 'LBL_SHIPPING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
            ),
          ),
        ),
      ),
      'lbl_line_items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'line_items',
            'label' => 'LBL_LINE_ITEMS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'products_amount_c',
            'label' => 'LBL_PRODUCTS_AMOUNT',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'products_tax_c',
            'label' => 'LBL_PRODUCTS_TAX',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'products_total_c',
            'label' => 'LBL_PRODUCTS_TOTAL',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'total_amt',
            'label' => 'LBL_TOTAL_AMT',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'tax_amount',
            'label' => 'LBL_TAX_AMOUNT',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'total_amount',
            'label' => 'LBL_GRAND_TOTAL',
          ),
          1 => '',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
      ),
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'qbo_id_c',
            'label' => 'LBL_QBO_ID',
          ),
          1 => 
          array (
            'name' => 'qbo_creditmemo_id_c',
            'label' => 'LBL_QBO_CREDITMEMO_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'qbo_bill_p_id_c',
            'label' => 'LBL_QBO_BILL_P_ID',
          ),
          1 => 
          array (
            'name' => 'creditmemo_id_c',
            'studio' => 'visible',
            'label' => 'LBL_CREDITMEMO_ID',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'qbo_bill_v_id_c',
            'label' => 'LBL_QBO_BILL_V_ID',
          ),
          1 => 
          array (
            'name' => 'is_creditmemo_c',
            'label' => 'LBL_IS_CREDITMEMO',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'exists_qbo_c',
            'label' => 'LBL_EXISTS_QBO_C',
          ),
          1 => 
          array (
            'name' => 'original_invoice_c',
            'studio' => 'visible',
            'label' => 'LBL_ORIGINAL_INVOICE',
          ),
        ),
      ),
    ),
  ),
);
?>
