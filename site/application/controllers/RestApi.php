<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestAPI extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	function topology(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['restApiGroup'] = 'TOPO REST API';
		$pPageParam['otherParam']['referenceUrl'] = '';
		$pPageParam['otherParam']['ofctlRestRequestList'] = json_decode(file_get_contents('assets/myjson/topology_rest.json'),TRUE);
		$x1 = file_get_contents('http://127.0.0.1:8080/stats/switches');
		if($x1 === FALSE){
			$pPageParam['otherParam']['switchList'] = array();
			http_response_code(500);
			print '<h1>Controller not running.</h1>';
			return;
		}
		$pPageParam['otherParam']['switchList'] = json_decode($x1,TRUE);
		//$pPageParam['otherParam']['topologyData'] = $this->getTopologyData();
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='cssAndJs';
		$s1=$this->load->view('myView/RestApi',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}

	function ofctl($dpid='' ){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['restApiGroup'] = 'OFCTL REST API';
		$pPageParam['otherParam']['referenceUrl'] = 'http://ryu.readthedocs.io/en/latest/app/ofctl_rest.html';
		$pPageParam['otherParam']['ofctlRestRequestList'] = json_decode(file_get_contents('assets/myjson/ofctl_rest.json'),TRUE);
		$x1 = file_get_contents('http://127.0.0.1:8080/stats/switches');
		if($x1 === FALSE){
			$pPageParam['otherParam']['switchList'] = array();
			http_response_code(500);
			print '<h1>Controller not running.</h1>';
			return;
		}
		$pPageParam['otherParam']['switchList'] = json_decode($x1,TRUE);
		//$pPageParam['otherParam']['topologyData'] = $this->getTopologyData();
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='cssAndJs';
		$s1=$this->load->view('myView/RestApi',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}
}
