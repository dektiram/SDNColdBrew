<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfModel extends CI_Model {
	var $TDBFCTL;
	var $PAGEPOSTPARAM;
	var $PAGEPOSTFILE;
	var $VARTESTING;
	var $FUNCCOLL;
	var $USERINFO;
	public function __construct(){
		//$this->load->helper('tdbf');
		//$this->load->view('TdbfView',$this->TDBFPARAM);
    }
	public function testing(){
		return $this->VARTESTING;
	}
	public function init(&$pTdbfCtl,$pTdbfPagePostParam,$pTdbfPagePostFile){		
		$this->load->model('TdbfFunctionCollection','FUNCCOLL1',true);
		$this->FUNCCOLL=$this->FUNCCOLL1;
		
		$this->TDBFCTL=$pTdbfCtl;
		$pArPageParam=$this->hexStr2Json($pTdbfPagePostParam);
		//print '<pre>';
		//print_r($pArPageParam);
		//print '</pre>';
		if(isset($pArPageParam['externalParam'])){
			//print '<pre>';
			//print_r($pArPageParam['externalParam']);
			//print '</pre>';
		}
		
		//check externalParam => used if window as select form
		if(isset($pArPageParam['externalParam'])){
			if (!array_key_exists($this->TDBFCTL->CTLID,$pArPageParam)){
				$pArPageParam[$this->TDBFCTL->CTLID]=array();
			}
			$pArPageParam[$this->TDBFCTL->CTLID]=array_merge($pArPageParam['externalParam'],$pArPageParam[$this->TDBFCTL->CTLID]);
		}
		//print '<pre>';
		//print_r($pArPageParam[$this->TDBFCTL->CTLID]);
		//print '</pre>';
		
		$this->PAGEPOSTPARAM=$pArPageParam;
		$this->PAGEPOSTFILE=$pTdbfPagePostFile;
		$this->TDBFCTL->TDBFPARAM['flashParam']=array();
		$this->TDBFCTL->TDBFPARAM['flashParam']['success']=array();
		$this->TDBFCTL->TDBFPARAM['flashParam']['info']=array();
		$this->TDBFCTL->TDBFPARAM['flashParam']['warning']=array();
		$this->TDBFCTL->TDBFPARAM['flashParam']['danger']=array();
		if (!array_key_exists('customParam',$this->TDBFCTL->TDBFPARAM)){
			$this->TDBFCTL->TDBFPARAM['customParam']=array();
		}
		if (!array_key_exists('viewPref',$this->TDBFCTL->TDBFPARAM['customParam'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']=array();
		}
		if (!array_key_exists('viewFilter',$this->TDBFCTL->TDBFPARAM['customParam'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewFilter']=array();
		}
		
		//$this->TDBFCTL->CI->session->unset_userdata($this->TDBFCTL->CTLID);//buat testing klo error
		//Check POST page param for user command utk eksekusi di awal
			//print_r($pArPageParam[$this->TDBFCTL->CTLID]);
		$pStaUserCommandResetViewPref=false;
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
			foreach($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'] as $pUserCommand){
				switch($pUserCommand){
					case 'resetViewPref':
						$pStaUserCommandResetViewPref=true;
						if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
							$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
						}else{
							$pSessionParam=array();
						}
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						if(!array_key_exists('viewPref',$pSessionParam['customParam'])){$pSessionParam['customParam']['viewPref']=array();}
						unset($pSessionParam['customParam']['viewPref']);
						$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
						break;
					case 'resetFilter':
						//$pStaUserCommandResetViewPref=true;
						if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
							$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
						}else{
							$pSessionParam=array();
						}
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						unset($pSessionParam['customParam']['viewFilter']);
						$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
						break;
				}
				
			}
		}
		
		//GET From SAVED USER CUSTOM (file on server)
		if(!$pStaUserCommandResetViewPref){
			$pUsername=$this->TDBFCTL->TDBFPARAM['USERINFO']['loginUsername'];
			$pFileName=$pUsername.'.'.$this->TDBFCTL->CTLID.'.viewPref';
			$pFullPathFile=$this->TDBFCTL->CI->config->item('data_model_path').'../myuser_saved_param/'.$pFileName;
			if(file_exists($pFullPathFile)){
				$pStrJSON=file_get_contents($pFullPathFile);
				$pDtJSON=json_decode($pStrJSON,true);
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']=$pDtJSON;
			}
		}
		
		//Get SESSION DATA
		//print_r($this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID));
		//print_r($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']);
		if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
			$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
			if (array_key_exists('customParam',$pSessionParam)){
				//check SESSION view pref
				if (array_key_exists('viewPref',$pSessionParam['customParam'])){
					//check SESSION record pre page
					if (array_key_exists('recordPerPage',$pSessionParam['customParam']['viewPref'])){
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['recordPerPage']=$pSessionParam['customParam']['viewPref']['recordPerPage'];
					}
					//check SESSION order by
					if (array_key_exists('orderBy',$pSessionParam['customParam']['viewPref'])){
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderBy']=$pSessionParam['customParam']['viewPref']['orderBy'];
					}
					//check SESSION order mode
					if (array_key_exists('orderMode',$pSessionParam['customParam']['viewPref'])){
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderMode']=$pSessionParam['customParam']['viewPref']['orderMode'];
					}
					//check SESSION page index
					if (array_key_exists('pageIndex',$pSessionParam['customParam']['viewPref'])){
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['pageIndex']=$pSessionParam['customParam']['viewPref']['pageIndex'];
					}
					//check SESSION search keyword
					if (array_key_exists('searchKeyword',$pSessionParam['customParam']['viewPref'])){
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['searchKeyword']=$pSessionParam['customParam']['viewPref']['searchKeyword'];
					}
					//check SESSION record col pref
					if (array_key_exists('colPref',$pSessionParam['customParam']['viewPref'])){
						//print 'MASUK SET FROM SESSION';
						$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']=$pSessionParam['customParam']['viewPref']['colPref'];
					}
				}
				//check SESSION view filter
				if (array_key_exists('viewFilter',$pSessionParam['customParam'])){
					$this->TDBFCTL->TDBFPARAM['customParam']['viewFilter']=$pSessionParam['customParam']['viewFilter'];
				}
				//check SESSION sectionMode
				if (array_key_exists('sectionMode',$pSessionParam['customParam'])){
					$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=$pSessionParam['customParam']['sectionMode'];
					//print $this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
				}
				//check SESSION editRecordKeyCode
				if (array_key_exists('editRecordKeyCode',$pSessionParam['customParam'])){
					$this->TDBFCTL->TDBFPARAM['customParam']['editRecordKeyCode']=$pSessionParam['customParam']['editRecordKeyCode'];
				}
				//check SESSION selectParentFormId
				if (array_key_exists('selectParentFormId',$pSessionParam['customParam'])){
					$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId']=$pSessionParam['customParam']['selectParentFormId'];
				}
				//check SESSION selectSessionCode
				if (array_key_exists('selectSessionCode',$pSessionParam['customParam'])){
					$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode']=$pSessionParam['customParam']['selectSessionCode'];
				}
			}			
		}
		
		//Check POST page param for sectionMode
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['sectionMode'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['sectionMode'];
		}
		//Check POST page param for editRecordKeyCode
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['editRecordKeyCode'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['editRecordKeyCode']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['editRecordKeyCode'];
		}
		//Check POST page param for selectParentFormId
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['selectParentFormId'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['selectParentFormId'];
			if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
				$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
			}else{
				$pSessionParam=array();
			}
			if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
			$pSessionParam['customParam']['selectParentFormId']=$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'];
			$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
		}
		//Check POST page param for selectSessionCode
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['selectSessionCode'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['selectSessionCode'];
			if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
				$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
			}else{
				$pSessionParam=array();
			}
			if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
			$pSessionParam['customParam']['selectSessionCode']=$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'];
			$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
			//print '<pre>';
			//print_r($pArPageParam);
			//print '</pre>';
		}
		//Check POST page param for page index
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['pageIndex'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['pageIndex']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['pageIndex'];
		}
		//Check POST page param for order by 
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['orderBy'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderBy']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['orderBy'];
		}
		//Check POST page param for order mode
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['orderMode'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderMode']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['orderMode'];
		}
		//Check POST page param for record per page 
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['recordPerPage'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['recordPerPage']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['recordPerPage'];
		}
		//Check POST page param for search keyword
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['searchKeyword'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['searchKeyword']=trim($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['searchKeyword']);
		}
		//Check POST page param for columns preference
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['colPref'])){
			//print 'MASUK';
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewPref']['colPref'];
		}
		//Check POST page param for filter
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewFilter'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewFilter']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['viewFilter'];
		}
		//Check POST page param for importExcelUpdateIfExist
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['customParam']['importExcelUpdateIfExist'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['importExcelUpdateIfExist']=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['importExcelUpdateIfExist'];
		}
		
		
		//IF no custom, load sectionMode from default model
		if (!array_key_exists('sectionMode',$this->TDBFCTL->TDBFPARAM['customParam'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['otherParam']['defaultSection'];
		}
		//corrected sectionMode
		$pArSectionMode=array('view','add','edit','templateExcel','importExcel','exportExcel','deleteExcel','select','selectAllRecord','deselectAllRecord');
		if(!in_array($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'],$pArSectionMode)){$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['otherParam']['defaultSection'];}
		
		//IF no custom, load page index from default model
		if (!array_key_exists('pageIndex',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['pageIndex']=1;
		}
		//IF no custom, load order by from default model
		if (!array_key_exists('orderBy',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderBy']=$this->TDBFCTL->TDBFPARAM['otherParam']['defaultOrderBy'];
		}

		//IF no custom, load order mode from default model
		if (!array_key_exists('orderMode',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['orderMode']=$this->TDBFCTL->TDBFPARAM['otherParam']['defaultOrderMode'];
		}

		//IF no custom, load record per page from default model
		if (!array_key_exists('recordPerPage',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['recordPerPage']=$this->TDBFCTL->TDBFPARAM['otherParam']['defaultRecordPerPage'];
		}
		
		//IF no custom, set searchKeyword to default
		if (!array_key_exists('searchKeyword',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['searchKeyword']='';
		}
		
		//IF no custom, load col pref from default model
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
			if(in_array('exportExcel',$pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
				unset($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']);
			}
			if(in_array('selectAllRecord',$pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
				unset($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']);
			}
			if(in_array('deselectAllRecord',$pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
				unset($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']);
			}
		}
		if (!array_key_exists('colPref',$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'])){
			//print 'RESET DEFAULT';
			$pAr1=array();
			$pAr1['edit']=$this->TDBFCTL->TDBFPARAM['otherParam']['editColPos'];
			$pAr1['delete']=$this->TDBFCTL->TDBFPARAM['otherParam']['deleteColPos'];
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				if($this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['sqlSelect']){
					$s1=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
					$j=$this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['colPos'];
					$pAr1[$s1]=$j;
				}
			}
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['extCols']);$i++){
				$s1=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['name'];
				$j=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['colPos'];
				$pAr1[$s1]=$j;
			}
			asort($pAr1);
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']=array();
			$i=0;
			foreach($pAr1 as $key => $val) {
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]=array();
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name']=$key;
				$pColAttr=$this->getColInfo($key);
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['caption']=$pColAttr['caption'];
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['visible']=$pColAttr['visible'];
				$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['exportExcel']=$pColAttr['exportExcel'];				
				$i++;
			}
		}
		
		//Check POST page param for user command utk eksekusi di akhir
			//print_r($pArPageParam[$this->TDBFCTL->CTLID]);
		if(isset($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){
			foreach($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'] as $pUserCommand){
				switch($pUserCommand){
					case 'changeSectionMode':
						//print 'MASUK';
						if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
							$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
						}else{
							$pSessionParam=array();
						}
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						$pSessionParam['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						if(isset($this->TDBFCTL->TDBFPARAM['customParam']['editRecordKeyCode'])){
							$pSessionParam['customParam']['editRecordKeyCode']=$this->TDBFCTL->TDBFPARAM['customParam']['editRecordKeyCode'];
						}
						$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
						break;
					case 'applyViewPref':
						//print 'MASUK';
						if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
							$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
						}else{
							$pSessionParam=array();
						}
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						if(!array_key_exists('viewPref',$pSessionParam['customParam'])){$pSessionParam['customParam']['viewPref']=array();}
						$pSessionParam['customParam']['viewPref']=$this->TDBFCTL->TDBFPARAM['customParam']['viewPref'];
						$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
						break;
					case 'saveViewPref':
						//print 'MASUK';
						$pUsername=$this->TDBFCTL->TDBFPARAM['USERINFO']['loginUsername'];
						$pFileName=$pUsername.'.'.$this->TDBFCTL->CTLID.'.viewPref';
						$pStrJSON=json_encode($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']);
						file_put_contents($this->TDBFCTL->CI->config->item('data_model_path').'../myuser_saved_param/'.$pFileName,$pStrJSON);
						break;
					case 'applyFilter':
						//print 'MASUK';
						if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
							$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
						}else{
							$pSessionParam=array();
						}
						if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
						$pSessionParam['customParam']['viewFilter']=$this->TDBFCTL->TDBFPARAM['customParam']['viewFilter'];
						$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
						break;
					case 'importExcel':
						$pFileTmpName=$this->PAGEPOSTFILE['tmp_name'][$this->TDBFCTL->CTLID]['importExcel'];
						$pFileRealName=$this->PAGEPOSTFILE['name'][$this->TDBFCTL->CTLID]['importExcel'];
						$pFileSavedPath='temp_files/';
						$pFileSavedName=$pFileSavedPath.date('Y-m-d-H-i-s').$this->TDBFCTL->TDBFPARAM['USERINFO']['loginUsername'].$pFileRealName;
						move_uploaded_file($pFileTmpName,$pFileSavedName);
						if(array_key_exists('importExcelUpdateIfExist',$this->TDBFCTL->TDBFPARAM['customParam'])){
							$pUpdateIfExist=$this->TDBFCTL->TDBFPARAM['customParam']['importExcelUpdateIfExist'];
						}else{
							$pUpdateIfExist=false;
						}
						$pArResult=$this->prosesImportExcel($pFileSavedName,$pUpdateIfExist);
						if(!array_key_exists('customParam',$this->TDBFCTL->TDBFPARAM)){$this->TDBFCTL->TDBFPARAM['customParam']=array();}
						$this->TDBFCTL->TDBFPARAM['customParam']['importExcelResult']=$pArResult;
						break;
					case 'deleteExcel':
						$pFileTmpName=$this->PAGEPOSTFILE['tmp_name'][$this->TDBFCTL->CTLID]['deleteExcel'];
						$pFileRealName=$this->PAGEPOSTFILE['name'][$this->TDBFCTL->CTLID]['deleteExcel'];
						$pFileSavedPath='temp_files/';
						$pFileSavedName=$pFileSavedPath.date('Y-m-d-H-i-s').$this->TDBFCTL->TDBFPARAM['USERINFO']['loginUsername'].$pFileRealName;
						move_uploaded_file($pFileTmpName,$pFileSavedName);
						$pArResult=$this->prosesDeleteExcel($pFileSavedName);
						if(!array_key_exists('customParam',$this->TDBFCTL->TDBFPARAM)){$this->TDBFCTL->TDBFPARAM['customParam']=array();}
						$this->TDBFCTL->TDBFPARAM['customParam']['deleteExcelResult']=$pArResult;
						break;
				}
				
			}
		}
		
		//adding col info to ['customParam']['viewPref']['colPref']
		for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref']);$i++){
			$pColInfo=$this->getColInfo($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name']);
			$pUserCustomVisible=$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['visible'];
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]=array_merge($this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i],$pColInfo);
			$this->TDBFCTL->TDBFPARAM['customParam']['viewPref']['colPref'][$i]['visible']=$pUserCustomVisible;
		}

		//adding atribut tambahan untuk mempermudah
		$this->TDBFCTL->TDBFPARAM['customParam']['keyFields']=array();
		for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
			if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
				array_push($this->TDBFCTL->TDBFPARAM['customParam']['keyFields'],$this->TDBFCTL->TDBFPARAM['fields'][$i]['name']);
			}
		}
		//set flash param
		$this->TDBFCTL->CI->session->set_flashdata($this->TDBFCTL->CTLID.'.flashData',$this->TDBFCTL->TDBFPARAM['flashParam']);
		
		//Change section mode session to view if in_array('templateExcel','importExcel','exportExcel','deleteExcel')
		$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
		//print '<pre>';
		//print_r($pSessionParam);
		//print '</pre>';
		//print $pSessionParam['customParam']['sectionMode'];exit;
		if(in_array($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'],array('templateExcel','importExcel','exportExcel','deleteExcel'))){
			$pSessionParam['customParam']['sectionMode']='view';
		}else{
			$pSessionParam['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
		}
		$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
		
		//Change section mode session to select if in_array('selectAllRecord')
		$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
		if(in_array($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'],array('selectAllRecord'))){
			$pSessionParam['customParam']['sectionMode']='select';
		}elseif(in_array($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'],array('deselectAllRecord'))){
			$pSessionParam['customParam']['sectionMode']='select';
		}else{
			//$pSessionParam['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
		}
		$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
		
		//check insert update delete process
		$this->checkInserUpdateDeleteCommand($pArPageParam);
	}
	public function myValidasiInputForm($pFormMode,$pDtRecInputForm){
		//print_r($pArPageParam);
		$pStaReturn=true;
		for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
			$pIsNotNull=false;
			if($pFormMode=='add'){
				$pIsNotNull=$this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['notNull'];
			}else{
				$pIsNotNull=$this->TDBFCTL->TDBFPARAM['fields'][$i]['updateForm']['notNull'];
			}
			//print $pIsNotNull;
			if($pIsNotNull){
				$s1=trim($pDtRecInputForm[$this->TDBFCTL->TDBFPARAM['fields'][$i]['name']]);
				if($s1==''){
					//print '===================='.$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'].'====================';
					$pStaReturn=false;
				}
			}
		}
		//print $pStaReturn;
		return $pStaReturn;
	}
	public function checkInserUpdateDeleteCommand($pArPageParam){
		//print_r($pArPageParam);
		if(!isset($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'])){return;}
		foreach($pArPageParam[$this->TDBFCTL->CTLID]['userCommand'] as $pUserCommand){
			switch($pUserCommand){
				case 'insertRecord':
					$pDtRec=$pArPageParam[$this->TDBFCTL->CTLID]['inputForm'];
					$pMessage=array();
					$pStaInsertRecord=$this->prosesInsertRecord($pDtRec,$pMessage);
					$this->TDBFCTL->TDBFPARAM['flashParam']=array_merge_recursive($this->TDBFCTL->TDBFPARAM['flashParam'],$pMessage);
					break;
				case 'updateRecord':
					$pDtRecRef=$pArPageParam[$this->TDBFCTL->CTLID]['inputFormRef'];
					$pDtRecUpdate=$pArPageParam[$this->TDBFCTL->CTLID]['inputForm'];
					$pMessage=array();
					$pStaUpdateRecord=$this->prosesUpdateRecord($pDtRecRef,$pDtRecUpdate,$pMessage);
					$this->TDBFCTL->TDBFPARAM['flashParam']=array_merge_recursive($this->TDBFCTL->TDBFPARAM['flashParam'],$pMessage);
					break;
				case 'deleteRecord':
					if(!array_key_exists('customParam',$pArPageParam[$this->TDBFCTL->CTLID])){
						array_push($this->TDBFCTL->TDBFPARAM['flashParam']['warning'],'Delete : error parameter.');
						break;
					}
					if(!array_key_exists('deleteKeyCode',$pArPageParam[$this->TDBFCTL->CTLID]['customParam'])){
						array_push($this->TDBFCTL->TDBFPARAM['flashParam']['warning'],'Delete : error parameter.');
						$this->TDBFCTL->CI->session->set_flashdata($this->TDBFCTL->CTLID.'.flashData',$this->TDBFCTL->TDBFPARAM['flashParam']);	
					}
					$pDeleteCode=$pArPageParam[$this->TDBFCTL->CTLID]['customParam']['deleteKeyCode'];
					$pDeleteKeyRecord=$this->hexStr2Json($pDeleteCode);
					//print_r($pDeleteKeyRecord);
					$pDeletedRecord=$this->getSingleRecordModel($pDeleteKeyRecord);
					
					$pMessage=array();
					$pStaDeleteRecord=$this->prosesDeleteRecord($pDeletedRecord,$pMessage);
					$this->TDBFCTL->TDBFPARAM['flashParam']=array_merge_recursive($this->TDBFCTL->TDBFPARAM['flashParam'],$pMessage);
					break;
			}
		}
	}
	public function prosesInsertRecord($pDtRec,&$pMessage,&$pDBErrorCode=null){
		$pInsertResult=false;
		$pMessage=array();
		$pMessage['info']=array();
		$pMessage['success']=array();
		$pMessage['warning']=array();
		$pMessage['danger']=array();
		if($this->myValidasiInputForm('add',$pDtRec)){
			$pAccept=true;
			$this->getLookupList();
			$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
			$this->TDBFCTL->beforeInsert($pDtRec,$pAccept,$pMessageTmp);
			$pMessage=array_merge($pMessage,$pMessageTmp);
			if($pAccept){
				$pStrSqlFields='';
				$pStrSqlValues='';
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['used']){						
						$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
						$pColInfo=$this->getColInfo($pFieldName);
						if($pColInfo['sqlQuote']){$pStrSqlQuote='"';}else{$pStrSqlQuote='';}
						$pStrSqlFields.='`'.$pFieldName.'`,';
						//print $this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
						$pXPostValue=$pDtRec[$pFieldName];
						if(is_array($pXPostValue)){
							$pPostValue='';
							foreach($pXPostValue as $s1){
								$pPostValue.=$s1.chr(10).chr(13);
							}
							if($pPostValue!=''){$pPostValue=substr($pPostValue,0,strlen($pPostValue)-2);}
						}else{
							$pPostValue=$pXPostValue;
						}
						// form input correction
						switch($this->TDBFCTL->TDBFPARAM['fields'][$i]['dataType']){
							case 'bigint':$pPostValue=intval($pPostValue);break;
							case 'bit':$pPostValue=intval($pPostValue);break;
							case 'boolean':$pPostValue=intval($pPostValue);break;
							case 'decimal':$pPostValue=floatval($pPostValue);break;
							case 'double':$pPostValue=floatval($pPostValue);break;
							case 'float':$pPostValue=floatval($pPostValue);break;
							case 'int':$pPostValue=intval($pPostValue);break;
						}
						$pStrSqlValues.=$pStrSqlQuote.$this->tdbfMySQLRealEscapeString($pPostValue).$pStrSqlQuote.',';
					
						//Lihat apabila isKey maka check lookuplist(jika field ada lookup nya)
						if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
							if(key_exists('lookupList', $this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm'])){
								//print_r($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']);
								//print '=='.$pDtRec[$pFieldName].'==';
								//print_r($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['lookupList']);
								//if(!key_exists($pDtRec[$pFieldName], $this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['lookupList'])){
								if(!isset($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['lookupList'][$pDtRec[$pFieldName]])){
									array_push($pMessage['warning'],'Field "'.$pFieldName.'" value ("'.$pDtRec[$pFieldName].'") is not in list.');
									return false;
								}
							}
						}
						
					}
				}
				if($pStrSqlFields!=''){$pStrSqlFields=substr($pStrSqlFields,0,strlen($pStrSqlFields)-1);}
				if($pStrSqlValues!=''){$pStrSqlValues=substr($pStrSqlValues,0,strlen($pStrSqlValues)-1);}
				$pStrSql='insert into '.$this->TDBFCTL->TDBFPARAM['tableName']['create'].' ('.$pStrSqlFields.') values ('.$pStrSqlValues.')';
				//print $pStrSql;
				//$pStrSql='haha'.$pStrSql;
				if($this->FUNCCOLL->checkRecordCountCompareLisence($this->TDBFCTL->TDBFPARAM['tableName']['create'])){
					$pQueryRes=$this->tdbfExecSQL($pStrSql);
					if($pQueryRes){
						array_push($pMessage['success'],'Insert success.');
						$pInsertResult=true;
						$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
						$this->TDBFCTL->afterInsert($pDtRec,$pMessageTmp);
						$pMessage=array_merge_recursive($pMessage,$pMessageTmp);
					}else{
						$dbErrorHdl = $this->TDBFCTL->DB->error();
						array_push($pMessage['danger'],'Insert fail. '.$dbErrorHdl['message']);
						if(isset($pDBErrorCode)){
							$pDBErrorCode=$dbErrorHdl['code'];
						}
					}
				}else{
					array_push($pMessage['warning'],'Record count limited by your lisence. Insert aborted.');
				}
			}else{
				array_push($pMessage['warning'],'Insert aborted.');
			}
		}else{
			array_push($pMessage['warning'],'Input form tidak lengkap.');
		}
		return $pInsertResult;
	}
	public function prosesUpdateRecord($pDtRecRef,$pDtRecUpdate,&$pMessage){
		$pUpdateResult=false;
		$this->getLookupList();
		$pMessage=array();
		$pMessage['info']=array();
		$pMessage['success']=array();
		$pMessage['warning']=array();
		$pMessage['danger']=array();
		if($this->myValidasiInputForm('update',$pDtRecUpdate)){
			$pAccept=true;
			$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
			$this->TDBFCTL->beforeUpdate($pDtRecRef,$pDtRecUpdate,$pAccept,$pMessageTmp);
			$pMessage=array_merge_recursive($pMessage,$pMessageTmp);
			if($pAccept){
				$pStrSqlUpdateSet='';
				$pStrSqlWhere='';
				$pStrSqlWhereAllFields='';
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['updateForm']['used']){
						$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
						//print $pFieldName;
						$pColInfo=$this->getColInfo($pFieldName);
						if($pColInfo['sqlQuote']){$pStrSqlQuote='"';}else{$pStrSqlQuote='';}
						//$pStrSqlFields.=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'].',';
						//print_r($pArPageParam[$this->TDBFCTL->CTLID]['inputForm'][$pFieldName]);
						$pPostValue=$pDtRecUpdate[$pFieldName];
						$pPostValueRef=$pDtRecRef[$pFieldName];
						
						$pXPostValue=$pDtRecUpdate[$pFieldName];
						if(is_array($pXPostValue)){
							$pPostValue='';
							foreach($pXPostValue as $s1){
								$pPostValue.=$s1.chr(10).chr(13);
							}
							if($pPostValue!=''){$pPostValue=substr($pPostValue,0,strlen($pPostValue)-2);}
						}else{
							$pPostValue=$pXPostValue;
						}
						
						$pXPostValueRef=$pDtRecUpdate[$pFieldName];
						if(is_array($pXPostValueRef)){
							$pPostValueRef='';
							foreach($pXPostValueRef as $s1){
								$pPostValueRef.=$s1.chr(10).chr(13);
							}
							if($pPostValueRef!=''){$pPostValueRef=substr($pPostValueRef,0,strlen($pPostValueRef)-2);}
						}else{
							$pPostValueRef=$pXPostValueRef;
						}
						
						// form input correction
						switch($this->TDBFCTL->TDBFPARAM['fields'][$i]['dataType']){
							case 'bigint':$pPostValue=intval($pPostValue);$pPostValueRef=intval($pPostValueRef);break;
							case 'bit':$pPostValue=intval($pPostValue);$pPostValueRef=intval($pPostValueRef);break;
							case 'boolean':$pPostValue=intval($pPostValue);$pPostValueRef=intval($pPostValueRef);break;
							case 'decimal':$pPostValue=floatval($pPostValue);$pPostValueRef=floatval($pPostValueRef);break;
							case 'double':$pPostValue=floatval($pPostValue);$pPostValueRef=floatval($pPostValueRef);break;
							case 'float':$pPostValue=floatval($pPostValue);$pPostValueRef=floatval($pPostValueRef);break;
							case 'int':$pPostValue=intval($pPostValue);$pPostValueRef=intval($pPostValueRef);break;
						}
						
						$pStrSqlUpdateSet.='`'.$pFieldName.'`='.$pStrSqlQuote.$this->tdbfMySQLRealEscapeString($pPostValue).$pStrSqlQuote.',';
						$pStrSqlWhereAllFields.='`'.$pFieldName.'`='.$pStrSqlQuote.$pPostValue.$pStrSqlQuote.' AND ';
						if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
							$pStrSqlWhere.='`'.$pFieldName.'`='.$pStrSqlQuote.$pPostValue.$pStrSqlQuote.' AND ';
						}
						//Lihat apabila isKey maka check lookuplist(jika field ada lookup nya)
						if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
							if(key_exists('lookupList', $this->TDBFCTL->TDBFPARAM['fields'][$i]['updateForm'])){
								if(!key_exists($pDtRecUpdate[$pFieldName], $this->TDBFCTL->TDBFPARAM['fields'][$i]['updateForm']['lookupList'])){
									array_push($pMessage['warning'],'Field "'.$pFieldName.'" value ("'.$pDtRecUpdate[$pFieldName].'") is not in list.');
									return false;
								}
							}
						}
					}
				}
				if($pStrSqlUpdateSet!=''){$pStrSqlUpdateSet=substr($pStrSqlUpdateSet,0,strlen($pStrSqlUpdateSet)-1);}
				if($pStrSqlWhere!=''){$pStrSqlWhere=substr($pStrSqlWhere,0,strlen($pStrSqlWhere)-5);}
				if($pStrSqlWhereAllFields!=''){$pStrSqlWhereAllFields=substr($pStrSqlWhereAllFields,0,strlen($pStrSqlWhereAllFields)-5);}
				if($pStrSqlWhere==''){$pStrSqlWhere=$pStrSqlWhereAllFields;}
				$pStrSql='update '.$this->TDBFCTL->TDBFPARAM['tableName']['update'].' set '.$pStrSqlUpdateSet.' where '.$pStrSqlWhere;
				//print $pStrSql;
				$pQueryRes=$this->tdbfExecSQL($pStrSql);
				if($pQueryRes){
					array_push($pMessage['success'],'Update success.');
					$pUpdateResult=true;
					$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
					$this->TDBFCTL->afterUpdate($pDtRecRef,$pDtRecUpdate,$pMessageTmp);
					$pMessage=array_merge_recursive($pMessage,$pMessageTmp);
				}else{
					$dbErrorHdl = $this->TDBFCTL->DB->error();
					array_push($pMessage['danger'],'Update fail. '.$dbErrorHdl['message']);
				}
			}else{
				array_push($pMessage['warning'],'Update aborted.');
			}
		}else{
			array_push($pMessage['warning'],'Input form tidak lengkap.');
		}
		return $pUpdateResult;
		
	}
	public function prosesDeleteRecord($pDtKeyRec,&$pMessage){
		$pDeleteResult=false;
		$this->getLookupList();
		$pMessage=array();
		$pMessage['info']=array();
		$pMessage['success']=array();
		$pMessage['warning']=array();
		$pMessage['danger']=array();
		$pDtRec=$this->getSingleRecordModel($pDtKeyRec);
		$pDeletedRecord=$pDtRec;
		if(sizeof($pDtRec)==0){
			array_push($pMessage['warning'],'Delete : data tidak ditemukan atau sudah terhapus sebelumnya.');
		}else{
			$pAccept=true;
			$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
			$this->TDBFCTL->beforeDelete($pDeletedRecord,$pAccept,$pMessageTmp);
			$pMessage=array_merge_recursive($pMessage,$pMessageTmp);
			if($pAccept){
				$pStrSqlWhere='';
				$pStrSqlWhereAllFields='';
				//print '<pre>';
				//print_r($this->TDBFCTL->TDBFPARAM['fields']);
				//print '</pre>';
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
					$pFieldValue=$pDtRec[$pFieldName];
					//print $pFieldName;
					$pColInfo=$this->getColInfo($pFieldName);
					if($pColInfo['sqlQuote']){$pStrSqlQuote='"';}else{$pStrSqlQuote='';}
					$pStrSqlWhereAllFields.='`'.$pFieldName.'`='.$pStrSqlQuote.$pFieldValue.$pStrSqlQuote.' AND ';
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
						$pStrSqlWhere.='`'.$pFieldName.'`='.$pStrSqlQuote.$pFieldValue.$pStrSqlQuote.' AND ';
					}
					
					//Lihat apabila isKey maka check lookuplist(jika field ada lookup nya)
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
						if(key_exists('lookupList', $this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable'])){
							if(!key_exists($pDtKeyRec[$pFieldName], $this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['lookupList'])){
								array_push($pMessage['warning'],'Field "'.$pFieldName.'" value ("'.$pDtKeyRec[$pFieldName].'") is not in list.');
								return false;
							}
						}
					}
				}
				if($pStrSqlWhere!=''){$pStrSqlWhere=substr($pStrSqlWhere,0,strlen($pStrSqlWhere)-5);}
				if($pStrSqlWhereAllFields!=''){$pStrSqlWhereAllFields=substr($pStrSqlWhereAllFields,0,strlen($pStrSqlWhereAllFields)-5);}
				if($pStrSqlWhere==''){$pStrSqlWhere=$pStrSqlWhereAllFields;}
				$pStrSql='delete from '.$this->TDBFCTL->TDBFPARAM['tableName']['delete'].' where '.$pStrSqlWhere;
				//print $pStrSql;
				$pQueryRes=$this->tdbfExecSQL($pStrSql);
				if($pQueryRes){
					array_push($pMessage['success'],'Delete success.');
					$pDeleteResult=true;
					$pMessageTmp=array('info'=>array(),'success'=>array(),'warning'=>array(),'danger'=>array());
					$this->TDBFCTL->afterDelete($pDeletedRecord,$pMessageTmp);
					$pMessage=array_merge_recursive($pMessage,$pMessageTmp);
				}else{
					$dbErrorHdl = $this->TDBFCTL->DB->error();
					array_push($pMessage['danger'],'Delete fail. '.$dbErrorHdl['message']);
				}
			}else{
				array_push($pMessage['warning'],'Delete aborted.');
			}
		}
		return $pDeleteResult;
	}
	public function prosesImportExcel($pExcelFile,$pUpdateIfExist){
		//print $pUpdateIfExist;
		$pArResult=array();
		array_push($pArResult,'File name : '.$this->PAGEPOSTFILE['name'][$this->TDBFCTL->CTLID]['importExcel']);
		array_push($pArResult,'Import result :');
		try{
			$this->TDBFCTL->CI->load->library('PHPExcel');
			$pFileType = PHPExcel_IOFactory::identify($pExcelFile);
			$pObjPHPExcelReader = PHPExcel_IOFactory::createReader($pFileType);
			$pObjPHPExcel = $pObjPHPExcelReader->load($pExcelFile);
			$pObjPHPExcelSheet=$pObjPHPExcel->setActiveSheetIndex(0);
			$pSuccessOpen=true;
		}catch (Exception $e){
			array_push($pArResult,'Fail to open uploaded file.');
			$pSuccessOpen=false;
		}
		
		if($pSuccessOpen){
			$pMaxDataRow=$pObjPHPExcelSheet->getHighestRow();
			for($pRow=6;$pRow<=$pMaxDataRow;$pRow++){
				$pDtRec=array();
				$pRecImportResult='';
				$pCol=1;
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['used']){
						$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
						if($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['visible']){
							$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pCol);
							$pExcelCellValue=$pObjPHPExcelSheet->getCell($pExcelColString.$pRow)->getValue();
							switch($this->TDBFCTL->TDBFPARAM['fields'][$i]['dataType']){
								case 'boolean':
									$pExcelCellValue=strtoupper($pExcelCellValue);
									if(in_array($pExcelCellValue,array('Y','YA','YES','TRUE'))){
										$pExcelCellValue='1';
									}else{
										$pExcelCellValue='0';
									}
									break;
							}
							if(trim($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['ctlObj'])=='list'){
								$pExcelCellValue=str_replace('',chr(13),$pExcelCellValue);
								$pAr1=explode(chr(10),$pExcelCellValue);
								$pExcelCellValue=$pAr1;
							}
							$pDtRec[$pFieldName]=$pExcelCellValue;
							$pCol++;
						}else{
							$pDtRec[$pFieldName]=$this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['defaultValue'];
						}
					}
				}
				$pInsertRecordMessage=array();
				//print_r($pDtRec);print 'TANDA AJA';exit();
				$pDBErrorCode=0;
				$pInsertRecordSta=$this->prosesInsertRecord($pDtRec,$pInsertRecordMessage,$pDBErrorCode);
				//print $pDBErrorCode;
				foreach($pInsertRecordMessage as $pInsertRecordMessage_Key => $pInsertRecordMessage_ArrayValue){
					foreach($pInsertRecordMessage_ArrayValue as $pInsertRecordMessage_Value){
						$pRecImportResult='Excel line '.$pRow.' ['.$pInsertRecordMessage_Key.'] : '.$pInsertRecordMessage_Value;
						array_push($pArResult,$pRecImportResult);
					}
				}
				if(($this->TDBFCTL->TDBFPARAM['features']['importExcelUpdateOnDuplicate']) and ($pUpdateIfExist) and ($pDBErrorCode==1062)){
					//$pDtRecRef=$this->getKeyArrayFromSingleRecordModel($pDtRec);
					$pDtRecRef=$pDtRec;
					$pUpdateRecordMessage=array();
					$pUpdateRecordSta=$this->prosesUpdateRecord($pDtRecRef,$pDtRec,$pUpdateRecordMessage);
					//print $pDBErrorCode;
					foreach($pUpdateRecordMessage as $pUpdateRecordMessage_Key => $pUpdateRecordMessage_ArrayValue){
						foreach($pUpdateRecordMessage_ArrayValue as $pUpdateRecordMessage_Value){
							$pRecImportResult='Excel line '.$pRow.' ['.$pUpdateRecordMessage_Key.'] : '.$pUpdateRecordMessage_Value;
							array_push($pArResult,$pRecImportResult);
						}
					}
				}
			}
		}
		//print_r($pArResult);
		return $pArResult;
	}
	public function prosesDeleteExcel($pExcelFile){
		//print $pExcelFile;
		$pArResult=array();
		array_push($pArResult,'File name : '.$this->PAGEPOSTFILE['name'][$this->TDBFCTL->CTLID]['deleteExcel']);
		array_push($pArResult,'Import result :');
		try{
			$this->TDBFCTL->CI->load->library('PHPExcel');
			$pFileType = PHPExcel_IOFactory::identify($pExcelFile);
			$pObjPHPExcelReader = PHPExcel_IOFactory::createReader($pFileType);
			$pObjPHPExcel = $pObjPHPExcelReader->load($pExcelFile);
			$pObjPHPExcelSheet=$pObjPHPExcel->setActiveSheetIndex(0);
			$pSuccessOpen=true;
		}catch (Exception $e){
			array_push($pArResult,'Fail to open uploaded file.');
			$pSuccessOpen=false;
		}
		
		if($pSuccessOpen){
			$pMaxDataRow=$pObjPHPExcelSheet->getHighestRow();
			for($pRow=6;$pRow<=$pMaxDataRow;$pRow++){
				$pDtRec=array();
				$pRecImportResult='';
				$pCol=1;
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['used']){
						$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
						if($this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['visible']){
							$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pCol);
							$pExcelCellValue=$pObjPHPExcelSheet->getCell($pExcelColString.$pRow)->getValue();
							switch($this->TDBFCTL->TDBFPARAM['fields'][$i]['dataType']){
								case 'boolean':
									$pExcelCellValue=strtoupper($pExcelCellValue);
									if(in_array($pExcelCellValue,array('Y','YA','YES','TRUE'))){
										$pExcelCellValue='1';
									}else{
										$pExcelCellValue='0';
									}
									break;
							}
							$pDtRec[$pFieldName]=$pExcelCellValue;
							$pCol++;
						}else{
							$pDtRec[$pFieldName]=$this->TDBFCTL->TDBFPARAM['fields'][$i]['createForm']['defaultValue'];
						}
					}
				}
				$pDeleteRecordMessage=array();
				//print_r($pDtRec);print 'TANDA AJA';exit();
				$pDeleteRecordSta=$this->prosesDeleteRecord($pDtRec,$pDeleteRecordMessage);
				foreach($pDeleteRecordMessage as $pDeleteRecordMessage_Key => $pDeleteRecordMessage_ArrayValue){
					foreach($pDeleteRecordMessage_ArrayValue as $pDeleteRecordMessage_Value){
						$pRecDeleteResult='Excel line '.$pRow.' ['.$pDeleteRecordMessage_Key.'] : '.$pDeleteRecordMessage_Value;
						array_push($pArResult,$pRecDeleteResult);
					}
				}
			}
		}
		//print_r($pArResult);
		return $pArResult;
	}
	public function clearSession(){
		$this->TDBFCTL->CI->session->unset_userdata($this->TDBFCTL->CTLID);
	}
	public function display($pSection='',&$pMainPageLoadMode){
		if($pSection!=''){
			//change sectionMode and save to SESSION
			$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=$pSection;
			if($this->TDBFCTL->CI->session->has_userdata($this->TDBFCTL->CTLID)){
				$pSessionParam=$this->TDBFCTL->CI->session->userdata($this->TDBFCTL->CTLID);
			}else{
				$pSessionParam=array();
			}
			if(!array_key_exists('customParam',$pSessionParam)){$pSessionParam['customParam']=array();}
			if(in_array($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'],array('templateExcel'))){
				$pSessionParam['customParam']['sectionMode']='view';
			}else{
				$pSessionParam['customParam']['sectionMode']=$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
			}
			$this->TDBFCTL->CI->session->set_userdata($this->TDBFCTL->CTLID,$pSessionParam);
		}
		$this->TDBFCTL->CI->session->set_flashdata($this->TDBFCTL->CTLID.'.flashData',$this->TDBFCTL->TDBFPARAM['flashParam']);
		$pStrHTML='';
		switch($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']){
			case 'view':
				$this->getLookupList();
				$this->getViewRecord();
				$this->getViewHTMLRecord($this->TDBFCTL->TDBFPARAM['viewRecord']);
				$this->TDBFCTL->TDBFPARAM['customParam']['addButtonHTML']=$this->getAddButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['importExcelButtonHTML']=$this->getImportExcelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['exportExcelButtonHTML']=$this->getExportExcelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['printButtonHTML']=$this->getPrintButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['deleteExcelButtonHTML']=$this->getDeleteExcelButtonHTML();
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfView',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				//print $pStrHTML;
				break;
			case 'add':
				$this->getLookupList();
				$this->TDBFCTL->TDBFPARAM['customParam']['saveButtonHTML']=$this->getSaveButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['cancelButtonHTML']=$this->getCancelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['viewButtonHTML']=$this->getViewButtonHTML();
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfEntry',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'edit':
				$pEditCode=$this->TDBFCTL->TDBFPARAM['customParam']['editRecordKeyCode'];
				$pEditKeyRecord=$this->hexStr2Json($pEditCode);
				$pEditedRecord=$this->getSingleRecordModel($pEditKeyRecord);
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					$this->TDBFCTL->TDBFPARAM['fields'][$i]['updateForm']['defaultValue']=$pEditedRecord[$this->TDBFCTL->TDBFPARAM['fields'][$i]['name']];
				}				
				//print_r($pEditedRecord);
				$this->getLookupList();
				$this->TDBFCTL->TDBFPARAM['customParam']['updateButtonHTML']=$this->getUpdateButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['cancelButtonHTML']=$this->getCancelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['viewButtonHTML']=$this->getViewButtonHTML();
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfEntry',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'templateExcel':
				//print 'masuk download template excel';
				$pMainPageLoadMode='empty';
				$this->TDBFCTL->CI->load->library('PHPExcel');
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfTemplateExcel',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'importExcel':
				$this->getLookupList();
				$pMainPageLoadMode='empty';
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfImportExcel',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'exportExcel':
				$this->getLookupList();
				$pMainPageLoadMode='empty';
				$this->TDBFCTL->CI->load->library('PHPExcel');
				$this->getViewRecord(true);
				$this->getViewHTMLRecord($this->TDBFCTL->TDBFPARAM['viewRecord']);
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfExportExcel',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'deleteExcel':
				$this->getLookupList();
				$pMainPageLoadMode='empty';
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfDeleteExcel',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				break;
			case 'select':
				$pMainPageLoadMode='cssAndJs';
				$this->getLookupList();
				$this->getViewRecord();
				$this->getViewHTMLRecord($this->TDBFCTL->TDBFPARAM['viewRecord']);
				$this->TDBFCTL->TDBFPARAM['customParam']['addButtonHTML']=$this->getAddButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['importExcelButtonHTML']=$this->getImportExcelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['exportExcelButtonHTML']=$this->getExportExcelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['deleteExcelButtonHTML']=$this->getDeleteExcelButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['selectButtonHTML']=$this->getSelectButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['selectAllButtonHTML']=$this->getSelectAllButtonHTML();
				$this->TDBFCTL->TDBFPARAM['customParam']['deselectAllButtonHTML']=$this->getDeselectAllButtonHTML();
				$pStrHTML=$this->TDBFCTL->CI->load->view('MyContentTdbfView',array('TDBFPARAM' => $this->TDBFCTL->TDBFPARAM),true);
				//print $pStrHTML;
				break;
			case 'selectAllRecord':
				$pMainPageLoadMode='empty';
				$this->getLookupList();
				$this->getViewRecord(true);
				$pArKeyDtRec=array();
				$pArKeyDtRec['selected']=array();
				$pArKeyDtRec['deselected']=array();
				foreach($this->TDBFCTL->TDBFPARAM['viewRecord'] as $pDtRec){
					$pKeyDtRec=$this->getKeyArrayFromSingleRecordModel($pDtRec);
					array_push($pArKeyDtRec['selected'],$pKeyDtRec);
				}
				$pDtSelectResult=array();
				$pDtSelectResult['selectResult']=$pArKeyDtRec;
				$pStrHTML=json_encode($pDtSelectResult);
				break;
			case 'deselectAllRecord':
				$pMainPageLoadMode='empty';
				$this->getLookupList();
				$this->getViewRecord(true);
				$pArKeyDtRec=array();
				$pArKeyDtRec['selected']=array();
				$pArKeyDtRec['deselected']=array();
				foreach($this->TDBFCTL->TDBFPARAM['viewRecord'] as $pDtRec){
					$pKeyDtRec=$this->getKeyArrayFromSingleRecordModel($pDtRec);
					array_push($pArKeyDtRec['deselected'],$pKeyDtRec);
				}
				$pDtSelectResult=array();
				$pDtSelectResult['selectResult']=$pArKeyDtRec;
				$pStrHTML=json_encode($pDtSelectResult);
				break;
		}
		return $pStrHTML;
		
	}
	public function getColInfo($pColName){
		$pArResult=array();
		if($pColName=='edit'){
			$pArResult['caption']='Edit';
			$pArResult['type']='actCol';
			$pArResult['visible']=$this->TDBFCTL->TDBFPARAM['features']['update'];
			$pArResult['exportExcel']=false;
		}elseif($pColName=='delete'){
			$pArResult['caption']='Delete';
			$pArResult['type']='actCol';
			$pArResult['visible']=$this->TDBFCTL->TDBFPARAM['features']['delete'];
			$pArResult['exportExcel']=false;
		}elseif($pColName=='chkSelect'){
			$pArResult['caption']='Select';
			$pArResult['type']='actCol';
			$pArResult['visible']=$this->TDBFCTL->TDBFPARAM['features']['select'];
			$pArResult['exportExcel']=false;
		}else{
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				$s1=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
				if($s1==$pColName){
					$pArResult['type']='fieldCol';
					$pArResult['colIndex']=$i;
					$pArResult['dataType']=$this->TDBFCTL->TDBFPARAM['fields'][$pArResult['colIndex']]['dataType'];
					switch($this->TDBFCTL->TDBFPARAM['fields'][$pArResult['colIndex']]['dataType']){
						case 'bigint':
							$pArResult['sqlQuote']=false;
							break;
						case 'bit':
							$pArResult['sqlQuote']=false;
							break;
						case 'blob':
							$pArResult['sqlQuote']=true;
							break;
						case 'boolean':
							$pArResult['sqlQuote']=false;
							break;
						case 'char':
							$pArResult['sqlQuote']=true;
							break;
						case 'date':
							$pArResult['sqlQuote']=true;
							break;
						case 'datetime':
							$pArResult['sqlQuote']=true;
							break;
						case 'decimal':
							$pArResult['sqlQuote']=false;
							break;
						case 'double':
							$pArResult['sqlQuote']=false;
							break;
						case 'float':
							$pArResult['sqlQuote']=false;
							break;
						case 'int':
							$pArResult['sqlQuote']=false;
							break;
						case 'time':
							$pArResult['sqlQuote']=true;
							break;
						case 'timestamp':
							$pArResult['sqlQuote']=true;
							break;
						case 'varchar':
							$pArResult['sqlQuote']=true;
							break;
					}
					$pArResult['caption']=$this->TDBFCTL->TDBFPARAM['fields'][$i]['caption'];
					$pArResult['visible']=$this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['visible'];
					$pArResult['exportExcel']=$this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['exportExcel'];
					$pArResult['listValue']=$this->TDBFCTL->TDBFPARAM['fields'][$i]['viewTable']['listValue'];
					break;
				}
			}
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['extCols']);$i++){
				$s1=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['name'];
				if($s1==$pColName){
					$pArResult['type']='extCol';
					$pArResult['colIndex']=$i;
					$pArResult['caption']=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['caption'];
					$pArResult['visible']=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['visible'];
					$pArResult['exportExcel']=$this->TDBFCTL->TDBFPARAM['extCols'][$i]['exportExcel'];
					break;
				}
			}
		}
		return $pArResult;
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
	public function getLookupList(){
		switch($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']){
			case 'view':
				$pStrFormKey='viewTable';
				break;
			case 'exportExcel':
				$pStrFormKey='viewTable';
				break;
			case 'importExcel':
				$pStrFormKey='createForm';
				break;
			case 'add':
				$pStrFormKey='createForm';
				break;
			case 'edit':
				$pStrFormKey='updateForm';
				break;
			case 'select':
				$pStrFormKey='viewTable';
				break;
			case 'selectAllRecord':
				$pStrFormKey='viewTable';
				break;
			case 'deselectAllRecord':
				$pStrFormKey='viewTable';
				break;
			default:
				$pStrFormKey=$this->TDBFCTL->TDBFPARAM['customParam']['sectionMode'];
				break;
		}
		for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
			if(trim($this->TDBFCTL->TDBFPARAM['fields'][$i][$pStrFormKey]['lookupValue'])!=''){
				$s1=trim($this->TDBFCTL->TDBFPARAM['fields'][$i][$pStrFormKey]['lookupValue']);
				//print $s1;
				$pAr1=explode(':',$s1);
				if(sizeof($pAr1)>=2){
					$s2=substr($s1,strlen($pAr1[0])+1);
					//print $s2;
					switch($pAr1[0]){
						case 'sql':
							$pAr2=$this->tdbfGetnxnResult($s2);
							$pAr3=array();
							foreach($pAr2 as $pAr4){
								$pAr3[$pAr4[0]]=$pAr4[1];
							}
							$this->TDBFCTL->TDBFPARAM['fields'][$i][$pStrFormKey]['lookupList']=$pAr3;
							break;
						case 'json':
							//print $s2;
							$this->TDBFCTL->TDBFPARAM['fields'][$i][$pStrFormKey]['lookupList']=json_decode($s2,true);
							break;
						case 'url-json':
							if(substr($s2, 0,1)== '/'){
								$s3 = $this->TDBFCTL->CI->config->item('base_url_local_no_port').substr($s2,1).'/'.
								$this->json2HexStr($this->TDBFCTL->TDBFPARAM['USERINFO']);
							}else{
								$s3 = $s2;
							}
							//print $s3;
							$s4 = file_get_contents($s3);
							//print $s4;
							$this->TDBFCTL->TDBFPARAM['fields'][$i][$pStrFormKey]['lookupList']=json_decode($s4,true);
							break;
					}
				}
			}
		}
	}
	public function getViewRecord($pAllRecord=false){
		//print $pAllRecord;
		$pStrSQL='select ';
		$pTDBFPARAM=$this->TDBFCTL->TDBFPARAM;
		for($i=0;$i<sizeof($pTDBFPARAM['fields']);$i++){
			if($pTDBFPARAM['fields'][$i]['viewTable']['sqlSelect']){
				$pStrSQL.='`'.$pTDBFPARAM['fields'][$i]['name'].'`';
				if($i+1<sizeof($pTDBFPARAM['fields'])){
					$pStrSQL.=',';
				}
				$pStrSQL.=' ';
			}
		}
		$pStrSQL.='from '.$pTDBFPARAM['tableName']['read'].' ';
		$pStrSQLFilter1Note='';
		$pStrSQLFilter1='';
		if(trim($pTDBFPARAM['otherParam']['preFilterSql']!='')){
			$pStrSQLFilter1=$pTDBFPARAM['otherParam']['preFilterSql'];
			$pStrSQLFilter1Note.=$pTDBFPARAM['otherParam']['preFilterNote'];
		}
		$pStrSQLFilter2='';
		$pStrSQLFilter2Note='';
		if(sizeof($pTDBFPARAM['customParam']['viewFilter'])>0){
			for($i=0;$i<sizeof($pTDBFPARAM['customParam']['viewFilter']);$i++){
				$pColInfo=$this->getColInfo($pTDBFPARAM['customParam']['viewFilter'][$i]['colName']);
				$pTDBFPARAM['customParam']['viewFilter'][$i]['colInfo']=$pColInfo;
				$pStrSQLFilter2.=$pTDBFPARAM['customParam']['viewFilter'][$i]['block1'].' ';
				$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['block1'].' ';
				$pStrSQLFilter2.='`'.$pTDBFPARAM['customParam']['viewFilter'][$i]['colName'].'` ';
				$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['colInfo']['caption'].' ';
				$pStrSQLFilter2.=str_replace('%','',$pTDBFPARAM['customParam']['viewFilter'][$i]['operand1']).' ';
				$pStrSQLFilter2Note.=str_replace('%','',$pTDBFPARAM['customParam']['viewFilter'][$i]['operand1']).' ';
				//print_r($pColInfo);
				if($pColInfo['sqlQuote']){
					$pStrSQLFilter2.='"';
					$pStrSQLFilter2Note.='"';
				}
				switch($pTDBFPARAM['customParam']['viewFilter'][$i]['operand1']){
					case '%LIKE':
						$pStrSQLFilter2.='%'.$pTDBFPARAM['customParam']['viewFilter'][$i]['value'];
						$pStrSQLFilter2Note.='%'.$pTDBFPARAM['customParam']['viewFilter'][$i]['value'];
						break;
					case 'LIKE%':
						$pStrSQLFilter2.=$pTDBFPARAM['customParam']['viewFilter'][$i]['value'].'%';
						$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['value'].'%';
						break;
					case '%LIKE%':
						$pStrSQLFilter2.='%'.$pTDBFPARAM['customParam']['viewFilter'][$i]['value'].'%';
						$pStrSQLFilter2Note.='%'.$pTDBFPARAM['customParam']['viewFilter'][$i]['value'].'%';
						break;
					default:
						$pStrSQLFilter2.=$pTDBFPARAM['customParam']['viewFilter'][$i]['value'];
						$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['value'];
						break;
				}
				if($pColInfo['sqlQuote']){
					$pStrSQLFilter2.='"';
					$pStrSQLFilter2Note.='"';
				}
				$pStrSQLFilter2.=' ';
				$pStrSQLFilter2Note.=' ';
				$pStrSQLFilter2.=$pTDBFPARAM['customParam']['viewFilter'][$i]['block2'].' ';
				$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['block2'].' ';
				if($i+1<sizeof($pTDBFPARAM['customParam']['viewFilter'])){
					$pStrSQLFilter2.=$pTDBFPARAM['customParam']['viewFilter'][$i]['operand2'].' ';
					$pStrSQLFilter2Note.=$pTDBFPARAM['customParam']['viewFilter'][$i]['operand2'].' ';
				}
				$pStrSQLFilter2.=' ';
				$pStrSQLFilter2Note.=' ';
			}
		}
		$pStrSQLFilter3='';
		$pStrSQLFilter3Note='';
		if(trim($pTDBFPARAM['customParam']['viewPref']['searchKeyword'])!=''){
			$pStrSQLFilter3Note.='search keyword = '.trim($pTDBFPARAM['customParam']['viewPref']['searchKeyword']);
			$pStrSQLFilter3.='CONCAT(';
			$s1='';
			for($i=0;$i<sizeof($pTDBFPARAM['fields']);$i++){
				if($pTDBFPARAM['fields'][$i]['viewTable']['sqlSelect'] and $pTDBFPARAM['fields'][$i]['viewTable']['visible']){
					$s1.='LOWER(IFNULL(`'.$pTDBFPARAM['fields'][$i]['name'].'`,""))';
					if($i+1<sizeof($pTDBFPARAM['fields'])){
						$s1.=',';
					}
					$s1.=' ';
				}
			}
			$s1=trim($s1);
			if(($s1 != '') && (substr($s1, strlen($s1)-1,1) == ',' )){
				$s1 = substr($s1, 0, strlen($s1)-1);
			}
			$s1 = ' '.$s1.' ';
			$pStrSQLFilter3.=$s1;
			$pStrSQLFilter3.=') LIKE LOWER("%'.trim($pTDBFPARAM['customParam']['viewPref']['searchKeyword']).'%")';
		}
		$pStrSQLFilter='';
		$pStrSQLFilterNote='';
		if($pStrSQLFilter1!=''){
			if($pStrSQLFilter==''){$pStrSQLFilter='where ';}
			$pStrSQLFilter.='('.$pStrSQLFilter1.')';
		}
		if($pStrSQLFilter1Note!=''){
			$pStrSQLFilterNote.='('.$pStrSQLFilter1Note.')';
		}
		if($pStrSQLFilter2!=''){
			if(trim($pStrSQLFilter)==''){$pStrSQLFilter='where ';}else{
				$pStrSQLFilter.=' AND ';
				//$pStrSQLFilterNote.=' AND ';
			}
			if(trim($pStrSQLFilterNote)!=''){
				$pStrSQLFilterNote.=' AND ';
			}
			$pStrSQLFilter.='('.$pStrSQLFilter2.')';
			$pStrSQLFilterNote.='('.$pStrSQLFilter2Note.')';
		}
		if($pStrSQLFilter3!=''){
			if(trim($pStrSQLFilter)==''){$pStrSQLFilter='where ';}else{
				$pStrSQLFilter.=' AND ';
				//$pStrSQLFilterNote.=' AND ';
			}
			if(trim($pStrSQLFilterNote)!=''){
				$pStrSQLFilterNote.=' AND ';
			}
			$pStrSQLFilter.='('.$pStrSQLFilter3.')';
			$pStrSQLFilterNote.='('.$pStrSQLFilter3Note.')';
		}
		$pStrSQL.=$pStrSQLFilter.' ';
		
		//Mendapatkan jumlah record data
		//print 'select count(*) from '.$pTDBFPARAM['tableName']['read'].' '.$pStrSQLFilter;
		//exit();
		$pJmlRecord=$this->tdbfGet1x1Result('select count(*) from '.$pTDBFPARAM['tableName']['read'].' '.$pStrSQLFilter);
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']=array();
		$pTDBFPARAM['customParam']['viewResult']=array();

		$pJmlRecSisa=$pJmlRecord % $pTDBFPARAM['customParam']['viewPref']['recordPerPage'];
		if($pJmlRecSisa==0){
			$pJmlPage=$pJmlRecord / $pTDBFPARAM['customParam']['viewPref']['recordPerPage'];
		}else{
			$pJmlPage=(($pJmlRecord - $pJmlRecSisa) / $pTDBFPARAM['customParam']['viewPref']['recordPerPage'])+1;
		}
		
		$pPageIndex=$pTDBFPARAM['customParam']['viewPref']['pageIndex'];
		if($pPageIndex<1){$pPageIndex=1;}
		if($pPageIndex>$pJmlPage){$pPageIndex=$pJmlPage;}
		$pRecStart=(($pPageIndex-1)*$pTDBFPARAM['customParam']['viewPref']['recordPerPage'])+1;
		if($pRecStart<0){$pRecStart=0;}
		$pRecEnd=$pRecStart+$pTDBFPARAM['customParam']['viewPref']['recordPerPage']-1;
		if($pRecEnd<0){$pRecEnd=0;}
		if($pRecEnd>$pJmlRecord){$pRecEnd=$pJmlRecord;}
		
		
		//Script SQL untuk order...
		if(isset($pTDBFPARAM['customParam']['viewPref']['orderBy'])){
			$pStrSQL.=' order by `'.$pTDBFPARAM['customParam']['viewPref']['orderBy'].'` '.$pTDBFPARAM['customParam']['viewPref']['orderMode'];
		}
		
		if(!$pAllRecord){
			//Script SQL untuk limit...
			if($pRecStart==0){$pRecStartLimit=0;}else{$pRecStartLimit=$pRecStart-1;}
			$pStrSQL.=' limit '.($pRecStartLimit).','.$pTDBFPARAM['customParam']['viewPref']['recordPerPage'];
		}
		
		//Mulai eksekusi query
		//print $pStrSQL;exit();
		$pQueryRes=$this->TDBFCTL->DB->query($pStrSQL);
		$pDataRes=$pQueryRes->result_array();
		//print_r($pDataRes);
		
		//update parameter
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['viewFilterNote']=$pStrSQLFilterNote;
		$pTDBFPARAM['customParam']['viewResult']['viewFilterNote']=$pStrSQLFilterNote;
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['recordCount']=$pJmlRecord;
		$pTDBFPARAM['customParam']['viewResult']['recordCount']=$pJmlRecord;
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['pageCount']=$pJmlPage;
		$pTDBFPARAM['customParam']['viewResult']['pageCount']=$pJmlPage;
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['pageIndex']=$pPageIndex;
		$pTDBFPARAM['customParam']['viewResult']['pageIndex']=$pPageIndex;
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['recordStart']=$pRecStart;
		$pTDBFPARAM['customParam']['viewResult']['recordStart']=$pRecStart;
		$this->TDBFCTL->TDBFPARAM['customParam']['viewResult']['recordEnd']=$pRecEnd;
		$pTDBFPARAM['customParam']['viewResult']['recordEnd']=$pRecEnd;
		$this->TDBFCTL->TDBFPARAM['viewRecord']=$pDataRes;
		return $pDataRes;
	}
	public function getViewHTMLRecord($pViewRecord){
		//print_r($pViewRecord);
		$pDtTemp=$pViewRecord;
		$pArFieldNameViewAsList=array();
		$pArFieldNameDataTypeBoolean=array();
		for($i=0;$i<sizeof($pDtTemp);$i++){
			//foreach($pDtTemp[$i] as $pFieldName){
			foreach ($pDtTemp[$i] as $pFieldName => $value){
				//print $pFieldName;
				//return;
				$pFieldValueBefore=$pDtTemp[$i][$pFieldName];
				$pFieldValueAfter=$this->TDBFCTL->onRecView($pDtTemp[$i],$pFieldName);
				if($i==0){
					$pColInfo=$this->getColInfo($pFieldName);
					//print '<pre>';
					//print $pFieldName;
					//print_r($pColInfo);
					//print '</pre>';
					if($pColInfo['listValue']){
						$pArFieldNameViewAsList[$pFieldName]=$pColInfo;
					}
					if($pColInfo['type']=='fieldCol'){
						if($pColInfo['dataType']=='boolean'){
							$pArFieldNameDataTypeBoolean[$pFieldName]=$pColInfo;
						}
					}
				}
				//if($i==1){print_r($pArFieldNameViewAsList);}
				if((array_key_exists($pFieldName,$pArFieldNameViewAsList))and($pFieldValueBefore==$pFieldValueAfter)){
					$pAr1=explode(chr(10).chr(13),$pFieldValueAfter);
					//print_r($pAr1);
					$s1='';
					foreach($pAr1 as $s2){
						$pColIndex=$pArFieldNameViewAsList[$pFieldName]['colIndex'];
						//print $s2.';';
						if(array_key_exists($s2,$this->TDBFCTL->TDBFPARAM['fields'][$pColIndex]['viewTable']['lookupList'])){
							$s3=$this->TDBFCTL->TDBFPARAM['fields'][$pColIndex]['viewTable']['lookupList'][$s2];
						}else{
							$s3=$s2;
						}
						$s1.=$s3.'<br/>';
					}
					if($s1!=''){$s1=substr($s1,0,strlen($s1)-5);}
					$pDtTemp[$i][$pFieldName]=$s1;
				}elseif((array_key_exists($pFieldName,$pArFieldNameDataTypeBoolean))and($pFieldValueBefore==$pFieldValueAfter)){
					if($pFieldValueAfter==1){
						$pDtTemp[$i][$pFieldName]='True';
					}else{
						$pDtTemp[$i][$pFieldName]='False';
					}
				}else{
					$pDtTemp[$i][$pFieldName]=$pFieldValueAfter;
				}
			}
			for($j=0;$j<sizeof($this->TDBFCTL->TDBFPARAM['extCols']);$j++){
				$pDtTemp[$i][$this->TDBFCTL->TDBFPARAM['extCols'][$j]['name']]=$this->TDBFCTL->onExtendedColView($pDtTemp[$i],$this->TDBFCTL->TDBFPARAM['extCols'][$j]['name']);
			}
			$pDtTemp[$i]['edit']=$this->getEditIconHTML($pDtTemp[$i]);			
			$pDtTemp[$i]['delete']=$this->getDeleteIconHTML($pDtTemp[$i]);	
			//add check bix HTML for form mode select
			if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=='select'){
				$pDtTemp[$i]['chkSelect']=$this->getChkSelectHTML($pDtTemp[$i]);
			}		
		}
		//update parameter
		$this->TDBFCTL->TDBFPARAM['viewHTMLRecord']=$pDtTemp;
		return $pDtTemp;
	}
	public function getEditIconHTML($pDtRec){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['update']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onEditIconClick($pDtRec,$pAccept);
			$pDtRecKeyEdit=array();
			$pDtRecKeyEditNoPrimary=array();
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
				if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
					$pDtRecKeyEdit[$pFieldName]=$pDtRec[$pFieldName];
				}
				if(array_key_exists($pFieldName,$pDtRec)){$pDtRecKeyEditNoPrimary[$pFieldName]=$pDtRec[$pFieldName];}
			}
			if(sizeof($pDtRecKeyEdit)==0){$pDtRecKeyEdit=$pDtRecKeyEditNoPrimary;}
			$pStrDtRecKeyEdit=$this->json2HexStr($pDtRecKeyEdit);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myEditIconClick('.chr(39).$pStrDtRecKeyEdit.chr(39).');';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}$pStrHTML='<span class="glyphicon glyphicon-edit myPointerCursor" onclick="'.$s3.'"></span>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getDeleteIconHTML($pDtRec){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['delete']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onEditIconClick($pDtRec,$pAccept);
			$pDtRecKeyDelete=array();
			$pDtRecKeyDeleteNoPrimary=array();
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
				if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
					$pDtRecKeyDelete[$pFieldName]=$pDtRec[$pFieldName];
				}
				if(array_key_exists($pFieldName,$pDtRec)){$pDtRecKeyEditNoPrimary[$pFieldName]=$pDtRec[$pFieldName];}
			}
			if(sizeof($pDtRecKeyDelete)==0){$pDtRecKeyDelete=$pDtRecKeyDeleteNoPrimary;}
			$pStrDtRecKeyDelete=$this->json2HexStr($pDtRecKeyDelete);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myDeleteIconClick('.chr(39).$pStrDtRecKeyDelete.chr(39).');';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}$pStrHTML='<span class="glyphicon glyphicon-remove-circle myPointerCursor" onclick="'.$s3.'"></span>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getAddButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['create']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onAddButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myAddButtonClick();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Add</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getImportExcelButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['importExcel']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onImportExcelButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myShowImportExcelDialog();';
			//$s2='$('.chr(39).'#'.$this->TDBFCTL->TDBFPARAM['CTLID'].'_divDialogImportExcel'.chr(39).').modal();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">ImportExcel</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getExportExcelButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['exportExcel']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onExportExcelButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myExportExcelButtonClick();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">ExportExcel</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getDeleteExcelButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['deleteExcel']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onDeleteExcelButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myShowDeleteExcelDialog();';
			//$s2='$('.chr(39).'#'.$this->TDBFCTL->TDBFPARAM['CTLID'].'_divDialogDeleteExcel'.chr(39).').modal();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">DeleteFromExcel</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getPrintButtonHTML(){
		return '';
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='view'){
			return '';
		}
		$pAccept=true;
		if(method_exists($this->TDBFCTL, 'onPrintButtonClick')){
			$s1=$this->TDBFCTL->onPrintButtonClick($pAccept);
		}else{
			$s1='';
		}
		$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myPrintButtonClick();';
		if($pAccept){
			$s3=$s1.$s2;
		}else{
			$s3=$s1;
		}
		$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Print</button>';
		return $pStrHTML;
	}
	public function getSelectButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='select'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['select']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onSelectButtonClick($pAccept);
			$pSelectParentFormId='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'])){
				$pSelectParentFormId=$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'];
			}
			$pSelectSessionCode='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'])){
				$pSelectSessionCode=$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'];
			}
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.mySelectButtonClick('.chr(39).$pSelectParentFormId.chr(39).','.chr(39).$pSelectSessionCode.chr(39).');';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Select</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getSelectAllButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='select'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['select']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onSelectAllButtonClick($pAccept);
			$pSelectParentFormId='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'])){
				$pSelectParentFormId=$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'];
			}
			$pSelectSessionCode='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'])){
				$pSelectSessionCode=$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'];
			}
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.mySelectAllRecordButtonClick('.chr(39).$pSelectParentFormId.chr(39).','.chr(39).$pSelectSessionCode.chr(39).');';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Select All Records</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getDeselectAllButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']!='select'){
			return '';
		}
		if($this->TDBFCTL->TDBFPARAM['features']['select']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onDeselectAllButtonClick($pAccept);
			$pSelectParentFormId='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'])){
				$pSelectParentFormId=$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'];
			}
			$pSelectSessionCode='';
			if(isset($this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'])){
				$pSelectSessionCode=$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode'];
			}
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myDeselectAllRecordButtonClick('.chr(39).$pSelectParentFormId.chr(39).','.chr(39).$pSelectSessionCode.chr(39).');';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Deselect All Records</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getSaveButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['features']['create']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onSaveButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.mySaveButtonClick();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Save</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getUpdateButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['features']['update']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onUpdateButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myUpdateButtonClick();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Update</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getCancelButtonHTML(){
		if(($this->TDBFCTL->TDBFPARAM['features']['create'])or($this->TDBFCTL->TDBFPARAM['features']['update'])){
			$pAccept=true;
			if($this->TDBFCTL->TDBFPARAM['customParam']['sectionMode']=='add'){
				$s1=$this->TDBFCTL->onCancelButtonClick($pAccept);
				$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myCancelButtonClick();';
				if($pAccept){
					$s3=$s1.$s2;
				}else{
					$s3=$s1;
				}
				$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Cancel</button>';
			}else{
				$s1=$this->TDBFCTL->onCancelButtonClick($pAccept);
				$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myCancelButtonClick();';
				if($pAccept){
					$s3=$s1.$s2;
				}else{
					$s3=$s1;
				}
				$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">Cancel</button>';
			}
			
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getViewButtonHTML(){
		if($this->TDBFCTL->TDBFPARAM['features']['view']){
			$pAccept=true;
			$s1=$this->TDBFCTL->onViewButtonClick($pAccept);
			$s2=$this->TDBFCTL->TDBFPARAM['CTLID'].'.myViewButtonClick();';
			if($pAccept){
				$s3=$s1.$s2;
			}else{
				$s3=$s1;
			}
			$pStrHTML='<button type="button" class="btn btn-primary btn-xs" onclick="'.$s3.'">View</button>';
		}else{
			$pStrHTML='';
		}
		return $pStrHTML;
	}
	public function getChkSelectHTML($pDtRec){
		$pArKeyRec=$this->getKeyArrayFromSingleRecordModel($pDtRec);
		$pArKeyRecHexStr=$this->json2HexStr($pArKeyRec);
		//print '<pre>';
		//	print_r($this->TDBFCTL->TDBFPARAM['customParam']);
		//	print '</pre>';
		$pPreSelectSta=$this->TDBFCTL->getDataRecordPreSelectionStatus($pDtRec,$this->TDBFCTL->TDBFPARAM['customParam']['selectParentFormId'],$this->TDBFCTL->TDBFPARAM['customParam']['selectSessionCode']);
		if($pPreSelectSta){
			$s1='checked="checked"';
		}else{
			$s1='';
		}
		$pStrHTML='<input type="checkbox" class="myChkSelect" value="'.$pArKeyRecHexStr.'" '.$s1.' />';
		return $pStrHTML;
	}
	public function getKeyArrayFromSingleRecordModel($pDtRec){
		$pArResult=array();
		if(sizeof($pDtRec)>0){
			$pArKey=array();
			$pArKeyFull=array();
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				$pFieldName=$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'];
				if(isset($pDtRec[$pFieldName])){
					if($this->TDBFCTL->TDBFPARAM['fields'][$i]['isKey']){
						$pArKey[$pFieldName]=$pDtRec[$pFieldName];
					}else{
						$pArKeyFull[$pFieldName]=$pDtRec[$pFieldName];
					}
				}
			}
			if(sizeof($pArKey)>0){$pArResult=$pArKey;}else{$pArResult=$pArKeyFull;}
		}
		return $pArResult;
	}
	public function getSingleRecordModel($pRecOrKeyArray){
		$pArResult=array();
		$pKeyArray=$this->getKeyArrayFromSingleRecordModel($pRecOrKeyArray);
		if(sizeof($pKeyArray)>0){
			$pStrSQL='select ';
			$s1='';
			for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
				$s1.='`'.$this->TDBFCTL->TDBFPARAM['fields'][$i]['name'].'`,';
			}
			if($s1!=''){$s1=substr($s1,0,strlen($s1)-1);}
			$pStrSQL.=$s1;
			$pStrSQL.=' from '.$this->TDBFCTL->TDBFPARAM['tableName']['read'].' where ';
			$s1='';
			//print_r($pKeyArray);
			foreach($pKeyArray as $pFieldName => $pFieldValue){
				$pColInfo=$this->getColInfo($pFieldName);
				if($pColInfo['sqlQuote']){$pSqlQuote='"';}else{$pSqlQuote='';}
				$s1.=$pFieldName.'='.$pSqlQuote.$pFieldValue.$pSqlQuote.' AND ';
			}
			if($s1!=''){$s1=substr($s1,0,strlen($s1)-5);}
			$pStrSQL.=$s1;
			//print '<br />'.$s1.'<br />';
			$pAr1=$this->tdbfGet1xnResult($pStrSQL);
			//print(sizeof($pAr1));
			$pAr2=array();
			if(sizeof($pAr1)>0){
				for($i=0;$i<sizeof($this->TDBFCTL->TDBFPARAM['fields']);$i++){
					$pAr2[$this->TDBFCTL->TDBFPARAM['fields'][$i]['name']]=$pAr1[$i];
				}
			}
			$pArResult=$pAr2;
		}
		return $pArResult;
	}
	function tdbfMySQLRealEscapeString($pString){
		//print $pStrSQL;
		$query = $this->TDBFCTL->DB->escape_str($pString);
		return $query;
	}
	function tdbfExecSQL($pStrSQL){
		$query = $this->TDBFCTL->DB->query($pStrSQL);
		return $query;
	}
	function tdbfGet1x1Result($pStrSQL){
		//print $pStrSQL;
		$pVal=null;
		$query = $this->TDBFCTL->DB->query($pStrSQL);
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
		$query = $this->TDBFCTL->DB->query($pStrSQL);
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
		$query = $this->TDBFCTL->DB->query($pStrSQL);
		$pAr1=array();
		foreach ($query->result_array() as $row){
			$pDtRow=array_values($row);
			$pAr1[]=$pDtRow[0];
		}
		$query->free_result();
		return $pAr1;
	}
	function tdbfGetnxnResult($pStrSQL, $modeFieldName=false){
		//print $pStrSQL.';<br />';
		$query = $this->TDBFCTL->DB->query($pStrSQL);
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
}