<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	function myCurlRyuRest($url, $postData, $formatReply = 'text', $requestMethod = 'GET', $timeout = 60 ){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch,CURLOPT_TIMEOUT, $timeout);
		if($postData != ''){
			//print $postData;
			curl_setopt($ch,CURLOPT_POST, TRUE);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		}
	    $content  = curl_exec($ch);
	    curl_close($ch);
		if($formatReply == 'json'){
			return json_decode($content, TRUE);
		}else{
			return $content;
		}
	}
	public function ryu($format = 'text', $requestMethod = 'GET'){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$x1 = explode('/', str_replace('<html>','',uri_string()));
		unset($x1[0]);
		unset($x1[1]);
		unset($x1[2]);
		unset($x1[3]);
		$ryuApiParam = implode('/', $x1);
		$ryuApiUrl = 'http://127.0.0.1:8080/'.$ryuApiParam;
		
		$strPostData = '';
		if(isset($_POST['tdbfPageParam'])){
			$x1 = $this->TdbfSystem->hexStr2Json($_POST['tdbfPageParam']);
			if(isset($x1['ofctlRestApi'])){
				$postParam = $x1['ofctlRestApi'];
				$strPostData = json_encode($postParam);
			}
		}
		$content  = $this->myCurlRyuRest($ryuApiUrl, $strPostData, 'text', $requestMethod);
		
		if($format == 'json'){
			header('Content-Type: application/json');
		}
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
		
		$content  = $this->TdbfSystem->myCurl($ryuApiUrl, $_POST, 'text');
    	print $content;
	}
}
