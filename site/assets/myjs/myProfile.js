function myChangeDisplayPicture(){
	var url = APP_BASE_URL+'Home/uploadDisplayPicture';
	myOpenPopupWindow(url, {}, function(){location.reload();},200);
};
function myUploadFromDisplayPictureForm(){
	var url = APP_BASE_URL+'TdbfRequestHandling/userChangeDisplayPicture/';
	//alert('MASUK');
	var pDtJSON={};
	var pFileParam={};
	pObjInputFileInput=document.getElementById('filePic');
	if(pObjInputFileInput.files.length==0){
		return;
	}
	pFileParam['filePic']=pObjInputFileInput.files[0];
	var pUploadDokumenResult=myAjaxRequest(url,pDtJSON,pFileParam,false,'json',null);
	//alert(pUploadDokumenResult);
	if(pUploadDokumenResult['requestStatus']==0){
		alert('Upload file success.');
		window.close();
	}else{
		alert(pUploadDokumenResult['errorMessage']);
	}
	//var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//pDivResultImportExcelObj.innerHTML=pImportExcelResult;
}

function myChangeDisplayName(){
	var url = APP_BASE_URL+'TdbfRequestHandling/userChangeDisplayName/';
	//alert('MASUK');
	var pDtJSON={};
	pDtJSON['displayName']=document.getElementById('displayName').value;
	var pFileParam={};
	var pUploadDokumenResult=myAjaxRequest(url,pDtJSON,pFileParam,false,'json',null);
	//alert(pUploadDokumenResult);
	if(pUploadDokumenResult['requestStatus']==0){
		location.reload();
	}else{
		alert(pUploadDokumenResult['errorMessage']);
	}
	//var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//pDivResultImportExcelObj.innerHTML=pImportExcelResult;
};

function myChangePassword(){
	var url = APP_BASE_URL+'TdbfRequestHandling/userChangePassword/';
	//alert('MASUK');
	var pDtJSON={};
	pDtJSON['oldPassword']=document.getElementById('oldPassword').value;
	pDtJSON['newPassword1']=document.getElementById('newPassword1').value;
	pDtJSON['newPassword2']=document.getElementById('newPassword2').value;
	var pFileParam={};
	var pUploadDokumenResult=myAjaxRequest(url,pDtJSON,pFileParam,false,'json',null);
	//alert(pUploadDokumenResult);
	if(pUploadDokumenResult['requestStatus']==0){
		location.reload();
	}else{
		alert(pUploadDokumenResult['errorMessage']);
	}
	//var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//pDivResultImportExcelObj.innerHTML=pImportExcelResult;
};