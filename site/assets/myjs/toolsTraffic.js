
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

function listenerExecute(){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfListenerExecute/';
	var cmd = document.getElementById('txtCmdListener').value;
	var pDtJSON={};
	pDtJSON['cmds'] = cmd;
	var pFileParam = {};
	var dtReply = myAjaxRequest(url,pDtJSON,pFileParam,false,'text',null);
	//document.getElementById('preListenerCmdResult').innerHTML = dtReply;
	listenerCheck();
};

function listenerKill(pid){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfListenerKill/'+pid;
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	//document.getElementById('preListenerCmdResult').innerHTML = dtReply;
	listenerCheck();
};

function listenerKillall(){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfListenerKillall/';
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	//document.getElementById('preListenerCmdResult').innerHTML = dtReply;
	listenerCheck();
};

function listenerCheck(){
	document.getElementById('bodyTblListener').innerHTML = '';
	document.getElementById('lblListener').innerHTML = 'Process list :';
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfListenerCheck/';
	var dtReply = myAjaxRequest(url,{},{},false,'json',null);
	var strHtml = '';
	for (var hostname in dtReply) {
		for (var i in dtReply[hostname]){
			strHtml = strHtml +
						'<tr class="tabledatarow">'+
						'<td><button class="btn btn-xs" onclick="listenerKill('+String.fromCharCode(39)+dtReply[hostname][i]['pid']+String.fromCharCode(39)+')">Kill</td>'+
						'<td>'+hostname+'</td>'+
						'<td>'+dtReply[hostname][i]['pid']+'</td>'+
						'<td>'+dtReply[hostname][i]['proto']+'</td>'+
						'<td>'+dtReply[hostname][i]['port']+'</td>'+
						'<td>'+dtReply[hostname][i]['log']+'</td>'+
						'<td>'+dtReply[hostname][i]['cmd']+'</td>'+
						'</tr>';
		}
	};
	var d1 = new Date();
	document.getElementById('lblListener').innerHTML = 'Process list (updated on '+d1.toLocaleDateString()+' '+d1.toLocaleTimeString()+') :';
	document.getElementById('bodyTblListener').innerHTML = strHtml;
};

function senderExecute(){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfSenderExecute/';
	var cmd = document.getElementById('txtCmdSender').value;
	var pDtJSON={};
	pDtJSON['cmds'] = cmd;
	var pFileParam = {};
	var dtReply = myAjaxRequest(url,pDtJSON,pFileParam,false,'text',null);
	//document.getElementById('preSenderCmdResult').innerHTML = dtReply;
	senderCheck();
};

function senderKill(pid){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfSenderKill/'+pid;
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	//document.getElementById('preSenderCmdResult').innerHTML = dtReply;
	senderCheck();
};

function senderKillall(){
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfSenderKillall/';
	var dtReply = myAjaxRequest(url,{},{},false,'text',null);
	//document.getElementById('preSenderCmdResult').innerHTML = dtReply;
	senderCheck();
};

function senderCheck(){
	document.getElementById('bodyTblSender').innerHTML = '';
	document.getElementById('lblSender').innerHTML = 'Process list :';
	var url = APP_BASE_URL+'TdbfRequestHandling/iperfSenderCheck/';
	var dtReply = myAjaxRequest(url,{},{},false,'json',null);
	var strHtml = '';
	for (var hostname in dtReply) {
		for (var i in dtReply[hostname]){
			strHtml = strHtml +
						'<tr class="tabledatarow">'+
						'<td><button class="btn btn-xs" onclick="senderKill('+String.fromCharCode(39)+dtReply[hostname][i]['pid']+String.fromCharCode(39)+')">Kill</td>'+
						'<td>'+hostname+'</td>'+
						'<td>'+dtReply[hostname][i]['pid']+'</td>'+
						'<td>'+dtReply[hostname][i]['iperfServer']+'</td>'+
						'<td>'+dtReply[hostname][i]['proto']+'</td>'+
						'<td>'+dtReply[hostname][i]['port']+'</td>'+
						'<td>'+dtReply[hostname][i]['log']+'</td>'+
						'<td>'+dtReply[hostname][i]['cmd']+'</td>'+
						'</tr>';
		}
	};
	var d1 = new Date();
	document.getElementById('lblSender').innerHTML = 'Process list (updated on '+d1.toLocaleDateString()+' '+d1.toLocaleTimeString()+') :';
	document.getElementById('bodyTblSender').innerHTML = strHtml;
};