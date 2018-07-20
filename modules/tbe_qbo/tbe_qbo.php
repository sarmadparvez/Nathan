<?PHP

require_once('modules/tbe_qbo/tbe_qbo_sugar.php');
class tbe_qbo extends tbe_qbo_sugar {
	
	var $oauth_request_url = 'https://oauth.intuit.com/oauth/v1/get_request_token';
	var $oauth_access_url = 'https://oauth.intuit.com/oauth/v1/get_access_token';
	var $oauth_authorise_url = 'https://appcenter.intuit.com/Connect/Begin';
	var $callback_url  = '/index.php?entryPoint=tbeQBOauthCallback';
	var $settings_category  = 'tbe_qbo';
	var $consumer_key ='';
	var $consumer_secret ='';
	var $realmid ='';
	var $access_token_datetime ='';
	var $access_token ='';
	var $access_token_secret ='';
	
	
	var $cnf_bill_producer_vendor_id = 636;//"Producer Commissions Payable" (prod name: Producer Commissions Accrued)
	var $cnf_bill_producer_term_id = 1;//"Due on receipt"
	var $cnf_bill_producer_dep_id = 3;//"Office"(Location)//OK
	var $cnf_invoice_term_id = 1;//"Due on receipt"
	var $cnf_invoice_dep_id =  1;//Trust (Location) //OK
	var $cnf_invoice_dc_dep_id =  3;//(DirectCommision)General',//OK
	var $cnf_bill_producer_item_acc_id = 19;//5100 Producer Expense//OK
	var $cnf_bill_vendor_item_acc_commission_id = 1;//4050 "Commission Income"//OK
	var $cnf_bill_vendor_item_acc_id = 12;//2310 Trust
	var $cnf_bill_vendor_term_id = 1;
	var $cnf_bill_vendor_dep_id = 1;//Trust (Location)
	
	
	var $dataService ='';
	//var $sdk_path = 'custom/qb/v3-sdk-2.3.0/';
	var $sdk_path = 'custom/qb/v3-sdk-2.4.1/';
	
	function isAllowedQBO(){
		global $current_user;
		if(
		//ACLController::checkAccess('tbe_qbo', 'edit', true) || 
		$current_user->is_admin
		){
			return true;
		}
		return false;
	}
	function cleanSetting(){
		$administration = BeanFactory::getBean('Administration');
		$administration->retrieveSettings($this->settings_category);
		$this->realmid = '';
		$administration->saveSetting($this->settings_category, 'realmid', '');
		$this->access_token_datetime = '';
		$administration->saveSetting($this->settings_category, 'access_token_datetime', '');
		$this->access_token = '';
		$administration->saveSetting($this->settings_category, 'access_token', '');
		$this->access_token_secret = '';
		$administration->saveSetting($this->settings_category, 'access_token_secret', '');
		$this->request_token_secret = '';
		//$administration->saveSetting($this->settings_category, 'request_token_secret', '');
		return true;
	}
	function initialSetting(){
		$administration = BeanFactory::getBean('Administration');
		$administration->retrieveSettings($this->settings_category);
		$value = '';
		$administration->saveSetting($this->settings_category, 'consumer_key', $value);
		$administration->saveSetting($this->settings_category, 'consumer_secret', $value);
		$administration->saveSetting($this->settings_category, 'realmid', '');
		$administration->saveSetting($this->settings_category, 'access_token_datetime', '');
		$administration->saveSetting($this->settings_category, 'access_token', '');
		$administration->saveSetting($this->settings_category, 'access_token_secret', '');
		$administration->saveSetting($this->settings_category, 'request_token_secret', '');//actually tmp value
		//$administration->encrpyt_before_save();
		return true;
	}

	function retrieveSetting(){
		global $sugar_config;
		$this->callback_url = empty($sugar_config['site_url'])?'':$sugar_config['site_url'].$this->callback_url;
		
		$administration = BeanFactory::getBean('Administration');
		$administration->retrieveSettings($this->settings_category); 
		
		$this->consumer_key = $administration->decrypt_after_retrieve($administration->settings[$this->settings_category.'_consumer_key']); //echo $this->consumer_key; die;
		$this->consumer_secret = $administration->decrypt_after_retrieve($administration->settings[$this->settings_category.'_consumer_secret']);
		$this->realmid = $administration->decrypt_after_retrieve($administration->settings[$this->settings_category.'_realmid']);
		
		$this->access_token_datetime = $administration->settings[$this->settings_category.'_access_token_datetime'];//lives 180d and possible to renew within 30d
		$this->access_token = $administration->decrypt_after_retrieve($administration->settings[$this->settings_category.'_access_token']);
		$this->access_token_secret = $administration->decrypt_after_retrieve($administration->settings[$this->settings_category.'_access_token_secret']);
		
		$this->request_token_secret = $administration->settings[$this->settings_category.'_request_token_secret'];
		
		return true;
	}
	
