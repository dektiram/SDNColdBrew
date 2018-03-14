function myEnvStart(){
	var url = APP_CONTROLLER_URL+'envStart';
	var postParam={'packagedRyuScript':[],'additionalRyuScript':[],'mininetTopoScript':[]};
	var x1 = document.getElementsByClassName('chkPackagedRyuScript');
	for(var x2 in x1){
		if(x1[x2].checked){
			postParam['packagedRyuScript'].push(x1[x2].value);
		}
	}
	var x1 = document.getElementsByClassName('chkAdditionalRyuScript');
	for(var x2 in x1){
		if(x1[x2].checked){
			postParam['additionalRyuScript'].push(x1[x2].value);
		}
	}
	if((postParam['packagedRyuScript'].length + postParam['additionalRyuScript'].length)== 0){
		myPageAlert('warning','No ryu script selected.',true,3000);
		return;
	}
	mySetThisWindowEnable(false,'Loading...');
	myAjaxRequest(url,postParam,{},true,'text',function(pCallBackParam){
		console.log(pCallBackParam);
		//mySetThisWindowEnable(true);
		location.reload();
	});
}

function myEnvStop(){
	var url = APP_CONTROLLER_URL+'envStop';
	mySetThisWindowEnable(false,'Loading...');
	myAjaxRequest(url,{},{},true,'text',function(pCallBackParam){
		console.log(pCallBackParam);
		//mySetThisWindowEnable(true);
		location.reload();
	});
}
$( document ).ready(function() {
	$('.btnDelAdditionalRyuScript' ).click(function() {
		creteFormAndSubmit({'delRyuScript':$(this).data('file')});
	});
	$('.btnDelMininetTopoScript' ).click(function() {
		creteFormAndSubmit({'delMininetTopoScript':$(this).data('file')});
	});
	$('#btnEnvStart' ).click(function() {
		myEnvStart();
	});
	$('#btnEnvStop' ).click(function() {
		myEnvStop();
	});
});