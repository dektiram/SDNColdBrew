var mChartGroups = new vis.DataSet();
var mChartItems = new vis.DataSet();
var mChartOptions = {
	start: vis.moment().add(-12, 'minutes'),
	end: vis.moment(),
	drawPoints: false ,
	moveable: false,
	zoomable: false,
	//interpolation: 'linear'
};
var mChartContainer = document.getElementById('divChart');
var mChart = null;
var mStartRequestStatus = true;
var mNeedStopRequest = false;
$( document ).ready(function() {
	
	for(var intf in mIntfs){
		mChartGroups.add({
			id : intf,
			content : intf
		});
		mChartGroups.add({
			id : intf+'-threshold',
			content : intf+'-threshold',
			className: 'chartLineThreshold'
		});
		var x1 = mChartGroups.get(intf+'-threshold');
		//console.log(x1);
		//x1.options.shaded = true;
		x1.style = '';
		x1.style += 'stroke:red;';
		x1.style += 'stroke-dasharray:2 2;';
		x1.style += 'stroke-width:1;';
	}
	/*
	mChartGroups.add({
		id : 'loadThreshold',
		content : 'loadThreshold',
		className: 'chartLineThreshold',
		options: {
			shaded:{
				orientation: 'top',
				style: 'chartShadedThreshold'
			}
		}
	});
	var x1 = mChartGroups.get('loadThreshold');
	console.log(x1);
	//x1.options.shaded = true;
	x1.style = '';
	x1.style += 'stroke:red;';
	x1.style += 'stroke-dasharray:2 2;';
	x1.style += 'stroke-width:1;';
	*/
	mChart = new vis.Graph2d(mChartContainer, mChartItems, mChartGroups, mChartOptions);
	//console.log(mChart);
	function randomNumber() {
		return Math.floor((Math.random() * 100000) + 1);
	};
	
	function requestCurrentData(){
		var url = APP_BASE_URL+'TdbfRequestHandling/getCurrentIntfBytesInLoad/';
		myAjaxRequest(url,{},{},true,'json',updateChart);
	}
	
	function updateChart(currentInfLoad){
		
		//console.log(currentInfLoad);
		var now = vis.moment();
		for(var i in currentInfLoad){
			var dtLoad = currentInfLoad[i]
			if(dtLoad['name']in mIntfs){
				//console.log(dtLoad['name']);
				mChartItems.add({
					x: now,
					y: dtLoad['bytes_in_s']*8,
					group: dtLoad['name']
				});
				mChartItems.add({
					x: now,
					y: mIntfs[dtLoad['name']]['threshold'],
					group: dtLoad['name']+'-threshold'
				});
			}
		}
		/*
		mChartItems.add({
			x: now,
			y: mLoadThreshold,
			group: 'loadThreshold'
		});
		*/
		var range = mChart.getWindow();
		var interval = range.end - range.start;
		mChart.setWindow(now - interval, now, {animation: true});
		
		var oldIds = mChartItems.getIds({
			filter: function (item) {
				return item.x < range.start - interval;
			}
		});
		mChartItems.remove(oldIds);
		if(mNeedStopRequest){
			mStartRequestStatus = false;
			$('#btnStartStopRequest').text('Start Request');
		}else{
			setTimeout(requestCurrentData,2000);
		}
	};
	requestCurrentData();
	
	$('.seriesCheckbox').click(function() {
		var chkId = $(this).attr('id');
		var prop = {};
		if ($(this).is(':checked')) {
			//console.log(chkId+' = checked');
			prop[chkId] = true;
			mChart.setOptions({groups:{visibility:prop}});
		}else{
			//console.log(chkId+' = unchecked');
			prop[chkId] = false;
			mChart.setOptions({groups:{visibility:prop}});
		}
	});
	$('#btnStartStopRequest').click(function() {
		if(mStartRequestStatus){
			mNeedStopRequest = true;
		}else{
			mNeedStopRequest = false;
			mStartRequestStatus = true;
			$('#btnStartStopRequest').text('Stop Request');
			requestCurrentData(); 
		}
	});
});
