<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topology extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	
	public function index(){
		$this->viewTopology();
	}
		
	private function myCurl($url, $postData, $formatReply = 'text'){
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		
		$ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
		//print $content;
		if($formatReply == 'json'){
			return json_decode($content, TRUE);
		}else{
			return $content;
		}
	}
	
	public function getTopologyData($reqType = 'function'){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(0);
			$dtReply['data'] = array();
			$dtReply['data']['switches'] = json_decode($this->myCurl('http://127.0.0.1:8080/v1.0/topology/switches', array()), true);
			$dtReply['data']['hosts'] = json_decode($this->myCurl('http://127.0.0.1:8080/v1.0/topology/hosts', array()), true);
			$dtReply['data']['links'] = json_decode($this->myCurl('http://127.0.0.1:8080/v1.0/topology/links', array()), true);
			
			foreach ($dtReply['data']['switches'] as $key => $value) {
				$strSql = 'select `label` from `t_switch` where `dpid`="'.$value['dpid'].'"';
				$label = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				if($label == null){$label = $value['dpid'];}
				$dtReply['data']['switches'][$key]['label'] = $label;
				$dtReply['data']['switches'][$key]['id'] = $value['dpid'];
			}
			foreach ($dtReply['data']['links'] as $key => $value) {
				$strSql = 'select `label` from `t_link` where `src_hw_addr`="'.$value['src']['hw_addr'].'" and `dst_hw_addr`="'.$value['dst']['hw_addr'].'"';
				$label = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				if($label == null){$label = '';}
				$dtReply['data']['links'][$key]['label'] = $label;
				$dtReply['data']['links'][$key]['id'] = $value['src']['hw_addr'].'-'.$value['dst']['hw_addr'];
				$dtReply['data']['links'][$key]['src']['type'] = 'SWITCH';
				$dtReply['data']['links'][$key]['dst']['type'] = 'SWITCH';
			}
			foreach ($dtReply['data']['hosts'] as $key => $value) {
				$strSql = 'select `label` from `t_host` where `hw_addr`="'.$value['mac'].'"';
				$label = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				if($label == null){
					$label = $value['mac'];
					foreach ($dtReply['data']['hosts'][$key]['ipv6'] as $key2 => $value2) {
						if($value2 != '::'){
							$label = $value2;
							break;
						}
					}
					foreach ($dtReply['data']['hosts'][$key]['ipv4'] as $key2 => $value2) {
						if($value2 != '127.0.0.1'){
							$label = $value2;
							break;
						}
					}
				}
				$dtReply['data']['hosts'][$key]['label'] = $label;
				$dtReply['data']['hosts'][$key]['id'] = $value['mac'];;
				
				//LINK SWITCH -> HOST
				$newLink = array('src'=>$value['port'], 'dst'=>array('hw_addr'=>$value['mac']));
				$newLink['src']['type'] = 'SWITCH';
				$newLink['dst']['type'] = 'HOST';
				$strSql = 'select `label` from `t_link` where `src_hw_addr`="'.$newLink['src']['hw_addr'].'" and `dst_hw_addr`="'.$newLink['dst']['hw_addr'].'"';
				$label = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				if($label == null){$label = '';}
				$newLink['label'] = $label;
				$newLink['id'] = $newLink['src']['hw_addr'].'-'.$newLink['dst']['hw_addr'];
				array_push($dtReply['data']['links'], $newLink);
				
				//LINK HOST -> SWITCH
				$newLink = array('src'=>array('hw_addr'=>$value['mac']), 'dst'=>$value['port']);
				$newLink['src']['type'] = 'HOST';
				$newLink['dst']['type'] = 'SWITCH';
				$strSql = 'select `label` from `t_link` where `src_hw_addr`="'.$newLink['src']['hw_addr'].'" and `dst_hw_addr`="'.$newLink['dst']['hw_addr'].'"';
				$label = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				if($label == null){$label = '';}
				$newLink['label'] = $label;
				$newLink['id'] = $newLink['src']['hw_addr'].'-'.$newLink['dst']['hw_addr'];
				array_push($dtReply['data']['links'], $newLink);
			}

			foreach ($dtReply['data']['links'] as $key => $value) {
				if($value['src']['type'] == 'SWITCH'){
					foreach ($dtReply['data']['switches'] as $key2 => $value2) {
						if($value['src']['dpid'] == $value2['dpid']){
							$dtReply['data']['links'][$key]['src']['node'] = $value2;
						}
					}
				}else{
					foreach ($dtReply['data']['hosts'] as $key2 => $value2) {
						if($value['src']['hw_addr'] == $value2['mac']){
							$dtReply['data']['links'][$key]['src']['node'] = $value2;
						}
					}
				}
				if($value['dst']['type'] == 'SWITCH'){
					foreach ($dtReply['data']['switches'] as $key2 => $value2) {
						if($value['dst']['dpid'] == $value2['dpid']){
							$dtReply['data']['links'][$key]['dst']['node'] = $value2;
						}
					}
				}else{
					foreach ($dtReply['data']['hosts'] as $key2 => $value2) {
						if($value['dst']['hw_addr'] == $value2['mac']){
							$dtReply['data']['links'][$key]['dst']['node'] = $value2;
						}
					}
				}
			}

			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		switch ($reqType) {
			case 'raw':
				print json_encode($dtReply);
				break;
			case 'json':
				header('Content-Type: application/json');
				print json_encode($dtReply);
				break;
		}
    	return $dtReply;
	}
	
	public function getTopologyPopupMenu($reqType = 'function'){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(0);
			$dtReply['data'] = array();
			//print file_get_contents('assets/myjson/popup_menu_switch.json');
			$dtReply['data']['switch'] = json_decode(file_get_contents('assets/myjson/popup_menu_switch.json'),TRUE);
			$dtReply['data']['host'] = json_decode(file_get_contents('assets/myjson/popup_menu_host.json'),TRUE);
			$dtReply['data']['edge'] = json_decode(file_get_contents('assets/myjson/popup_menu_edge.json'),TRUE);
			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		switch ($reqType) {
			case 'raw':
				print json_encode($dtReply);
				break;
			case 'json':
				header('Content-Type: application/json');
				print json_encode($dtReply);
				break;
		}
    	return $dtReply;
	}
	
	public function viewTopology($viewFormat = 'html'){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		switch ($viewFormat) {
			case 'json':
				$this->getTopologyData('json');
				break;
			default:
				$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
				$pPageParam=array();
				$pPageParam['otherParam']=array();
				$pPageParam['otherParam']['alertMessage'] = $pageAlert;
				//$pPageParam['otherParam']['topologyData'] = $this->getTopologyData();
				$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
				$pHTMLMyContent='';
				$pMainPageLoadMode='full';
				$s1=$this->load->view('myView/network/Topology',$pPageParam,true);
				$pHTMLMyContent.=$s1;
				$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);
				break;
		}
	}
	
	public function changeObjNetLabel(){
		$param = $this->TdbfSystem->hexStr2Json($_POST['tdbfPageParam']);
		$objNetData = json_decode($param['objNetData'], TRUE);
		//print_r($objNetData);
		$objNetLabel = $param['objNetLabel'];
		if($objNetData['type'] == 'SWITCH'){
			$strSQL = 'insert into `t_switch` (`dpid`,`label`) values ("'.$objNetData['dpid'].'","'.$objNetLabel.'") 
						on duplicate key update `label`="'.$objNetLabel.'"';
		}else{
			$strSQL = 'insert into `t_host` (`hw_addr`,`label`) values ("'.$objNetData['mac'].'","'.$objNetLabel.'") 
						on duplicate key update `label`="'.$objNetLabel.'"';
		}
		if($this->TdbfSystem->tdbfExecSQL($strSQL)){
			print json_encode(array('status'=>'SUCCESS'));
		}else{
			print json_encode(array('status'=>'FAIL'));
		}
	}
}
