<script type="text/javascript">
	APP_BASE_URL = "<?php print base_url();?>";
	APP_CONTROLLER_URL = "<?php $explodedUri = explode('/',uri_string());print base_url().$explodedUri[0].'/';?>";
	APP_ACTIVE_MENU_ID = "<?php if(isset($GLOBALS['currentMenuId'])){print $GLOBALS['currentMenuId'];} ?>";
	
	$( document ).ready(function() {
		myDrawBreadCrumb();
		<?php
		if(isset($otherParam['alertMessage'])){
			$pageMessageAlert=$otherParam['alertMessage'];
			foreach ($pageMessageAlert as $msgType => $msgs) {
				foreach($msgs as $msg){
					if($msg['autoClose']){$x1='true';}else{$x1='false';}
					print "myPageAlert('".$msgType."','".$msg['text']."',".$x1.",2000);";
				}
			}
		}
		?>
	});
</script>