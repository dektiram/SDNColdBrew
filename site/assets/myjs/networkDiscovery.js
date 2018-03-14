function myPingTrigger(){
	var url = APP_CONTROLLER_URL+'pingTrigger';
	mySetThisWindowEnable(false,'Please wait a minutes...');
	myAjaxRequest(url,{},{},true,'json',function(pCallBackParam){
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

function myClearNetworkData(){
	var url = APP_CONTROLLER_URL+'clearNetworkData';
	mySetThisWindowEnable(false,'Please wait a minutes...');
	myAjaxRequest(url,{},{},true,'json',function(pCallBackParam){
		console.log(pCallBackParam);
		mySetThisWindowEnable(true);
		if(pCallBackParam.status == 'success'){
			myPageAlert('success','Clear network data finish.',true,3000);
		}else{
			myPageAlert('warning','Clear network data fail.',true,3000);
			console.log(pCallBackParam.err_message);
		}
	});
}

function mySaveNetworkData(){
	var url = APP_CONTROLLER_URL+'saveNetworkData';
	mySetThisWindowEnable(false,'Please wait a minutes...');
	myAjaxRequest(url,{},{},true,'json',function(pCallBackParam){
		console.log(pCallBackParam);
		mySetThisWindowEnable(true);
		if(pCallBackParam.status == 'success'){
			myPageAlert('success','Save network data finish.',true,3000);
		}else{
			myPageAlert('warning','Save network data fail.',true,3000);
			console.log(pCallBackParam.err_message);
		}
	});
}

$( document ).ready(function() {
	$('#btnPingTrigger' ).click(function() {
		myPingTrigger();
	});
	$('#btnClearNetworkData' ).click(function() {
		myClearNetworkData();
	});
	$('#btnSaveNetworkData' ).click(function() {
		mySaveNetworkData();
	});
});