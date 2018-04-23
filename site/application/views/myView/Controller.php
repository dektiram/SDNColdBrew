<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/controller.js"></script>
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
                	<div class="col-md-12">
                		<div class="box box-primary box-solid">
	            			<div class="box-header with-border">
	              				<h3 class="box-title">Process Information</h3>
	            			</div><!-- /.box-header -->
	            			<div class="box-body">
	            				<?php //print_r($otherParam['processInfoAll']);?>
	              				<table class="table table-bordered">
	              					<tbody>
	              						<tr>
	              							<th style="width: 100px">Process</th>
	              							<th style="width: 80px">PID</th>
	              							<th style="width: 150px">Create Time</th>
	              							<th>Command</th>
	              						</tr>
	              						<tr>
	              							<td><code>ryu-manager</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['ryu']['pid'])){
	              									print $otherParam['processInfoAll']['ryu']['pid'];
												}else{print '-';}
	              								?>
	              							</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['ryu']['create_time'])){
	              									print $otherParam['processInfoAll']['ryu']['create_time'];
												}else{print '-';}
	              								?>
	              							</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['ryu']['command'])){
	              									print implode(' ',$otherParam['processInfoAll']['ryu']['command']);
												}else{print '-';}
	              								?>
	              							</code></td>
	              						</tr>
	              						<tr>
	              							<td><code>sflow-rt</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['sflowrt']['pid'])){
	              									print $otherParam['processInfoAll']['sflowrt']['pid'];
												}else{print '-';}
	              								?>
	              							</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['sflowrt']['create_time'])){
	              									print $otherParam['processInfoAll']['sflowrt']['create_time'];
												}else{print '-';}
	              								?>
	              							</code></td>
	              							<td><code>
	              								<?php
	              								if(isset($otherParam['processInfoAll']['sflowrt']['command'])){
	              									print implode(' ',$otherParam['processInfoAll']['sflowrt']['command']);
												}else{print '-';}
	              								?>
	              							</code></td>
	              						</tr>
	              					</tbody>
					            </table>
	              				<br />
	              				<button id="btnEnvStart" class="btn btn-primary btn-xs">START</button>&nbsp;&nbsp;
	              				<button id="btnEnvStop" class="btn btn-primary btn-xs">STOP</button>
	              				<br />
	              				<label class="mySmallRedItalic">*)Select ryu script below before start.</label>
	            			</div><!-- /.box-body -->
	          			</div>
                	</div>
                	<div class="col-md-6">
                		<div class="box box-primary box-solid">
	            			<div class="box-header with-border">
	              				<h3 class="box-title">Packaged Ryu Script</h3>
	              				<div class="box-tools pull-right">
					            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					            	</button>
					        	</div>
	            			</div><!-- /.box-header -->
	            			<div class="box-body">
	            				<?php
	            				foreach($otherParam['ryuScript']['packaged'] as $x1){
	            				?>
	              				<div class="col-md-6 form-check">
	              					<input class="form-check-input chkPackagedRyuScript" type="checkbox" value="<?php print $x1['file']?>" id="defaultCheck1" <?php if($x1['selected']){print 'checked="checked"';} ?>>
	              					<label class="form-check-label" for="defaultCheck1"><?php print $x1['file']?></label>
	              				</div>
	              				<?php
								}
	              				?>
	            			</div><!-- /.box-body -->
	          			</div>
                	</div>
                	<div class="col-md-6">
                		<div class="box box-primary box-solid">
	            			<div class="box-header with-border">
	              				<h3 class="box-title">Additional Ryu Script</h3>
	              				<div class="box-tools pull-right">
					            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					            	</button>
					        	</div>
	            			</div><!-- /.box-header -->
	            			<div class="box-body">
	              				<?php
	            				foreach($otherParam['ryuScript']['additional'] as $x1){
	            				?>
	              				<div class="form-check">
	              					<input class="form-check-input chkAdditionalRyuScript" type="checkbox" value="<?php print $x1['file']?>" <?php if($x1['selected']){print 'checked="checked"';} ?>>
	              					<label class="form-check-label" for="defaultCheck1"><?php print $x1['file']?></label>
	              					<span class="glyphicon glyphicon-trash pull-right myPointerCursor btnDelAdditionalRyuScript" data-file="<?php print $x1['file']; ?>"></span>
	              				</div>
	              				<?php
								}
	              				?>
	              				<hr>
	              				<form method="post" enctype="multipart/form-data">
	              					<div class="form-group-sm">
										<div class="input-group" name="Fichier1">
											<input name="fileRyuScript" type="file" class="" />
								    		<span class="input-group-btn">
								       			 <button class="btn btn-primary btn-xs" type="submit">Upload</button>
								    		</span>
										</div>
									</div>
	              				</form>
	              				
	            			</div><!-- /.box-body -->
	          			</div>
                	</div>
                	<div class="col-md-12">
                		<div class="box box-primary box-solid collapsed-box">
	            			<div class="box-header with-border">
	              				<h3 class="box-title">ryu-manager shell capture</h3>
	              				<div class="box-tools pull-right">
					            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
					            	</button>
					        	</div>
	            			</div><!-- /.box-header -->
	            			<div class="box-body" style="height: 200px; overflow:scroll">
	            				<pre><?php print $otherParam['processInfoAll']['ryu']['lastLog']; ?></pre>
	            			</div><!-- /.box-body -->
	          			</div>
                	</div>
                	<div class="col-md-12">
                		<div class="box box-primary box-solid collapsed-box">
	            			<div class="box-header with-border">
	              				<h3 class="box-title">sFlow-RT shell capture</h3>
	              				<div class="box-tools pull-right">
					            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
					            	</button>
					        	</div>
	            			</div><!-- /.box-header -->
	            			<div class="box-body" style="height: 200px; overflow:scroll">
	            				<pre><?php print $otherParam['processInfoAll']['sflowrt']['lastLog']; ?></pre>
	            			</div><!-- /.box-body -->
	          			</div>
                	</div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->