<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfRequestHandling extends CI_Controller {
	var $PAGEPARAM=array();
	var $PAGEFILE=array();
	var $FUNCCOLL;
	public function __construct(){
		parent::__construct(); 
		$this->init();
    }
	public function init(){
		$this->load->model('TdbfFunctionCollection','FUNCCOLL1',true);
		$this->FUNCCOLL=$this->FUNCCOLL1;
		if(isset($_POST['tdbfPageParam'])){
			$this->PAGEPARAM=$this->FUNCCOLL->hexStr2Json($_POST['tdbfPageParam']);
		}
		if(isset($_FILES['tdbfPageFile'])){$this->PAGEFILE=$_FILES['tdbfPageFile'];}
		//print '<pre>';print_r($this->PAGEPARAM);print '</pre>';
	}
	
	public function checkDongle(){
		$return = array();
		$x1 = $this->TdbfLisence->runCheckDongleCommand();
		if($x1 === TRUE){
			$return['requestStatus']=0;
			$return['successMessage']='Dongle found and valid. Refresh page to see detail activation.';
		}else{
			$return['requestStatus']=1;
			$return['errorMessage']=$x1;
		}
		print json_encode($return);
	}
	public function setAppUserPrivilege(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$allowAccess = $this->TdbfSystem->checkAllowedPrivilegeAction('edit_app_user_account', false);
		if(!$allowAccess){
			$pDtReply=array();
			$pDtReply['requestStatus']=1;
			$pDtReply['errorMessage']='Your user account not allowed to access this feature. ';
			print json_encode($pDtReply);
			return;
		}
		
		//print 'aaaaaaaaaaaaaaaaaa';exit();
		$pAppUserId=$this->PAGEPARAM['appUserId'];
		$pAppPrivilege=$this->PAGEPARAM['appPrivilege'];
		foreach($pAppPrivilege['selected'] as $pAppPrivilegeItem){
			$pAppPrivilegeId=$pAppPrivilegeItem['id'];
			$pStrSQL='insert ignore tbl_user_privilege (`user_account_id`,`privilege_id`,`enable`)values("'.$pAppUserId.'","'.$pAppPrivilegeId.'",1)';
			$this->FUNCCOLL->tdbfExecSQL($pStrSQL);
		}
		foreach($pAppPrivilege['deselected'] as $pAppPrivilegeItem){
			$pAppPrivilegeId=$pAppPrivilegeItem['id'];
			$pStrSQL='delete from tbl_user_privilege where `user_account_id`="'.$pAppUserId.'" and `privilege_id`="'.$pAppPrivilegeId.'"';
			$this->FUNCCOLL->tdbfExecSQL($pStrSQL);
		}
		$pDtReply=array();
		$pDtReply['requestStatus']=0;
		print json_encode($pDtReply);
	}
	public function setAppPrivilegeAction(){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$allowAccess = $this->TdbfSystem->checkAllowedPrivilegeAction('edit_app_privilege', false);
		if(!$allowAccess){
			$pDtReply=array();
			$pDtReply['requestStatus']=1;
			$pDtReply['errorMessage']='Your user account not allowed to access this fiture. ';
			print json_encode($pDtReply);
			return;
		}
		//print 'aaaaaaaaaaaaaaaaaa';exit();
		$pAppPrivilegeId=$this->PAGEPARAM['appPrivilegeId'];
		$pAppAction=$this->PAGEPARAM['appAction'];
		foreach($pAppAction['selected'] as $pAppActionItem){
			$pAppActionId=$pAppActionItem['id'];
			$pStrSQL='insert ignore tbl_privilege_action (`privilege_id`,`app_action_id`)values("'.$pAppPrivilegeId.'","'.$pAppActionId.'")';
			$this->FUNCCOLL->tdbfExecSQL($pStrSQL);
		}
		foreach($pAppAction['deselected'] as $pAppActionItem){
			$pAppActionId=$pAppActionItem['id'];
			$pStrSQL='delete from tbl_privilege_action where `privilege_id`="'.$pAppPrivilegeId.'" and `app_action_id`="'.$pAppActionId.'"';
			$this->FUNCCOLL->tdbfExecSQL($pStrSQL);
		}
		$pDtReply=array();
		$pDtReply['requestStatus']=0;
		print json_encode($pDtReply);
	}
	public function userChangeDisplayPicture(){
		$pDtReply=array();
		$pDtReply['requestStatus']=0;
		$uploadedFiles = $this->PAGEFILE;
		$newFile = 'assets/myimages/userImage/'.$this->TdbfSystem->USERINFO['loginUsername'].'.jpg';
		if (move_uploaded_file($uploadedFiles['tmp_name']['filePic'], $newFile)) {
			
		}else{
			$pDtReply['requestStatus']=1;
			$pDtReply['errorMessage']='Error during moving uploded file.';
		}
		print json_encode($pDtReply);
	}
	public function userChangeDisplayName(){
		$pDtReply=array();
		$pDtReply['requestStatus']=0;
		$strSql = 'update `tbl_user_account` set `name`="'.$this->PAGEPARAM['displayName'].'" where `id`="'.$this->TdbfSystem->USERINFO['loginUsername'].'"';
		if(!$this->FUNCCOLL->tdbfExecSQL($strSql)){
			$pDtReply['requestStatus']=1;
			$pDtReply['errorMessage']='Error while saving to database.';
		}
		print json_encode($pDtReply);
	}
	public function userChangePassword(){
		$pDtReply=array();
		$pDtReply['requestStatus']=0;
		$pUsername = $this->TdbfSystem->USERINFO['loginUsername'];
		$oldPassword = $this->PAGEPARAM['oldPassword'];
		$newPassword1 = $this->PAGEPARAM['newPassword1'];
		$newPassword2 = $this->PAGEPARAM['newPassword2'];
		
		
		if($newPassword1 == $newPassword2){
			$pStrSql='select count(*) from tbl_user_account where id="'.$pUsername.'" and password="'.md5($oldPassword).'"';
			$pFlag=$this->FUNCCOLL->tdbfGet1x1Result($pStrSql);
			if($pFlag==1){
				$strSql = 'update `tbl_user_account` set `password`="'.md5($newPassword1).'" where `id`="'.$pUsername.'"';
				if(!$this->FUNCCOLL->tdbfExecSQL($strSql)){
					$pDtReply['requestStatus']=1;
					$pDtReply['errorMessage']='Error while saving to database.';
				}
			}else{
				$pDtReply['requestStatus']=1;
				$pDtReply['errorMessage']='Old password not match.';
			}
		}else{
			$pDtReply['requestStatus']=1;
			$pDtReply['errorMessage']='New password not match.';
		}
		print json_encode($pDtReply);
	}
	
	public function getGraphData(){
		$dtGraph=array('nodes' => array(), 'links' => array());
		$strSql = 'select * from `v_node`';
		$dtSwitch = $this->FUNCCOLL->tdbfGetnxnResult($strSql, TRUE);
		foreach ($dtSwitch as $key => $value) {
			$dtGraph['nodes'][$value['id']] = $value;
			$dtGraph['nodes'][$value['id']]['intf'] = array();
		}
		
		$strSql = 'select * from `v_intf`';
		$dtIntf = $this->FUNCCOLL->tdbfGetnxnResult($strSql, TRUE);
		foreach ($dtIntf as $key => $value) {
			$dtGraph['nodes'][$value['node_id']]['intf'][$value['id']] = $value;
		}

		$strSql = 'select * from `v_link`';
		$dtLink = $this->FUNCCOLL->tdbfGetnxnResult($strSql, TRUE);
		foreach ($dtLink as $key => $value) {
			$srcNodeId = $value['src_node_id'];
			$dstNodeId = $value['dst_node_id'];
			if(!isset($dtGraph['links'][$srcNodeId])){
				$dtGraph['links'][$srcNodeId] = array();
			}
			$dtGraph['links'][$srcNodeId][$dstNodeId] = $value;
		}
		print json_encode($dtGraph);
	}
	
	public function getFlowRoute($srcNodeId, $dstNodeId, $proto, $protoPort ){
		$proto = intval($proto);
		$protoPort = intval($protoPort);
		
		$srcIpv4 = $this->FUNCCOLL->tdbfGet1x1Result('select `ipv4` from `t_host` where `id`="'.$srcNodeId.'"');
		$dstIpv4 = $this->FUNCCOLL->tdbfGet1x1Result('select `ipv4` from `t_host` where `id`="'.$dstNodeId.'"');
		
		$strSql = array(
			0 => array(
				'forward' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`='.$protoPort,
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`='.$protoPort
				),
				'reverse' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`='.$protoPort.' and 
										`dst_proto_port`=-1',
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`='.$protoPort.' and 
										`dst_proto_port`=-1'
				)
			),
			1 => array(
				'forward' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1',
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1'
				),
				'reverse' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1',
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`='.$proto.' and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1'
				)
			),
			2 => array(
				'forward' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`=-1 and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1',
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$srcIpv4.'" and 
										`dst_ipv4`="'.$dstIpv4.'" and 
										`proto`=-1 and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1'
				),
				'reverse' => array(
					'trafficSegment' => 'select * from `t_traffic_segment` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`=-1 and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1',
					'flowTableLink' => 'select * from `v_flow_table_link` where 
										`src_ipv4`="'.$dstIpv4.'" and 
										`dst_ipv4`="'.$srcIpv4.'" and 
										`proto`=-1 and 
										`src_proto_port`=-1 and 
										`dst_proto_port`=-1'
				)
			)
		);
		
		$dtFlowRoute = array('forward' => array(), 'reverse' => array());
		
		for($i=0; $i<=2; $i++){
			$dtSegment = $this->FUNCCOLL->tdbfGetnxnResult($strSql[$i]['forward']['trafficSegment'], TRUE);
			//print $strSql[$i]['forward']['trafficSegment'].'<br />';
			if(sizeof($dtSegment)>0){
				$dtFlowTable = $this->FUNCCOLL->tdbfGetnxnResult($strSql[$i]['forward']['flowTableLink'], TRUE);
				$dtFlowRoute['forward'] = $dtFlowTable;
				//print $strSql[$i]['forward']['flowTableLink'].'<br />';
				break;
			}
		}	
		
		for($i=0; $i<=2; $i++){
			$dtSegment = $this->FUNCCOLL->tdbfGetnxnResult($strSql[$i]['reverse']['trafficSegment'], TRUE);
			//print $strSql[$i]['reverse']['trafficSegment'].'<br />';
			if(sizeof($dtSegment)>0){
				$dtFlowTable = $this->FUNCCOLL->tdbfGetnxnResult($strSql[$i]['reverse']['flowTableLink'], TRUE);
				$dtFlowRoute['reverse'] = $dtFlowTable;
				//print $strSql[$i]['reverse']['flowTableLink'].'<br />';
				break;
			}
		}
		//exit;
		//header('Content-type: application/json; charset=utf-8');
		print json_encode($dtFlowRoute);
	}
	
	function getCurrentIntfBytesInLoad(){
		//$strSql = 'SELECT `name`,`bytes_in_s` FROM `t_intf`';
		//$intfs = $this->FUNCCOLL->tdbfGetnxnResult($strSql, TRUE);
		$csvFile = '/var/log/dsi/bwm-ng/'.date('Y-m-d-H').'.csv';
		$intfs = array();
		$cmd = 'bwm-ng -o csv -c 1';
		$output = shell_exec($cmd);
		$x1 = explode(chr(10), $output);
		//print '<pre>';
		foreach ($x1 as $x2 => $x3) {
			$x4 = explode(';', $x3);
			if(sizeof($x4) >= 4){
				//shell_exec('echo '.chr(39).$x3.chr(39).' >> '.$csvFile);
				array_push($intfs, array('name'=>$x4[1], 'bytes_in_s'=>intval($x4[3])));
			}
			
		}
		//print '</pre>';
		print json_encode($intfs);
	}
	
	function monitoringPathLoadToCsv($action = 'status'){
		switch ($action) {
			case 'start':
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$strSql = 'SELECT `src_intf_name` FROM `v_link` WHERE `src_node_type`="SWITCH" AND `dst_node_type`="SWITCH"';
				$intfs = $this->FUNCCOLL->tdbfGetnx1Result($strSql);
				//$cmd = $dsiAppDir.'/dsi-app.py startBwmNg &';
				$strIntfs = implode(',', $intfs);
				$strTime = date('YmdHis');
				$cmd = '/usr/bin/bwm-ng -t 1000 -I '.$strIntfs.' -T avg -t 10000 -A 21 -o csv '.
						'-F /var/log/dsi/csv/bwm-ng-serial-'.$strTime.'.csv '.
						'> /dev/null 2>/dev/null &';
				$strReply .= shell_exec($cmd).chr(13);
				$cmd = 'ps ax | grep bwm-ng | grep I | grep eth';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
			case 'stop':
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$cmd = 'ps ax | grep bwm-ng | grep I | grep eth';
				$x1 = shell_exec($cmd);
				$x2 = explode(chr(10), $x1);
				//print_r($x2);
				foreach($x2 as $value){
					$x3 = explode(' ', trim($value));
					//print $x3[0];
					$cmd = 'kill -9 '.$x3[0];
					shell_exec($cmd);
				}
				$cmd = 'ps ax | grep bwm-ng | grep I | grep eth';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
			default: //status
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$cmd = 'ps ax | grep bwm-ng | grep I | grep eth';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
		}
	}
	
	function overloadDetection($action = 'status'){
		$dsiAppDir = $this->FUNCCOLL->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="dsi_app_dir"');
		switch ($action) {
			case 'start':
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$cmd = $dsiAppDir.'/dsi-app.py doMonitoringPathLoad '.
					'> /dev/null 2>/dev/null &';
				shell_exec($cmd);
				$cmd = 'ps ax | grep doMonitoringPathLoad';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
			case 'stop':
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$cmd = 'ps ax | grep doMonitoringPathLoad';
				$x1 = shell_exec($cmd);
				$x2 = explode(chr(10), $x1);
				//print_r($x2);
				foreach($x2 as $value){
					$x3 = explode(' ', trim($value));
					//print $x3[0];
					$cmd = 'kill -9 '.$x3[0];
					shell_exec($cmd);
				}
				$cmd = 'ps ax | grep doMonitoringPathLoad';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
			default:
				$strReply = 'Update on '.date('Y-m-d H:i:s'.chr(10));
				$cmd = 'ps ax | grep doMonitoringPathLoad';
				$strReply .= shell_exec($cmd);
				print $strReply;
				break;
		}
	}
	
	function iperfListenerExecute(){
		$mininetUtilDir = $this->FUNCCOLL->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_dir"');
		$strDateNow = date('YmdHis');
		$cmds = $this->PAGEPARAM['cmds'];
		$_SESSION['txtCmdListener'] = $cmds;
		$x1 = explode(chr(10), $cmds);
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		foreach ($x1 as $key => $value) {
			if(trim($value) != ''){
				//get mininet host
				$mininetHost =  explode(' ', trim($value))[0];
				//get iperf proto
				if(strpos($value, ' -u') !== false){$proto = 'udp';}else{$proto = 'tcp';}
				//get iperf port
				if(strpos($value, ' -p ') === false){
					$port = 5201;
				}else{
					$x2 = substr($value, strpos($value, ' -p ')+4);
					$x3 = explode(' ', trim($x2));
					$port = intval($x3[0]);
				}
				
				$fileLog = '/var/log/dsi/iperf/listener.'.$mininetHost.'.'.$proto.'.'.$port.'.log';
				$fileLog = '/var/log/dsi/iperf/listener.'.str_replace(' ', '.', $value);
				$cmd = $mininetUtilDir.'/m '.$value.' > '.$fileLog.' &';//.
					//'2>/dev/null &';
				$strReply .= shell_exec($cmd).chr(10);
			}
		}
		//$strReply .= shell_exec('/home/tarom/mythesis/mininet/util/m h1 iperf -s  -p 3001  > /var/log/dsi/iperf/listener.h1.20170904235748 .log &').chr(10);
		print $strReply;
	}
	
	function iperfListenerKill($pid){
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		$cmd = 'sudo kill -9 '.$pid;
		shell_exec($cmd);
	}
	
	function iperfListenerKillall(){
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		$cmd = 'ps ax | grep iperf | grep "\-s"';
		$x1 = shell_exec($cmd);
		$x2 = explode(chr(10), $x1);
		//print_r($x2);
		foreach($x2 as $value){
			$x3 = explode(' ', trim($value));
			//print $x3[0];
			$cmd = 'sudo kill -9 '.$x3[0];
			print $cmd.chr(10);
			shell_exec($cmd);
		}
	}
	
	function iperfListenerCheck($withPrint = true){
		$mininetUtilDir = $this->FUNCCOLL->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_dir"');
		$mininetHosts = $this->FUNCCOLL->getMininetHostnameList();
		$dtReply = array();
		foreach ($mininetHosts as $key => $hostname) {
			$dtReply[$hostname] = array();
			$cmd = $mininetUtilDir.'/m '.$hostname.' netstat -ntulp | grep iperf';
			$x1 = shell_exec($cmd);
			$x2 = explode(chr(10), $x1);
			foreach (array_filter($x2) as $key => $value) {
				$x3 = array();
				$x3['proto'] = explode(' ', trim($value))[0];
				$x3['port'] = explode(' ',explode(':', $value)[1])[0];
				$x4 = explode(' ',explode('/', $value)[0]);
				$x3['pid'] = $x4[sizeof($x4)-1];
				$x3['log'] = 'listener.'.$hostname.'.'.$x3['proto'].'.'.$x3['port'].'.log';
				$cmd = 'ps -p '.$x3['pid'].' -o cmd';
				$x5 = shell_exec($cmd);
				$x3['cmd'] = explode(chr(10), trim($x5))[1];
				array_push($dtReply[$hostname],$x3);
			}
		}
		if($withPrint){print json_encode($dtReply);}
		return $dtReply;
	}
	
	function iperfSenderExecute(){
		$mininetUtilDir = $this->FUNCCOLL->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_dir"');
		$strDateNow = date('YmdHis');
		$cmds = $this->PAGEPARAM['cmds'];
		$_SESSION['txtCmdSender'] = $cmds;
		$x1 = explode(chr(10), $cmds);
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		foreach ($x1 as $key => $value) {
			if(trim($value) != ''){
				//get mininet host
				$mininetHost =  explode(' ', trim($value))[0];
				//get iperf proto
				if(strpos($value, ' -u') !== false){$proto = 'udp';}else{$proto = 'tcp';}
				//get iperf port
				if(strpos($value, ' -p ') === false){
					$port = 5201;
				}else{
					$x2 = substr($value, strpos($value, ' -p ')+4);
					$x3 = explode(' ', trim($x2));
					$port = intval($x3[0]);
				}
				//get iperf server
				$iperfServer = explode(' ',trim(explode(' -c ', trim($value))[1]))[0];
				
				$fileLog = '/var/log/dsi/iperf/sender.'.$mininetHost.'.'.$iperfServer.'.'.$proto.'.'.$port.'.log';
				$fileLog = '/var/log/dsi/iperf/sender.'.str_replace(' ', '.', $value);
				$cmd = $mininetUtilDir.'/m '.$value.' > '.$fileLog.' &';//.
					//'2>/dev/null &';
				$strReply .= shell_exec($cmd).chr(10);
			}
		}
		//$strReply .= shell_exec('/home/tarom/mythesis/mininet/util/m h1 iperf -s  -p 3001  > /var/log/dsi/iperf/sender.h1.20170904235748 .log &').chr(10);
		print $strReply;
	}
	
	function iperfSenderKill($pid){
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		$cmd = 'sudo kill -9 '.$pid;
		shell_exec($cmd);
	}
	
	function iperfSenderKillall(){
		$strReply = 'Reply on '.date('Y-m-d H:i:s'.chr(10));
		$cmd = 'ps ax | grep iperf | grep "\-c\ "';
		$x1 = shell_exec($cmd);
		$x2 = explode(chr(10), $x1);
		//print_r($x2);
		foreach($x2 as $value){
			$x3 = explode(' ', trim($value));
			//print $x3[0];
			$cmd = 'sudo kill -9 '.$x3[0];
			print $cmd.chr(10);
			shell_exec($cmd);
		}
	}
	
	function iperfSenderCheck(){
		$mininetUtilDir = $this->FUNCCOLL->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_dir"');
		$mininetHosts = $this->FUNCCOLL->getMininetHostnameList();
		$dtIperfListenerCheck = $this->iperfListenerCheck(false);
		$dtReply = array();
		foreach ($mininetHosts as $key => $hostname) {
			$dtReply[$hostname] = array();
			$cmd = $mininetUtilDir.'/m '.$hostname.' netstat -ntup | grep iperf';
			$x1 = shell_exec($cmd);
			$x2 = explode(chr(10), $x1);
			foreach (array_filter($x2) as $key => $value) {
				$x3 = array();
				$x3['proto'] = explode(' ', trim($value))[0];
				$x3['localPort'] = explode(' ',explode(':', $value)[1])[0];
				$x3['port'] = explode(' ',explode(':', $value)[2])[0];
				$x4 = explode(' ',explode(':', $value)[1]);
				$x3['iperfServer'] = $x4[sizeof($x4)-1];
				$x4 = explode(' ',explode('/', $value)[0]);
				$x3['pid'] = $x4[sizeof($x4)-1];
				$x3['log'] = 'sender.'.$hostname.'.'.$x3['proto'].'.'.$x3['port'].'.log';
				$cmd = 'ps -p '.$x3['pid'].' -o cmd';
				$x5 = shell_exec($cmd);
				$x3['cmd'] = explode(chr(10), trim($x5))[1];
				if(intval($x3['localPort']) >= 32768){
					array_push($dtReply[$hostname],$x3);
				}
			}
		}
		print json_encode($dtReply);
	}
}