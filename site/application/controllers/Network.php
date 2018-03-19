<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	public function discovery(&$viewMode=''){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['currentMenuId'] = 'viewNetGraph';
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/network/Discovery',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
	}
	
	public function clearNetworkData($printJson = TRUE){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			$strSqls = array('delete from t_switch',
							'delete from t_switch_port',
							'delete from t_switch_ipv4',
							'delete from t_switch_ipv6',
							'delete from t_switch_route',
							'delete from t_link',
							'delete from t_host',
							'delete from t_host_ipv4',
							'delete from t_host_ipv6');
			foreach ($strSqls as $strSql) {
				$this->TdbfSystem->tdbfExecSQL($strSql);
			}
			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		if($printJson){
			print json_encode($dtReply);
		}
	}
	
	private function mininetCmd($cmd){
		//$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$mininetUtilPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_path"');
		$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
		$xPostParam = array(
			'mininetUtilPath' => $mininetUtilPath,
			'mininetCmd' => $cmd
		);
		$url = $internalServiceUrl.'mininetCmd/'.$this->TdbfSystem->json2HexStr($xPostParam);
		$options = array(CURLOPT_RETURNTRANSFER => true); 
		
		$ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);
		//print $content;
    	return json_decode($content, TRUE);
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
	
	public function pingTrigger(){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(0);
			$dtReply['data'] = array();
			$this->load->library('SubnetCalculator');
			$mininetUtilPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_path"');
			$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
			
			//Could not find Mininet host h100
			for ($i=1; $i <= 100; $i++) {
				$cmd1 = 'h'.$i.' ip -4 a';
				$x1 = $this->mininetCmd($cmd1);
				if($x1['responseCode'] == 200){
					if(strpos($x1['data']['output'], 'Could not find Mininet host h'.$i) === FALSE){
						$dtReply['data']['h'.$i] = array(); 
						$x2 = explode("\n", $x1['data']['output']);
						/*
						print '<pre>';
						print_r($x2);
						print '</pre>';
						 * 
						 */
						foreach ($x2 as $x3) {
							$x4 = trim($x3);
							//print strpos($x4, 'inet');
							if(strpos($x4, 'inet') === 0){
								$x5 = explode(' ', $x4)[1];
								if($x5 != '127.0.0.1/8'){
									//print $x5;
									$dtReply['data']['h'.$i][$x5] = array(); 
									$x6 = explode('/', $x5);
									$ipCalc = new SubnetCalculator();
									$ipCalc->setNetwork($x6[0], intval($x6[1]));
									$myIntIp = bindec($ipCalc->getIPAddressBinary());
									$intIpSebelah = bindec($ipCalc->getNetworkPortionBinary())+1;
									if($myIntIp == $intIpSebelah){
										$intIpSebelah = bindec($ipCalc->getNetworkPortionBinary())+2;
									}
									$ipSebelah = long2ip($intIpSebelah);
									//print $ipSebelah.'<br>';
									$cmd2 = 'h'.$i.' ping '.$ipSebelah.' -c 1 -W 1';
									$dtReply['data']['h'.$i][$x5] = $this->mininetCmd($cmd2)['data'];
									$dtReply['data']['h'.$i][$x5]['command'] = $cmd2; 
								}
							}
							
						}
					}else{
						$dtReply['status'] = 'success';
						break;
					}
				}else{
					$dtReply['err_message'] = 'Internal service error.';
					break;
				}
			}
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		//header('Content-Type: application/json');
    	print json_encode($dtReply);
	}
	
	public function saveNetworkData(){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(0);
			$this->clearNetworkData(FALSE);
			$dtReply['data'] = array();
			$this->load->library('SubnetCalculator');
			$mininetUtilPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_path"');
			$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
			$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
			
			$ryuApiUrl = 'http://127.0.0.1:8080/';
			
			//Collecting switch data
			$dtSwitches = $this->myCurl($ryuApiUrl.'v1.0/topology/switches', null, 'json');
			$dtReply['data']['switches'] = $dtSwitches;
			foreach ($dtSwitches as $key1 => $dtSwitch) {
				foreach ($dtSwitch['ports'] as $key2 => $swPort) {
					$strSql1 = 'insert ignore into t_switch (`dpid`,`label`) values ("'.$swPort['dpid'].'","'.$swPort['dpid'].'")';
					$this->TdbfSystem->tdbfExecSQL($strSql1);
					$strSql2 = 'insert into t_switch_port (`hw_addr`,`name`,`port_no`,`dpid`) values ("'.$swPort['hw_addr'].'","'.$swPort['name'].'",'.$swPort['port_no'].',"'.$swPort['dpid'].'")';
					$this->TdbfSystem->tdbfExecSQL($strSql2);
				}
				
				//Internal network
				$dtInternalNetwork = $this->myCurl($ryuApiUrl.'router/'.$dtSwitch['dpid'], null, 'json');
				$dtReply['data']['switches'][$key1]['internal_network'] = $dtInternalNetwork[0]['internal_network'][0];
				if (in_array('address',$dtInternalNetwork[0]['internal_network'][0])){
					foreach ($dtInternalNetwork[0]['internal_network'][0]['address'] as $key3 => $dtInternalNetworkAddr) {
						if(strpos($dtInternalNetworkAddr['address'], ':') === FALSE){
							//IPV4
							$x1 = explode('/', $dtInternalNetworkAddr['address']);
							$strSql2_1 = 'insert into t_switch_ipv4 (`dpid`,`index`,`addr`,`prefix`)values("'.$dtSwitch['dpid'].'",'.$key3.',"'.$x1[0].'",'.$x1[1].')';
							$this->TdbfSystem->tdbfExecSQL($strSql2_1);
						}else{
							//IPV6
							$x1 = explode('/', $dtInternalNetworkAddr['address']);
							$strSql2_2 = 'insert into t_switch_ipv6 (`dpid`,`index`,`addr`,`prefix`)values("'.$dtSwitch['dpid'].'",'.$key3.',"'.$x1[0].'",'.$x1[1].')';
							$this->TdbfSystem->tdbfExecSQL($strSql2_2);
						}
					}
				}
				if (in_array('route',$dtInternalNetwork[0]['internal_network'][0])){
					foreach ($dtInternalNetwork[0]['internal_network'][0]['route'] as $key4 => $dtInternalNetworkRoute) {
						$strSql2_3 = 'insert into t_switch_route (`dpid`,`index`,`dst_net`,`gateway`)values("'.$dtSwitch['dpid'].'",'.$key4.',"'.$dtInternalNetworkRoute['destination'].'","'.$dtInternalNetworkRoute['gateway'].'")';
						//print '======='.$strSql2_3.'========';
						$this->TdbfSystem->tdbfExecSQL($strSql2_3);
					}
				}
			}
			
			
			
			//Collecting switct to switch link data
			$dtLinks = $this->myCurl($ryuApiUrl.'v1.0/topology/links', null, 'json');
			$dtReply['data']['links'] = $dtLinks;
			foreach ($dtLinks as $key1 => $dtLink) {
				$strSql3 = 'insert into t_link (`src_hw_addr`,`src_type`,`dst_hw_addr`,`dst_type`,`label`) values ('.
							'"'.$dtLink['src']['hw_addr'].'",'.
							'"SWITCH",'.
							'"'.$dtLink['dst']['hw_addr'].'",'.
							'"SWITCH",'.
							'""'.
							')';
				$this->TdbfSystem->tdbfExecSQL($strSql3);
			}
			
			//Collecting host data
			$dtHosts = $this->myCurl($ryuApiUrl.'v1.0/topology/hosts', null, 'json');
			$dtReply['data']['hosts'] = $dtHosts;
			foreach ($dtHosts as $key1 => $dtHost) {
				$strSql4 = 'insert into t_host (`hw_addr`,`label`) values ("'.$dtHost['mac'].'","'.$dtHost['mac'].'")';
				$this->TdbfSystem->tdbfExecSQL($strSql4);
				$strSql5 = 'insert into t_link (`src_hw_addr`,`src_type`,`dst_hw_addr`,`dst_type`,`label`) values ('.
							'"'.$dtHost['port']['hw_addr'].'",'.
							'"SWITCH",'.
							'"'.$dtHost['mac'].'",'.
							'"HOST",'.
							'""'.
							')';
				$this->TdbfSystem->tdbfExecSQL($strSql5);
				$strSql6 = 'insert into t_link (`src_hw_addr`,`src_type`,`dst_hw_addr`,`dst_type`) values ('.
							'"'.$dtHost['mac'].'",'.
							'"HOST",'.
							'"'.$dtHost['port']['hw_addr'].'",'.
							'"SWITCH")';
				$this->TdbfSystem->tdbfExecSQL($strSql6);
				
				foreach ($dtHost['ipv4'] as $key2 => $dtHostIpv4) {
					$strSql7 = 'insert into t_host_ipv4 (`hw_addr`,`index`,`addr`)values("'.$dtHost['mac'].'",'.$key2.',"'.$dtHostIpv4.'")';
					$this->TdbfSystem->tdbfExecSQL($strSql7);
				}
				foreach ($dtHost['ipv6'] as $key3 => $dtHostIpv6) {
					$strSql8 = 'insert into t_host_ipv6 (`hw_addr`,`index`,`addr`)values("'.$dtHost['mac'].'",'.$key3.',"'.$dtHostIpv6.'")';
					$this->TdbfSystem->tdbfExecSQL($strSql8);
				}
			}
			
			
			//MININET ONLY => Mapping hostname, Get host net prefix, gateway
			$hosts = $this->TdbfSystem->tdbfGetnx1Result('select `hw_addr` from t_host');
			for ($i=1; $i <= 100; $i++) {
				$cmd1 = 'h'.$i.' ifconfig';
				$x1 = $this->mininetCmd($cmd1);
				if($x1['responseCode'] == 200){
					if(strpos($x1['data']['output'], 'Could not find Mininet host h'.$i) === FALSE){
						$hostId = '';
						foreach ($hosts as $hostHwAddr) {
							if(strpos($x1['data']['output'], $hostHwAddr) !== FALSE){
								$hostId = $hostHwAddr;
							}
						}
						if($hostId != ''){
							//Get prefix
							$cmd2 = 'h'.$i.' ip -4 a';
							$x2 = $this->mininetCmd($cmd2);
							$x3 = explode("\n", $x2['data']['output']);
							foreach ($x3 as $x4) {
								$x5 = trim($x4);
								if(strpos($x5, 'inet') === 0){
									$x6 = explode(' ', $x5)[1];
									if($x6 != '127.0.0.1/8'){
										$x7 = explode('/', $x6);
										$strSql9 = 'update t_host_ipv4 set `prefix`='.$x7[1].' where `hw_addr`="'.$hostId.'" and `addr`="'.$x7[0].'"';
										$this->TdbfSystem->tdbfExecSQL($strSql9);	
									}
								}
							}
							
							//Get gateway
							$cmd3 = 'h'.$i.' ip -4 route';
							$x2 = $this->mininetCmd($cmd3);
							$x3 = explode("\n", $x2['data']['output']);
							foreach ($x3 as $x4) {
								$x5 = trim($x4);
								if(strpos($x5, 'default') === 0){
									$x6 = explode(' ', $x5)[2];
									$strSql10 = 'update t_host_ipv4 set `gateway`="'.$x6.'" where `hw_addr`="'.$hostId.'"';
									$this->TdbfSystem->tdbfExecSQL($strSql10);	
								}
							}
							
							//Get routing information
							$cmd3 = 'h'.$i.' ip route';
							$x2 = $this->mininetCmd($cmd3);
							$x3 = explode("\n", $x2['data']['output']);
							$strSql11 = 'update t_host set `hostname`="h'.$i.'", `label`="h'.$i.'", `route`="'.$x2['data']['output'].'" where `hw_addr`="'.$hostId.'"';
							$this->TdbfSystem->tdbfExecSQL($strSql11);	
						}
					}else{
						break;
					}
				}else{
					break;
				}
			}
			
			$dtReply['status'] = 'success';
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		//header('Content-Type: application/json');
		print json_encode($dtReply);
	}
	
	public function getTopologyData($reqType = 'function'){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			set_time_limit(0);
			$dtReply['data'] = array();
			$dtReply['data']['nodes'] = array();
			$dtReply['data']['edges'] = array();
			$strSql = 'select * from `t_switch`';
			$dtSwitches = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
			foreach ($dtSwitches as $key1 => $dtSwitch) {
				$newNode = array('type'=>'SWITCH','dpid'=>$dtSwitch['dpid'],'label'=>$dtSwitch['label'],'port'=>array(),'ipv4'=>array(),'ipv6'=>array(),'route'=>array());
				$strSql = 'select * from t_switch_port where `dpid`="'.$dtSwitch['dpid'].'"';
				$dtSwitchPorts = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtSwitchPorts as $key2 => $dtSwitchPort) {
					array_push($newNode['port'],$dtSwitchPort);
				}
				$strSql = 'select * from t_switch_ipv4 where `dpid`="'.$dtSwitch['dpid'].'" order by `index`';
				$dtSwitchIpv4s = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtSwitchIpv4s as $key2 => $dtSwitchIpv4) {
					array_push($newNode['ipv4'],$dtSwitchIpv4);
				}
				$strSql = 'select * from t_switch_ipv6 where `dpid`="'.$dtSwitch['dpid'].'" order by `index`';
				$dtSwitchIpv6s = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtSwitchIpv6s as $key2 => $dtSwitchIpv6) {
					array_push($newNode['ipv6'],$dtSwitchIpv6);
				}
				$strSql = 'select * from t_switch_route where `dpid`="'.$dtSwitch['dpid'].'" order by `index`';
				$dtSwitchRoutes = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtSwitchRoutes as $key2 => $dtSwitchRoute) {
					array_push($newNode['route'],$dtSwitchRoute);
				}
				$dtReply['data']['nodes'][$dtSwitch['dpid']] = $newNode;
			}
			$strSql = 'select * from `t_host`';
			$dtHosts = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
			foreach ($dtHosts as $key1 => $dtHost) {
				$newNode = array('type'=>'HOST'	,'hw_addr'=>$dtHost['hw_addr'],'hostname'=>$dtHost['hostname'],'label'=>$dtHost['label'],'route'=>$dtHost['route'],'ipv4'=>array(),'ipv6'=>array());
				$strSql = 'select * from t_host_ipv4 where `hw_addr`="'.$dtHost['hw_addr'].'" order by `index`';
				$dtHostIpv4s = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtHostIpv4s as $key2 => $dtHostIpv4) {
					array_push($newNode['ipv4'],$dtHostIpv4);
				}
				$strSql = 'select * from t_host_ipv6 where `hw_addr`="'.$dtHost['hw_addr'].'" order by `index`';
				$dtHostIpv6s = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
				foreach ($dtHostIpv6s as $key2 => $dtHostIpv6) {
					array_push($newNode['ipv6'],$dtHostIpv6);
				}
				$dtReply['data']['nodes'][$dtHost['hw_addr']] = $newNode;
			}
			$strSql = 'select * from t_link order by src_type desc';
			$dtLinks = $this->TdbfSystem->tdbfGetnxnResult($strSql, TRUE);
			foreach ($dtLinks as $key1 => $dtLink) {
				if($dtLink['src_type'] == 'SWITCH'){
					$strSql = 'select dpid from t_switch_port where hw_addr="'.$dtLink['src_hw_addr'].'"';
					$srcNodeId = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				}else{
					$srcNodeId = $dtLink['src_hw_addr'];
				}
				if($dtLink['dst_type'] == 'SWITCH'){
					$strSql = 'select dpid from t_switch_port where hw_addr="'.$dtLink['dst_hw_addr'].'"';
					$dstNodeId = $this->TdbfSystem->tdbfGet1x1Result($strSql);
				}else{
					$dstNodeId = $dtLink['dst_hw_addr'];
				}
				$newEdge = array(	'src_node_id'=>$srcNodeId,'dst_node_id'=>$dstNodeId,
									'label'=>$dtLink['label'],
									'src_hw_addr'=>$dtLink['src_hw_addr'],'dst_hw_addr'=>$dtLink['dst_hw_addr'],
									'src_node_info'=>$dtReply['data']['nodes'][$srcNodeId],'dst_node_info'=>$dtReply['data']['nodes'][$dstNodeId]
								);
				array_push($dtReply['data']['edges'], $newEdge);
				//$dtReply['data']['edges'][$dtLink['src_hw_addr']] = $newEdge;
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
	
	public function viewSwitchList(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlSwitchList',null,'MyTdbfCtlSwitchList');
		$this->MyTdbfCtlSwitchList->init($this,$this->MyTdbfCtlSwitchList,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlSwitchList->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App User Account';
		$this->MyTdbfCtlSwitchList->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlSwitchList->TDBFPARAM);	
	}
	
	public function viewSwitchPortList(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlSwitchPortList',null,'MyTdbfCtlSwitchPortList');
		$this->MyTdbfCtlSwitchPortList->init($this,$this->MyTdbfCtlSwitchPortList,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlSwitchPortList->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App User Account';
		$this->MyTdbfCtlSwitchPortList->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlSwitchPortList->TDBFPARAM);	
	}
	
	public function viewHostList(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlHostList',null,'MyTdbfCtlHostList');
		$this->MyTdbfCtlHostList->init($this,$this->MyTdbfCtlHostList,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlHostList->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App User Account';
		$this->MyTdbfCtlHostList->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlHostList->TDBFPARAM);	
	}
	
	public function viewLinkList(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		
		$this->load->library('../controllers/TdbfCtlLinkList',null,'MyTdbfCtlLinkList');
		$this->MyTdbfCtlLinkList->init($this,$this->MyTdbfCtlLinkList,$this->TdbfSystem->USERINFO);
				
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->MyTdbfCtlLinkList->display('',$pMainPageLoadMode);
		$pHTMLMyContent.=$s1;
		$pHeaderStructuralMenu=array();
		$pHeaderStructuralMenu[0]='<a href="#"><i class="fa fa-dashboard"></i>Administrator</a>';
		$pHeaderStructuralMenu[1]='App User Account';
		$this->MyTdbfCtlLinkList->TDBFPARAM['headerStructuralMenu']=$pHeaderStructuralMenu;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$this->MyTdbfCtlLinkList->TDBFPARAM);	
	}
}