	function tbe_qbo(){
		parent::tbe_qbo_sugar();
	}
	
    function grabLists(){
		
		if(empty($this->access_token)){
			$this->retrieveSetting();
		}
		
		require_once($this->sdk_path.'config.php');
		require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
		require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
		require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');

		if (empty($this->realmid)) exit("RealmID is not specified.\n");
		
		$requestValidator = new OAuthRequestValidator($this->access_token, $this->access_token_secret, $this->consumer_key, $this->consumer_secret);
		if (!$requestValidator) exit("Problem while initializing requestValidator.\n");

		$serviceContext = new ServiceContext($this->realmid, IntuitServicesType::QBO, $requestValidator);
		if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");

		$dataService = new DataService($serviceContext);
		if (!$dataService) exit("Problem while initializing DataService.\n");	

		echo '<br/>Start list grabing...<br/>';
		//account--begin
		echo '<br/>Account...';
		$list_arr = array();
		$list_arr['BLANK'] = "";
		$i = 1;
		while(1){
			$allItems = $dataService->FindAll('account', $i, 500);
			if( !$allItems || (0==count($allItems)) ){break;}
			foreach($allItems as $oneItem){
				$i++;
				//$oneItem->SubAccount
				//$oneItem->Name
				//$oneItem->AccountType
				//$oneItem->Classification
				$list_arr[$oneItem->Id] = "{$oneItem->AcctNum} {$oneItem->FullyQualifiedName}";
			}
		}
		ksort($list_arr);
		self::saveDDList($list_arr, 'qbo_account_list');
		echo 'seems is OK';
		//account--end

		//class--begin
		echo '<br/>Class...';
		$list_arr = array();
		$list_arr['BLANK'] = "";
		$i = 1;
		while(1){
			$allItems = $dataService->FindAll('class', $i, 500);
			if( !$allItems || (0==count($allItems)) ){break;}
			foreach($allItems as $oneItem){
				$i++;
				$list_arr[$oneItem->Id] = $oneItem->Name;
			}
		}
		self::saveDDList($list_arr, 'qbo_class_list');
		echo 'seems is OK';
		//class--end

		//department--begin
		echo '<br/>Department...';
		$list_arr = array();
		$list_arr['BLANK'] = "";
		$i = 1;
		while(1){
			$allItems = $dataService->FindAll('department', $i, 500);
			if( !$allItems || (0==count($allItems)) ){break;}
			foreach($allItems as $oneItem){
				$i++;
				$list_arr[$oneItem->Id] = $oneItem->Name;//OR FullyQualifiedName
			}
		}
		self::saveDDList($list_arr, 'qbo_department_list');
		echo 'seems is OK';
		//department--end

		//term--begin
		echo '<br/>Terms...';
		$list_arr = array();
		$list_arr['BLANK'] = "";
		$i = 1;
		while(1){
			$allItems = $dataService->FindAll('term', $i, 500);
			if( !$allItems || (0==count($allItems)) ){break;}
			foreach($allItems as $oneItem){
				$i++;
				$list_arr[$oneItem->Id] = $oneItem->Name;//OR FullyQualifiedName
			}
		}
		self::saveDDList($list_arr, 'qbo_term_list');	
		echo 'seems is OK';
		//term--end
		echo '<br/>Done!<br/>';
	}
	static public function saveDDList($list_arr, $dropdown_name, $is_push = false, $lang_key = 'en_us'){
		$success = false;
		if( !empty($list_arr) && !empty($dropdown_name) && !empty($lang_key) ){
			$params['dropdown_name'] = $dropdown_name;//crm list id
			$params['dropdown_lang'] = $lang_key;
			$params['use_push'] = ($is_push)?1:0;//if true will add to the end
			$i = 0;
			foreach($list_arr as $k => $v){
				$params['slot_'.$i] = $i;
				$params['key_'.$i] = $k;
				$params['value_'.$i] = $v;
				$i++;
			}
			require_once("modules/Studio/DropDowns/DropDownHelper.php");
			$dd = new DropDownHelper();
			$dd->saveDropDown($params);
			$success = true;
		}
		return $success;
	}	
}
?>