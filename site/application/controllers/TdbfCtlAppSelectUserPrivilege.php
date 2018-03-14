<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfCtlAppSelectUserPrivilege {
	var $CTLID='TdbfCtlAppSelectUserPrivilege';
	var $TDBFPARAM;
	var $CI;
	var $thisInParent;
	var $TDBFModel;
	var $DB;
	var $ExtIncludeFile=array(
								'js'=>array('myjs/TdbfCtlAppPrivilege.js'),
								'css'=>array('mycss/TdbfCtlAppPrivilege.css')
							);

	public function init(&$pCtlObj,&$thisInParent,$pUserInfo){
		//$this->CI =& get_instance();
		$this->CI =$pCtlObj;
		$pStrJSON=file_get_contents($this->CI->config->item('data_model_path').'AppPrivilege.tdbf');
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
		return $this->TDBFModel->display($pSection,$pMainPageLoadMode);
	}
	
	//Optional Callback Function
	public function onRecView($pDtRec,$pFieldName){
		return $pDtRec[$pFieldName];
	}
	public function onExtendedColView($pDtRec,$pColName){
		$pStrColView='';
		switch($pColName){
			case 'extCol2':
				$pFormUrl=$this->TDBFPARAM['CTLURI'];
				$s1=$this->TDBFPARAM['CTLID'].'.myOpenSelectForm('.chr(39).$pFormUrl.chr(39).');';
				$pStrColView='<span class="glyphicon glyphicon-tags myPointerCursor" onclick="'.$s1.'"></span>';
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
		//print $pSelectParentFormId.','.$pSelectSessionCode.'<br />';
		$pExtractSelectSessionCode=explode('/', $pSelectSessionCode);
		switch($pSelectParentFormId.'/'.$pExtractSelectSessionCode[0]){
			case 'TdbfCtlAppUserAccount/sessAppUserPrivilege':
				$pStrSql='select count(*) from tbl_user_privilege where `user_account_id`="'.$pExtractSelectSessionCode[1].'" and `privilege_id`="'.$pDtRec['id'].'"';
				$pCount=$this->TDBFModel->tdbfGet1x1Result($pStrSql);
				if($pCount>0){
					$pSelectSta=true;
				}
				break;
		}
		return $pSelectSta;
	}
	public function beforeInsert(&$pDtRec,&$pAccept,&$pMessage){
		$pAccept=true;
	}
	public function afterInsert(&$pDtRec,&$pMessage){
		
	}
	public function beforeUpdate(&$pDtRecRef,&$pDtRec,&$pAccept,&$pMessage){
		
	}
	public function afterUpdate(&$pDtRecRef,&$pDtRec,&$pMessage){
		
	}
	public function beforeDelete(&$pDtRec,&$pAccept,&$pMessage){
		//print_r($pDtRec);
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