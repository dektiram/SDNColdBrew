var mNet = null;
var mNetData = null;
var mNetNodes = null;
var mNetEdges = null;
var mTopologyData = {};
function myLoadGraphData(){
	var url = APP_BASE_URL+'Network/getTopologyData/raw';
	myAjaxRequest(url,{},{},true,'json',function(dtJson){
		console.log(dtJson);
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
			var nodeLabel = '';
			if(mTopologyData.nodes[nodeId].type == 'SWITCH'){
				nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].dpid;
				/*
				nodeLabel = nodeLabel + 'DPID = '+ mTopologyData.nodes[nodeId].dpid+'\n';
				if(mTopologyData.nodes[nodeId].ipv4.length >0){
					for(var idx2 in mTopologyData.nodes[nodeId].ipv4){
						nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].ipv4[idx2].addr+'/'+mTopologyData.nodes[nodeId].ipv4[idx2].prefix+'\n';
					}
				}
				if(mTopologyData.nodes[nodeId].ipv6.length >0){
					for(var idx2 in mTopologyData.nodes[nodeId].ipv6){
						nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].ipv6[idx2].addr+'/'+mTopologyData.nodes[nodeId].ipv6[idx2].prefix+'\n';
					}
				}
				if(mTopologyData.nodes[nodeId].route.length >0){
					for(var idx2 in mTopologyData.nodes[nodeId].route){
						nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].route[idx2].dst_net+' gw '+mTopologyData.nodes[nodeId].route[idx2].gateway+'\n';
					}
				}
				*/
			}else{
				if(mTopologyData.nodes[nodeId].hostname != ''){
					nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].hostname;
				}else{
					nodeLabel = nodeLabel + mTopologyData.nodes[nodeId].hw_addr;
				}
				if(mTopologyData.nodes[nodeId].ipv4.length >0){
					for(var idx2 in mTopologyData.nodes[nodeId].ipv4){
						nodeLabel = nodeLabel + '\n'+mTopologyData.nodes[nodeId].ipv4[idx2].addr+'/'+mTopologyData.nodes[nodeId].ipv4[idx2].prefix;
					}
				}
			}
			mNetNodes.add({
				id: nodeId, 
				label: nodeLabel, 
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
			params.event = "[original event]";
			//console.log('click event, getNodeAt returns: ' + this.getNodeAt(params.pointer.DOM));
			console.log(params);
			for(i in params.nodes){
				console.log(mNetNodes.get(params.nodes[i]).data);
				$('#netObjInfoBody').jsonViewer(mNetNodes.get(params.nodes[i]).data);
				$('#netObjInfoHeader').html(mNetNodes.get(params.nodes[i]).label);
				$('#netObjInfoBox').collapse('show');
			}
			for(i in params.edges){
				console.log(mNetEdges.get(params.edges[i]).data);
				$('#netObjInfoBody').jsonViewer(mNetEdges.get(params.edges[i]).data);
				$('#netObjInfoHeader').html('Link');
				$('#netObjInfoBox').collapse('show');
			}
		});
		mNet.on('deselectNode', function (params) {
			console.log('deselectNode');
		});
		mNet.on('deselectEdge', function (params) {
			console.log('deselectEdge');
		});
	});
};

$( document ).ready(function() {
	myLoadGraphData();
$("#netObjInfoBody").css({"height":$("#myTopology").height()-40});
});

