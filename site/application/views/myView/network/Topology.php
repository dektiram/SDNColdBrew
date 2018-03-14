<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis-network.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/json-viewer/jquery.json-viewer.css" rel="stylesheet" type="text/css"/>
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
				            <div id="netObjInfoBody" class="box-body">
				              The body of the box
				            </div>
				            <!-- /.box-body -->
				          </div>
	                	</div>
	                	
					<div class="myDebugBox"></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->