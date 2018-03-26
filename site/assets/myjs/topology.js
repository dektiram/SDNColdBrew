var mNet = null;
var mNetData = null;
var mNetNodes = null;
var mNetEdges = null;
var mPopupMenuItems = null;

function initDraw(){
	mNetNodes = new vis.DataSet();
	mNetEdges = new vis.DataSet();
	mNetData = {nodes:mNetNodes, edges:mNetEdges};
	var options = {
		interaction: {
			selectConnectedEdges: false
		}
	};
	var container = document.getElementById('myTopology');
	mNet = new vis.Network(container, mNetData, options);
	
	mNet.on('click', function (params) {
		//console.log(params);
		params.event = "[original event]";
		showSelectedNetObjInfo();
	});
	mNet.on('deselectNode', function (params) {
			//console.log('deselectNode');
	});
	mNet.on('deselectEdge', function (params) {
		//console.log('deselectEdge');
	});
}

function refreshTopology(){
	var url = APP_BASE_URL+'Network/getTopologyData/raw';
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		//console.log(dtJson);
		var dataTopo = dtJson.data;
		dataTopo['nodes'] = [];
		dataTopo.nodes = dataTopo.nodes.concat(dataTopo.switches);
		dataTopo.nodes = dataTopo.nodes.concat(dataTopo.hosts);
		for(var idx in dataTopo.nodes){
			if('dpid' in dataTopo.nodes[idx]){
				dataTopo.nodes[idx]['type'] = 'SWITCH';
			}else{
				dataTopo.nodes[idx]['type'] = 'HOST';
			}
		}
		//console.log(dataTopo);
		
		//Add & update node
		for (var idx1 in dataTopo.nodes){
			var node = dataTopo.nodes[idx1];
			var imgPath = '';
			if(node.type == 'SWITCH'){
				imgPath = APP_BASE_URL+'assets/myimages/icon/switch-openflow.png';
			}else{
				imgPath = APP_BASE_URL+'assets/myimages/icon/host.png';
			}
			if(mNetNodes.get(node.id) === null){
				mNetNodes.add({
					id: node.id,
					label: node.label, 
					shape: 'image',
					image: imgPath,
					data: node
				});
			}else{
				mNetNodes.update({
					id: node.id,
					label: node.label, 
					//shape: 'image',
					//image: imgPath,
					data: node
				});
			}
		}
		//Remove node if not used
		var nodeIds = mNetNodes.getIds();
		for (var idx1 in nodeIds){
			var isUsed = false;
			for (var idx2 in dataTopo.nodes){
				if(nodeIds[idx1] == dataTopo.nodes[idx2].id){
					isUsed = true;
					break;
				}
			}
			if(!isUsed){
				mNetNodes.remove({id: nodeIds[idx1]});
			}
		}
		//Add & update edge
		for (var idx1 in dataTopo.links){
			var edge = dataTopo.links[idx1];
			if(mNetEdges.get(edge.id) === null){
				var reverseEdgeId = edge.id.split('-')[1]+'-'+edge.id.split('-')[0];
				if(mNetEdges.get(reverseEdgeId) === null){
					mNetEdges.add({
						id:edge.id,
						from: edge.src.node.id, 
						to: edge.dst.node.id,
						label: edge.label,
						data: edge
					});
				}
			}else{
				mNetEdges.update({
					id:edge.id,
					from: edge.src.node.id, 
					to: edge.dst.node.id,
					label: edge.label,
					data: edge
				});
			}
		}
		//Remove edge if not used
		var edgeIds = mNetEdges.getIds();
		for (var idx1 in edgeIds){
			var isUsed = false;
			for (var idx2 in dataTopo.links){
				if(edgeIds[idx1] == dataTopo.links[idx2].id){
					isUsed = true;
					break;
				}
			}
			if(!isUsed){
				mNetEdges.remove({id: edgeIds[idx1]});
			}
		}
		setTimeout(function(){ refreshTopology(); }, 5000);
	});
};


function loadPopupMenuItems(){
	var url = APP_BASE_URL+'Network/getTopologyPopupMenu/raw';
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		//console.log(dtJson);
		mPopupMenuItems = dtJson.data;
		createContextMenu();
	});
};

