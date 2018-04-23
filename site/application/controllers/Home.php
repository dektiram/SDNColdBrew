<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
		//print_r($GLOBALS['dataLisence']);
    }
	public function login($pMode='form'){
		if(!isset($pMode)){$pMode='form';}
		switch($pMode){
			case 'form':
				$pActionPage=base_url().'Home/login/action';
				$pLandingPage=base_url().'Home';
				$pHTMLMyContent=$this->load->view('MyLoginPage',array(
																		'pActionPage'=>$pActionPage,
																		'pLandingPage'=>$pLandingPage,
																	),true);
				print $pHTMLMyContent;
				break;
			case 'action':
				$this->TdbfSystem->loginAction();
				break;
		}
	}
	public function logout(){
		$this->TdbfSystem->logoutAction();
		header('Location: '.base_url().'Home/login');
		exit();
	}
	public function index(){
		header('Location: '.base_url().'Topology');
		exit();
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		//$this->load->library('../controllers/tdbfCtl',null,'MyTdbfCtl1');
		//$this->MyTdbfCtl1->init($this,$this->MyTdbfCtl1,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		//$s1=$this->MyTdbfCtl1->display('',$pMainPageLoadMode);
		//$pHTMLMyContent.=$s1;
		$TDBFPARAM=array();
		$TDBFPARAM['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$TDBFPARAM);
		
	}
	public function restricted(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('MyContentTdbfRestricted',array(),true);
		$pHTMLMyContent.=$s1;
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['sectionHeader']='Access Denied';
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}
	public function myProfil(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('MyContentMyProfile',array('USERINFO'=>$this->TdbfSystem->USERINFO),true);
		$pHTMLMyContent.=$s1;
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['sectionHeader']='My Profile';
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
	}
	public function uploadDisplayPicture(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		//$this->TdbfSystem->checkAllowedPrivilegeAction('form_sewa_baru');
		$pHTMLMyContent='';
		$pMainPageLoadMode='cssAndJs';
		$viewParam = array();
		$viewParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$s1=$this->load->view('form/UploadDisplayPicture',$viewParam,true);
		$pHTMLMyContent.=$s1;
		
		$this->MyTdbfCtlAppPrivilege->TDBFPARAM['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppPrivilege->TDBFPARAM);	
	}
	public function activation(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$this->TdbfSystem->checkAllowedPrivilegeAction('activation');
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$viewParam = array();
		$viewParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$s1=$this->load->view('Activation',$viewParam,true);
		$pHTMLMyContent.=$s1;
		
		$this->MyTdbfCtlAppPrivilege->TDBFPARAM['USERINFO']=$this->TdbfSystem->USERINFO;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppPrivilege->TDBFPARAM);	
	}
}