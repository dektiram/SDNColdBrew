var mRequestUrl = null;
var mRequestMethod = null;
var mRequestPostData = null;

function doSubmitRequest(){
	$('#boxRequestResult').show();
	$('#lblResultRequest').html('');
	$('#lblResultReplyTime').html('');
	$('#containerDataResultRaw').html('');
	$('#containerDataResultJson').html('');
	$('#containerDataResultTable').html('');
	$('#containerDataResultLoading').show();
	myAjaxRequest(mRequestUrl,{'ofctlRestApi':mRequestPostData},{},true,'text',
		function(dtReply){
			$('#lblResultRequest').html('<code>'+mRequestUrl+' '+mRequestPostData+'</code>');
			$('#lblResultReplyTime').html('<code>'+Date().toString()+'</code>');
			$('#containerDataResultRaw').html(dtReply);
			var xcontainer = document.getElementById('containerDataResultJson');
			var xoptions = {
				mode: 'view',
				ace: ace
			};
			var xJsonEditor = new JSONEditor(xcontainer, xoptions);
			var xJSON = null;
			try{
				xJSON = JSON.parse(dtReply);
				xJsonEditor.set(xJSON);
				generateTableView('dtResult', xJSON, null, 2, 'containerDataResultTable');
			}catch(err){
				$('#containerDataResultTable').html('Not tabular data format.');
			}
			$('#containerDataResultLoading').hide();
			if($('#chkAutoRefresh').prop('checked')){
				setTimeout(function(){ doSubmitRequest(); }, parseInt($('#autoRefreshRate').val())*1000);
			}
		},
		function (xhr, ajaxOptions, thrownError){
			console.log(xhr.status);
			console.log(ajaxOptions);
			console.log(thrownError);
			$('#chkAutoRefresh').attr('checked',false);
		}
	);
	
}

function generateTableView(itemKey, dtJson, colStartTreeLevel, colDeepth, divContainer){
	var returnHtml = 'Not available.';
	
	var dtCol =[];
	var dtTable = [];
	var dtRow = {};
	
	function iterateJson(obj, onTreeLevel, parentColName, collectMode) {
		for (var property in obj) {
			if(!$.isNumeric(property)){
				if(parentColName != ''){
					var thisColName = parentColName + '->'+property;
				}else{
					var thisColName = property;
				}
			}else{
				var thisColName = parentColName;
			}
			
			if(collectMode == 'colName'){
				//console.log('property', property);
				if(colStartTreeLevel === null){
					if(!$.isNumeric(property)){
						colStartTreeLevel = onTreeLevel;
						//console.log(colStartTreeLevel);
					}
				}
				if(colStartTreeLevel !== null){
					if(onTreeLevel >= colStartTreeLevel){
						var x1 = thisColName.split('->');
						if(x1.length <= colDeepth){
							if(dtCol.indexOf(thisColName ) < 0){
								dtCol.push(thisColName);
							}
						}
					}
				}
			}else{
				if(onTreeLevel < colStartTreeLevel){
					if(Object.keys(dtRow).length > 0){
						dtTable.push(dtRow);
						dtRow = {};
					}
				}
				//console.log(dtRow);
				if(dtCol.indexOf(thisColName) >= 0 ){
					if (obj.hasOwnProperty(property)) {
						if (typeof obj[property] == "object") {
							dtRow[thisColName] = JSON.stringify(obj[property]);
						}else{
							dtRow[thisColName] = obj[property];
						}
					}
				}
			}
			
			if (obj.hasOwnProperty(property)) {
				if (typeof obj[property] == "object") {
					iterateJson(obj[property], onTreeLevel+1, thisColName, collectMode);
				}else{
					//console.log(property + "   " + obj[property]);
				}
			}
		}
	}
	
	iterateJson(dtJson, 0, '', 'colName');
	for(var i in dtCol){
		var x1 = dtCol[i].split('->');
		if(x1.length >1){
			x1.pop();
			var x2 = dtCol.indexOf(x1.join('->'));
			if(x2>=0){
				dtCol.splice(x2, 1);
			}
		}
	}
	//console.log(dtCol);
	iterateJson(dtJson, 0, '', 'dtTable');
	//console.log(dtRow);
	if(Object.keys(dtRow).length > 0){dtTable.push(dtRow);}
	//console.log(dtTable);
	
	if(dtCol.length > 0){
		//console.log('display tabular');
		var tableId = 'tbl_'+itemKey;
		returnHtml = '<table id="'+tableId+'" style="width:100%" class="table table-bordered table-striped tableScrollX">';
		returnHtml += '<thead>';
		returnHtml += '<tr role="row">';
		for(var i in dtCol){
			returnHtml += '<th>';
			returnHtml += dtCol[i];
			returnHtml += '</th>';
		}
		returnHtml += '</tr>';
		returnHtml += '</thead>';
		returnHtml += '<tbody>';
		var rowOddEven = 'odd';
		for(var idx1 in dtTable){
			returnHtml += '<tr role="row" class="'+rowOddEven+'">';
			//console.log(dtTable[idx1]);
			for(var idx2 in dtCol){
				if(dtCol[idx2] in dtTable[idx1]){
					returnHtml += '<td>'+dtTable[idx1][dtCol[idx2]]+'</td>';
				}else{
					returnHtml += '<td></td>';
				}
			}
			returnHtml += '</tr>';
			if(rowOddEven == 'odd'){rowOddEven = 'even';}else{rowOddEven = 'odd';}
		}
		returnHtml += '</tbody>';
		returnHtml += '<tfoot>';
		returnHtml += '</tfoot>';
		returnHtml += '</table>';
		
		$('#'+divContainer).html(returnHtml);
		$('#'+tableId).DataTable({
			'scrollX': true,
			'pageLength':25,
			'lengthMenu':[10,25,50,100,200,400],
		});
	}else{
		$('#'+divContainer).html(returnHtml);
	}
	
	return returnHtml;
}

