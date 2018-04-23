function tdbfEntry(pViewID,pCtlUri){
	//alert(0);
	this.mViewID=pViewID;
	this.mCtlUri=pCtlUri;
};

tdbfEntry.prototype.myPrepare=function(){
	//alert('hhaha');
};
tdbfEntry.prototype.myGotoUrlWithPostPageParam=function(pUrl,pJSONParam){
	//alert(0);
	var form = $('<form action="' + pUrl + '" method="post">' +
		'<input type="hidden" name="tdbfPageParam" value="' + this.myJson2HexStr(pJSONParam) + '" />' +
		'</form>');
	$('body').append(form);
	form.submit();
};
tdbfEntry.prototype.myJson2HexStr=function(pDtJSON){
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
tdbfEntry.prototype.mySaveButtonClick=function(){
	//alert('masuk');
	if(this.myValidasiInputForm()){
		var pDtJSON={};
		pDtJSON[this.mViewID]={};
		pDtJSON[this.mViewID]['userCommand']=[];
		pDtJSON[this.mViewID]['userCommand'].push('insertRecord');
		pDtJSON[this.mViewID]['inputForm']={};
		pDtJSON[this.mViewID]['inputForm']=this.myGetInputFormDtJson('ctlObj_');
		//alert(JSON.stringify(pDtJSON[this.mViewID]['inputForm']));
		this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
	}else{
		alert('Data belum lengkap.');
	}
};
tdbfEntry.prototype.myUpdateButtonClick=function(){
	//alert('update record button click');
	if(this.myValidasiInputForm()){
		var pDtJSON={};
		pDtJSON[this.mViewID]={};
		pDtJSON[this.mViewID]['userCommand']=[];
		pDtJSON[this.mViewID]['userCommand'].push('updateRecord');
		pDtJSON[this.mViewID]['inputForm']={};
		pDtJSON[this.mViewID]['inputForm']=this.myGetInputFormDtJson('ctlObj_');
		pDtJSON[this.mViewID]['inputFormRef']={};
		pDtJSON[this.mViewID]['inputFormRef']=this.myGetInputFormDtJson('ctlObjRefValue_');
		//alert(JSON.stringify(pDtJSON[this.mViewID]['inputFormRef']));
		this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
	}else{
		alert('Data belum lengkap.');
	}
	
};
tdbfEntry.prototype.myCancelButtonClick=function(){
	//alert(pKeyword);
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('');
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfEntry.prototype.myViewButtonClick=function(){
	//alert(1);
	pDtJSON={};
	pDtJSON[this.mViewID]={};
	pDtJSON[this.mViewID]['userCommand']=[];
	pDtJSON[this.mViewID]['userCommand'].push('changeSectionMode');
	pDtJSON[this.mViewID]['customParam']={};
	pDtJSON[this.mViewID]['customParam']['sectionMode']='view';
	this.myGotoUrlWithPostPageParam(window.location.href,pDtJSON);
};
tdbfEntry.prototype.myValidasiInputForm=function(){
	var pStaReturn=true;
	var pTblEntryObj=document.getElementById(this.mViewID+'_tblEntry');
	var pInputObjs=pTblEntryObj.getElementsByClassName('notNull');
	var i;
	var s1;
	for(i=0;i<pInputObjs.length;i++){
		s1=pInputObjs[i].value;
		if(s1.trim()==''){
			pStaReturn=false;
			break;
		}
	}
	return pStaReturn;
};
tdbfEntry.prototype.myGetInputFormDtJson=function(pPrefixClass){
	var pDtJSON={};
	pTblEntryObj=document.getElementById(this.mViewID+'_tblEntry');
	pInputObjs=pTblEntryObj.getElementsByTagName('*');
	//alert(pInputObjs.length);
	for(i=0;i<pInputObjs.length;i++){
		if(pInputObjs[i].hasAttribute('id')){
			pID=pInputObjs[i].id;
			if(pID.length>pPrefixClass.length){
				//alert(pID.substring(0,pPrefixClass.length));
				if(pID.substring(0,pPrefixClass.length)==pPrefixClass){
					//alert(pID);
					pFieldName=pID.substring(pPrefixClass.length);
					pIsMultiSelect=false;
					if(pInputObjs[i].tagName=='SELECT'){
						if(pInputObjs[i].hasAttribute('multiple')){
							if(pInputObjs[i].getAttribute('multiple')=='multiple'){
								pIsMultiSelect=true;
								pValue=[];
								pOptionObjs=pInputObjs[i].getElementsByTagName('option');
								for(j=0;j<pOptionObjs.length;j++){
									if(pOptionObjs[j].selected){
										pValue.push(pOptionObjs[j].value);
									}
								}
							}
						}
					}
					//alert(9999999999999999999);
					if(pIsMultiSelect==false){
						pDtJSON[pFieldName]=pInputObjs[i].value;
					}else{
						pDtJSON[pFieldName]=pValue;
					}
					if(pInputObjs[i].tagName=='INPUT'){
						if(pInputObjs[i].type=='checkbox'){
							//alert('checkbox');
							if(pInputObjs[i].checked){
								pDtJSON[pFieldName]=1;
							}else{
								pDtJSON[pFieldName]=0;
							}
						}
					}
				}
			}
		}
	}
	//alert(JSON.stringify(pDtJSON));
	return pDtJSON;
};
//alert('JS FINISH');