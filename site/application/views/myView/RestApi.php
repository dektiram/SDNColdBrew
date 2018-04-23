<link href="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
<script src="<?php print base_url().'assets/'; ?>plugins/jsoneditor/dist/jsoneditor.min.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/restApi.js"></script>
<script>
	var mOfctlRestRequestList = JSON.parse(<?php print chr(39).json_encode($otherParam['ofctlRestRequestList']).chr(39); ?>);
</script>

<div class="myAlertBox"></div>
<div class="box box-primary box-solid">
	<div class="box-header with-border">
		<h3 class="box-title">SDN ColdBrew -> <?php print $otherParam['restApiGroup'];?> -> Request editor</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div><!-- /.box-tools -->
	</div><!-- /.box-header -->
	<div class="box-body" style="">
		<table cellpadding="2" cellspacing="2">
			<tr>
				<td valign="top">DPID</td>
				<td valign="top">:</td>
				<td valign="top">
					<select id="cmbDpid">
						<?php
						foreach ($otherParam['switchList'] as $key => $value) {
							?><option value="<?php print $value; ?>"><?php print $value; ?></option><?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top">API request type</td>
				<td valign="top">:</td>
				<td valign="top">
					<select id="cmbApiRequestType">
						<?php
						foreach ($otherParam['ofctlRestRequestList'] as $key => $value) {
							?><option value="<?php print $key; ?>"><?php print $value['name']; ?></option><?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top">HTTP URL</td>
				<td valign="top">:</td>
				<td valign="top" id="httpRequestUrl"></td>
			</tr>
			<tr>
				<td valign="top">HTTP request method</td>
				<td valign="top">:</td>
				<td valign="top" id="httpRequestMethod"></td>
			</tr>
			<tr id="rowPostDataTemplate">
				<td valign="top">Load from post data template</td>
				<td valign="top">:</td>
				<td valign="top">
					<select id="cmbPostDataTemplate"></select>
				</td>
			</tr>
			<tr id="rowRequestPostData">
				<td valign="top">
					Request post data<br />
					<button id="btnChangeJson" class="btn btn-primary btn-xs">Change</button>
					<?php
					if(isset($otherParam['referenceUrl'])){
						if($otherParam['referenceUrl'] != ''){
							?><small><a href="<?php print $otherParam['referenceUrl'];?>" target="_blank">*)reference</a></small><?php
						}
					}
					?>
				</td>
				<td valign="top">:</td>
				<td valign="top" id="requestPostData"></td>
			</tr>
		</table>
	</div><!-- /.box-body -->
	<div class="box-footer with-border">
		<button id="btnSumbitRequest" class="btn btn-primary btn-xs">Submit Request</button>
	</div>
</div>

<div class="box box-primary box-solid" id="boxRequestResult">
	<div class="box-header with-border">
		<h3 class="box-title">SDN ColdBrew -> <?php print $otherParam['restApiGroup'];?> -> Request result</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div><!-- /.box-tools -->
	</div><!-- /.box-header -->
	<div class="box-body" style="">
		<table cellpadding="2" cellspacing="2">
			<tr>
				<td valign="top">Request</td>
				<td valign="top">:</td>
				<td valign="top" id="lblResultRequest"></td>
			</tr>
			<tr>
				<td valign="top">Reply time</td>
				<td valign="top">:</td>
				<td valign="top" id="lblResultReplyTime"></td>
			</tr>
			<tr>
				<td valign="top">Display mode</td>
				<td valign="top">:</td>
				<td valign="top">
					<select id="cmbResultDisplayMode">
						<option value="raw">Raw</option>
						<option value="json">JSON</option>
						<option value="table">Table</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top">Auto refresh&nbsp;<input type="checkbox" id="chkAutoRefresh"/></td>
				<td valign="top">:</td>
				<td valign="top">
					<input type="number" id="autoRefreshRate" value="10" size="2"/> seconds
				</td>
			</tr>
		</table>
		<hr />
		<div id="containerDataResult">
			<div id="containerDataResultLoading" align="center"><img src="<?php print base_url().'assets/'; ?>myimages/animatedEllipse.gif" /></div>
			<div id="containerDataResultRaw"></div>
			<div id="containerDataResultJson"></div>
			<div id="containerDataResultTable"></div>
		</div>
	</div>
</div>

<div class="myDebugBox"></div>

<div class="modal fade bs-example-modal-md" id="modal-json-editor">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div id="containerJsonEditor"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-xs btn-primary" id="btnApplyJsonChange">Apply</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->