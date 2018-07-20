<?php

echo 'Working.....';

    if(!(ACLController::checkAccess('AOS_Invoices', 'edit', true))){
        ACLController::displayNoAccess();
        die;
    }

	require_once('modules/AOS_Invoices/AOS_Invoices.php');
	require_once('modules/AOS_Products_Quotes/AOS_Products_Quotes.php');
	require_once('modules/AOS_Contracts/AOS_Contracts.php');

	$policy = BeanFactory::getBean('AOS_Contracts',$_REQUEST['record']);
	if(!empty($policy->aos_invoices_aos_contracts_1aos_invoices_ida)){
		$url = 'index.php?module=AOS_Invoices&action=DetailView&record='.$policy->aos_invoices_aos_contracts_1aos_invoices_ida;

		ob_clean();
		header('Location: '.$url);
		exit;
	}

	$crm_direct_commision_product_id = '9cdd752b-1abe-a748-cb25-56ba1fc0e576';//TODO: add to conf?
	$product_direct_commision = BeanFactory::getBean('AOS_Products', $crm_direct_commision_product_id);
	if( $policy->direct_commission_c && empty($product_direct_commision->id) ){
		echo '<br/>Unable to retrieve "Direct Commision" product data';
		exit;
	}
	//if($policy->status == 'Invoiced'){
	//	echo '<br/>Seems you already have Invoice';
	//	die;
	//}

	$invoice = BeanFactory::getBean('AOS_Invoices');
	$rawRow = $policy->fetched_row;

	$rawRow['id'] = '';
	$rawRow['template_ddown_c'] = ' ';
	$rawRow['invoice_date'] = date('Y-m-d');

	$invoice->populateFromRow($rawRow);

	$invoice->status = '';

	//aos_invoices_aos_contracts_1_name;
	$invoice->aos_invoices_aos_contracts_1aos_contracts_idb = $policy->id;
	//$policy->aos_invoices_aos_contracts_1aos_invoices_ida = $invoice->id;

	$acc = BeanFactory::getBean('Accounts', $policy->contract_account_id);

	$invoice->billing_account_id = $policy->contract_account_id;

	//copy address from account
	$invoice->billing_address_street = $acc->billing_address_street;
	$invoice->billing_address_city = $acc->billing_address_city;
	$invoice->billing_address_state = $acc->billing_address_state;
	$invoice->billing_address_postalcode = $acc->billing_address_postalcode;
	$invoice->billing_address_country = $acc->billing_address_country;
		$invoice->shipping_address_street = $acc->shipping_address_street;
		$invoice->shipping_address_city = $acc->shipping_address_city;
		$invoice->shipping_address_state = $acc->shipping_address_state;
		$invoice->shipping_address_postalcode = $acc->shipping_address_postalcode;
		$invoice->shipping_address_country = $acc->shipping_address_country;


	$invoice->invoice_date = $policy->start_date;
	$invoice->due_date = $policy->start_date;

	$invoice->process_save_dates =false;
	$invoice->save();


	$direct_total = 0;

  
	$sql = "SELECT * FROM aos_products_quotes as c
	LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c
	WHERE c.parent_type = 'AOS_Contracts' AND c.parent_id = '".$policy->id."' AND c.deleted = 0";
  	$result = $policy->db->query($sql);
	while ($row = $policy->db->fetchByAssoc($result)) {
		if($policy->direct_commission_c){
			//if( (float) $row['commission_c'] > 0 ){
				$productOfInvoce = BeanFactory::getBean('AOS_Products_Quotes');
				$productOfInvoce->parent_id = $invoice->id;
				$productOfInvoce->parent_type = 'AOS_Invoices';
				$fields_to_copy = array('product_cost_price' ,'product_list_price' ,'product_discount' ,'product_discount_amount' ,'product_unit_price' ,'vat_amt' ,'vat' ,'product_total_price' ,'payable_premium_c' ,'commission_rate_c' ,'commission_c' ,'charges_amount_c' ,'charges_tax_c' ,'charges_total_c' ,'products_amount_c' ,'products_tax_c' ,'products_total_c' );
				foreach($fields_to_copy as $i => $field){
					$productOfInvoce->$field = format_number($product_direct_commision->$field);
				}
				$productOfInvoce->item_description = $row['name'];
				$productOfInvoce->product_qty = 1;
				$productOfInvoce->product_id = $product_direct_commision->id;
				$productOfInvoce->name = $product_direct_commision->name;
				$productOfInvoce->product_unit_price = format_number($row['commission_c']);
				$productOfInvoce->save(false);
				$direct_total += $row['commission_c'];
			//}
		}else{
			$row['id'] = '';
			$row['parent_id'] = $invoice->id;
			$row['parent_type'] = 'AOS_Invoices';
			if($row['product_cost_price'] != null)
			{
				$row['product_cost_price'] = format_number($row['product_cost_price']);
			}
			$row['product_list_price'] = format_number($row['product_list_price']);
			if($row['product_discount'] != null)
			{
				$row['product_discount'] = format_number($row['product_discount']);
				$row['product_discount_amount'] = format_number($row['product_discount_amount']);
			}
			$row['product_unit_price'] = format_number($row['product_unit_price']);
			$row['vat_amt'] = format_number($row['vat_amt']);
			$row['product_total_price'] = format_number($row['product_total_price']);

			$row['payable_premium_c'] = format_number($row['payable_premium_c']);
			$row['commission_c'] = format_number($row['commission_c']);


			$row['charges_amount_c'] = format_number($row['charges_amount_c']);
			$row['charges_tax_c'] = format_number($row['charges_tax_c']);
			$row['charges_total_c'] = format_number($row['charges_total_c']);

			$row['products_amount_c'] = format_number($row['products_amount_c']);
			$row['products_tax_c'] = format_number($row['products_tax_c']);
			$row['products_total_c'] = format_number($row['products_total_c']);


			$row['product_qty'] = 1;

			$prod_invoice = BeanFactory::getBean('AOS_Products_Quotes');
			$prod_invoice->populateFromRow($row);
			$prod_invoice->save(false);
		}
	}

	if($policy->direct_commission_c){
		$formated_total = format_number($direct_total);
		$invoice->products_amount_c = $formated_total;
		$invoice->products_tax_c = 0;
		$invoice->products_total_c = $formated_total;
		$invoice->total_amt = $formated_total;
		$invoice->tax_amount = 0;
		$invoice->total_amount = $formated_total;
		$invoice->save(false);
	}

	if(!empty($invoice->id)){
		$policy->status = 'Invoiced';
		$policy->save(false);
	}
/*
	//Setting invoice quote relationship
	require_once('modules/Relationships/Relationship.php');
	$key = Relationship::retrieve_by_modules('AOS_Quotes', 'AOS_Invoices', $GLOBALS['db']);
	if (!empty($key)) {
		$quote->load_relationship($key);
		$quote->$key->add($invoice->id);
	}
*/

	$url = 'index.php?module=AOS_Invoices&action=DetailView&record='.$invoice->id;

	ob_clean();
	header('Location: '.$url);
