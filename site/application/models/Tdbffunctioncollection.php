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
	
	public function angkaTerbilang($x){
  		$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		if ($x < 12)
			return " " . $abil[$x];
		elseif ($x < 20)
			return $this->angkaTerbilang($x - 10) . "belas";
		elseif ($x < 100)
			return $this->angkaTerbilang($x / 10) . " puluh" . $this->angkaTerbilang($x % 10);
		elseif ($x < 200)
			return " seratus" . $this->angkaTerbilang($x - 100);
		elseif ($x < 1000)
			return $this->angkaTerbilang($x / 100) . " ratus" . $this->angkaTerbilang($x % 100);
		elseif ($x < 2000)
			return " seribu" . $this->angkaTerbilang($x - 1000);
		elseif ($x < 1000000)
			return $this->angkaTerbilang($x / 1000) . " ribu" . $this->angkaTerbilang($x % 1000);
		elseif ($x < 1000000000)
			return $this->angkaTerbilang($x / 1000000) . " juta" . $this->angkaTerbilang($x % 1000000);
	}
	public function integerToRoman($integer){
	 // Convert the integer into an integer (just to make sure)
	 $integer = intval($integer);
	 $result = '';
	 
	 // Create a lookup array that contains all of the Roman numerals.
	 $lookup = array('M' => 1000,
	 'CM' => 900,
	 'D' => 500,
	 'CD' => 400,
	 'C' => 100,
	 'XC' => 90,
	 'L' => 50,
	 'XL' => 40,
	 'X' => 10,
	 'IX' => 9,
	 'V' => 5,
	 'IV' => 4,
	 'I' => 1);
	 
	 foreach($lookup as $roman => $value){
	  // Determine the number of matches
	  $matches = intval($integer/$value);
	 
	  // Add the same number of characters to the string
	  $result .= str_repeat($roman,$matches);
	 
	  // Set the integer to be the remainder of the integer and the value
	  $integer = $integer % $value;
	 }
	 
	 // The Roman numeral should be built, return it
	 return $result;
	}
	
	public function getMininetHostnameList(){
		//$mininetUtilDir = $this->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_dir"');
		$hostList = array();
		$cmd = 'ps ax | grep "mininet\:h" | grep bash';
		$x1 = trim(shell_exec($cmd));
		$x2 = explode(chr(10), $x1);
		foreach ($x2 as $value) {
			$x3 = explode(':', trim($value));
			$hostname = trim($x3[sizeof($x3)-1]);
			if($hostname != ''){
				array_push($hostList,$x3[sizeof($x3)-1]);
			}
		}
		return $hostList;
	}
}