function showSelectedNetObjInfo(){
	var selectedObj = null;
	
	var edgeIds = mNet.getSelectedEdges();
	for(var i in edgeIds){
		selectedObj = mNetEdges.get(edgeIds[i]);
		//console.log(selectedObj.data);
		$('#netObjInfoBody').jsonViewer(selectedObj.data);
		if(selectedObj.label == ''){
			$('#netObjInfoHeader').html('Link');
		}else{
			$('#netObjInfoHeader').html(selectedObj.label);
		}
		$('#netObjInfoBox').collapse('show');
	}
	var nodeIds = mNet.getSelectedNodes();
	for(var i in nodeIds){
		selectedObj = mNetNodes.get(nodeIds[i]);
		//console.log(selectedObj.data);
		$('#netObjInfoBody').jsonViewer(selectedObj.data);
		$('#netObjInfoHeader').html(selectedObj.label);
		$('#netObjInfoBox').collapse('show');
	}
	if(selectedObj !== null){
		return selectedObj.data;
	}else{
		return null;
	}
}

function createContextMenu(){
	
	$.contextMenu({
		selector: '#myTopology',
		build: function ($trigger, e){
			//console.log(e);
			var popupMenu = null;
			var contextMenuAt = {'x':e.offsetX, 'y':e.offsetY};
			
			x1 = mNet.getEdgeAt(contextMenuAt);
			if(x1 !== undefined){
				mNet.selectEdges([x1]);
				var objNetData = showSelectedNetObjInfo();
				popupMenu = mPopupMenuItems.edge;
			}
			
			x1 = mNet.getNodeAt(contextMenuAt);
			if(x1 !== undefined){
				mNet.selectNodes([x1],false);
				var objNetData = showSelectedNetObjInfo();
				switch(objNetData.type){
					case 'SWITCH':
						popupMenu = mPopupMenuItems['switch'];
						break;
					case 'HOST':
						popupMenu = mPopupMenuItems['host'];
						break;
					default:
						popupMenu = mPopupMenuItems['edge'];
						break;
				}
			}
			var option = {
				callback: function(itemKey, opt) {
					//console.log(itemKey);
					//console.log(popupMenu);
					//console.log(objNetData);
					if(itemKey.substring(0,10) == 'ofctl_get_'){
						displayModalDataNet(itemKey, popupMenu.ofctl.items.ofctl_get.items[itemKey].name, objNetData);
					}else if(itemKey.substring(0,10) == 'ofctl_set_'){
						showFormRyuRestOfctl(itemKey, objNetData.dpid);
					}
				},
				items:  popupMenu
			}
			if(popupMenu === null){
				return false;
			}else{
				return option;
			}
		}
	});
}

function clearModalDataNet(){
	$('#modal-data-net .modal-dialog .modal-content .modal-header .modal-title').html('Network Object');
	$('#modal-data-net .modal-dialog .modal-content .modal-body .loadingImage').show();
	$('#modal-data-net .modal-dialog .modal-content .modal-body .nav-tabs').hide();
	$('#modal-data-net .modal-dialog .modal-content .modal-body .tab-content').hide();
	$('#modal-data-net-raw').html('Display with raw format.');
	$('#modal-data-net-json').html('Display with JSON format.');
	$('#modal-data-net-tabular').html('Display with tabular format.');
	//console.log('clearModalDataNet');
}

