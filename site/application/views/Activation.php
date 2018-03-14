        <script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
        <script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/activation.js"></script>
        <script type="text/javascript">
        	var mActivation=new activation("<?php print base_url();?>");
        </script>
        <section class="content" id="bulkCheckDomain_section">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Activation</h3>
                  <h5></h5>
                </div><!-- /.box-header -->
                <div class="box-body">
					<div id="divInput1" class="form-group col-sm-6">
						<table id="tblDataPenyewa" class="tblEntry table no-padding no-margin no-footer no-border table-hover">
	                		<tbody class="tablebody-xs">
								<tr>
									<td>Activation Status</td>
									<td>:</td>
									<td width="70%">
										<?php 
											if($GLOBALS['dataLisence']['lisenceStatus']){
												print '<code>ACTIVATED</code>';
											}else{
												print '<code>NOT ACTIVATED</code>';
											}
										?>
									</td>
								</tr>
								<tr>
									<td>Application Name</td>
									<td>:</td>
									<td width="70%"><?php print $GLOBALS['dataLisence']['appName']; ?></td>
								</tr>
								<tr>
									<td>Application Version</td>
									<td>:</td>
									<td width="70%"><?php print $GLOBALS['dataLisence']['appVersion']; ?></td>
								</tr>
								<tr>
									<td>Activation Date</td>
									<td>:</td>
									<td width="70%"><?php print $GLOBALS['dataLisence']['lisenceDate']; ?></td>
								</tr>
								<tr>
									<td>Lisenced To</td>
									<td>:</td>
									<td width="70%"><?php print $GLOBALS['dataLisence']['lisenceTo']; ?></td>
								</tr>
					    	</tbody>
						</table>
						<br />
						
                		<div class="myAlertBox"></div>
						<div>
							<?php
							//if($GLOBALS['dataLisence']['lisenceStatus']){
							?>
							<button type="button" class="btn btn-xs" onclick="mActivation.myCheckDongle();">Check Dongle</button>
							<?php
							//}
							?>
							<button type="button" class="btn btn-xs" onclick="location.reload();">Refresh</button>
						</div>
					</div>
				</div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        <script type="text/javascript">
        	//mActivation.prepare();
        </script>