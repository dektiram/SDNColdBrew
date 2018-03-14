<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfCtlLinkList {
	var $CTLID='TdbfCtlLinkList';
	var $TDBFPARAM;
	var $CI;
	var $thisInParent;
	var $TDBFModel;
	var $DB;
	var $ExtIncludeFile=array(
								'js'=>array('myjs/TdbfFunctionCollection.js','myjs/TdbfCtlLinkList.js'),
								'css'=>array('mycss/TdbfCtlLinkList.css')
							);

	public function init(&$pCtlObj,&$thisInParent,$pUserInfo){
		//$this->CI =& get_instance();
		$this->CI =$pCtlObj;
		$pStrJSON=file_get_contents($this->CI->config->item('data_model_path').'LinkList.tdbf');
		$this->TDBFPARAM=json_decode($pStrJSON,true);
		$this->TDBFPARAM['CTLID']=$this->CTLID;
		$this->TDBFPARAM['USERINFO']=$pUserInfo;
		
		$explodedUri = explode('/',uri_string());
		$this->TDBFPARAM['CTLURI']=base_url().$explodedUri[0].'/';
		$this->CI->load->model('TdbfModel',$this->CTLID.'_tdbfModel',true);
		eval('$this->TDBFModel=$this->CI->'.$this->CTLID.'_tdbfModel;');
		if(isset($_POST['tdbfPageParam'])){
			$pPagePostParam=$_POST['tdbfPageParam'];
		}else{
			$pPagePostParam=null;
		}
		if(sizeof($_FILES)>0){
			if(isset($_FILES['tdbfPageFile'])){
				$pPagePostFile=$_FILES['tdbfPageFile'];
			}else{
				$pPagePostFile=null;
			}
		}else{
			$pPagePostFile=null;
		}
		$this->DB=$this->CI->load->database($this->TDBFPARAM['databaseConfigName'],true);
		$this->TDBFModel->init($this,$pPagePostParam,$pPagePostFile);
		
		//Assign $this->ExtIncludeFile to $this->TDBFPARAM
		$this->TDBFPARAM['ExtIncludeFile']=$this->ExtIncludeFile;
	}
	public function test(){
		//return $this->CI->myTdbfModel1->contoh();
		$this->CI->MyTdbfModel1->contoh();
	}
	public function display($pSection='',&$pMainPageLoadMode){
		switch ($pSection) {
			case 'view':$this->CI->TdbfSystem->checkAllowedPrivilegeAction('lihat_app_user_account'); break;
			case 'exportExcel':$this->CI->TdbfSystem->checkAllowedPrivilegeAction('lihat_app_user_account'); break;
			case '':$this->CI->TdbfSystem->checkAllowedPrivilegeAction('lihat_app_user_account'); break;
		}
		$pStrHTML='';
		//$pStrHTML.=current_url().'<br />';
		//$pStrHTML.=uri_string().'<br />';
		//$pStrHTML.=site_url(0).'<br />';
		//$pStrHTML.=site_url(1).'<br />';
		//$pStrHTML.=site_url(2).'<br />';
		//$pStrHTML.=$this->TDBFPARAM['URI'].'<br />';
		if(isset($_POST['tdbfPageParam'])){
			//$pStrHTML.=json_encode($this->TDBFModel->VARTESTING).'<br />';
		}
		//if(isset($this->TDBFPARAM['fields'][99])){$pStrHTML.='ADAAAA'; }
		//print '<pre>';print_r($this->TDBFPARAM);print '</pre>';
		return $this->TDBFModel->display($pSection,$pMainPageLoadMode);
	}
	
	//Optional Callback Function
	public function onRecView($pDtRec,$pFieldName){
		$pStrColView='';
		switch ($pFieldName) {
			default:
				$pStrColView = $pDtRec[$pFieldName];
				break;
		}
		return $pStrColView;
	}
	public function onExtendedColView($pDtRec,$pColName){
		$pStrColView='';
		switch($pColName){
			case 'src_info' or 'dst_info':
				if($pColName == 'src_info'){
					$hwAddr = $pDtRec['src_hw_addr'];
					$hwType = $pDtRec['src_type'];
				}else{
					$hwAddr = $pDtRec['dst_hw_addr'];
					$hwType = $pDtRec['dst_type'];
				}
				if($hwType == 'HOST'){
					$strHostname = '<code>'.$this->TDBFModel->tdbfGet1x1Result('select `hostname` from t_host where hw_addr="'.$hwAddr.'"').'</code>';
					$x1 = $this->TDBFModel->tdbfGetnxnResult('select * from t_host_ipv4 where hw_addr="'.$hwAddr.'" order by `index`', TRUE);
					$strIpv4 = '';
					foreach ($x1 as $key => $value) {
						$strIpv4 .= '<code>'.$value['addr'].'/'.$value['prefix'].' gw '.$value['gateway'].'</code><br />';
					}
					$x1 = $this->TDBFModel->tdbfGetnxnResult('select * from t_host_ipv6 where hw_addr="'.$hwAddr.'" order by `index`', TRUE);
					$strIpv6 = '';
					foreach ($x1 as $key => $value) {
						$strIpv6 .= '<code>'.$value['addr'].'/'.$value['prefix'].' gw '.$value['gateway'].'</code><br />';
					}
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Hostname</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">'.$strHostname.'</div>';
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">IPV4</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">'.$strIpv4.'</div>';
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">IPV6</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">'.$strIpv6.'</div>';
				}else{
					$x1 = $this->TDBFModel->tdbfGet1xnResult('select * from t_switch_port where hw_addr="'.$hwAddr.'"', TRUE);
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">DPID</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><code>'.$x1['dpid'].'</code></div>';
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Name</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><code>'.$x1['name'].'</code></div>';
					$pStrColView .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">PortNo.</div>';
					$pStrColView .= '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><code>'.$x1['port_no'].'</code></div>';
				}
				
				break;
		}
		return $pStrColView;
	}
	public function onEditIconClick($pDtRec,&$pAccept){
		$s1='';
		return $s1;
	}
	public function onDeleteIconClick($pDtRec,&$pAccept){
		$s1='';
		$pAccept=false;
		return $s1;
	}
	public function onAddButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onSaveButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onUpdateButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onCancelButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onViewButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onImportExcelButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onExportExcelButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onDeleteExcelButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onSelectButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onSelectAllButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function onDeselectAllButtonClick(&$pAccept){
		$s1='';
		return $s1;
	}
	public function getDataRecordPreSelectionStatus($pDtRec,$pSelectParentFormId,$pSelectSessionCode){
		$pSelectSta=false;
		return $pSelectSta;
	}
	public function beforeInsert(&$pDtRec,&$pAccept,&$pMessage){
		$this->CI->TdbfSystem->checkAllowedPrivilegeAction('tambah_app_user_account');
		$pDtRec['password']=md5($pDtRec['password']);
		$pAccept=true;
	}
	public function afterInsert(&$pDtRec,&$pMessage){
		
	}
	public function beforeUpdate(&$pDtRecRef,&$pDtRec,&$pAccept,&$pMessage){
		$this->CI->TdbfSystem->checkAllowedPrivilegeAction('edit_app_user_account');
		$pDtRec['password']=md5($pDtRec['password']);
		$pAccept=true;
	}
	public function afterUpdate(&$pDtRecRef,&$pDtRec,&$pMessage){
		
	}
	public function beforeDelete(&$pDtRec,&$pAccept,&$pMessage){
		$this->CI->TdbfSystem->checkAllowedPrivilegeAction('hapus_app_user_account');
		//print_r($pDtRec);
		if(($pDtRec['id']=='superuser') or ($pDtRec['id']=='superadmin')){
			$pAccept=false;
			$pMessage['warning']='This user account cannot deleted.';
		}
	}
	public function afterDelete(&$pDtRec,&$pMessage){
		
	}
	public function onImportExcel($pDtImport){
		
	}
	public function onExportExcel(){
		
	}
	public function onSelectData(){
		
	}
}