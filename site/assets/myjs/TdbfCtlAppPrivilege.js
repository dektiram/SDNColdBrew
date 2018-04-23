function myOnReceiveSelectResult(pSelectSessionCode,pHexStrSelectResult){
	//alert('myOnReceiveSelectResult');
	var pDtSelectResult=myHexStr2Json(pHexStrSelectResult);
	//alert(pSelectSessionCode);
	//alert(JSON.stringify(pDtSelectResult));
	var pArSelectSessionCode=pSelectSessionCode.split('/');
	var pPrefixSelectSessionCode=pArSelectSessionCode[0];
	//alert(pPrefixSelectSessionCode);
	switch(pPrefixSelectSessionCode){
		case 'sessAppSelectPrivilegeAction':
			mySetPrivilegeAction(pSelectSessionCode,pDtSelectResult);
			break;
	}
	
}
function mySetPrivilegeAction(pSelectSessionCode,pDtSelectResult){
	var pDtRequest={};
	pDtRequest['appPrivilegeId']=pSelectSessionCode.split('/')[1];
	pDtRequest['appAction']=pDtSelectResult;
	var pUrl=TdbfCtlAppPrivilege.mBaseUrl+'TdbfRequestHandling/setAppPrivilegeAction';
	var pDtReqResult=myAjaxRequest(pUrl,pDtRequest,[],false,'json');
	//alert(pDtReqResult);
	if(pDtReqResult['requestStatus']==0){
		//alert('Proses sukses.');
		TdbfCtlAppPrivilege.myPageAlert('success','Proses sukses.',true);
	}else{
		//alert('Terjadi kesalahan.');
		TdbfCtlAppPrivilege.myPageAlert('danger','Terjadi kesalahan.<br />'+pDtReqResult['errorMessage'],true);
	}
}