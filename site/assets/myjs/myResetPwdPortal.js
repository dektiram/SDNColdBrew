function myResetPwdPortal(){
	var url = APP_BASE_URL+'TdbfRequestHandling/resetPwdPortal/';
	//alert('MASUK');
	var pDtJSON={};
	pDtJSON['tipeAkun']=document.getElementById('tipeAkun').value;
	pDtJSON['akunId']=document.getElementById('akunId').value;
	pDtJSON['resetPassword']=document.getElementById('resetPassword').value;
	var pFileParam={};
	var pUploadDokumenResult=myAjaxRequest(url,pDtJSON,pFileParam,false,'json',null);
	//alert(pUploadDokumenResult);
	if(pUploadDokumenResult['requestStatus']==0){
		alert('Reset password sukses.');
		location.reload();
	}else{
		alert(pUploadDokumenResult['errorMessage']);
	}
	//var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//pDivResultImportExcelObj.innerHTML=pImportExcelResult;
};