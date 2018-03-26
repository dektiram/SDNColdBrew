<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/networkDiscovery.js"></script>
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <!-- /<div class="box-header">
                  <h3 class="box-title">Controller</h3>
                  <h5></h5>
                </div>.box-header -->
                <div class="box-body">
                	<div class="myAlertBox"></div>
                	If you run a ryu controller after the network (mininet) is up then you need this action for network discovery. If you run the ryu controller before the network (mininet) is running, then you do not need to do this action.
	              	<br />
	              	<button id="btnPingTrigger" class="btn btn-primary btn-xs">Ping trigger</button>
					<div class="myDebugBox"></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->