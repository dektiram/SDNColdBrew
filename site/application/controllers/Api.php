<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	public function ryu(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$x1 = explode('/', uri_string());
		//print uri_string();
		unset($x1[0]);
		unset($x1[1]);
		$ryuApiParam = implode('/', $x1);
		$ryuApiUrl = 'http://127.0.0.1:8080/'.$ryuApiParam;
		
		//print $ryuApiUrl;
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		$ch = curl_init($ryuApiUrl);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
		header('Content-Type: application/json');
    	print $content;
	}
	public function sflowrt(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$x1 = explode('/', uri_string());
		//print uri_string();
		unset($x1[0]);
		unset($x1[1]);
		$ryuApiParam = implode('/', $x1);
		$ryuApiUrl = 'http://localhost:8008/'.$ryuApiParam;
		
		//print $ryuApiUrl;
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		$ch = curl_init($ryuApiUrl);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
		//header('Content-Type: application/json');
    	print $content;
	}
}
