function myStr2Hex(pString){
	s1=pString;
	s2='';
	for(i=0;i<s1.length;i++){
		//alert(s1[i]);
		s3=s1.charCodeAt(i).toString(16).toUpperCase();
		//alert(s3);
		s2=s2+s3;
		//if(i==2){break;}
	}
	return s2;
};
function myHex2Str(pHexStr){
	var s1,s2,s3;
	var i,j,k,l;
	s1='';
	for(i=0;i<pHexStr.length-1;i=i+2){
		s2=pHexStr[i]+pHexStr[i+1];
		j=parseInt(s2,16);
		s1=s1+String.fromCharCode(j);
	}
	return s1;
};
function myJson2HexStr(pDtJSON){
	s1=JSON.stringify(pDtJSON);
	s2='';
	for(i=0;i<s1.length;i++){
		//alert(s1[i]);
		s3=s1.charCodeAt(i).toString(16).toUpperCase();
		//alert(s3);
		s2=s2+s3;
		//if(i==2){break;}
	}
	return s2;
};
function myHexStr2Json(pHexStr){
	var pDtJSON;
	var s1,s2,s3;
	var i,j,k,l;
	s1='';
	for(i=0;i<pHexStr.length-1;i=i+2){
		s2=pHexStr[i]+pHexStr[i+1];
		j=parseInt(s2,16);
		s1=s1+String.fromCharCode(j);
	}
	pDtJSON=JSON.parse(s1);
	return pDtJSON;
};
function myAjaxRequest(pUrl,pJSONParam,pFileParam,pAsync,pReplyType,pCallBackFunction){
	//alert('myAjaxPost');
	var pStrReply='';
	var pDataPost=new FormData();
	pDataPost.append('tdbfPageParam',this.myJson2HexStr(pJSONParam));
	for(var pFileKeyName in pFileParam){
		pDataPost.append('tdbfPageFile['+pFileKeyName+']',pFileParam[pFileKeyName]);
	}
	//alert(pUrl);
	$.ajax({
		url: pUrl,
		type: 'POST',
		data: pDataPost,
		processData: false,
		contentType: false,
		async: pAsync,
		success: function (pHTML) {
			pStrReply=pHTML;
			//alert(pHTML);
			if(pCallBackFunction!=undefined){
				switch(pReplyType){
					case 'text':
						var pCallBackParam=pStrReply;
						break;
					case 'json':
						var pCallBackParam={};
						try{
							pCallBackParam=JSON.parse(pStrReply);
						}catch(err){
							pCallBackParam=pCallBackParam;
						}
						break;
				}
				pCallBackFunction(pCallBackParam);
			}
		}//,
		//error: function (xhr, ajaxOptions, thrownError) {
		//	alert(xhr.status);
		//	alert(thrownError);
		//}
	});
	switch(pReplyType){
		case 'text':
			return pStrReply;
			break;
		case 'json':
			pDtReply={};
			try{
				pDtReply=JSON.parse(pStrReply);
				return pDtReply;
			}catch(err){
				//alert('HAHAHAHA');
				return pDtReply;
			}
			break;
	}
};

function creteFormAndSubmit(postParam) {
	var form = document.createElement('form');
	document.body.appendChild(form);
	form.method = 'POST';
	form.action = '';    
	for(var x1 in postParam){
		var element = null;
		element = document.createElement('INPUT');
		element.name = x1;
		element.value = postParam[x1];
		element.type = 'hidden';
		form.appendChild(element);
	}
	form.submit();
};

