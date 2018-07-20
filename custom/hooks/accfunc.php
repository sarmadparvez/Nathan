<?php

//account_code_c
//acode_base_c
//acode_sequence_c

function massGenAccCode(){
	global $db;
	$output = array();
	
	$i = 0;
	$sql = " SELECT a.id, a.name 
	FROM accounts as a
	LEFT JOIN accounts_cstm as cc ON a.id = cc.id_c
	WHERE a.deleted = 0 
	AND cc.account_code_c = ''
	ORDER BY a.date_entered ASC; ";
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
	
		$code = genAccCode($row['name']);
		$d = updAccCode($row['id'], $row['name'], $code['base'], $code['n']);
		
		$output[$row['id']] = array('name'=>$row['name'],'base'=>$code['base'],'n'=>$code['n'],);
	}
	
	return $output;
}

function updAccCode($account_id, $account_name, $base, $n){
	global $db;
	
	$account_id = $db->quote($account_id);
	$base = $db->quote($base);
	$n = $db->quote($n);
	
	$code = $base.$n;
	
	$sql = " UPDATE accounts_cstm SET account_code_c = '{$code}', acode_base_c = '$base', acode_sequence_c = '{$n}' WHERE id_c = '{$account_id}'; ";
	$db->query($sql);
	
	return 1;
}

function cleanAccCode($account_id){
	//global $db;
	//$account_id = $db->quote($account_id);
	//$sql = " UPDATE accounts_cstm SET account_code_c = '', acode_base_c = '', acode_sequence_c = '' WHERE id_c = '{$account_id}'; ";
	//$db->query($sql);
	
	return 1;
}
function genAccCode($account_name, $as_array = true){
	$account_name = empty($account_name)?'EMPTY':trim($account_name);
	$code = getBaseName($account_name);
	$sn = getBaseNameSequence($code);
	return ($as_array)?array('base'=>$code, 'n'=>$sn):($code.$sn);
}

function getBaseName($account_name){
	$limit = 5;
	$filler = 0;
	//$code = str_replace(array(" ", "_", "-", "/", "'", '"', "&", "*", "!", "#", "$", "@", "."), "", $account_name);
	$code = preg_replace("/[^[:alpha:]]/", '', $account_name);
	$code = substr($code, 0, $limit);
	$code = strtoupper($code);
	$code = fillToLength($code, $limit, $filler);
	
	return $code;
}

function getBaseNameSequence($base){
	global $db;
	$limit = 4;
	$filler = 0;
	$n = 0;
	//echo ' base-- '.
	$base = $db->quote($base);
	//echo '<br/>';
	//echo 
	$sql = " SELECT MAX(acode_sequence_c) as n FROM accounts_cstm WHERE acode_base_c = '{$base}'; ";
	//echo '<br/>';
	$res = $db->query($sql);
	$row = $db->fetchByAssoc($res);
	if(!empty($row['n'])){
		//echo ' sql-'.
		$n = $row['n'];
	}
	//echo ' inc-'.
	$n++;
	
	$sn = fillToLength($n, $limit, $filler,false);
	
	return $sn;
}

function fillToLength($str, $target_length, $filler, $to_end = true){
	$length = strlen($str);
	$i = $target_length - $length;
	if( $i > 0){
		while($i){
			if($to_end){
				$str .= $filler;
			}else{
				$str = $filler.$str;
			}
			$i--;
		}
	}
	return $str;
}