function displayModalDataNet(itemKey, title, objNetData){
	clearModalDataNet();
	var modalTitle = '['+objNetData.id+'] '+title;
	$('#modal-data-net .modal-dialog .modal-content .modal-header .modal-title').html(modalTitle);
	$('#modal-data-net').modal('show');
	var url = '';
	var postParam = {};
	switch(itemKey){
		case 'ofctl_get_desc_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/desc/'+objNetData.dpid;
			break;
		case 'ofctl_get_all_flows_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/flow/'+objNetData.dpid;
			break;
		case 'ofctl_get_flows_stats_filtered_by_field':
			alert('POST PARAM DIALOG');
			url = APP_BASE_URL+'Api/ryu/text/stats/flow/'+objNetData.dpid;
			break;
		case 'ofctl_get_aggregate_flow_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/aggregateflow/'+objNetData.dpid;
			break;
		case 'ofctl_get_aggregate_flow_stats_filtered_by_fields':
			alert('POST PARAM DIALOG');
			url = APP_BASE_URL+'Api/ryu/text/stats/aggregateflow/'+objNetData.dpid;
			break;
		case 'ofctl_get_table_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/table/'+objNetData.dpid;
			break;
		case 'ofctl_get_table_features':
			url = APP_BASE_URL+'Api/ryu/text/stats/tablefeatures/'+objNetData.dpid;
			break;
		case 'ofctl_get_ports_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/port/'+objNetData.dpid;
			break;
		case 'ofctl_get_ports_description':
			url = APP_BASE_URL+'Api/ryu/text/stats/portdesc/'+objNetData.dpid;
			break;
		case 'ofctl_get_queues_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/queue/'+objNetData.dpid;
			break;
		case 'ofctl_get_queues_config':
			url = APP_BASE_URL+'Api/ryu/text/stats/queueconfig/'+objNetData.dpid;
			break;
		case 'ofctl_get_queues_description':
			url = APP_BASE_URL+'Api/ryu/text/stats/queuedesc/'+objNetData.dpid;
			break;
		case 'ofctl_get_groups_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/group/'+objNetData.dpid;
			break;
		case 'ofctl_get_group_description_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/groupdesc/'+objNetData.dpid;
			break;
		case 'ofctl_get_group_features_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/groupfeatures/'+objNetData.dpid;
			break;
		case 'ofctl_get_meters_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/meter/'+objNetData.dpid;
			break;
		case 'ofctl_get_meter_config_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/meterconfig/'+objNetData.dpid;
			break;
		case 'ofctl_get_meter_description_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/meterconfig/'+objNetData.dpid;
			break;
		case 'ofctl_get_meter_features_stats':
			url = APP_BASE_URL+'Api/ryu/text/stats/meterfeatures/'+objNetData.dpid;
			break;
		case 'ofctl_get_role':
			url = APP_BASE_URL+'Api/ryu/text/stats/role/'+objNetData.dpid;
			break;
	}
	//console.log(url);
	myAjaxRequest(url,postParam,{},true,'json',function(dtJson){
		$('#modal-data-net-raw').html('<pre>'+JSON.stringify(dtJson, null, 2)+'</pre>');
		$('#modal-data-net-json').html('<div align="center">'+$('.loadingImage').html()+'</div>');
		$('#modal-data-net-tabular').html('<div align="center">'+$('.loadingImage').html()+'</div>');
		//$('#modal-data-net-json').jsonViewer(dtJson, {collapsed: true, withQuotes: true});
		//generateTableView(itemKey, dtJson, null, 2, 'modal-data-net-tabular');
		//$('#tbl_'+itemKey).DataTable();
		$('.nav-tabs a').on('shown.bs.tab', function(event){
			var activeTab = $(event.target).text();
			switch(activeTab){
				case 'JSON':
					$('#modal-data-net-json').jsonViewer(dtJson, {collapsed: true, withQuotes: true});
					break;
				case 'Tabular':
					generateTableView(itemKey, dtJson, null, 2, 'modal-data-net-tabular');
					break;
			}
		});
		$('#modal-data-net .modal-dialog .modal-content .modal-body .loadingImage').hide();
		$('#modal-data-net .modal-dialog .modal-content .modal-body .nav-tabs').show();
		$('#modal-data-net .modal-dialog .modal-content .modal-body .tab-content').show();
		$('#modal-data-net .modal-dialog .modal-content .modal-body .nav-tabs a:first').tab('show');
	});
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
		returnHtml = '<table id="'+tableId+'" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="'+tableId+'_info">';
		returnHtml += '<thead>';
		returnHtml += '<tr role="row">';
		for(var i in dtCol){
			returnHtml += '<th class="sorting" tabindex="0" aria-controls="tbl_'+itemKey+'" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">';
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
		$('#'+tableId).DataTable()
	}else{
		$('#'+divContainer).html(returnHtml);
	}
	
	return returnHtml;
}

function showFormRyuRestOfctl(itemKey, dpid){
	url = APP_BASE_URL+'RyuRestOfctl/index/'+itemKey+'/'+dpid;
	console.log(url);
	$('#modal-ofctl-rest-api-form').modal('show');
	$("#iframeOfctlRestApiForm").attr('src', url);
}

$( document ).ready(function() {
	document.addEventListener("contextmenu", function (e) {
		e.preventDefault();
	}, false);
	$("#netObjInfoBody").css({"height":$("#myTopology").height()-40});
	initDraw();
	refreshTopology();
	loadPopupMenuItems();
});

