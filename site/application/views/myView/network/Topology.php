<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/dist/jquery.contextMenu.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/dist/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/js/main.js"></script> 
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis-network.min.css" rel="stylesheet" type="text/css" />
<link href="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/css/screen.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/css/theme.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/css/theme-fixes.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/jQuery-contextMenu/dist/jquery.contextMenu.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/topology.js"></script>
        <section class="content">
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
              <div class="box">
                <!-- /<div class="box-header">
                  <h3 class="box-title">Controller</h3>
                  <h5></h5>
                </div>.box-header -->
                <div class="box-body">
	                	<div class="myAlertBox"></div>
	                	<div id="myTopology" class="netGraphCanvas "></div>
	                	
	                	<div class="netObjInfo col-md-4 col-sm-12">
	                		<div id="netObjInfoBox" class="box box-success box-solid collapse">
				            <div class="box-header with-border">
				              <h3 id="netObjInfoHeader" class="box-title">Removable</h3>
				              <div class="box-tools pull-right">
				                <button type="button" class="btn btn-box-tool" onclick="$('#netObjInfoBox').collapse('hide');"><i class="fa fa-times"></i></button>
				              </div>
				              <!-- /.box-tools -->
				            </div>
				            <!-- /.box-header -->
				            <div id="netObjInfoBody" class="box-body"></div>
				            <!-- /.box-body -->
				          </div>
	                	</div>
	                	
					<div class="myDebugBox"></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        
<div class="modal fade bs-example-modal-lg" id="modal-data-net">
	<div class="modal-dialog modal-lg modal-data-net-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Default Modal</h4>
			</div>
			<div class="modal-body">
				<div class="loadingImage" align="center">
					<img src="<?php print base_url().'assets/'; ?>myimages/animatedEllipse.gif" />
				</div>
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#modal-data-net-raw">Raw</a></li>
					<li><a data-toggle="tab" href="#modal-data-net-json">JSON</a></li>
					<li><a data-toggle="tab" href="#modal-data-net-tabular">Tabular</a></li>
				</ul>
				<div class="tab-content">
					<div id="modal-data-net-raw" class="tab-pane fade in active"><div>Raw content.</div></div>
					<div id="modal-data-net-json" class="tab-pane fade"><div>JSON content.</div></div>
					<div id="modal-data-net-tabular" class="tab-pane fade"><div>Tabular content.</div></div>
				</div>
			</div>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-xs btn-primary">Save changes</button>
			</div>
			-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->      
  
<div class="modal fade bs-example-modal-md" id="modal-ofctl-rest-api-form">
	<div class="modal-dialog modal-lg modal-ofctl-rest-api-form-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Default Modal</h4>
			</div>
			<div class="modal-body">
				<div class="modal-ofctl-rest-api-form-iframe-container">
					<iframe id="iframeOfctlRestApiForm" class="modal-ofctl-rest-api-form-iframe"></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-xs btn-primary" onclick="document.getElementById('iframeOfctlRestApiForm').contentWindow.formSubmit();">Submit</button>
				<button type="button" class="btn btn-xs btn-primary" onclick="document.getElementById('iframeOfctlRestApiForm').contentWindow.formValidate();">Validate</button>
				<button type="button" class="btn btn-xs btn-primary" onclick="document.getElementById('iframeOfctlRestApiForm').contentWindow.formGetData();">Get Data</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->