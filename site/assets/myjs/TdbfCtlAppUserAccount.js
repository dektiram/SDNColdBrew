function myOnReceiveSelectResult(pSelectSessionCode,pHexStrSelectResult){
	//alert('myOnReceiveSelectResult');
	var pDtSelectResult=myHexStr2Json(pHexStrSelectResult);
	//alert(pSelectSessionCode);
	//alert(JSON.stringify(pDtSelectResult));
	var pArSelectSessionCode=pSelectSessionCode.split('/');
	var pPrefixSelectSessionCode=pArSelectSessionCode[0];
	//alert(pPrefixSelectSessionCode);
	switch(pPrefixSelectSessionCode){
		case 'sessAppUserPrivilege':
			mySetUserPrivilege(pSelectSessionCode,pDtSelectResult);
			break;
		case 'sessSelectAppUserOutletId':
			mySetAppUserOutletId(pSelectSessionCode,pDtSelectResult);
			break;
	}
	
}
function mySetUserPrivilege(pSelectSessionCode,pDtSelectResult){
	var pDtRequest={};
	pDtRequest['appUserId']=pSelectSessionCode.split('/')[1];
	pDtRequest['appPrivilege']=pDtSelectResult;
	var pUrl=TdbfCtlAppUserAccount.mBaseUrl+'TdbfRequestHandling/setAppUserPrivilege';
	var pDtReqResult=myAjaxRequest(pUrl,pDtRequest,[],false,'json');
	if(pDtReqResult['requestStatus']==0){
		//alert('Proses sukses.');
		TdbfCtlAppUserAccount.myPageAlert('success','Proses sukses.',true);
	}else{
		//alert('Terjadi kesalahan.');
		TdbfCtlAppUserAccount.myPageAlert('danger','Terjadi kesalahan.',true);
	}
}
function mySetAppUserOutletId(pSelectSessionCode,pDtSelectResult){
	var pDtRequest={};
	pDtRequest['appUserId']=pSelectSessionCode.split('/')[1];
	pDtRequest['selectResultOutletId']=pDtSelectResult;
	var pUrl=TdbfCtlAppUserAccount.mBaseUrl+'TdbfRequestHandling/setAppUserOutletId';
	var pDtReqResult=myAjaxRequest(pUrl,pDtRequest,[],false,'json');
	//alert(pDtReqResult);
	if(pDtReqResult['requestStatus']==0){
		//alert('Proses sukses.');
		TdbfCtlAppUserAccount.myPageAlert('success','Proses sukses.',true);
	}else{
		//alert('Terjadi kesalahan.');
		TdbfCtlAppUserAccount.myPageAlert('danger','Terjadi kesalahan.<br />'+pDtReqResult['errorMessage'],true);
	}
}