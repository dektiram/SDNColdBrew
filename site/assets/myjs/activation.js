 mAsyncRequestCount=0;
function activation(pBaseUrl){
	this.mBaseUrl=pBaseUrl;
	this.mEmptyTblResultHtml='';
	this.mViewID='mActivation';
	this.mBtnObjSelectSender=null;
};
activation.prototype.myPageAlert=function(pAlertType,pMessage,pAutoClose){
	var pDateNow=new Date();
	var pDivId='myAlert'+ pDateNow.getFullYear()+ (pDateNow.getMonth()+1)+ pDateNow.getDate()+ pDateNow.getHours()+ pDateNow.getMinutes()+ pDateNow.getSeconds();
	var pAlertDivObj=document.getElementsByClassName('myAlertBox')[0];
	var pOldHtmlStr=pAlertDivObj.innerHTML;
	var pAddHtmlStr='<div class="alert alert-'+pAlertType+' fade in" id="'+pDivId+'">';
	pAddHtmlStr=pAddHtmlStr+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	pAddHtmlStr=pAddHtmlStr+pMessage;
	pAddHtmlStr=pAddHtmlStr+'</div>';
	var pNewHtmlStr=pAddHtmlStr+pOldHtmlStr;
	pAlertDivObj.innerHTML=pNewHtmlStr;
	if(pAutoClose){
		setTimeout(function() {
			$('#'+pDivId).fadeOut('slow', function() {
				$('#'+pDivId).remove();
				//alert('complete');
			});
		}, 5000);
	}
	
};
activation.prototype.myCheckDongle=function(){
	var pDtRequest={};
	var pUrl=this.mBaseUrl+'TdbfRequestHandling/checkDongle';
	//alert(pUrl);
	var pDtReqResult=myAjaxRequest(pUrl,pDtRequest,[],false,'json');
	//this.myPageAlert('info',pDtReqResult,false);
	if(pDtReqResult['requestStatus']==0){
		this.myPageAlert('success',pDtReqResult['successMessage'],true);
	}else{
		this.myPageAlert('warning',pDtReqResult['errorMessage'],false);
	}
};