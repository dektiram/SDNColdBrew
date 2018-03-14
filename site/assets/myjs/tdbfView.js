function tdbfView(pViewID,pCtlUri,pBaseUrl){
	//alert(0);
	this.mViewID=pViewID;
	this.mCtlUri=pCtlUri;
	this.mBaseUrl=pBaseUrl;
};

tdbfView.prototype.myTesting=function(){
	return 'Testing OK';
};
tdbfView.prototype.myPrepare=function(){
	this.mSectionContentObj=document.getElementById(this.mViewID+'_section');
	//delete data field row and save HTML to variable
	this.mTblFilterBodyObj=document.getElementById(this.mViewID+'_section').getElementsByClassName('tblFilter')[0].getElementsByTagName('tbody')[0];
	//alert('MASUK');
	this.mTblFilterRowPatternObj=this.mTblFilterBodyObj.getElementsByTagName('tr')[0];
	//alert(this.mFilterRowHTML);
	this.mTblFilterBodyObj.getElementsByTagName('tr')[0].remove();
	this.mDivDlgImportExcelObj=document.getElementById(this.mViewID+'_section').getElementsByClassName('divDialogImportExcel')[0];
	this.mDivDlgDeleteExcelObj=document.getElementById(this.mViewID+'_section').getElementsByClassName('divDialogDeleteExcel')[0];
	this.mTblDataRecordObj=document.getElementById(this.mViewID+'_tblData');
};
tdbfView.prototype.myPageAlert=function(pAlertType,pMessage,pAutoClose){
	var pDateNow=new Date();
	var pDivId='myAlert'+ pDateNow.getFullYear()+ (pDateNow.getMonth()+1)+ pDateNow.getDate()+ pDateNow.getHours()+ pDateNow.getMinutes()+ pDateNow.getSeconds();
	var pAlertDivObj=this.mSectionContentObj.getElementsByClassName('myAlertBox')[0];
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
	}, 5000);
};
tdbfView.prototype.myJson2HexStr=function(pDtJSON){
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
tdbfView.prototype.myHexStr2Json=function(pHexStr){
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
tdbfView.prototype.myMoveUpColPrefRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if(pRowIdx>0){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx], pRowObj.parentElement.children[pRowIdx-1]);
	}
};
tdbfView.prototype.myMoveDownColPrefRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if((pRowIdx+1)<(pRowObj.parentElement.childElementCount)){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx+1], pRowObj.parentElement.children[pRowIdx]);
	}
};
tdbfView.prototype.myGetColPrefJSON=function(){
	pDivPrefObj=document.getElementById(this.mViewID+'_divPreference');
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']['recordPerPage']=pDivPrefObj.getElementsByClassName('txtRecordPerPage')[0].value;
	pDtJSON[this.mViewID]['customParam']['viewPref']['colPref']=[];
	var pRowsColPrefObj=pDivPrefObj.getElementsByClassName('tblColPreference')[0].getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	
	for(i=0;i<pRowsColPrefObj.length;i++){
		pDtJSON[this.mViewID]['customParam']['viewPref']['colPref'][i]={};
		pDtJSON[this.mViewID]['customParam']['viewPref']['colPref'][i]['name']=pRowsColPrefObj[i].getElementsByTagName('td')[0].innerHTML;
		pDtJSON[this.mViewID]['customParam']['viewPref']['colPref'][i]['caption']=pRowsColPrefObj[i].getElementsByTagName('td')[1].innerHTML;
		pDtJSON[this.mViewID]['customParam']['viewPref']['colPref'][i]['visible']=pRowsColPrefObj[i].getElementsByTagName('td')[2].getElementsByClassName('chkColPrefVisible')[0].checked;
	}
	return pDtJSON;
};
tdbfView.prototype.myApplyColPref=function(){
	var pDtJSON=this.myGetColPrefJSON();
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	//alert(JSON.stringify(pDtJSON));
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.mySaveColPref=function(){
	pDtJSON=this.myGetColPrefJSON();
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	pDtJSON[this.mViewID]['userCommand'].push('saveViewPref');
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myResetColPref=function(){
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('resetViewPref');
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myGotoUrlWithPostPageParam=function(pUrl,pJSONParam){
	//alert(0);
	var form = $('<form action="' + pUrl + '" method="post">' +
		'<input type="hidden" name="tdbfPageParam" value="' + this.myJson2HexStr(pJSONParam) + '" />' +
		'</form>');
	$('body').append(form);
	form.submit();
};
tdbfView.prototype.myAjaxPost=function(pUrl,pJSONParam,pFileParam,pAsync){
	//alert('myAjaxPost');
	pAsync = pAsync || false;
	pStrReply='';
	pDataPost=new FormData();
	pDataPost.append('tdbfPageParam',this.myJson2HexStr(pJSONParam));
	//pDataPost.append('tdbfPageFile['+this.mViewID+'][importExcel]',pFileParam[this.mViewID]['importExcel']);
	if(this.mViewID in pFileParam){
		for(var pFileKeyName in pFileParam[this.mViewID]){
			pDataPost.append('tdbfPageFile['+this.mViewID+']['+pFileKeyName+']',pFileParam[this.mViewID][pFileKeyName]);
		}
	}
	//pDataPost.append('tdbfPageFile['+this.mViewID+'][importExcel2]',pFileParam[this.mViewID]['importExcel2']);
	//alert(1);
	//for(var pFileKeyParam in pFileParam[this.mV]){
	//	alert(pFileKeyParam);
	//	pDataPost.append(pFileKeyParam, pFileParam[pFileKeyParam]);
	//}
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
		}
	});
	return pStrReply;
};
tdbfView.prototype.myAddFilterRow=function(){
	var pChild = this.mTblFilterRowPatternObj.cloneNode(true);
	//alert(pChild.innerHTML);
	this.mTblFilterBodyObj.appendChild(pChild);
};
tdbfView.prototype.myRemoveFilterRow=function (pBtnRemoveObj){
	pBtnRemoveObj.parentElement.parentElement.remove();
};
tdbfView.prototype.myGetFilterJSON=function(){
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['viewFilter']={};
	pDtJSON[this.mViewID]['customParam']['viewFilter']=[];
	var pFilterRowsObj=this.mTblFilterBodyObj.getElementsByTagName('tr');
	
	for(i=0;i<pFilterRowsObj.length;i++){
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]={};
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['block1']=pFilterRowsObj[i].getElementsByClassName('txtFilterBlock1')[0].value;
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['colName']=pFilterRowsObj[i].getElementsByClassName('cmbFilterField')[0].value;
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['operand1']=pFilterRowsObj[i].getElementsByClassName('cmbFilterOperand1')[0].value;
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['value']=pFilterRowsObj[i].getElementsByClassName('txtFilterValue')[0].value;
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['block2']=pFilterRowsObj[i].getElementsByClassName('txtFilterBlock2')[0].value;
		pDtJSON[this.mViewID]['customParam']['viewFilter'][i]['operand2']=pFilterRowsObj[i].getElementsByClassName('cmbFilterOperand2')[0].value;
	}
	return pDtJSON;
};
tdbfView.prototype.myApplyFilter=function(){
	pDtJSON=this.myGetFilterJSON();
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyFilter');
	//alert(JSON.stringify(pDtJSON));
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myResetFilter=function(){
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('resetFilter');
	pDtJSON[this.mViewID]['userCommand'].push('applyFilter');
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myChangeViewOrder=function(pOrderBy,pOrderMode){
	//alert(0);
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']['orderBy']=pOrderBy;
	pDtJSON[this.mViewID]['customParam']['viewPref']['orderMode']=pOrderMode;
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myGotoPageIndex=function(pPageIndex){
	//alert(0);
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']['pageIndex']=pPageIndex;
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.mySearchRecord=function(pKeyword){
	//alert(pKeyword);
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('applyViewPref');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']={};
	pDtJSON[this.mViewID]['customParam']['viewPref']['searchKeyword']=pKeyword;
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myAddButtonClick=function(){
	//alert(pKeyword);
	var pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='add';
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myShowImportExcelDialog=function(){
	//membersihkan file input & div result
	var pObjInputFileInput=this.mDivDlgImportExcelObj.getElementsByClassName('inpFileExcel')[0];
	var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//alert('haha');
	if(pObjInputFileInput.files.length > 0){
		//alert('ada file');
		pObjInputFileInput.value='';
	}
	pDivResultImportExcelObj.innerHTML='';
	
	$('#'+this.mViewID+'_divDialogImportExcel').modal();
};
tdbfView.prototype.myShowDeleteExcelDialog=function(){
	//membersihkan file input & div result
	var pObjInputFileInput=this.mDivDlgDeleteExcelObj.getElementsByClassName('inpFileExcel')[0];
	var pDivResultDeleteExcelObj=this.mDivDlgDeleteExcelObj.getElementsByClassName('divResultDeleteExcel')[0];
	//alert('haha');
	if(pObjInputFileInput.files.length > 0){
		//alert('ada file');
		pObjInputFileInput.value='';
	}
	pDivResultDeleteExcelObj.innerHTML='';
	
	$('#'+this.mViewID+'_divDialogDeleteExcel').modal();
};
tdbfView.prototype.myDownloadTemplateExcelButtonClick=function(){
	//alert('myDownloadTemplateExcelButtonClick');
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='templateExcel';
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myImportExcelButtonClick=function(){
	//alert('MASUK');
	var pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['userCommand'].push('importExcel');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='importExcel';
	pChkUpdateIfExist=this.mDivDlgImportExcelObj.getElementsByClassName('chkImportExcelUpdateIfExist')[0];
	//alert(pChkUpdateIfExist);
	if( typeof pChkUpdateIfExist === 'undefined' || pChkUpdateIfExist === null ){
		pImportExcelUpdateIfExist=false;
	}else{
		pImportExcelUpdateIfExist=pChkUpdateIfExist.checked;
	}
	
	if(pImportExcelUpdateIfExist){
		pDtJSON[this.mViewID]['customParam']['importExcelUpdateIfExist']=true;
	}
	var pUrl=window.location.href,pDtJSON;
	//alert(pUrl);
	var pFileParam={};
	pFileParam[this.mViewID]={};
	pObjInputFileInput=this.mDivDlgImportExcelObj.getElementsByClassName('inpFileExcel')[0];
	if(pObjInputFileInput.files.length==0){
		return;
	}
	pFileParam[this.mViewID]['importExcel']=pObjInputFileInput.files[0];
	var pImportExcelResult=this.myAjaxPost(pUrl,pDtJSON,pFileParam,false);
	var pDivResultImportExcelObj=this.mDivDlgImportExcelObj.getElementsByClassName('divResultImportExcel')[0];
	//alert(pImportExcelResult);
	pDivResultImportExcelObj.innerHTML=pImportExcelResult;
};
tdbfView.prototype.myExportExcelButtonClick=function(){
	//alert('myExportExcelButtonClick');
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['userCommand'].push('exportExcel');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='exportExcel';
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myDeleteExcelButtonClick=function(){
	//alert('MASUK');
	var pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['userCommand'].push('deleteExcel');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='deleteExcel';
	var pUrl=window.location.href,pDtJSON;
	//alert(pUrl);
	var pFileParam={};
	pFileParam[this.mViewID]={};
	var pObjInputFileInput=this.mDivDlgDeleteExcelObj.getElementsByClassName('inpFileExcel')[0];
	if(pObjInputFileInput.files.length==0){
		return;
	}
	pFileParam[this.mViewID]['deleteExcel']=pObjInputFileInput.files[0];
	var pDeleteExcelResult=this.myAjaxPost(pUrl,pDtJSON,pFileParam,false);
	var pDivResultDeleteExcelObj=this.mDivDlgDeleteExcelObj.getElementsByClassName('divResultDeleteExcel')[0];
	//alert(pDeleteExcelResult);
	pDivResultDeleteExcelObj.innerHTML=pDeleteExcelResult;
};
tdbfView.prototype.myPrintButtonClick =function(pSelectParentFormId,pSelectSessionCode){
	//var pdivX = document.getElementsByClassName('divRecordContent')[0];
	//var s1 = pdivX.innerHTML;
	//pdivX.innerHTML = '';
	//pdivX.innerHTML = s1;
	//$(".divRecordFooter").remove();
	//$("body").removeAttr("class");
	//$(".divRecordHeader").remove();
	$(".divRecordContent").removeAttr("style");
	$(".tblDataRecord").attr("border","1");
	$(".tblDataRecord").attr("cellspacing","0");
	$(".tblDataRecord").attr("cellpadding","1");
	$('.tblDataRecord tr').find('td:eq(0),th:eq(0)').remove();
	$('.tblDataRecord tr').find('td:eq(0),th:eq(0)').remove();
	//window.print();
	//location.reload();
	var w = window.open();
  	var html = document.getElementsByClassName('divRecordHeaderPrint')[0].outerHTML + 
  				document.getElementsByClassName('divRecordContent')[0].outerHTML + 
  				document.getElementsByClassName('divRecordFooterPrint')[0].outerHTML;
  				
	
    $(w.document.body).html(html);
    w.print();
};
tdbfView.prototype.mySelectButtonClick=function(pSelectParentFormId,pSelectSessionCode){
	var pDtSelect=this.myGetSelectResult('checked');
	this.myCloseAndSendResultToParent(pSelectParentFormId,pSelectSessionCode,pDtSelect);
};
tdbfView.prototype.mySelectAllRecordButtonClick=function(pSelectParentFormId,pSelectSessionCode){
	var pDtSelect=this.myGetSelectResult('selectAllRecords');
	this.myCloseAndSendResultToParent(pSelectParentFormId,pSelectSessionCode,pDtSelect);
};
tdbfView.prototype.myDeselectAllRecordButtonClick=function(pSelectParentFormId,pSelectSessionCode){
	var pDtSelect=this.myGetSelectResult('deselectAllRecords');
	this.myCloseAndSendResultToParent(pSelectParentFormId,pSelectSessionCode,pDtSelect);
};
tdbfView.prototype.myChkSelectAllClick=function(pChkObj){
	var pArObjCheckbox=this.mTblDataRecordObj.getElementsByClassName('myChkSelect');
	for(i=0;i<(pArObjCheckbox.length);i++){
		pArObjCheckbox[i].checked=pChkObj.checked;
	}
};
tdbfView.prototype.myEditIconClick=function(pStrCode){
	//alert('edit : '+pStrCode);
	var pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='edit';
	pDtJSON[this.mViewID]['customParam']['editRecordKeyCode']=pStrCode;
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfView.prototype.myDeleteIconClick=function(pStrCode){
	//alert('delete : '+pStrCode);
	if(confirm('Continue delete ?')){
		var pDtJSON={};
		pDtJSON[this.mViewID]={};
		pDtJSON[this.mViewID]['userCommand']=[];
		pDtJSON[this.mViewID]['userCommand'].push('deleteRecord');
		pDtJSON[this.mViewID]['customParam']={};
		pDtJSON[this.mViewID]['customParam']['deleteKeyCode']=pStrCode;
		this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
	}
	
};
tdbfView.prototype.mySetThisWindowEnable=function(pEnable){
	if(!pEnable){
		var overlay = jQuery('<div id="myOverlayPage" class="myOverlayPage"></div>');
		overlay.appendTo(document.body);
	}else{
		var pDivOverlay=document.getElementById('myOverlayPage');
		pDivOverlay.parentElement.removeChild(pDivOverlay);
	}
};
tdbfView.prototype.myOpenSelectForm=function(pUrl,pSelectSessionCode){
	this.mySetThisWindowEnable(false);
	var pMargin=100;
	var pFormTop=(pMargin-50).toString();
	var pFormLeft=pMargin.toString();
	var pFormHeight=(screen.height - (pMargin * 2)).toString();
	var pFormWidth=(screen.width - (pMargin * 2)).toString();
	var pPopup=window.open('', '', 'top='+pFormTop+',left='+pFormLeft+',height='+pFormHeight+',width='+pFormWidth+'');
	var pJSONParam={};
	pJSONParam['externalParam']={};
	pJSONParam['externalParam']['userCommand']=[];
	pJSONParam['externalParam']['userCommand'].push('changeSectionMode');
	pJSONParam['externalParam']['customParam']={};
	pJSONParam['externalParam']['customParam']['sectionMode']='select';
	pJSONParam['externalParam']['customParam']['selectParentFormId']=this.mViewID;
	pJSONParam['externalParam']['customParam']['selectSessionCode']=pSelectSessionCode;
	var form = $('<form action="' + pUrl + '" method="post">' +
		'<input type="hidden" name="tdbfPageParam" value="' + this.myJson2HexStr(pJSONParam) + '" />' +
		'</form>');
	form.appendTo(pPopup.document.body);
	form.submit();
	var pTimer = setInterval(function() {
        if (pPopup.closed) {
            clearInterval(pTimer);
			var pDivOverlay=document.getElementById('myOverlayPage');
			pDivOverlay.parentElement.removeChild(pDivOverlay);
       }
      },500);
};
tdbfView.prototype.myGetSelectResult=function(pModeSelect){
	var i,j,k,l;
	var pDtSelect={};
	pDtSelect['selected']=[];
	pDtSelect['deselected']=[];
	var pObjViewTable=document.getElementById(this.mViewID);
	if(pModeSelect=='selectAllRecords'){
		pDtJSON={};
		pDtJSON[this.mViewID]={};
		pDtJSON[this.mViewID]['userCommand']=[];
		pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
		pDtJSON[this.mViewID]['userCommand'].push('selectAllRecord');
		pDtJSON[this.mViewID]['customParam']={};
		pDtJSON[this.mViewID]['customParam']['sectionMode']='selectAllRecord';
		var pSelectAllRecordResult=this.myAjaxPost(window.location.href,pDtJSON,{},false);
		//alert(pSelectAllRecordResult);
		var pDtResult=JSON.parse(pSelectAllRecordResult);
		pDtSelect=pDtResult['selectResult'];
	}else if(pModeSelect=='deselectAllRecords'){
		pDtJSON={};
		pDtJSON[this.mViewID]={};
		pDtJSON[this.mViewID]['userCommand']=[];
		pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
		pDtJSON[this.mViewID]['userCommand'].push('deselectAllRecord');
		pDtJSON[this.mViewID]['customParam']={};
		pDtJSON[this.mViewID]['customParam']['sectionMode']='deselectAllRecord';
		var pSelectAllRecordResult=this.myAjaxPost(window.location.href,pDtJSON,{},false);
		var pDtResult=JSON.parse(pSelectAllRecordResult);
		pDtSelect=pDtResult['selectResult'];
	}else{
		var pArObjCheckbox=this.mTblDataRecordObj.getElementsByClassName('myChkSelect');
		for(i=0;i<(pArObjCheckbox.length);i++){
			//alert(i);
			var pDtKeyRec=this.myHexStr2Json(pArObjCheckbox[i].value);
			if(pArObjCheckbox[i].checked){
				//alert(pDtKeyRec);
				pDtSelect['selected'].push(pDtKeyRec);
			}else{
				pDtSelect['deselected'].push(pDtKeyRec);
			}
		}
	}
	//alert(JSON.stringify(pDtSelect));
	return pDtSelect;
};
tdbfView.prototype.myCloseAndSendResultToParent=function(pSelectParentFormId,pSelectSessionCode,pSelectResult){
	var pScript='opener.'+pSelectParentFormId+'.myOnReceiveSelectResult('+String.fromCharCode(39) +pSelectSessionCode+String.fromCharCode(39)+','+String.fromCharCode(39) +this.myJson2HexStr(pSelectResult)+String.fromCharCode(39)+');';
	//alert(pScript);
	eval(pScript);
	window.close();
};
tdbfView.prototype.myOnReceiveSelectResult=function(pSelectSessionCode,pHexStrSelectResult){
	//alert('myOnReceiveSelectResult');
	var pDtSelectResult=this.myHexStr2Json(pHexStrSelectResult);
	//alert(pSelectSessionCode);
	//alert(JSON.stringify(pDtSelectResult));
	if (typeof myOnReceiveSelectResult === 'function'){ 
		try{
			myOnReceiveSelectResult(pSelectSessionCode,pHexStrSelectResult);
		}catch(err){
			alert('Error on function myOnReceiveSelectResult.');
		}
	}
};
//alert('JS FINISH');