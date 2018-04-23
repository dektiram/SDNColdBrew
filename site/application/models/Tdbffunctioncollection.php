<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfFunctionCollection extends CI_Model {
	var $DB;
	var $USERINFO;
	
	public function __construct(){
		parent::__construct(); 
		$this->DB=$this->load->database('default',true);
		
		$this->load->model('TdbfSystem','MODEL_SYSTEM_1',true);
		$this->USERINFO=$this->MODEL_SYSTEM_1->USERINFO;
		//print_r($this->USERINFO);
    }
	public function checkRecordCountCompareLisence($tableName){
		if(!$GLOBALS['dataLisence']['lisenceStatus']){
			$recordLimit = $this->config->item('lisence_record_count_limit');
			$strSQL = 'select count(*) from `'.$tableName.'`';
			$count = $this->tdbfGet1x1Result($strSQL);
			
			if($count >= $recordLimit){
				return FALSE;
			}else{
				return TRUE;
			}
		}else{
			return TRUE;
		}
	}
	public function tdbChangeDbConn($pDB){
		$this->DB=$pDB;
	}
	public function hex2Str($pHexStr){
		$pStr='';
		for ($i=0; $i < strlen($pHexStr)-1; $i+=2){
			$pStr .= chr(hexdec($pHexStr[$i].$pHexStr[$i+1]));
		}
		return $pStr;
	}
	public function str2Hex($pStr){
		$pHexStr='';
		for ($i=0; $i < strlen($pStr); $i++){
			$s1=dechex(ord($pStr[$i]));
			$pHexStr.=$s1;
		}
		return $pHexStr;
	}
    public function hexStr2Json($pHexStr){
		$pStrJSON='';
		for ($i=0; $i < strlen($pHexStr)-1; $i+=2){
			$pStrJSON .= chr(hexdec($pHexStr[$i].$pHexStr[$i+1]));
		}
		$pDtJSON=json_decode($pStrJSON, true);
		return $pDtJSON;
	}
	public function json2HexStr($pDtJSON){
		$pHexStr='';
		$s1=json_encode($pDtJSON);
		for ($i=0; $i < strlen($s1); $i++){
			$s2=dechex(ord($s1[$i]));
			$pHexStr.=$s2;
		}
		return $pHexStr;
	}
    function tdbfMySQLRealEscapeString($pString){
		//print $pStrSQL;
		$query = $this->DB->escape_str($pString);
		return $query;
	}
    public function tdbfExecSQL($pStrSQL){
    	$query = $this->DB->query($pStrSQL);
		return $query;
	}
	public function tdbfGet1x1Result($pStrSQL){
		$pVal=null;
		$query = $this->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			$pDtRow=array_values($row);	
			$pVal=$pDtRow[0];	
			break;
		}
		$query->free_result();
		return $pVal;
	}
	public function tdbfGet1xnResult($pStrSQL, $modeFieldName=false){
		//print $pStrSQL;
		$query = $this->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			if($modeFieldName){
				$pAr1=$row;
			}else{
				$pAr1=array_values($row);	
			}
			break;
		}
		$query->free_result();
		return $pAr1;
	}
	public function tdbfGetnx1Result($pStrSQL){
		$query = $this->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			$pDtRow=array_values($row);	
			$pAr1[]=$pDtRow[0];	
		}
		$query->free_result();
		return $pAr1;
	}
	public function tdbfGetnxnResult($pStrSQL, $modeFieldName=false){
		//print $pStrSQL;
		$query = $this->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			if($modeFieldName){
				$pAr1[]=$row;
			}else{
				$pAr1[]=array_values($row);
			}
		}
		$query->free_result();
		return $pAr1;
	}

	////FUNGSI TAMBAHAN UNTUK APLIKASI
	
	function myCurl($url, $postData, $formatReply = 'text', $timeout = 60){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch,CURLOPT_TIMEOUT, $timeout);
		if(sizeof($postData)>0){
			foreach($postData as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			curl_setopt($ch,CURLOPT_POST, count($postData));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		}
	    $content  = curl_exec($ch);
	    curl_close($ch);
		//print $content;
		if($formatReply == 'json'){
			return json_decode($content, TRUE);
		}else{
			return $content;
		}
	}
}