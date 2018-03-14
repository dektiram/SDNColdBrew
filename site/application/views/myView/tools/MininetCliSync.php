<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/mininetCliSync.js"></script>
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <!-- /<div class="box-header">
                  <h3 class="box-title">Controller</h3>
                  <h5></h5>
                </div>.box-header -->
                <div class="box-body">
                	<div class="myAlertBox"></div>
                	<div class="mininetCliDisplay">
                		<pre class="mininetCliDisplayCommand">mininet ></pre>
                	</div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<div class="input-group">
                  		<input id="txtMininetCmd" placeholder="Type command ..." class="form-control" type="text" value="h1 ip a">
                      	<span class="input-group-btn">
                        	<button id="btnSendMininetCmd" class="btn btn-primary btn-flat">Send</button>
                      	</span>
                	</div>
            	</div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
 