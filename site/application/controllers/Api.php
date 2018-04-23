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
		//curl_setopt($ch, CURLOPT_HEADER, true); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch,CURLOPT_TIMEOUT, $timeout);
		if($requestMethod == 'POST'){
			if($postData != ''){
				//print '<pre>'.$postData.'</pre>';
				curl_setopt($ch,CURLOPT_POST, TRUE);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
			}
		}elseif($requestMethod == 'DELETE'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
	    $content  = curl_exec($ch);
		$httpcode = curl_getinfo($ch)['http_code'];
		//print $httpcode;
	    curl_close($ch);
		if($formatReply == 'json'){
			return array(
				'http_code' => $httpcode,
				'http_content' => json_decode($content, TRUE)
			);
		}else{
			return array(
				'http_code' => $httpcode,
				'http_content' => $content
			);
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
				$strPostData = $postParam;
			}
		}
		$content  = $this->myCurlRyuRest($ryuApiUrl, $strPostData, 'text', $requestMethod);
		
		http_response_code($content['http_code']);
		if($format == 'json'){
			header('Content-Type: application/json');
		}
    	print $content['http_content'];
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
