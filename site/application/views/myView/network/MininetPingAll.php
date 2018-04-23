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
                	This feature used when using mininet on local machine with this site.<br />
                	Because each host on mininet doesn't send any packet periodictly, you need to trigger each host to send ICMP packet each so the switch can learn mac address fro this packet.
	              	<br />
	              	Hostname:<br />
	              	<input type="radio" name="rdHostname" value="Auto" checked="checked"/> Default detection (h1, h2, h3, ...)<br />
	              	<input type="radio" name="rdHostname" value="Manual"/> Manual, input hostname list below separate by space:<br />
	              	<textarea id="hostnameList" name="hostnameList"></textarea><br />
	              	<button id="btnPingTrigger" class="btn btn-primary btn-xs">Ping trigger</button>
					<div class="myDebugBox"></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->