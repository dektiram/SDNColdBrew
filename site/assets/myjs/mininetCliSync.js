function mySendMininetSyncCmd(cmd){
	var url = APP_BASE_URL+'Tools/mininetCliSyncExec/raw/'+myStr2Hex(cmd);
	//console.log(url);
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		if(dtJson.status == 'success'){
			console.log(dtJson);
			if(dtJson.data !== null){
				if(dtJson.data.responseCode == 200){
					if(dtJson.data.data.output !== null){
						myDisplayMessage('outSuccess', dtJson.data.data.output);
					}
					if(dtJson.data.data.err !== null){
						myDisplayMessage('outError', dtJson.data.data.err);
					}
				}else{
					myDisplayMessage('outError', 'Internal service error.');
				}
			}else{
				myDisplayMessage('outError', 'Command output is null or timeout.');
			}
		}else{
			myDisplayMessage('outError', 'Internal service error.');
		}
	});
};

function myDisplayMessage(msgType, msgText){
	switch(msgType){
		case 'cmd':
			$('.mininetCliDisplay').append('<pre class="mininetCliDisplayCommand">mininet > '+msgText+'</pre>');
			break;
		case 'outSuccess':
			$('.mininetCliDisplay').append('<pre class="mininetCliDisplayOutputSuccess">'+msgText+'</pre>');
			break;
		case 'outError':
			$('.mininetCliDisplay').append('<pre class="mininetCliDisplayOutputError">'+msgText+'</pre>');
			break;
	}
	//$('.mininetCliDisplay').scrollTop($('.mininetCliDisplay')[0]['clientHeight']);
	$(".mininetCliDisplay").animate({ scrollTop: $('.mininetCliDisplay').prop("scrollHeight")}, 1000);
}

$( document ).ready(function() {
	$("#netObjInfoBody").css({"height":$("#myTopology").height()-40});
	$("#btnSendMininetCmd").click(function(){
		var cmd = $('#txtMininetCmd').val();
		myDisplayMessage('cmd', cmd);
		mySendMininetSyncCmd(cmd);
		$('#txtMininetCmd').val('');
	});
	$( "#txtMininetCmd" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			var cmd = $('#txtMininetCmd').val();
			myDisplayMessage('cmd', cmd);
			mySendMininetSyncCmd(cmd);
			$('#txtMininetCmd').val('');
		}
	});
});

