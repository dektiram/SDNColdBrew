function mininetKillCommandProcessList(pids){
	var url = APP_BASE_URL+'Tools/mininetKillCommandProcessList/raw/'+myJson2HexStr(pids);
	console.log(url);
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		if(dtJson.status == 'success'){
			console.log(dtJson);
			if(dtJson.data.responseCode == 200){
				for(var idx1 in dtJson.data.data){
					if(dtJson.data.data[idx1].status == 'SUCCESS'){
						myPageAlert('success','Mininet command process with PID '+dtJson.data.data[idx1].pid.toString()+' was killed.',true,3000);
						$('#row_pid_'+dtJson.data.data[idx1].pid.toString()).remove();
					}else{
						myPageAlert('warning','Mininet command process with PID '+dtJson.data.data[idx1].pid.toString()+' not killed. '+dtJson.data.data[idx1].message,true,3000);
					}
				}
			}else{
				myPageAlert('warning','Internal service error.',true,3000);
			}
		}else{
			myPageAlert('warning','Internal service error.',true,3000);
		}
	});
};


$( document ).ready(function() {
	$('.btnKillMininetCmd').each(function () {
	    var $this = $(this);
	    $this.on("click", function () {
	        //alert($(this).data('pid'));
	        mininetKillCommandProcessList({'pids':[$(this).data('pid')]});
	    });
	});
});

