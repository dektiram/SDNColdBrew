<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfRequestLocalOnly extends CI_Controller {
	var $FUNCCOLL;
	
	public function __construct(){
		parent::__construct(); 
		$this->init();
    }
	public function init(){
		if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
			header('HTTP/1.0 403 Forbidden');
			echo 'You are forbidden!';
			exit; //just for good measure
		}
		$this->load->model('TdbfFunctionCollection','FUNCCOLL1',true);
		$this->FUNCCOLL=$this->FUNCCOLL1;
		
	}
	public function getCurrentUserDropdownOuletList($pJsonHexStrUserInfo){
		$pUserInfo = $this->FUNCCOLL->hexStr2Json($pJsonHexStrUserInfo);
		//print_r($pUserInfo);
		$pStrSql1 = 'select `id`,`nama` from `t_outlet`';
		$s1 = '';
		foreach ($pUserInfo['outletId'] as $key => $value) {
			$s1 .= '`id`="'.$value.'" or ';
		}
		if($s1 != ''){
			$s1 = substr($s1, 0, strlen($s1)-4);
			$pStrSql1 .= ' where ('.$s1.') and (is_enable=1)';
			//print $pStrSql1;
			$pAr1 = $this->FUNCCOLL->tdbfGetnxnResult($pStrSql1);
			$pAr2 = array();
			foreach ($pAr1 as $key => $value) {
				$pAr2[$value[0]] = $value[1];
			}
			print json_encode($pAr2);
		}else{
			print json_encode(array());
		}
	}
}