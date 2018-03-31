<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RyuRestOfctl extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	public function test(){
		$this->load->view('myView/test');
	}
	public function test2(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/test2',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
	}
	public function index($itemKey, $dpid, $withFormButton = true){
		switch ($itemKey) {
			case 'ofctl_set_add_a_flow_entry':
				$this->addFlowEntry($dpid, $withFormButton);
				break;
			case 'ofctl_set_modify_all_matching_flow_entries':
				break;
			case 'ofctl_set_modify_flow_entry_strictly':
				break;
			case 'ofctl_set_delete_all_matching_flow_entries':
				break;
			case 'ofctl_set_delete_flow_entry_strictly':
				break;
			case 'ofctl_set_delete_all_flow_entries':
				break;
			case 'ofctl_set_add_a_group_entry':
				break;
			case 'ofctl_set_modify_a_group_entry':
				break;
			case 'ofctl_set_delete_a_group_entry':
				break;
			case 'ofctl_set_modify_the_behavior_of_the_port':
				break;
			case 'ofctl_set_add_a_meter_entry':
				break;
			case 'ofctl_set_modify_a_meter_entry':
				break;
			case 'ofctl_set_delete_a_meter_entry':
				break;
			case 'ofctl_set_modify_role':
				break;
		}
	}
	
	public function geChildMenu(){
		
	}
	
	private function addFlowEntry($dpid, $withFormButton){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['dpid'] = $dpid;
		$pPageParam['otherParam']['withFormButton'] = $withFormButton;
		$pPageParam['otherParam']['formUrl'] = base_url().'Api/ryu/text/stats/flowentry/add';
		
		$formSchema = json_decode(file_get_contents('assets/myjson/ofctlRestApi/addFlowEntry.json'),TRUE);
		$pPageParam['otherParam']['formSchema'] = json_encode($formSchema);
		
		$formData = array(
			'dpid'=>intval($dpid)
		);
		$pPageParam['otherParam']['formData'] = json_encode($formData);
		
		$pHTMLMyContent='';
		$pMainPageLoadMode='cssAndJs';
		$s1=$this->load->view('myView/ryuRestOfctl/AddFlowEntry',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}
	
}