function myDrawBreadCrumb(){
	
	var innerHTML = '';
	var objMenu = document.getElementById('menu_'+APP_ACTIVE_MENU_ID);
	if(objMenu === null){return;}
	if(objMenu.hasAttribute('id')){
		var i=1;
		do {
			if(objMenu.hasAttribute('id')){
				if(i==1){strClass = 'active';}else{strClass = '';}
		    	innerHTML = '<li class="'+strClass+'">'+ 
		    				'<a href="'+APP_BASE_URL+'/'+$('#'+objMenu.id).data('url')+'">'+ 
		    				'<i class="'+$('#'+objMenu.id).data('icon')+'"></i>'+ 
		    				$('#'+objMenu.id).data('label')+ 
		    				'</a>'+ 
		    				'</li>'+innerHTML;
		   }
	    	do{
	    		objMenu = objMenu.parentElement;
	    		//console.log(objMenu.tagName);
	    	}while((objMenu.tagName != 'LI')&&(objMenu.id != 'ulRootMenu'));
	    	//alert(objMenu.id);
	    	i++;
		}while ((objMenu.id != 'ulRootMenu') && (objMenu.id !== undefined));
		//alert(innerHTML);
		document.getElementById('olBreadcrumb').innerHTML = innerHTML;
	}
	
}
//tak tambahin disini aja dari pada create file 1 1

function mySetThisWindowEnable(pEnable, overlayHTML){
	if(!pEnable){
		var overlay = jQuery('<div id="myOverlayPage" class="myOverlayPage">'+overlayHTML+'</div>');
		overlay.appendTo(document.body);
	}else{
		var pDivOverlay=document.getElementById('myOverlayPage');
		pDivOverlay.parentElement.removeChild(pDivOverlay);
	}
};
function myOpenPopupWindow(pUrl, pPostParam, onAfterClose, pMargin){
	mySetThisWindowEnable(false);
	var pMargin=pMargin || 100;
	var pFormTop=(pMargin-50).toString();
	var pFormLeft=pMargin.toString();
	var pFormHeight=(screen.height - (pMargin * 2)).toString();
	var pFormWidth=(screen.width - (pMargin * 2)).toString();
	var pPopup=window.open('', '', 'top='+pFormTop+',left='+pFormLeft+',height='+pFormHeight+',width='+pFormWidth+'');
	var form = $('<form action="' + pUrl + '" method="post">' +
		'<input type="hidden" name="tdbfPageParam" value="' + myJson2HexStr(pPostParam) + '" />' +
		'</form>');
	form.appendTo(pPopup.document.body);
	form.submit();
	var pTimer = setInterval(function() {
        if (pPopup.closed) {
            clearInterval(pTimer);
			var pDivOverlay=document.getElementById('myOverlayPage');
			pDivOverlay.parentElement.removeChild(pDivOverlay);
			if(onAfterClose !== undefined){
				onAfterClose();
			}
       }
      },500);
};

function sleep(delay) {
	var start = new Date().getTime();
	while (new Date().getTime() < start + delay);
};

function myPageAlert(pAlertType,pMessage,pAutoClose,pWaitCloseSeconds){
	var pDateNow=new Date();
	sleep(1);
	var pDivId='myAlert'+ pDateNow.getFullYear()+ (pDateNow.getMonth()+1)+ pDateNow.getDate()+ pDateNow.getHours()+ pDateNow.getMinutes()+ pDateNow.getSeconds()+ pDateNow.getMilliseconds();
	var pAlertDivObj= document.getElementsByClassName('myAlertBox')[0];
	var pOldHtmlStr=pAlertDivObj.innerHTML;
	var pAddHtmlStr='<div class="alert alert-'+pAlertType+' fade in" id="'+pDivId+'">';
	pAddHtmlStr=pAddHtmlStr+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	pAddHtmlStr=pAddHtmlStr+pMessage;
	pAddHtmlStr=pAddHtmlStr+'</div>';
	var pNewHtmlStr=pAddHtmlStr+pOldHtmlStr;
	pAlertDivObj.innerHTML=pNewHtmlStr;
	setTimeout(function() {
		$('#'+pDivId).fadeOut('slow', function() {
			$('#'+pDivId).remove();
			//alert('complete');
		});
	}, pWaitCloseSeconds);
}