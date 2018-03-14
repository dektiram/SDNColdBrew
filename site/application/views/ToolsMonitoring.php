<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis-network.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/jstree/dist/jstree.min.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/toolsMonitoring.js"></script>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div id="" class="col-md-12 col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">Path load to CSV</div>
								<div class="panel-body">
									<button type="button" class="btn btn-primary btn-xs" onclick="pathLoadToCsv('start');">Start</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="pathLoadToCsv('stop');">Stop</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="pathLoadToCsv('status');">Status</button>
									<br />
									<label>Status :</label>
									<pre id="outputPathLoadToCsv"></pre>
								</div>
							</div>
						</div>
						<div id="" class="col-md-12 col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">Overload detection</div>
								<div class="panel-body">
									<button type="button" class="btn btn-primary btn-xs" onclick="overloadDetection('start');">Start</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="overloadDetection('stop');">Stop</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="overloadDetection('status');">Status</button>
									<br />
									<label>Status :</label>
									<pre id="outputOverloadDetection"></pre>
								</div>
							</div>
						</div>
						<div id="" class="col-md-12 col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">Collect flow segment</div>
								<div class="panel-body">
									<button type="button" class="btn btn-primary btn-xs" onclick="">Start</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="">Stop</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="">Status</button>
									<br />
									<label>Status :</label>
									<pre></pre>
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
	pathLoadToCsv('status');
	overloadDetection('status');
</script>