$( document ).ready(function() {
	$('#cmbApiRequestType').change(function() {
		var url = APP_BASE_URL+'Api/ryu/text/'+mOfctlRestRequestList[$(this).val()].reqMethod+mOfctlRestRequestList[$(this).val()].url;
		url = url.replace('<dpid>',$('#cmbDpid').val());
		$('#httpRequestUrl').html(url);
		$('#httpRequestMethod').html(mOfctlRestRequestList[$(this).val()].reqMethod);
		$('#requestPostData').html('');
		$('#cmbPostDataTemplate').html('');
		if(mOfctlRestRequestList[$(this).val()].reqMethod == 'POST'){
			$('#rowRequestPostData').show();
			$('#rowPostDataTemplate').hide();
			if('templates' in mOfctlRestRequestList[$(this).val()]){
				var template = mOfctlRestRequestList[$(this).val()].templates;
				if(template.length > 0){
					for(var i in template){
						$('#cmbPostDataTemplate').append($('<option>', {value:JSON.stringify(template[i].defParam), text:template[i].name}));
					}
					$('#cmbPostDataTemplate').append($('<option>', {value:'{}', text:'Custom'}));
					$('#rowPostDataTemplate').show();
					$('#cmbPostDataTemplate').change();
				}
			}
		}else{
			$('#rowRequestPostData').hide();
			$('#rowPostDataTemplate').hide();
		}
	});
	$('#cmbPostDataTemplate').change(function() {
		var strTemplate = $(this).val();
		var jsonTemplate = JSON.parse(strTemplate);
		if('dpid' in jsonTemplate){jsonTemplate.dpid = parseInt($('#cmbDpid').val());}
		$('#requestPostData').html(JSON.stringify(jsonTemplate));
	});
	$('#btnChangeJson').click(function() {
		mJsonEditor.set(JSON.parse($('#requestPostData').html()));
		$('#modal-json-editor').modal('show');
	});
	$('#btnApplyJsonChange').click(function() {
		$('#requestPostData').html(JSON.stringify(mJsonEditor.get()));
		$('#modal-json-editor').modal('hide');
	});
	$('#cmbApiRequestType').change();
	$('#btnSumbitRequest').click(function() {
		mRequestUrl = $('#httpRequestUrl').html();
		mRequestMethod = $('#httpRequestMethod').html();
		mRequestPostData = $('#requestPostData').html();
		$('#chkAutoRefresh').attr('checked',false);
		doSubmitRequest();
	});
	$('#cmbResultDisplayMode').change(function() {
		switch($(this).val()){
			case 'raw':
				$('#containerDataResultRaw').show();
				$('#containerDataResultJson').hide();
				$('#containerDataResultTable').hide();
				break;
			case 'json':
				$('#containerDataResultRaw').hide();
				$('#containerDataResultJson').show();
				$('#containerDataResultTable').hide();
				break;
			case 'table':
				$('#containerDataResultRaw').hide();
				$('#containerDataResultJson').hide();
				$('#containerDataResultTable').show();
				break;
		}
	});
	$('#chkAutoRefresh').click(function(){
		doSubmitRequest();
	});
	var container = document.getElementById('containerJsonEditor');
	var options = {
		modes: ['text', 'code', 'tree', 'form', 'view'],
		mode: 'tree',
		ace: ace
	};
	var mJsonEditor = new JSONEditor(container, options);
	$('#containerDataResultLoading').hide();
	$('#containerDataResultJson').hide();
	$('#containerDataResultTable').hide();
	$('#boxRequestResult').hide();
});