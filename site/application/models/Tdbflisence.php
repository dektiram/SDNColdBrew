<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TdbfLisence extends CI_Model  {
	
	public function __construct(){
		parent::__construct();
		$this->getDongleLisence();
    }
	public function runCheckDongleCommand(){
		$s1 = shell_exec('sudo /usr/bin/python /other/tiramlisence/readlisence.py');
		#print $s1;
		$dongleDir = $this->config->item('dongle_dir');
		if(
			(file_exists($dongleDir.'dongle.content'))and
			(file_exists($dongleDir.'dongle.block.0'))and
			(file_exists($dongleDir.'dongle.block.1'))and
			(file_exists($dongleDir.'dongle.block.2'))and
			(file_exists($dongleDir.'dongle.block.3'))and
			(file_exists($dongleDir.'dongle.block.4'))
		){
			$this->getDongleLisence();
			if($GLOBALS['dataLisence']['lisenceStatus']){
				return TRUE;
			}else{
				return 'Invalid dongle lisence.';
			}
		}else{
			return 'Cannot read dongle.';
		}
	}
	public function getDongleLisence(){
		$dongleBypass = $this->config->item('dongle_bypass');
		$dongleDir = $this->config->item('dongle_dir');
		//$dtReturn['lisenceStatus'] = 'asfasfas';
		
		$dtReturn = array();
		$dtReturn['dongleContent']='';
		$dtReturn['appName'] = '';
		$dtReturn['appVersion'] = '';
		$dtReturn['lisenceDate'] = '';
		$dtReturn['lisenceTo'] = '';
		$dtReturn['maxSewaItem'] = 0;
		if($dongleBypass){
			$dtReturn['lisenceStatus'] = TRUE;
			$dtReturn['appName'] = 'SDN ColdBrew';
			$dtReturn['appVersion'] = '1.0.0';
			$dtReturn['lisenceDate'] = '2018-02-08';
			$dtReturn['lisenceTo'] = 'mukhtarom@ub.ac.id';
		}else{
			if(file_exists($dongleDir.'dongle.content')){
				$dtReturn['dongleContent'] = file_get_contents($dongleDir.'dongle.content');
			}
			$dtBlock = array();
			$dtBlock[0] = '';
			$dtBlock[1] = '';
			$dtBlock[2] = '';
			$dtBlock[3] = '';
			$dtBlock[4] = '';
			if(file_exists($dongleDir.'dongle.block.0')){
				$dtBlock[0] = trim(file_get_contents($dongleDir.'dongle.block.0'));
			}
			if(file_exists($dongleDir.'dongle.block.1')){
				$dtBlock[1] = trim(file_get_contents($dongleDir.'dongle.block.1'));
			}
			if(file_exists($dongleDir.'dongle.block.2')){
				$dtBlock[2] = trim(file_get_contents($dongleDir.'dongle.block.2'));
			}
			if(file_exists($dongleDir.'dongle.block.3')){
				$dtBlock[3] = trim(file_get_contents($dongleDir.'dongle.block.3'));
			}
			if(file_exists($dongleDir.'dongle.block.4')){
				$dtBlock[4] = trim(file_get_contents($dongleDir.'dongle.block.4'));
			}
			if(
				($dtBlock[1] == 'hanacarakadatasawalamagabathangapadhajayanya') 
			){
				$dtReturn['lisenceStatus'] = TRUE;
				$dtReturn['dongleBlock'] = $dtBlock;
				$ar1 = explode('|', $dtBlock[0]);
				$dtReturn['appName'] = $ar1[0];
				$dtReturn['appVersion'] = $ar1[1];
				$dtReturn['lisenceDate'] = $ar1[2];
				$dtReturn['lisenceTo'] = $ar1[3];
				$dtReturn['maxSewaItem'] = 0;
			}else{
				$dtReturn['lisenceStatus'] = FALSE;
			}
		}
		$GLOBALS['dataLisence'] = $dtReturn;
		
		return $dtReturn;
	}
}