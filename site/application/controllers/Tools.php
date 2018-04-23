<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {
	//var $pageParam=array();
	public function __construct(){
		parent::__construct(); 
    }
	
	public function mininetPingAll(&$viewMode=''){
		$this->TdbfSystem->ifNotLoginRedirectToLoginPage();
		$pageAlert = array('success'=>array(),'info'=>array(),'warning'=>array(),'danger'=>array());
		$pPageParam=array();
		$pPageParam['otherParam']=array();
		$pPageParam['otherParam']['alertMessage'] = $pageAlert;
		$pPageParam['otherParam']['currentMenuId'] = 'viewNetGraph';
		$pPageParam['USERINFO']=$this->TdbfSystem->USERINFO;
		$pHTMLMyContent='';
		$pMainPageLoadMode='full';
		$s1=$this->load->view('myView/network/MininetPingAll',$pPageParam,true);
		$pHTMLMyContent.=$s1;
		$this->TdbfSystem->displayToBrowser($pHTMLMyContent,$pMainPageLoadMode,$pPageParam);	
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
	
	public function pingTrigger($hostnameMode = 'Auto'){
		$dtReply = array('status'=>'fail');
		if($this->TdbfSystem->isUserLoggedIn()){
			//set_time_limit(0);
			$dtReply['data'] = array();
			$this->load->library('SubnetCalculator');
			$mininetUtilPath = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="mininet_util_path"');
			$internalServiceUrl = $this->TdbfSystem->tdbfGet1x1Result('select `val` from `tbl_setting` where `id`="internal_service_url"');
			
			$strHostnames = json_decode($this->TdbfSystem->hex2Str($_POST['tdbfPageParam']),TRUE)['hostnames'];
			$arHostname = array();
			if($hostnameMode == 'Manual'){
				$arHostname = explode(' ', $strHostnames);
			}else{
				for ($i=1; $i <= 1000; $i++) {
					$arHostname[$i-1] = 'h'.$i;
				}
			}
			
			//print_r($arHostname);
			foreach ($arHostname as $key => $hostname) {
				$cmd1 = $hostname.' ip -4 a';
				//print $cmd1;
				$x1 = $this->mininetCmd($cmd1);
				//print_r($x1);
				if($x1['responseCode'] == 200){
					$staLanjut = TRUE;
					if($hostnameMode == 'Auto'){
						if(strpos($x1['data']['output'], 'Could not find Mininet host '.$hostname) !== FALSE){
							$staLanjut = FALSE;
						}
					}
					if($staLanjut){
						$dtReply['data'][$hostname] = array(); 
						$x2 = explode("\n", $x1['data']['output']);
						/*
						print '<pre>';
						print_r($x2);
						print '</pre>';
						*/
						foreach ($x2 as $x3) {
							$x4 = trim($x3);
							//print strpos($x4, 'inet');
							if(strpos($x4, 'inet') === 0){
								$x5 = explode(' ', $x4)[1];
								if($x5 != '127.0.0.1/8'){
									//print $x5;
									$dtReply['data'][$hostname][$x5] = array(); 
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
									$cmd2 = $hostname.' ping '.$ipSebelah.' -c 1 -W 1';
									$dtReply['data'][$hostname][$x5] = $this->mininetCmd($cmd2)['data'];
									$dtReply['data'][$hostname][$x5]['command'] = $cmd2; 
								}
							}
							
						}
					}else{
						break;
					}
					$dtReply['status'] = 'success';
				}else{
					$dtReply['status'] = 'fail';
					$dtReply['err_message'] = 'Internal service error.';
					break;
				}
			}
			//Could not find Mininet host h100
		}else{
			$dtReply['err_message'] = 'Your login session is expired.';
		}
		//header('Content-Type: application/json');
    	print json_encode($dtReply);
	}
	
}
