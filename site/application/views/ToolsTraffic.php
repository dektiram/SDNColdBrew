<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis-network.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/jstree/dist/jstree.min.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/toolsTraffic.js"></script>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div id="" class="col-md-6 col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">Listener</div>
								<div class="panel-body">
									<label>Iperf command :</label>
									<textarea id="txtCmdListener" class="form-control textareaFullWidth"><?php print $otherParam['txtCmdListener'];?></textarea>
									<button type="button" class="btn btn-primary btn-xs" onclick="listenerExecute();">Execute</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="listenerKillall();">Killall</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="listenerCheck();">Check</button>
									<br />
									<label id="lblListener">Process list :</label>
									<table id="tblListener" class="tblDataRecord table no-padding no-margin no-footer table-bordered table-hover">
										<thead class="tableheader-xs">
											<tr>
												<th>Kill</th>
												<th>Host</th>
												<th>PID</th>
												<th>Proto</th>
												<th>Port</th>
												<th>Log</th>
												<th>Cmd</th>
											</tr>
										</thead>
										<tbody id="bodyTblListener" class="tablebody-xs">
											<tr class="tabledatarow">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div id="" class="col-md-6 col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">Sender</div>
								<div class="panel-body">
									<label>Iperf command :</label>
									<textarea id="txtCmdSender" class="form-control textareaFullWidth"><?php print $otherParam['txtCmdSender'];?></textarea>
									<button type="button" class="btn btn-primary btn-xs" onclick="senderExecute();">Execute</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="senderKillall();">Killall</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="senderCheck();">Check</button>
									<br />
									<label id="lblSender">Process list :</label>
									<table id="tblSender" class="tblDataRecord table no-padding no-margin no-footer table-bordered table-hover">
										<thead class="tableheader-xs">
											<tr>
												<th>Kill</th>
												<th>Host</th>
												<th>PID</th>
												<th>Server</th>
												<th>Proto</th>
												<th>Port</th>
												<th>Log</th>
												<th>Cmd</th>
											</tr>
										</thead>
										<tbody id="bodyTblSender" class="tablebody-xs">
											<tr class="tabledatarow">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section><!-- /.content -->
<script type="text/javascript">
	listenerCheck();
	senderCheck();
</script>