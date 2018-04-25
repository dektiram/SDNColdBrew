<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	public function appAction(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppAction',null,'MyTdbfCtlAppAction');
		$this->MyTdbfCtlAppAction->init($this,$this->MyTdbfCtlAppAction,$this->TdbfSystem->USERINFO);
		
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlAppAction->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App Action';
		$this->MyTdbfCtlAppAction->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppAction->TDBFPARAM);		
	}
	public function appPrivilege(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppPrivilege',null,'MyTdbfCtlAppPrivilege');
		$this->MyTdbfCtlAppPrivilege->init($this,$this->MyTdbfCtlAppPrivilege,$this->TdbfSystem->USERINFO);
		
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlAppPrivilege->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App Privilege';
		$this->MyTdbfCtlAppPrivilege->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppPrivilege->TDBFPARAM);		
	}
	public function appUserAccount(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppUserAccount',null,'MyTdbfCtlAppUserAccount');
		$this->MyTdbfCtlAppUserAccount->init($this,$this->MyTdbfCtlAppUserAccount,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlAppUserAccount->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App User Account';
		$this->MyTdbfCtlAppUserAccount->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppUserAccount->TDBFPARAM);		
	}
	public function appUserPrivilege(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppSelectUserPrivilege',null,'MyTdbfCtlAppSelectUserPrivilege');
		$this->MyTdbfCtlAppSelectUserPrivilege->init($this,$this->MyTdbfCtlAppSelectUserPrivilege,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlAppSelectUserPrivilege->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppSelectUserPrivilege->TDBFPARAM);		
	}
	public function appSelectAction(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppSelectAction',null,'MyTdbfCtlAppSelectAction');
		$this->MyTdbfCtlAppSelectAction->init($this,$this->MyTdbfCtlAppSelectAction,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlAppSelectAction->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlAppSelectAction->TDBFPARAM);		
	}
	public function appSettings(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlAppSettings',null,'MyTdbfCtlAppSettings');
		$pMyTdbfCtl_X1=$this->MyTdbfCtlAppSettings;
		
		$pMyTdbfCtl_X1->init($this,$pMyTdbfCtl_X1,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$pMyTdbfCtl_X1->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pMyTdbfCtl_X1->TDBFPARAM);		
	}
}
