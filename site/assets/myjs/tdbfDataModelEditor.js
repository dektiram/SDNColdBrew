function tdbfDataModelEditor(pViewID){
	//alert(0);
	this.mViewID=pViewID;
	this.mUrlRequest=window.location.href+'/urlRequest/';
};
tdbfDataModelEditor.prototype.myTesting=function(){
	return 'Testing OK';
};
tdbfDataModelEditor.prototype.myPrepare=function(){
	//alert('prepare');
	this.mSectionObj=document.getElementById(this.mViewID+'_section');
	this.mTblFieldObj=document.getElementById(this.mViewID+'_tblField');
	this.mTblFieldTbodyObj=this.mTblFieldObj.getElementsByTagName('tbody')[0];
	this.mTblExtColObj=document.getElementById(this.mViewID+'_tblExtCol');
	this.mTblExtColTbodyObj=this.mTblExtColObj.getElementsByTagName('tbody')[0];
	//delete data field row and save HTML to variable
	this.mHTMLRowDataField=this.mTblFieldTbodyObj.innerHTML;
	this.mHTMLRowDataFieldObj=this.mTblFieldTbodyObj.getElementsByTagName('tr')[0];
	this.mTblFieldTbodyObj.getElementsByTagName('tr')[0].remove();
	//delete ext col row and save HTML to variable
	this.mHTMLRowExtCol=this.mTblExtColTbodyObj.innerHTML;
	this.mHTMLRowExtColObj=this.mTblExtColTbodyObj.getElementsByTagName('tr')[0];
	this.mTblExtColTbodyObj.getElementsByTagName('tr')[0].remove();
};
tdbfDataModelEditor.prototype.myAddDataFieldRow=function (){
	var pChild = this.mHTMLRowDataFieldObj.cloneNode(true);
	this.mTblFieldTbodyObj.appendChild(pChild);
};
tdbfDataModelEditor.prototype.myRemoveDataFieldRow=function(pBtnRemoveObj){
	pBtnRemoveObj.parentElement.parentElement.remove();
};
tdbfDataModelEditor.prototype.myMoveUpDataFieldRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if(pRowIdx>0){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx], pRowObj.parentElement.children[pRowIdx-1]);
	}
};
tdbfDataModelEditor.prototype.myMoveDownDataFieldRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if((pRowIdx+1)<(pRowObj.parentElement.childElementCount)){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx+1], pRowObj.parentElement.children[pRowIdx]);
	}
};
tdbfDataModelEditor.prototype.myAddExtColRow=function(){
	var pChild = this.mHTMLRowExtColObj.cloneNode(true);
	this.mTblExtColTbodyObj.appendChild(pChild);
};
tdbfDataModelEditor.prototype.myRemoveExtColRow=function(pBtnRemoveObj){
	pBtnRemoveObj.parentElement.parentElement.remove();
};
tdbfDataModelEditor.prototype.myMoveUpExtColRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if(pRowIdx>0){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx], pRowObj.parentElement.children[pRowIdx-1]);
	}
};
tdbfDataModelEditor.prototype.myMoveDownExtColRow=function(pBtnMoveUpObj){
	pRowObj=pBtnMoveUpObj.parentElement.parentElement;
	pRowIdx = [].indexOf.call (pRowObj.parentNode.children, pRowObj);
	if((pRowIdx+1)<(pRowObj.parentElement.childElementCount)){
		pRowObj.parentElement.insertBefore(pRowObj.parentElement.children[pRowIdx+1], pRowObj.parentElement.children[pRowIdx]);
	}
};
tdbfDataModelEditor.prototype.myGenerateJSONDataModel=function(){
	var pDtJSON={};
	
	pDtJSON['dataModelName']=document.getElementById(this.mViewID+'_txtDataModelName').value;
	pDtJSON['databaseConfigName']=document.getElementById(this.mViewID+'_txtDatabaseConfigName').value;
	pDtJSON['tableName']={};
	pDtJSON['tableName']['create']=document.getElementById(this.mViewID+'_txtTableNameCreate').value;
	pDtJSON['tableName']['read']=document.getElementById(this.mViewID+'_txtTableNameRead').value;
	pDtJSON['tableName']['update']=document.getElementById(this.mViewID+'_txtTableNameUpdate').value;
	pDtJSON['tableName']['delete']=document.getElementById(this.mViewID+'_txtTableNameDelete').value;
	var pDtRows=this.mTblFieldObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	pDtJSON['fields']=[];
	for(i=0;i<pDtRows.length;i++){
		pDtJSON['fields'][i]={};
		pDtJSON['fields'][i]['isKey']=pDtRows[i].getElementsByClassName('chkIsKey')[0].checked;
		pDtJSON['fields'][i]['notNull']=pDtRows[i].getElementsByClassName('chkNotNull')[0].checked;
		pDtJSON['fields'][i]['name']=pDtRows[i].getElementsByClassName('txtFieldName')[0].value;
		pDtJSON['fields'][i]['caption']=pDtRows[i].getElementsByClassName('txtFieldCaption')[0].value;
		pDtJSON['fields'][i]['dataType']=pDtRows[i].getElementsByClassName('cmbFieldDataType')[0].value;
		pDtJSON['fields'][i]['dataLength']=Number(pDtRows[i].getElementsByClassName('txtFieldDataLength')[0].value);
		pDtJSON['fields'][i]['defaultValue']=pDtRows[i].getElementsByClassName('txtFieldDefaultValue')[0].value;
		pDtJSON['fields'][i]['createForm']={};
		pDtJSON['fields'][i]['createForm']['used']=pDtRows[i].getElementsByClassName('chkCFUsed')[0].checked;
		pDtJSON['fields'][i]['createForm']['readOnly']=pDtRows[i].getElementsByClassName('chkCFReadOnly')[0].checked;
		pDtJSON['fields'][i]['createForm']['notNull']=pDtRows[i].getElementsByClassName('chkCFNotNull')[0].checked;
		pDtJSON['fields'][i]['createForm']['visible']=pDtRows[i].getElementsByClassName('chkCFVisible')[0].checked;
		pDtJSON['fields'][i]['createForm']['ctlObj']=pDtRows[i].getElementsByClassName('cmbCFObjCtl')[0].value;
		pDtJSON['fields'][i]['createForm']['defaultValue']=pDtRows[i].getElementsByClassName('txtCFDefaultValue')[0].value;
		pDtJSON['fields'][i]['createForm']['lookupValue']=pDtRows[i].getElementsByClassName('txtCFLookupValue')[0].value;
		pDtJSON['fields'][i]['createForm']['extAttr']=pDtRows[i].getElementsByClassName('txtCFExtendedAttribute')[0].value;
		pDtJSON['fields'][i]['createForm']['note']=pDtRows[i].getElementsByClassName('txtCFNote')[0].value;
		pDtJSON['fields'][i]['updateForm']={};
		pDtJSON['fields'][i]['updateForm']['used']=pDtRows[i].getElementsByClassName('chkUFUsed')[0].checked;
		pDtJSON['fields'][i]['updateForm']['readOnly']=pDtRows[i].getElementsByClassName('chkUFReadOnly')[0].checked;
		pDtJSON['fields'][i]['updateForm']['notNull']=pDtRows[i].getElementsByClassName('chkUFNotNull')[0].checked;
		pDtJSON['fields'][i]['updateForm']['visible']=pDtRows[i].getElementsByClassName('chkUFVisible')[0].checked;
		pDtJSON['fields'][i]['updateForm']['ctlObj']=pDtRows[i].getElementsByClassName('cmbUFObjCtl')[0].value;
		pDtJSON['fields'][i]['updateForm']['defaultValue']=pDtRows[i].getElementsByClassName('txtUFDefaultValue')[0].value;
		pDtJSON['fields'][i]['updateForm']['lookupValue']=pDtRows[i].getElementsByClassName('txtUFLookupValue')[0].value;
		pDtJSON['fields'][i]['updateForm']['extAttr']=pDtRows[i].getElementsByClassName('txtUFExtendedAttribute')[0].value;
		pDtJSON['fields'][i]['updateForm']['note']=pDtRows[i].getElementsByClassName('txtUFNote')[0].value;
		pDtJSON['fields'][i]['viewTable']={};
		pDtJSON['fields'][i]['viewTable']['sqlSelect']=pDtRows[i].getElementsByClassName('chkVTSqlSelect')[0].checked;
		pDtJSON['fields'][i]['viewTable']['visible']=pDtRows[i].getElementsByClassName('chkVTVisible')[0].checked;
		pDtJSON['fields'][i]['viewTable']['listValue']=pDtRows[i].getElementsByClassName('chkVTListValue')[0].checked;
		pDtJSON['fields'][i]['viewTable']['exportExcel']=pDtRows[i].getElementsByClassName('chkVTExportExcel')[0].checked;
		pDtJSON['fields'][i]['viewTable']['selectReturn']=pDtRows[i].getElementsByClassName('chkVTSelectReturn')[0].checked;
		pDtJSON['fields'][i]['viewTable']['textBefore']=pDtRows[i].getElementsByClassName('txtVTTextBefore')[0].value;
		pDtJSON['fields'][i]['viewTable']['textAfter']=pDtRows[i].getElementsByClassName('txtVTTextAfter')[0].value;
		pDtJSON['fields'][i]['viewTable']['lookupValue']=pDtRows[i].getElementsByClassName('txtVTLookupValue')[0].value;
		//alert(pDtJSON['fields'][i]['viewTable']['lookupValue']);
		pDtJSON['fields'][i]['viewTable']['colPos']=Number(pDtRows[i].getElementsByClassName('txtVTColPos')[0].value);
		pDtJSON['fields'][i]['viewTable']['cellExtAttr']=pDtRows[i].getElementsByClassName('txtVTCellExtendedAttribute')[0].value;
	}
	
	var pDtRows=this.mTblExtColObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	pDtJSON['extCols']=[];
	for(i=0;i<pDtRows.length;i++){
		pDtJSON['extCols'][i]={};
		pDtJSON['extCols'][i]['name']=pDtRows[i].getElementsByClassName('txtExtColName')[0].value;
		pDtJSON['extCols'][i]['caption']=pDtRows[i].getElementsByClassName('txtExtColCaption')[0].value;
		pDtJSON['extCols'][i]['visible']=pDtRows[i].getElementsByClassName('chkExtColVisible')[0].checked;
		pDtJSON['extCols'][i]['exportExcel']=pDtRows[i].getElementsByClassName('chkExtColExportExcel')[0].checked;
		pDtJSON['extCols'][i]['colPos']=Number(pDtRows[i].getElementsByClassName('txtExtColPos')[0].value);
		pDtJSON['extCols'][i]['cellExtAttr']=pDtRows[i].getElementsByClassName('txtExtColCellExtendedAttribute')[0].value;
	}
	
	pDtJSON['features']={};
	pDtJSON['features']['view']=document.getElementById(this.mViewID+'_chkView').checked;
	pDtJSON['features']['create']=document.getElementById(this.mViewID+'_chkCreate').checked;
	pDtJSON['features']['update']=document.getElementById(this.mViewID+'_chkUpdate').checked;
	pDtJSON['features']['delete']=document.getElementById(this.mViewID+'_chkDelete').checked;
	pDtJSON['features']['importExcel']=document.getElementById(this.mViewID+'_chkImportExcel').checked;
	pDtJSON['features']['importExcelUpdateOnDuplicate']=document.getElementById(this.mViewID+'_chkImportExcelUpdateOnDuplicate').checked;
	pDtJSON['features']['exportExcel']=document.getElementById(this.mViewID+'_chkExportExcel').checked;
	pDtJSON['features']['deleteExcel']=document.getElementById(this.mViewID+'_chkDeleteExcel').checked;
	pDtJSON['features']['select']=document.getElementById(this.mViewID+'_chkSelect').checked;
	pDtJSON['features']['filterPanel']=document.getElementById(this.mViewID+'_chkFiterPanel').checked;
	pDtJSON['features']['viewPreference']=document.getElementById(this.mViewID+'_chkViewPreference').checked;
	
	pDtJSON['otherParam']={};
	pDtJSON['otherParam']['sectionHeader']=document.getElementById(this.mViewID+'_txtSectionHeader').value;
	pDtJSON['otherParam']['sectionNote']=document.getElementById(this.mViewID+'_txtSectionNote').value;
	pDtJSON['otherParam']['preFilterSql']=document.getElementById(this.mViewID+'_txtPreFilterSql').value;
	pDtJSON['otherParam']['preFilterNote']=document.getElementById(this.mViewID+'_txtPreFilterNote').value;
	pDtJSON['otherParam']['editColPos']=Number(document.getElementById(this.mViewID+'_txtEditColPos').value);
	pDtJSON['otherParam']['deleteColPos']=Number(document.getElementById(this.mViewID+'_txtDeleteColPos').value);
	pDtJSON['otherParam']['defaultSection']=document.getElementById(this.mViewID+'_cmbDefaultSection').value;
	pDtJSON['otherParam']['defaultRecordPerPage']=Number(document.getElementById(this.mViewID+'_txtDefaultRecordPerPage').value);
	pDtJSON['otherParam']['defaultOrderBy']=document.getElementById(this.mViewID+'_txtDefaultOrderBy').value;
	pDtJSON['otherParam']['defaultOrderMode']=document.getElementById(this.mViewID+'_cmbDefaultOrderMode').value;
	pDtJSON['otherParam']['viewTableRowExtAttr']=document.getElementById(this.mViewID+'_txtViewTableRowExtendedAttribute').value;
	
	//alert(JSON.stringify(pDtJSON));
	return pDtJSON;
};
tdbfDataModelEditor.prototype.myViewDataModel=function(){
	var pStrJSON=JSON.stringify(this.myGenerateJSONDataModel(), null, '\t');
	document.getElementById(this.mViewID+'_divDlgViewDataModel').getElementsByClassName('modal-body')[0].innerHTML='<pre>'+pStrJSON+'</pre>';
	
};
tdbfDataModelEditor.prototype.mySaveDataModelToFile=function(){
	var pDtJSON=this.myGenerateJSONDataModel();
	var pStrJSON=JSON.stringify(pDtJSON, null, '\t');
	this.mySaveTextAsFile(pStrJSON,pDtJSON['dataModelName']+'.tdbf');
};
tdbfDataModelEditor.prototype.mySaveTextAsFile=function(pText,pFileName)
{
	var textToWrite = pText;
	var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'});
	var fileNameToSaveAs = pFileName;

	var downloadLink = document.createElement("a");
	downloadLink.download = fileNameToSaveAs;
	downloadLink.innerHTML = "Download File";
	if (window.webkitURL != null)
	{
		// Chrome allows the link to be clicked
		// without actually adding it to the DOM.
		downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
	}
	else
	{
		// Firefox requires the link to be added to the DOM
		// before it can be clicked.
		downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
		downloadLink.onclick = destroyClickedElement;
		downloadLink.style.display = "none";
		document.body.appendChild(downloadLink);
	}

	downloadLink.click();
};
tdbfDataModelEditor.prototype.mySelectDataModelFile=function(){
	var pObjFile=document.getElementById(this.mViewID+'_fileToLoad');
	pObjFile.value=null;
	pObjFile.click();
};
tdbfDataModelEditor.prototype.myLoadDataModelFromFile=function(pFile){
	var pClassThis=this;
	var fileToLoad = pFile;

	var fileReader = new FileReader();
	fileReader.onload = function(fileLoadedEvent) 
	{
		var textFromFileLoaded = fileLoadedEvent.target.result;
		pClassThis.myOnLoadDataModelFromFile(textFromFileLoaded);
	};
	fileReader.readAsText(fileToLoad, "UTF-8");
};
tdbfDataModelEditor.prototype.myOnLoadDataModelFromFile=function(pStrJSON){
	//alert(pStrJSON);
	var pDtJSON=JSON.parse(pStrJSON);
	document.getElementById(this.mViewID+'_txtDataModelName').value=pDtJSON['dataModelName'];
	document.getElementById(this.mViewID+'_txtDatabaseConfigName').value=pDtJSON['databaseConfigName'];
	document.getElementById(this.mViewID+'_txtTableNameCreate').value=pDtJSON['tableName']['create'];
	document.getElementById(this.mViewID+'_txtTableNameRead').value=pDtJSON['tableName']['read'];
	document.getElementById(this.mViewID+'_txtTableNameUpdate').value=pDtJSON['tableName']['update'];
	document.getElementById(this.mViewID+'_txtTableNameDelete').value=pDtJSON['tableName']['delete'];
	
	var pDtRows=this.mTblFieldObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	//alert('jml row yg ada = '+String(pDtRows.length));
	for(i=pDtRows.length-1;i>=0;i--){
		//alert(0);
		pDtRows[i].remove();
		//alert(1);
	}
	for(i=0;i<pDtJSON['fields'].length;i++){
		this.myAddDataFieldRow();
	}
	var pDtRows=this.mTblFieldObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	//alert(pDtJSON['fields'].length);
	for(i=0;i<pDtJSON['fields'].length;i++){
		pDtRows[i].getElementsByClassName('chkIsKey')[0].checked=pDtJSON['fields'][i]['isKey'];
		pDtRows[i].getElementsByClassName('chkNotNull')[0].checked=pDtJSON['fields'][i]['notNull'];
		pDtRows[i].getElementsByClassName('txtFieldName')[0].value=pDtJSON['fields'][i]['name'];
		pDtRows[i].getElementsByClassName('txtFieldCaption')[0].value=pDtJSON['fields'][i]['caption'];
		pDtRows[i].getElementsByClassName('cmbFieldDataType')[0].value=pDtJSON['fields'][i]['dataType'];
		pDtRows[i].getElementsByClassName('txtFieldDataLength')[0].value=String(pDtJSON['fields'][i]['dataLength']);
		pDtRows[i].getElementsByClassName('txtFieldDefaultValue')[0].value=pDtJSON['fields'][i]['defaultValue'];
		pDtRows[i].getElementsByClassName('chkCFUsed')[0].checked=pDtJSON['fields'][i]['createForm']['used'];
		pDtRows[i].getElementsByClassName('chkCFReadOnly')[0].checked=pDtJSON['fields'][i]['createForm']['readOnly'];
		pDtRows[i].getElementsByClassName('chkCFNotNull')[0].checked=pDtJSON['fields'][i]['createForm']['notNull'];
		pDtRows[i].getElementsByClassName('chkCFVisible')[0].checked=pDtJSON['fields'][i]['createForm']['visible'];
		pDtRows[i].getElementsByClassName('cmbCFObjCtl')[0].value=pDtJSON['fields'][i]['createForm']['ctlObj'];
		pDtRows[i].getElementsByClassName('txtCFDefaultValue')[0].value=pDtJSON['fields'][i]['createForm']['defaultValue'];
		pDtRows[i].getElementsByClassName('txtCFLookupValue')[0].value=pDtJSON['fields'][i]['createForm']['lookupValue'];
		pDtRows[i].getElementsByClassName('txtCFExtendedAttribute')[0].value=pDtJSON['fields'][i]['createForm']['extAttr'];
		pDtRows[i].getElementsByClassName('txtCFNote')[0].value=pDtJSON['fields'][i]['createForm']['note'];
		pDtRows[i].getElementsByClassName('chkUFUsed')[0].checked=pDtJSON['fields'][i]['updateForm']['used'];
		pDtRows[i].getElementsByClassName('chkUFReadOnly')[0].checked=pDtJSON['fields'][i]['updateForm']['readOnly'];
		pDtRows[i].getElementsByClassName('chkUFNotNull')[0].checked=pDtJSON['fields'][i]['updateForm']['notNull'];
		pDtRows[i].getElementsByClassName('chkUFVisible')[0].checked=pDtJSON['fields'][i]['updateForm']['visible'];
		pDtRows[i].getElementsByClassName('cmbUFObjCtl')[0].value=pDtJSON['fields'][i]['updateForm']['ctlObj'];
		pDtRows[i].getElementsByClassName('txtUFDefaultValue')[0].value=pDtJSON['fields'][i]['updateForm']['defaultValue'];
		pDtRows[i].getElementsByClassName('txtUFLookupValue')[0].value=pDtJSON['fields'][i]['updateForm']['lookupValue'];
		pDtRows[i].getElementsByClassName('txtUFExtendedAttribute')[0].value=pDtJSON['fields'][i]['updateForm']['extAttr'];
		pDtRows[i].getElementsByClassName('txtUFNote')[0].value=pDtJSON['fields'][i]['updateForm']['note'];
		pDtRows[i].getElementsByClassName('chkVTSqlSelect')[0].checked=pDtJSON['fields'][i]['viewTable']['sqlSelect'];
		pDtRows[i].getElementsByClassName('chkVTVisible')[0].checked=pDtJSON['fields'][i]['viewTable']['visible'];
		pDtRows[i].getElementsByClassName('chkVTListValue')[0].checked=pDtJSON['fields'][i]['viewTable']['listValue'];
		pDtRows[i].getElementsByClassName('chkVTExportExcel')[0].checked=pDtJSON['fields'][i]['viewTable']['exportExcel'];
		pDtRows[i].getElementsByClassName('chkVTSelectReturn')[0].checked=pDtJSON['fields'][i]['viewTable']['selectReturn'];
		pDtRows[i].getElementsByClassName('txtVTTextBefore')[0].value=pDtJSON['fields'][i]['viewTable']['textBefore'];
		pDtRows[i].getElementsByClassName('txtVTTextAfter')[0].value=pDtJSON['fields'][i]['viewTable']['textAfter'];
		pDtRows[i].getElementsByClassName('txtVTLookupValue')[0].value=String(pDtJSON['fields'][i]['viewTable']['lookupValue']);
		pDtRows[i].getElementsByClassName('txtVTColPos')[0].value=String(pDtJSON['fields'][i]['viewTable']['colPos']);
		pDtRows[i].getElementsByClassName('txtVTCellExtendedAttribute')[0].value=pDtJSON['fields'][i]['viewTable']['cellExtAttr'];
	}
	
	var pDtRows=this.mTblExtColObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	for(i=pDtRows.length-1;i>=0;i--){
		pDtRows[i].remove();
	}
	for(i=0;i<pDtJSON['extCols'].length;i++){
		this.myAddExtColRow();
	}
	var pDtRows=this.mTblExtColObj.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	for(i=0;i<pDtJSON['extCols'].length;i++){
		pDtRows[i].getElementsByClassName('txtExtColName')[0].value=pDtJSON['extCols'][i]['name'];
		pDtRows[i].getElementsByClassName('txtExtColCaption')[0].value=pDtJSON['extCols'][i]['caption'];
		pDtRows[i].getElementsByClassName('chkExtColVisible')[0].checked=pDtJSON['extCols'][i]['visible'];
		pDtRows[i].getElementsByClassName('chkExtColExportExcel')[0].checked=pDtJSON['extCols'][i]['exportExcel'];
		pDtRows[i].getElementsByClassName('txtExtColPos')[0].value=String(pDtJSON['extCols'][i]['colPos']);
		pDtRows[i].getElementsByClassName('txtExtColCellExtendedAttribute')[0].value=pDtJSON['extCols'][i]['cellExtAttr'];
	}
	
	document.getElementById(this.mViewID+'_chkView').checked=pDtJSON['features']['view'];
	document.getElementById(this.mViewID+'_chkCreate').checked=pDtJSON['features']['create'];
	document.getElementById(this.mViewID+'_chkUpdate').checked=pDtJSON['features']['update'];
	document.getElementById(this.mViewID+'_chkDelete').checked=pDtJSON['features']['delete'];
	document.getElementById(this.mViewID+'_chkImportExcel').checked=pDtJSON['features']['importExcel'];
	document.getElementById(this.mViewID+'_chkImportExcelUpdateOnDuplicate').checked=pDtJSON['features']['importExcelUpdateOnDuplicate'];
	document.getElementById(this.mViewID+'_chkExportExcel').checked=pDtJSON['features']['exportExcel'];
	document.getElementById(this.mViewID+'_chkDeleteExcel').checked=pDtJSON['features']['deleteExcel'];
	document.getElementById(this.mViewID+'_chkSelect').checked=pDtJSON['features']['select'];
	document.getElementById(this.mViewID+'_chkFiterPanel').checked=pDtJSON['features']['filterPanel'];
	document.getElementById(this.mViewID+'_chkViewPreference').checked=pDtJSON['features']['viewPreference'];
	
	document.getElementById(this.mViewID+'_txtSectionHeader').value=pDtJSON['otherParam']['sectionHeader'];
	document.getElementById(this.mViewID+'_txtSectionNote').value=pDtJSON['otherParam']['sectionNote'];
	document.getElementById(this.mViewID+'_txtPreFilterSql').value=pDtJSON['otherParam']['preFilterSql'];
	document.getElementById(this.mViewID+'_txtPreFilterNote').value=pDtJSON['otherParam']['preFilterNote'];
	document.getElementById(this.mViewID+'_txtEditColPos').value=String(pDtJSON['otherParam']['editColPos']);
	document.getElementById(this.mViewID+'_txtDeleteColPos').value=String(pDtJSON['otherParam']['deleteColPos']);
	document.getElementById(this.mViewID+'_cmbDefaultSection').value=pDtJSON['otherParam']['defaultSection'];
	document.getElementById(this.mViewID+'_txtDefaultRecordPerPage').value=String(pDtJSON['otherParam']['defaultRecordPerPage']);
	document.getElementById(this.mViewID+'_txtDefaultOrderBy').value=pDtJSON['otherParam']['defaultOrderBy'];
	document.getElementById(this.mViewID+'_cmbDefaultOrderMode').value=pDtJSON['otherParam']['defaultOrderMode'];
	document.getElementById(this.mViewID+'_txtViewTableRowExtendedAttribute').value=pDtJSON['otherParam']['viewTableRowExtAttr'];
};
tdbfDataModelEditor.prototype.myShowDlgServerFile=function(pAction){
	if(pAction=='load'){
		document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('modal-title')[0].innerHTML='Load From Server File';
		document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('btnDlgServerFileOk')[0].innerHTML='Load';
		document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('txtServerFileName')[0].value='';
	}else{
		document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('modal-title')[0].innerHTML='Save To Server File';
		document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('btnDlgServerFileOk')[0].innerHTML='Save';
	}
};
tdbfDataModelEditor.prototype.myDlgServerFileOk=function(pBtnOkObj){
	//alert(pBtnOkObj.innerHTML);
	var pClassThis=this;
	if(pBtnOkObj.innerHTML=='Load'){
		//alert(this.mUrlRequest);
		pFileName=document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('txtServerFileName')[0].value;
		pFormData = {fileName:pFileName}; //Array
		$.ajax({
			url: this.mUrlRequest+'loadFromServerFile',
			datatype: 'html',
			type: 'POST',
			data: pFormData,
			async: false,
	  		success: function(DtHTML){
	  			//console.log(DtHTML);
				pClassThis.myOnLoadDataModelFromFile(DtHTML);
			}
		});
		
	}else{
		pFileName=document.getElementById(this.mViewID+'_divDlgServerFile').getElementsByClassName('txtServerFileName')[0].value;
		var pDtJSON=this.myGenerateJSONDataModel();
		var pStrJSON=JSON.stringify(pDtJSON, null, '\t');
		pFormData = {fileName:pFileName, strJSON:pStrJSON}; //Array
		$.ajax({
			url: this.mUrlRequest+'saveToServerFile',
			datatype: 'html',
			type: 'POST',
			data: pFormData,
			async: false,
	  		success: function(DtHTML){
	  			console.log(DtHTML);
	  			if(DtHTML=='OK'){
	  				alert('Save success.');
	  			}else{
	  				alert('Save fail.');
	  			}
			}
		});
	}
};
tdbfDataModelEditor.prototype.myReorderColumnPositionIndex=function(){
	var pColPos = 0;
	document.getElementById(this.mViewID+'_txtDeleteColPos').value='0';
	document.getElementById(this.mViewID+'_txtEditColPos').value='1';
	pColPos = 2;
	var pArTxtColPos=this.mTblFieldTbodyObj.getElementsByClassName('txtVTColPos');
	for(i=0;i<pArTxtColPos.length;i++){
		pArTxtColPos[i].value = String(pColPos);
		pColPos++;
	}
	var pArTxtColPos=this.mTblExtColTbodyObj.getElementsByClassName('txtExtColPos');
	for(i=0;i<pArTxtColPos.length;i++){
		pArTxtColPos[i].value = String(pColPos);
		pColPos++;
	}
	alert('Reorder complete.');
};
//alert('JS FINISH');