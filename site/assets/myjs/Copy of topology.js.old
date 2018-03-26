var mNet = null;
var mNetData = null;
var mNetNodes = null;
var mNetEdges = null;
var mTopologyData = null;
var mPopupMenuItems = null;

function myLoadGraphData(){
	var url = APP_BASE_URL+'Network/getTopologyData/raw';
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		//console.log(dtJson);
		mTopologyData = dtJson.data;
		mNetNodes = new vis.DataSet();
		mNetEdges = new vis.DataSet();
		for(var nodeId in mTopologyData.nodes){
			var imgPath = '';
			if(mTopologyData.nodes[nodeId].type == 'SWITCH'){
				if(mTopologyData.nodes[nodeId].route.length > 0){
					imgPath = APP_BASE_URL+'assets/myimages/icon/router-openflow.png';
				}else{
					imgPath = APP_BASE_URL+'assets/myimages/icon/switch-openflow.png';
				}
			}else{
				imgPath = APP_BASE_URL+'assets/myimages/icon/host.png';
			}
			mNetNodes.add({
				id: nodeId, 
				label: mTopologyData.nodes[nodeId].label, 
				shape: 'image',
				image: imgPath,
				data: mTopologyData.nodes[nodeId]
			});
		}
		for(var edgeId in mTopologyData.edges){
			var pStaNodeExist = false;
			var edgeIds = mNetEdges.getIds();
			//console.log(edgeIds);
			for(var idx1 in edgeIds){
				var edgeId2 = edgeIds[idx1];
				var x1 = mNetEdges.get(edgeId2);
				//console.log(x1);
				if((x1.from == mTopologyData.edges[edgeId].dst_node_id) && (x1.to == mTopologyData.edges[edgeId].src_node_id)){
					pStaNodeExist = true;
					//console.log(x1.data);
					break;
				}
			}
			if(pStaNodeExist==false){
				//console.log('add edge id = '+String(mTopologyData.edges[edgeId].src_node_id+'.'+mTopologyData.edges[edgeId].dst_node_id));
				mNetEdges.add({	
					id:mTopologyData.edges[edgeId].src_node_id+'.'+mTopologyData.edges[edgeId].dst_node_id,
					from: mTopologyData.edges[edgeId].src_node_id, 
					to: mTopologyData.edges[edgeId].dst_node_id,
					label: mTopologyData.edges[edgeId].label,
					data: mTopologyData.edges[edgeId]
				});
			}
		}
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
		myLoadPopupMenuItems();
	});
};

function myLoadPopupMenuItems(){
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
					console.log(objNetData);
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

$( document ).ready(function() {
	document.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    }, false);
	$("#netObjInfoBody").css({"height":$("#myTopology").height()-40});
	myLoadGraphData();
});

