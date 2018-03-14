<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/mininetCommandProcessList.js"></script>
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
                	<table id="TdbfCtlAppUserAccount_tblData" class="tblDataRecord table no-padding no-margin no-footer table-bordered table-hover">
						<thead class="tableheader-xs">
							<tr>
								<th>PID</th>
								<th>Create Time</th>
								<th>Command</th>
								<th>Kill</th>
							</tr>
						</thead>
						<tbody class="tablebody-xs">
							<?php
							foreach ($otherParam['mininetCommandProcessList'] as $key => $value) {
								//print_r($value);
								?>
								<tr id="row_pid_<?php print $value['pid'];?>">
								<td><?php print $value['pid'];?></td>
								<td><?php print $value['create_time'];?></td>
								<td><code><?php print implode(' ',$value['command']);?></code></td>
								<td><button class="btn btn-primary btn-xs btnKillMininetCmd" data-pid="<?php print $value['pid'];?>">Kill</button></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
 