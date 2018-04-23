function myPingTrigger(){
	var mode = $('input[name=rdHostname]:checked').val();
	var url = APP_CONTROLLER_URL+'pingTrigger/'+mode;
	mySetThisWindowEnable(false,'Please wait a minutes...');
	myAjaxRequest(url,{'hostnames':$('#hostnameList').val()},{},true,'json',function(pCallBackParam){
		console.log(pCallBackParam);
		mySetThisWindowEnable(true);
		if(pCallBackParam.status == 'success'){
			myPageAlert('success','Ping trigger finish.',true,3000);
		}else{
			myPageAlert('warning','Ping trigger fail.',true,3000);
			console.log(pCallBackParam.err_message);
		}
	});
}

$( document ).ready(function() {
	$('#btnPingTrigger' ).click(function() {
		myPingTrigger();
	});
});