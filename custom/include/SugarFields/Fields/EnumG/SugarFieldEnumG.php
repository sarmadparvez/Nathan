<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldEnumG extends SugarFieldBase {
   
	function getGroupedDD($type = 'EditView', $parentFieldArray, $vardef, $displayParams, $tabindex){
		
		//echo '<pre>';
		//print_r($vardef);
		//print_r($displayParams);
		//echo '</pre>';
		
		$field_name = $vardef['name'];
		$field_id = $vardef['name'];
		
		$_size = '';
		//if($displayParams['size']){
		//	$_size = ' size = "'.$displayParams['size'].'" ';
		//}		

		
		$multiple = '';
		if($type == 'Search'){
			$multiple = ' multiple="1" ';
			$field_name .= '[]';
			$_size = ' size = "10" ';
		}

		$html = '<select name="'.$field_name.'" id="'.$field_id.'" '.$multiple.' '.$_size.' style="width: 200px !important;" >';
		
		$value = $vardef['value'];
		
		//print_r($value);
		
		$is_open = false;
		if(!empty($vardef['options'])){
			
			foreach($vardef['options'] as $item_key => $item_val){
			
				$pos = strpos($item_key, 'GR_B');
				if($pos !== false){
					if($is_open){$html .= '</optgroup>';}//close previous group

					$html .= '<optgroup label="'.$item_val.'">';
					$is_open = true;
					continue;
				}

				$pos = strpos($item_key, 'GR_E');
				if($pos !== false){
					if($is_open){//only if already opened
						$html .= '</optgroup>';
						$is_open = false;
					}
					continue;
				}

				$selected = '';
				if(is_array($value)){
					if(in_array($item_key, $value)){
						$selected = ' selected="selected" ';
					}				
				}else{
					if(strcmp($value, $item_key) == 0){
						$selected = ' selected="selected" ';
					}
				}
				$html .= '<option '.$selected.' value="'.$item_key.'">'.$item_val.'</option>';
				
			}
		}
		
		$html .= '</select>';
		
		return $html;
	}
    
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
			
    	if(empty($displayParams['size'])) {
		   $displayParams['size'] = 6;
		}
    	
    	if(isset($vardef['function']) && !empty($vardef['function']['returns']) && $vardef['function']['returns']== 'html'){
    		  $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        	  return $this->fetch($this->findTemplate('EditViewFunction'));
    	}else{
			return $this->getGroupedDD('EditView', $parentFieldArray, $vardef, $displayParams, $tabindex);
    		//return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    	}
    }

	function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex){
		
		if(empty($displayParams['size'])) {
			$displayParams['size'] = 6;
		}
		
    	if(!empty($vardef['function']['returns']) && $vardef['function']['returns']== 'html'){
			$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
			return $this->fetch($this->findTemplate('EditViewFunction'));
    	}else{
			return $this->getGroupedDD('Search', $parentFieldArray, $vardef, $displayParams, $tabindex);
			//$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
			//return $this->fetch($this->findTemplate('SearchView'));
    	}
    }

   
   
   
   
   
   
   
	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		if(!empty($vardef['function']['returns']) && $vardef['function']['returns']== 'html')
		{
    		  $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        	  return "<span id='{$vardef['name']}'>" . $this->fetch($this->findTemplate('DetailViewFunction')) . "</span>";
    	} else {
    		  return parent::getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    	}
    }
    
 

    function displayFromFunc( $displayType, $parentFieldArray, $vardef, $displayParams, $tabindex ) {
        if ( isset($vardef['function']['returns']) && $vardef['function']['returns'] == 'html' ) {
            return parent::displayFromFunc($displayType, $parentFieldArray, $vardef, $displayParams, $tabindex);
        }

        $displayTypeFunc = 'get'.$displayType.'Smarty';
        return $this->$displayTypeFunc($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
    
    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        global $app_list_strings;
        
        // Bug 27467 - Trim the value given
        $value = trim($value);
        
        if ( isset($app_list_strings[$vardef['options']]) 
                && !isset($app_list_strings[$vardef['options']][$value]) ) {
            // Bug 23485/23198 - Check to see if the value passed matches the display value
            if ( in_array($value,$app_list_strings[$vardef['options']]) )
                $value = array_search($value,$app_list_strings[$vardef['options']]);
            // Bug 33328 - Check for a matching key in a different case
            elseif ( in_array(strtolower($value), array_keys(array_change_key_case($app_list_strings[$vardef['options']]))) ) {
                foreach ( $app_list_strings[$vardef['options']] as $optionkey => $optionvalue )
                    if ( strtolower($value) == strtolower($optionkey) )
                        $value = $optionkey;
            }
            // Bug 33328 - Check for a matching value in a different case
            elseif ( in_array(strtolower($value), array_map('strtolower', $app_list_strings[$vardef['options']])) ) {
                foreach ( $app_list_strings[$vardef['options']] as $optionkey => $optionvalue )
                    if ( strtolower($value) == strtolower($optionvalue) )
                        $value = $optionkey;
            }
            else
                return false;
        }
        
        return $value;
    }
    
	public function formatField($rawField, $vardef){
		global $app_list_strings;
		
		if(!empty($vardef['options'])){
			$option_array_name = $vardef['options'];
			
			if(!empty($app_list_strings[$option_array_name][$rawField])){
				return $app_list_strings[$option_array_name][$rawField];
			}else {
				return $rawField;
			}
		} else {
			return $rawField;
		}
    }
}
?>