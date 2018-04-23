<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfSystem extends CI_Model {
	var $DB;
	var $USERINFO;
	public function __construct(){
		parent::__construct(); 
		$this->init();
    }
	public function init(){
		$this->DB=$this->load->database('tdbfAdmin',true);
		$this->USERINFO=array();
		$this->USERINFO['loggedIn']=$this->isUserLoggedIn();
	}
	public function isUserLoggedIn(){
		$pLoggedIn=false;
		if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
			$pLoggedIn = true;
			$pSessionParam['loginUsername']=$_SERVER['REMOTE_ADDR'];
			$pSessionParam['loginPassword']='';
			$this->USERINFO['loginUsername']=$_SERVER['REMOTE_ADDR'];
			$this->USERINFO['loginPassword']='';
			$this->session->set_userdata('TDBF',$pSessionParam);
			$this->getUserAttribute();
		}else{
			if($this->session->has_userdata('TDBF')){
				$pSessionParam=$this->session->userdata('TDBF');
				if(array_key_exists('loginUsername',$pSessionParam)){
					$pUsername=$pSessionParam['loginUsername'];
					$pPassword=$pSessionParam['loginPassword'];
					if($this->userAuth($pUsername,$pPassword)){
						$pSessionParam['loginUsername']=$pUsername;
						$pSessionParam['loginPassword']=$pPassword;
						$this->USERINFO['loginUsername']=$pUsername;
						$this->USERINFO['loginPassword']=$pPassword;
						$this->session->set_userdata('TDBF',$pSessionParam);
						$this->getUserAttribute();
						$pLoggedIn=true;
					}
				}
			}
		}
		return $pLoggedIn;
	}
	public function ifNotLoginRedirectToLoginPage(){
		if(!$this->USERINFO['loggedIn']){
			header('Location: '.base_url().'Home/login');
			exit();
		}
	}
	public function loginAction(){
		if(isset($_POST['TDBF'])){
			$pArPageParam=$this->hexStr2Json($_POST['TDBF']);
			if(isset($pArPageParam['userCommand'])){
				switch($pArPageParam['userCommand']){
					case 'login':
						$pDtJSON=array();
						$pDtJSON['loginResult']=false;
						$pUsername=$pArPageParam['customParam']['username'];
						$pPassword=$pArPageParam['customParam']['password'];
						if($this->userAuth($pUsername,$pPassword)){
							if($this->isUserEnable($pUsername)){
								//UPdate login session
								if($this->session->has_userdata('TDBF')){
									$pSessionParam=$this->session->userdata('TDBF');
									$pSessionParam['loginUsername']=$pUsername;
									$pSessionParam['loginPassword']=$pPassword;
								}else{
									$pSessionParam=array();
									$pSessionParam['loginUsername']=$pUsername;
									$pSessionParam['loginPassword']=$pPassword;
								}
								$this->session->set_userdata('TDBF',$pSessionParam);
								$pDtJSON['loginResult']=true;
							}else{
								$pDtJSON['loginDesc']='Your account has been disabled.';
							}
						}else{
							$pDtJSON['loginDesc']='Invalid username or password.';
						}
						print $this->json2HexStr($pDtJSON);
						break;
				}
			}
		}
	}
	public function logoutAction(){
		if($this->session->has_userdata('TDBF')){
			$pSessionParam=$this->session->userdata('TDBF');
			if(array_key_exists('loginUsername',$pSessionParam)){
				unset($pSessionParam['loginUsername']);
				unset($pSessionParam['loginPassword']);
				$this->session->set_userdata('TDBF',$pSessionParam);
			}
		}
	}
	public function userAuth($pUsername,$pPassword){
		$pSta=false;
		$pStrSql='select count(*) from tbl_user_account where id="'.$pUsername.'" and password="'.md5($pPassword).'"';
		$pFlag=$this->tdbfGet1x1Result($pStrSql);
		if($pFlag==1){
			$pSta=true;
		}
		return $pSta;
	}
	public function isUserEnable($pUsername){
		$pSta=false;
		$pStrSql='select count(*) from tbl_user_account where id="'.$pUsername.'" and enable=1';
		$pFlag=$this->tdbfGet1x1Result($pStrSql);
		if($pFlag==1){
			$pSta=true;
		}
		return $pSta;
	}
	public function getUserAttribute(){
		$pStrSql1='select `name` from tbl_user_account where id="'.$this->USERINFO['loginUsername'].'"';
		$this->USERINFO['displayName']=$this->tdbfGet1x1Result($pStrSql1);
		if(file_exists('assets/myimages/userImage/'.$this->USERINFO['loginUsername'].'.jpg')){
			$this->USERINFO['userImage']='assets/myimages/userImage/'.$this->USERINFO['loginUsername'].'.jpg';
		}else{
			$this->USERINFO['userImage']='assets/myimages/userImage/none.jpg';
		}
		$this->USERINFO['privileges']=array();
		$pStrSql2='select privilege_id from tbl_user_privilege where `user_account_id`="'.$this->USERINFO['loginUsername'].'" and `enable`=1';
		$pDt1=$this->tdbfGetnx1Result($pStrSql2);
		$this->USERINFO['privileges']=$pDt1;
	}
	/*
	public function checkAllowedPrivilege($pArAllowedPriv){
		$pResult=false;
		//print_r($pArAllowedPriv);
		//print '<br/>';
		//print_r($this->USERINFO['privileges']);
		$pAr1=array_intersect_assoc($pArAllowedPriv,$this->USERINFO['privileges']);
		if(sizeof($pAr1)>0){$pResult=true;}
		if(in_array('superuser',$this->USERINFO['privileges'])){$pResult=true;}
		if(in_array('anonymous',$pArAllowedPriv)){$pResult=true;}
		if(!$pResult){
			header('Location: '.base_url().'Home/restricted');
			exit();
		}
	}
	 */
	public function checkAllowedPrivilegeAction($pActionId, $userRedirect = true){
		$pResult=false;
		//print_r($this->USERINFO);
		if($this->USERINFO['loginUsername'] == 'superuser'){
			$pResult=true;
			return true;
		}
		if($this->USERINFO['loginUsername'] == 'superadmin'){
			$pResult=true;
			return true;
		}
		if(($pActionId=='anonymous')or($pActionId=='')){
			$pResult=true;
			return true;
		}
		$strSQL = 'select `privilege_id` from `tbl_user_privilege` where 
					`user_account_id`="'.$this->USERINFO['loginUsername'].'" and 
					`enable`=1';
		$arPrivilege = $this->tdbfGetnx1Result($strSQL);
		//print_r($arPrivilege);exit;
		foreach ($arPrivilege as $idx1 => $privilegeId) {
			$strSQL = 'select count(*) from tbl_privilege_action where 
						privilege_id="'.$privilegeId.'" and 
						app_action_id="'.$pActionId.'"';
			//print $strSQL;exit;
			if($this->tdbfGet1x1Result($strSQL)>0){
				$pResult=true;
				return true;
				break;
			}
		}
		
		if(!$pResult){
			if($userRedirect){
				header('Location: '.base_url().'Home/restricted');
				exit();
			}
		}
		return $pResult;
	}
	public function displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pTDBFPARAM){
		$pArMainPageLoadMode=array('full','empty','cssAndJs');
		if(!in_array($pMainPageLoadMode,$pArMainPageLoadMode)){$pMainPageLoadMode='full';}
		switch($pMainPageLoadMode){
			case 'full':
				$pHTMLMyHtmlMeta=$this->load->view('MyHtmlMeta',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pHTMLMyLeftBar=$this->load->view('MyLeftBar',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pHTMLMyNavBar=$this->load->view('MyNavBar',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pHTMLMyContentHeader=$this->load->view('MyContentHeader',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pHTMLMyRightBar=$this->load->view('MyRightBar',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pPageHTML=$this->load->view('MyMainPage',
										array(
											'pPageLoadMode'=>$pMainPageLoadMode,
											'pHTMLMyHtmlMeta'=>$pHTMLMyHtmlMeta,
											'pHTMLMyLeftBar'=>$pHTMLMyLeftBar,
											'pHTMLMyNavBar'=>$pHTMLMyNavBar,
											'pHTMLMyContentHeader'=>$pHTMLMyContentHeader,
											'pHTMLMyContent'=>$pHTMLMyContent,
											'pHTMLMyRightBar'=>$pHTMLMyRightBar
										),
										true);
				break;
			case 'empty':
				$pPageHTML=$pHTMLMyContent;
				break;
			case 'cssAndJs':
				$pHTMLMyHtmlMeta=$this->load->view('MyHtmlMeta',array('TDBFPARAM'=>$pTDBFPARAM),true);
				$pHTMLMyLeftBar='';
				$pHTMLMyNavBar='';
				$pHTMLMyContentHeader='';
				$pHTMLMyRightBar='';
				$pPageHTML=$this->load->view('MyMainPage',
										array(
											'pPageLoadMode'=>$pMainPageLoadMode,
											'pHTMLMyHtmlMeta'=>$pHTMLMyHtmlMeta,
											'pHTMLMyLeftBar'=>$pHTMLMyLeftBar,
											'pHTMLMyNavBar'=>$pHTMLMyNavBar,
											'pHTMLMyContentHeader'=>$pHTMLMyContentHeader,
											'pHTMLMyContent'=>$pHTMLMyContent,
											'pHTMLMyRightBar'=>$pHTMLMyRightBar
										),
										true);
				break;
		}
		print $pPageHTML;
		//print $this->MyTdbfCtl1->test();
		//print_r($this->TdbfFunction->tdbfGetnx1Result('select fdate from tbl1'));
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
	function tdbfExecSQL($pStrSQL){
		//print $pStrSQL;
		$query = $this->DB->query($pStrSQL);
		return $query;
	}
	function tdbfGet1x1Result($pStrSQL){
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
	function tdbfGet1xnResult($pStrSQL, $modeFieldName=false){
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
	function tdbfGetnx1Result($pStrSQL){
		$query = $this->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			$pDtRow=array_values($row);	
			$pAr1[]=$pDtRow[0];	
		}
		$query->free_result();
		return $pAr1;
	}
	function tdbfGetnxnResult($pStrSQL, $modeFieldName=false){
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