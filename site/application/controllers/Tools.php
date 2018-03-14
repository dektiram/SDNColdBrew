<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	
	private function mininetCmd($cmd){
		//$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$mininetUtilPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_path"');
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$xPostParam = array(
			'mininetUtilPath' => $mininetUtilPath,
			'mininetCmd' => $cmd
		);
		$url = $internalServiceUrl.'mininetCmd/'.$this->TdbfSystem->json2HexStr($xPostParam);
		$content = $this->myCurl($url, array());
		//print $content;
    	return json_decode($content, TRUE);
	}
	
	private function myCurl($url, $postData, $formatReply = 'text', $timeout = 60){
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
	
	public function mininetCommandProcessList(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$url = $internalServiceUrl.'mininetCmdProcessList';
		$x1 = $this->myCurl($url, array(),'json');
		if((array_key_exists('data', $x1)) and ($x1['responseCode']==200)){
			$x2 = $x1['data'];
		}else{
			$x2 = array();
		}
		//print_r($x1);
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['mininetCommandProcessList'] = $x2;
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/tools/MininetCommandProcessList',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
	}
	
	public function mininetKillCommandProcessList($reqType = 'function', $pidsHexEncode){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(120);
			$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
			//$pidsHexEncode = $this->TdbfSystem->json2HexStr(array(
			//	'pids'=>array(15366)
			//));
			$url = $internalServiceUrl.'mininetKillCmdProcess/'.$pidsHexEncode;
			//print $url;
			$x1 = $this->myCurl($url, array(),'json');
			$dtReply['data'] = $x1;			
			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		switch ($reqType) {
			case 'raw':
				print json_encode($dtReply);
				break;
			case 'json':
				header('Content-Type: application/json');
				print json_encode($dtReply);
				break;
		}
    	return $dtReply;
	}
	
	public function mininetCliSync(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/tools/MininetCliSync',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
	}
	
	public function mininetCliSyncExec($reqType = 'function', $cmdHexEncode){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(60);
			$cmd = $this->TdbfSystem->hex2Str($cmdHexEncode);
			$dtReply['data'] = $this->mininetCmd($cmd);			
			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		switch ($reqType) {
			case 'raw':
				print json_encode($dtReply);
				break;
			case 'json':
				header('Content-Type: application/json');
				print json_encode($dtReply);
				break;
		}
    	return $dtReply;
	}
	
	
}
