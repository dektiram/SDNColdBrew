<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	public function index(&$viewMode=''){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$ryuPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_packaged_path"');
		$ryuAdditionalScriptPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_additional_script_path"');
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		
		//UPLOAD FILE RYU
		if (!empty($_FILES['fileRyuScript'])) {
			if(pathinfo($_FILES['fileRyuScript']['name'], PATHINFO_EXTENSION) == 'py'){
				$uploadedFile = $ryuAdditionalScriptPath.$_FILES['fileRyuScript']['name'];
				if(!file_exists($uploadedFile)){
					if(move_uploaded_file($_FILES['fileRyuScript']['tmp_name'], $uploadedFile)){
						array_push($pageAlert['success'], array('text'=>'Upload file success.','autoClose'=>TRUE));
					}else{
						array_push($pageAlert['warning'], array('text'=>'Upload file failed.','autoClose'=>TRUE));
					}
				}else{
					array_push($pageAlert['warning'], array('text'=>'File '.$uploadedFile.' is already exist. Please rename your script file.','autoClose'=>TRUE));
				}
			}else{
				array_push($pageAlert['warning'], array('text'=>'Ryu script must with .py extension.','autoClose'=>TRUE));
			}
		}
		
		//DELETE RYU SCRIPT
		if(isset($_POST['delRyuScript'])){
			if(dirname($_POST['delRyuScript']).'/' == $ryuAdditionalScriptPath){
				if(file_exists($_POST['delRyuScript'])){
					if(unlink($_POST['delRyuScript'])){
						array_push($pageAlert['success'], array('text'=>'Remove file '.$_POST['delRyuScript'].' success.','autoClose'=>TRUE));
					}else{
						array_push($pageAlert['warning'], array('text'=>'Remove file '.$_POST['delRyuScript'].' failed.','autoClose'=>TRUE));
					}
				}else{
					array_push($pageAlert['warning'], array('text'=>'File '.$_POST['delRyuScript'].' is not exist.','autoClose'=>TRUE));
				}
			}else{
				array_push($pageAlert['warning'], array('text'=>'File '.$_POST['delRyuScript'].' not in additional ryu script directory.','autoClose'=>TRUE));
			}
		}
		
		//PACKAGED RYU SCRIPT
		$x1 = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_script_default_selected"');
		$defaulSelectedRyuScript = explode(',', $x1);
		$ryuScript = array();
		$ryuScript['packaged'] = array();
		$ryuScript['additional'] = array();
		$x2 = glob($ryuPath.'app/*.py');
		foreach ($x2 as $x3) {
				$x4 = basename($x3);
			if($x4 != '__init__.py'){
				$x5 = array();
				$x5['file'] = $x4;
				$x5['selected'] = FALSE;
				if(in_array($x4, $defaulSelectedRyuScript)){
					$x5['selected'] = TRUE;
				}
				array_push($ryuScript['packaged'], $x5);
			}
		}
		
		//ADDITIONAL RYU SCRIPT
		$x2 = glob($ryuAdditionalScriptPath.'*.py');
		foreach ($x2 as $x3) {
			$x4 = $x3;
			if($x4 != '__init__.py'){
				$x5 = array();
				$x5['file'] = $x4;
				$x5['selected'] = FALSE;
				if(in_array($x4, $defaulSelectedRyuScript)){
					$x5['selected'] = TRUE;
				}
				array_push($ryuScript['additional'], $x5);
			}
		}		
		
		//GET PROCESS INFO
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$ryuShellCaptureFile = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_shell_capture_file"');
		$sflowrtShellCaptureFile = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="sflowrt_shell_capture_file"');
		
		$xPostParam = array(
			'ryuShellCaptureFile' => $ryuShellCaptureFile,
			'sflowrtShellCaptureFile' => $sflowrtShellCaptureFile
		);
		$url = $internalServiceUrl.'infoAll/'.$this->TdbfSystem->json2HexStr($xPostParam);
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		
		$ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $infoAllContent  = curl_exec($ch);
	    curl_close($ch);
		//print $infoAllContent;
		$dtProcessAllInfo = json_decode($infoAllContent, TRUE)['data'];
		//print '<pre>';
		//print_r($dtProcessAllInfo);
		//print '</pre>';
		
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['currentMenuId'] = 'viewNetGraph';
		$pPageParam['otherParam']['ryuScript'] = $ryuScript;
		$pPageParam['otherParam']['processInfoAll'] = $dtProcessAllInfo;
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/Controller',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
	}

	public function envStart(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$ryuPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_packaged_path"');
		$sflowrtPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="sflowrt_path"');
		$ryuShellCaptureFile = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="ryu_shell_capture_file"');
		$sflowrtShellCaptureFile = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="sflowrt_shell_capture_file"');
		
		$postParam = $this->TdbfSystem->hexStr2Json($_POST['tdbfPageParam']);
		foreach ($postParam['packagedRyuScript'] as $key => $value) {
			$postParam['packagedRyuScript'][$key] = $ryuPath.'app/'.$value;
		}
		$xPostParam = array(
			'ryuScript' => array_merge($postParam['packagedRyuScript'], $postParam['additionalRyuScript']),
			'sflowrtPath' => $sflowrtPath,
			'ryuShellCaptureFile' => $ryuShellCaptureFile,
			'sflowrtShellCaptureFile' => $sflowrtShellCaptureFile
		);
		$url = $internalServiceUrl.'startAll/'.$this->TdbfSystem->json2HexStr($xPostParam);
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		
		$ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
    	print $content;
	}
	public function envStop(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$url = $internalServiceUrl.'stopAll';
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		
		$ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
    	print $content;
		
	}
}
