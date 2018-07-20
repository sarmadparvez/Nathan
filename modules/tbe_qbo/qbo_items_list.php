<?php

	//die('Lock');

	error_reporting(E_ALL ^E_STRICT ^E_NOTICE);
	ini_set('display_errors', true);

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
	
	global $db;
	
	echo '<br/>Items:<br/>';
	
	$tbe_qbo = BeanFactory::getBean('tbe_qbo');
	$tbe_qbo->retrieveSetting();
	
	//if($tbe_qbo->isAllowedQBO()){

	echo $tbe_qbo->sdk_path;
	
	require_once($tbe_qbo->sdk_path.'config.php');
	require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
	require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
	require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
	//require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');

	if (empty($tbe_qbo->realmid)) exit("RealmID is not specified.\n");
	
	$requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
	if (!$requestValidator) exit("Problem while initializing requestValidator.\n");

	$serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
	if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");

	$dataService = new DataService($serviceContext);
	if (!$dataService) exit("Problem while initializing DataService.\n");

	//echo '<pre>';
	//print_r($dataService);
	//echo '</pre>';
	
	$i = 1;
	//while(1){
		try{
			$allItems = $dataService->FindAll('Item', $i, 200);
		}catch (IdsException $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		if( !$allItems || (0==count($allItems)) ){break;}

		//echo '<pre>';
		//print_r($allItems);
		//echo '</pre>';	

		foreach($allItems as $oneItem){
			$i++;
			//$name = ($oneItem->SubItem)?'[sub_'.$oneItem->ParentRef.']':'';
			$name = $oneItem->Name;
			/*
			if($oneItem->IncomeAccountRef == 12){
				$name .= ' ---- SET BOX ';
				$sql = " SELECT id_c FROM aos_products_cstm WHERE qbo_id_c = '{$oneItem->Id}' ; ";
				$res = $db->query($sql);
				while( $row = $db->fetchByAssoc($res) ){
					$name .= ' ---- '.$row['id_c'];
					$crm_product = BeanFactory::getBean('AOS_Products', $row['id_c']);
					$crm_product->to_insurer_c = 1;
					$crm_product->save(false);
				}
			}
			*/
			/*
			$p_name = $db->quote($oneItem->Name);
			$sql = " SELECT id, name FROM aos_products WHERE name = '{$p_name}' ; ";
			$res = $db->query($sql);
			while( $row = $db->fetchByAssoc($res) ){
				$name .= ' ---------crmID:'.$row['id'];
				$crm_product = BeanFactory::getBean('AOS_Products', $row['id']);
				if(!$crm_product->qbo_id_c){
					$crm_product->qbo_id_c = $oneItem->Id;
					$crm_product->save(false);
				}else{
					$name .= '-------SKIP';
				}
			}
			*/
			$list_arr[$oneItem->Id] = $name;
		}
	//}
	
	echo '<pre>';
	print_r($list_arr);
	echo '</pre>';
	
	//Items can have hierarchy [FullyQualifiedName] => Brokerage Charges:Administration Fee
/*
(
	[Name] => Administration Fee
	[Sku] => 
	[Description] => Administration Fee
	[Active] => true
	[SubItem] => true
	[ParentRef] => 27
	[Level] => 1
	[FullyQualifiedName] => Brokerage Charges:Administration Fee
	[Taxable] => false
	[SalesTaxIncluded] => false
	[PercentBased] => 
	[UnitPrice] => 0
	[RatePercent] => 
	[Type] => Service
	[PaymentMethodRef] => 
	[UOMSetRef] => 
	[IncomeAccountRef] => 1
	[PurchaseDesc] => 
	[PurchaseTaxIncluded] => false
	[PurchaseCost] => 0
	[ExpenseAccountRef] => 
	[COGSAccountRef] => 
	[AssetAccountRef] => 
	[PrefVendorRef] => 
	[AvgCost] => 
	[TrackQtyOnHand] => false
	[QtyOnHand] => 
	[QtyOnPurchaseOrder] => 
	[QtyOnSalesOrder] => 
	[ReorderPoint] => 
	[ManPartNum] => 
	[DepositToAccountRef] => 
	[SalesTaxCodeRef] => 6
	[PurchaseTaxCodeRef] => 
	[InvStartDate] => 
	[BuildPoint] => 
	[PrintGroupedItems] => 
	[SpecialItem] => 
	[SpecialItemType] => 
	[ItemGroupDetail] => 
	[ItemAssemblyDetail] => 
	[AbatementRate] => 
	[ReverseChargeRate] => 
	[ServiceType] => 
	[ItemCategoryType] => 
	[ItemEx] => 
	[Id] => 14
	[SyncToken] => 2
	[MetaData] => IPPModificationMetaData Object
		(
			[CreatedByRef] => 
			[CreateTime] => 2014-10-21T10:49:39-07:00
			[LastModifiedByRef] => 
			[LastUpdatedTime] => 2015-01-28T08:12:19-08:00
			[LastChangedInQB] => 
			[Synchronized] => 
		)

	[CustomField] => 
	[AttachableRef] => 
	[domain] => 
	[status] => 
	[sparse] => 
)
*/

echo '<br/>.';