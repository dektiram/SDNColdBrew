<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataModelEditor extends CI_Controller {
	var $pageParam=array();
	public function index(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$this->TdbfSystem->checkAllowedPrivilegeAction('datamodeleditor');
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('MyContentTdbfDataModelEditor',array(),true);
		$pHTMLMyContent.=$s1;
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['sectionHeader']='Data Model Editor';
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}
	
	public function urlRequest($pReqType){
		//print $this->config->item('data_model_path');		
		switch($pReqType){
			case 'loadFromServerFile':
				$pFileName=$_POST['fileName'].'.tdbf';
				print file_get_contents($this->config->item('data_model_path').$pFileName);
				//print getcwd();
				break;
			case 'saveToServerFile':
				$pFileName=$_POST['fileName'].'.tdbf';
				$pStrJSON=$_POST['strJSON'];
				file_put_contents($this->config->item('data_model_path').$pFileName,$pStrJSON);
				print 'OK';
				break;
		}
	}
}
