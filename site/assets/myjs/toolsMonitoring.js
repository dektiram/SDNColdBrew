
function pathLoadToCsv(pAction){
	var url = APP_BASE_URL+'TdbfRequestHandling/monitoringPathLoadToCsv/'+pAction;
	console.log(url);
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	document.getElementById('outputPathLoadToCsv').innerHTML = dtReply;
};
function overloadDetection(pAction){
	var url = APP_BASE_URL+'TdbfRequestHandling/overloadDetection/'+pAction;
	console.log(url);
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	document.getElementById('outputOverloadDetection').innerHTML = dtReply;
};
