<?php
foreach($TDBFPARAM['ExtIncludeFile'] as $pFileType => $pArFiles){
	foreach($pArFiles as $pFile){
		if(file_exists('assets/'.$pFile)){
			switch($pFileType){
				case 'js':
					?><script type="text/javascript" src="<?php print base_url().'assets/'.$pFile; ?>"></script><?php
					break;
				case 'css':
					?><link href="<?php print base_url().'assets/'.$pFile; ?>" rel="stylesheet" type="text/css" /><?php
					break;
			}
		}
	}
}
?>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfView.js"></script>
<script type="text/javascript">
	<?php
	print 'var '.$TDBFPARAM['CTLID'].'=new tdbfView("'.$TDBFPARAM['CTLID'].'","'.$TDBFPARAM['CTLURI'].'","'.base_url().'");';
	?>
//tdbfDataModelEditor_001.myPrepare(); 
</script>
<section class="content" id="<?php print $TDBFPARAM['CTLID']; ?>_section">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
		  		<!-- SUDAH DIWAKILI DI view MyContentHeader.php
		  		<div class="box-header">
					<h3 class="box-title"><?php print $TDBFPARAM['otherParam']['sectionHeader']; ?></h3>
					<h5><?php print $TDBFPARAM['otherParam']['sectionNote']; ?></h5>
				</div>--><!-- /.box-header -->
			  	
			  	<div class="box-body">
			  		<div class="myAlertBox">
						<?php
						//print_r($TDBFPARAM);
						$pFlashData=$this->session->flashdata($TDBFPARAM['CTLID'].'.flashData');
						?>
						<?php if(sizeof($pFlashData['success'])>0){ ?>
							<div class="alert alert-success fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<?php 
								for($i=0;$i<sizeof($pFlashData['success']);$i++){
									print $pFlashData['success'][$i];
									if($i+1<sizeof($pFlashData['success'])){print '<br />';}
								}
							   	?>
							</div>
						<?php } ?>
						<?php if(sizeof($pFlashData['info'])>0){ ?>
							<div class="alert alert-info fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<?php 
								for($i=0;$i<sizeof($pFlashData['info']);$i++){
									print $pFlashData['info'][$i];
									if($i+1<sizeof($pFlashData['info'])){print '<br />';}
								}
								?>
							</div>
						<?php } ?>
						<?php if(sizeof($pFlashData['warning'])>0){ ?>
							<div class="alert alert-warning fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<?php 
								for($i=0;$i<sizeof($pFlashData['warning']);$i++){
									print $pFlashData['warning'][$i];
									if($i+1<sizeof($pFlashData['warning'])){print '<br />';}
								}
								?>
							</div>
						<?php } ?>
						<?php if(sizeof($pFlashData['danger'])>0){ ?>
							<div class="alert alert-danger fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<?php 
								for($i=0;$i<sizeof($pFlashData['danger']);$i++){
									print $pFlashData['danger'][$i];
									if($i+1<sizeof($pFlashData['danger'])){print '<br />';}
								}
								?>
							</div>
						<?php } ?>
					</div>
				<div class="divRecordHeaderPrint col-lg-12 col-md-12 col-sm-12 hidden-lg hidden-md hidden-sm">
					<h3 class="box-title"><?php print $TDBFPARAM['otherParam']['sectionHeader']; ?></h3>
					<h5><?php print $TDBFPARAM['otherParam']['sectionNote']; ?></h5>
					<div class="divRecordShowNote">
						Show <?php print $TDBFPARAM['customParam']['viewResult']['recordStart']; ?> - <?php print $TDBFPARAM['customParam']['viewResult']['recordEnd']; ?> of <?php print $TDBFPARAM['customParam']['viewResult']['recordCount']; ?> records
					</div>
					<div class="divFilterNote">
						<?php 
						$s1=trim($TDBFPARAM['customParam']['viewResult']['viewFilterNote']);
						if($s1!=''){
							print 'Data filter = '.$s1;
						} 
						?>
					</div>
				</div>
				<div class="divRecordHeader">
					<div class="divRecordHeaderLeft col-lg-9 col-md-9 col-sm-9">
						<div class="divRecordShowNote">
							Show <?php print $TDBFPARAM['customParam']['viewResult']['recordStart']; ?> - <?php print $TDBFPARAM['customParam']['viewResult']['recordEnd']; ?> of <?php print $TDBFPARAM['customParam']['viewResult']['recordCount']; ?> records
						</div>
						<div class="divFilterNote">
							<?php 
							$s1=trim($TDBFPARAM['customParam']['viewResult']['viewFilterNote']);
							if($s1!=''){
								print 'Data filter = '.$s1;
							} 
							?>
						</div>
					</div>
					<div class="divRecordHeaderRight col-lg-3 col-md-3 col-sm-3">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<input type="text" class="txtCari form-control input-xs" placeholder="Pencarian..." aria-describedby="sizing-addon4" value="<?php print $TDBFPARAM['customParam']['viewPref']['searchKeyword']; ?>" onkeypress="if(event.keyCode == 13){<?php print $TDBFPARAM['CTLID']; ?>.mySearchRecord(this.value);}">
								</td>
								<td width="50px" align="right">
									<select class="cmbPage form-control input-xs" onchange="<?php print $TDBFPARAM['CTLID']; ?>.myGotoPageIndex(this.value);">
										<?php
										for($i=1;$i<=$TDBFPARAM['customParam']['viewResult']['pageCount'];$i++){
											if($i==$TDBFPARAM['customParam']['viewResult']['pageIndex']){
												print '<option value="'.$i.'" selected="selected">'.$i.'</option>';
											}else{
												print '<option value="'.$i.'">'.$i.'</option>';
											}
										}
										?>
									</select>
								</td>
								<td width="30px" align="right">
									<button type="button" class="btnPreference btn btn-primary btn-xs" data-toggle="modal" data-target="#<?php print $TDBFPARAM['CTLID']; ?>_divPreference">
										<span class="glyphicon glyphicon-cog"></span>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- Setting View Modal Preference Dialog-->
				<div id="<?php print $TDBFPARAM['CTLID']; ?>_divPreference" class="divPreference modal fade" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Costumize View</h4>
							</div>
							<div class="modal-body">
								<div>
									<h4>Filter record :</h4>
									<table class="tblFilter table no-padding no-margin no-footer table-bordered table-hover">
										<thead class="tableheader-xs">
											<tr>
												<th>Delete</th>
												<th>Block</th>
												<th>Column Name</th>
												<th>Operand</th>
												<th>Value</th>
												<th>Block</th>
												<th>Operand</th>
											</tr>
										</thead>
										<tbody class="tablebody-xs">
											<tr class="tabledatarow">
												<td class="tabledatacell"><span class="btnFilterDelete glyphicon glyphicon-trash btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myRemoveFilterRow(this)"></span></td>
												<td class="tabledatacell"><input class="txtFilterBlock1 form-control input-xs" type="text" /></td>
												<td class="tabledatacell">
													<select class="cmbFilterField form-control input-xs">
														<?php
														for($i=0;$i<sizeof($TDBFPARAM['fields']);$i++){
															print '<option value="'.$TDBFPARAM['fields'][$i]['name'].'">'.$TDBFPARAM['fields'][$i]['caption'].'</option>';
														}
														?>
													</select>
												</td>
												<td class="tabledatacell">
													<select class="cmbFilterOperand1 form-control input-xs">
														<option value="=">=</option>
														<option value="!=">!=</option>
														<option value="&gt;">&gt;</option>
														<option value="&gt;=">&gt;=</option>
														<option value="&lt;">&lt;</option>
														<option value="&lt;=">&lt;=</option>
														<option value="%LIKE">%LIKE</option>
														<option value="LIKE%">LIKE%</option>
														<option value="%LIKE%">%LIKE%</option>
													</select>
												</td>
												<td class="tabledatacell"><input class="txtFilterValue form-control input-xs" type="text" /></td>
												<td class="tabledatacell"><input class="txtFilterBlock2 form-control input-xs" type="text" /></td>
												<td class="tabledatacell">
													<select class="cmbFilterOperand2 form-control input-xs">
														<option value=""></option>
														<option value="AND">AND</option>
														<option value="OR">OR</option>
													</select>
												</td>
											</tr>
												<?php
												//print_r($TDBFPARAM['customParam']['viewFilter']);
												for($i=0;$i<sizeof($TDBFPARAM['customParam']['viewFilter']);$i++){
												?>
													<tr class="tabledatarow">
													<td class="tabledatacell"><span class="btnFilterDelete glyphicon glyphicon-trash btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myRemoveFilterRow(this)"></span></td>
													<td class="tabledatacell"><input class="txtFilterBlock1 form-control input-xs" type="text" value="<?php print $TDBFPARAM['customParam']['viewFilter'][$i]['block1']; ?>" /></td>
													<td class="tabledatacell">
														<select class="cmbFilterField form-control input-xs">
															<?php
															for($j=0;$j<sizeof($TDBFPARAM['fields']);$j++){
																if($TDBFPARAM['customParam']['viewFilter'][$i]['colName']==$TDBFPARAM['fields'][$j]['name']){
																	print '<option value="'.$TDBFPARAM['fields'][$j]['name'].'" selected="selected">'.$TDBFPARAM['fields'][$j]['caption'].'</option>';
																}else{
																	print '<option value="'.$TDBFPARAM['fields'][$j]['name'].'" >'.$TDBFPARAM['fields'][$j]['caption'].'</option>';
																}
															}
															?>
														</select>
													</td>
													<td class="tabledatacell">
														<select class="cmbFilterOperand1 form-control input-xs">
															<option value="=" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='='){print 'selected="selected"';} ?>>=</option>
															<option value="!=" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='!='){print 'selected="selected"';} ?>>!=</option>
															<option value="&gt;" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='>'){print 'selected="selected"';} ?>>&gt;</option>
															<option value="&gt;=" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='>='){print 'selected="selected"';} ?>>&gt;=</option>
															<option value="&lt;" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='<'){print 'selected="selected"';} ?>>&lt;</option>
															<option value="&lt;=" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='<='){print 'selected="selected"';} ?>>&lt;=</option>
															<option value="%LIKE" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='%LIKE'){print 'selected="selected"';} ?>>%LIKE</option>
															<option value="LIKE%" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='LIKE%'){print 'selected="selected"';} ?>>LIKE%</option>
															<option value="%LIKE%" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand1']=='%LIKE%'){print 'selected="selected"';} ?>>%LIKE%</option>
														</select>
													</td>
													<td class="tabledatacell"><input class="txtFilterValue form-control input-xs" type="text" value="<?php print $TDBFPARAM['customParam']['viewFilter'][$i]['value']; ?>" /></td>
													<td class="tabledatacell"><input class="txtFilterBlock2 form-control input-xs" type="text" value="<?php print $TDBFPARAM['customParam']['viewFilter'][$i]['block2']; ?>" /></td>
													<td class="tabledatacell">
														<select class="cmbFilterOperand2 form-control input-xs">
															<option value="" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand2']==''){print 'selected="selected"';} ?>>	</option>
															<option value="AND" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand2']=='AND'){print 'selected="selected"';} ?>>AND</option>
															<option value="OR" <?php if($TDBFPARAM['customParam']['viewFilter'][$i]['operand2']=='OR'){print 'selected="selected"';} ?>>OR</option>
														</select>
													</td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
									<button type="button" class="btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myAddFilterRow()">Add Filter</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myApplyFilter()">Apply Filter</button>
									<button type="button" class="btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myResetFilter()">Reset Filter</button>
								</div>
								<hr />
								<div>
									<h4>Columns preference :</h4>
									<table>
										<tr>
											<td>Record per page</td>
											<td>&nbsp;:&nbsp;</td>
											<td><input class="txtRecordPerPage form-control input-xs" type="text" value="<?php print $TDBFPARAM['customParam']['viewPref']['recordPerPage']; ?>"/></td>
										</tr>
									</table>
									<br />
									<table class="tblColPreference table no-padding no-margin no-footer table-bordered table-hover">
										<thead class="tableheader-xs">
											<tr>
												<th class="hidden-lg hidden">Column Name</th>
												<th>Column Name</th>
												<th>Visible</th>
												<th>Position</th>
											</tr>
										</thead>
										<tbody class="tablebody-xs">
											<?php
											for($i=0;$i<sizeof($TDBFPARAM['customParam']['viewPref']['colPref']);$i++){
											?>
												<tr class="tabledatarow">
													<?php
													print '<td class="tabledatacell hidden-lg hidden">'.$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name'].'</td>';
													print '<td class="tabledatacell">'.$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['caption'].'</td>';
													if($TDBFPARAM['customParam']['viewPref']['colPref'][$i]['visible']){
														print '<td class="tabledatacell"><input type="checkbox" class="chkColPrefVisible" checked="true"></td>';
													}else{
														print '<td class="tabledatacell"><input type="checkbox" class="chkColPrefVisible"></td>';
													}
													print '<td class="tabledatacell">';
													print '<span class="btnMoveUp glyphicon glyphicon-arrow-up btn btn-primary btn-xs" onclick="'.$TDBFPARAM['CTLID'].'.myMoveUpColPrefRow(this)"></span>';
													print '<span class="btnMoveDown glyphicon glyphicon-arrow-down btn btn-primary btn-xs" onclick="'.$TDBFPARAM['CTLID'].'.myMoveDownColPrefRow(this)"></span>';
													print '</td>';
													?>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
									<button type="button" class="btnApplyColPref btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myApplyColPref()">Apply Layout</button>
									<button type="button" class="btnResetColPref btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myResetColPref()">Reset Layout</button>
									<button type="button" class="btnSaveColPref btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.mySaveColPref()">Save Layout</button>
								</div>
							</div>
							<div class="modal-footer">
								
							</div>
							</div>
						</div>
					</div>
					
					<!-- Setting View Modal Import Excel Dialog-->
					<div id="<?php print $TDBFPARAM['CTLID']; ?>_divDialogImportExcel" class="divDialogImportExcel modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Import From Excel</h4>
								</div>
								<div class="modal-body">
									<div>
										Excel file : <input type="file" name="file" class="inpFileExcel" /><br />
										<?php
										if($TDBFPARAM['features']['importExcelUpdateOnDuplicate']){
										?>
										<input type="checkbox" name="chkImportExcelUpdateIfExist" class="chkImportExcelUpdateIfExist" />Update record if exist/duplicate.<br />
										<?php
										}
										?>
										Download excel template <label class="myLabelTextButton myPointerCursor" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myDownloadTemplateExcelButtonClick()">here</label>.
										<div class="divResultImportExcel"></div>
									</div>
									<div align="right">
										<button type="button" class="btnImportExcel btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myImportExcelButtonClick()">Import</button>
									</div>
								</div>
							</div>	  
						</div>
					</div>  
					
					<!-- Setting View Modal Delete Excel Dialog-->
					<div id="<?php print $TDBFPARAM['CTLID']; ?>_divDialogDeleteExcel" class="divDialogDeleteExcel modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Delete From Excel</h4>
								</div>
								<div class="modal-body">
									<div>
										Excel file : <input type="file" name="file" class="inpFileExcel" /><br />
										Download excel template <label class="myLabelTextButton myPointerCursor" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myDownloadTemplateExcelButtonClick()">here</label>.
										<div class="divResultDeleteExcel"></div>
									</div>
									<div align="right">
										<button type="button" class="btnDeleteExcel btn btn-primary btn-xs" onclick="<?php print $TDBFPARAM['CTLID']; ?>.myDeleteExcelButtonClick()">Delete</button>
									</div>
								</div>
							</div>	  
						</div>
					</div>  
					<!-- </div> -->
					<?php
					//print '<pre>';
					//print_r($TDBFPARAM['customParam']['viewPref']['colPref']);
					//print '</pre>';
					?>
					<div style="overflow: auto" class="divRecordContent col-lg-12 col-md-12 col-sm-12">
					<table id="<?php print $TDBFPARAM['CTLID']; ?>_tblData" class="tblDataRecord table no-padding no-margin no-footer table-bordered table-hover">
						<thead class="tableheader-xs">
							<tr>
								<?php
								if(($TDBFPARAM['features']['select'])and($TDBFPARAM['customParam']['sectionMode']=='select')){
									print '<th><input type="checkbox" onclick="'.$TDBFPARAM['CTLID'].'.myChkSelectAllClick(this);" /></th>';
								}
								for($i=0;$i<sizeof($TDBFPARAM['customParam']['viewPref']['colPref']);$i++){
									$pStrClass='';
									$pStrOnClick='';
									if(!$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['visible']){
										$pStrClass.='hidden-lg hidden ';
									}else{
										//print '==='.$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name'].'===';
										if($TDBFPARAM['customParam']['viewPref']['colPref'][$i]['type']=='fieldCol'){
											$pStrClass.='myPointerCursor ';
											if($TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name']==$TDBFPARAM['customParam']['viewPref']['orderBy']){
												if($TDBFPARAM['customParam']['viewPref']['orderMode']=='asc'){$pStrOrderMode='desc';}else{$pStrOrderMode='asc';}
												$pStrOnClick=$TDBFPARAM['CTLID'].'.myChangeViewOrder('.chr(39).$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name'].chr(39).','.chr(39).$pStrOrderMode.chr(39).');';
											}else{
												$pStrOnClick=$TDBFPARAM['CTLID'].'.myChangeViewOrder('.chr(39).$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name'].chr(39).','.chr(39).'asc'.chr(39).');';
											}
										}
									}
									print '<th class="'.$pStrClass.'" onclick="'.$pStrOnClick.'">';							
									
									print $TDBFPARAM['customParam']['viewPref']['colPref'][$i]['caption'];
									if($TDBFPARAM['customParam']['viewPref']['orderBy']==$TDBFPARAM['customParam']['viewPref']['colPref'][$i]['name']){
										if($TDBFPARAM['customParam']['viewPref']['orderMode']=='asc'){
											print '<img class="pull-right" src="'.base_url().'assets/plugins/datatables/images/sort_asc.png" />';
										}else{
											print '<img class="pull-right" src="'.base_url().'assets/plugins/datatables/images/sort_desc.png" />';
										}
									}
									
									print '</th>';
								}
								?>
							</tr>
						</thead>
						<tbody class="tablebody-xs">
							<?php
							//[viewHTMLRecord]
							for($i=0;$i<sizeof($TDBFPARAM['viewHTMLRecord']);$i++){
							?>
							<tr class="tabledatarow">
								<?php
								if(($TDBFPARAM['features']['select'])and($TDBFPARAM['customParam']['sectionMode']=='select')){
									print '<td>';
									print $TDBFPARAM['viewHTMLRecord'][$i]['chkSelect'];
									print '</td>';
								}
								for($j=0;$j<sizeof($TDBFPARAM['customParam']['viewPref']['colPref']);$j++){
									if($TDBFPARAM['customParam']['viewPref']['colPref'][$j]['visible']){
										print '<td>';
									}else{
										print '<td class="hidden-lg hidden ">';
									}
									print $TDBFPARAM['viewHTMLRecord'][$i][$TDBFPARAM['customParam']['viewPref']['colPref'][$j]['name']];
									print '</td>';
								}
								?>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					</div>
					<br />
					<div class="divRecordFooter col-lg-12 col-md-12 col-sm-12">
						<?php if(isset($TDBFPARAM['customParam']['addButtonHTML'])){print $TDBFPARAM['customParam']['addButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['importExcelButtonHTML'])){print $TDBFPARAM['customParam']['importExcelButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['exportExcelButtonHTML'])){print $TDBFPARAM['customParam']['exportExcelButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['deleteExcelButtonHTML'])){print $TDBFPARAM['customParam']['deleteExcelButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['selectButtonHTML'])){print $TDBFPARAM['customParam']['selectButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['selectAllButtonHTML'])){print $TDBFPARAM['customParam']['selectAllButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['deselectAllButtonHTML'])){print $TDBFPARAM['customParam']['deselectAllButtonHTML'];}?>
						<?php if(isset($TDBFPARAM['customParam']['printButtonHTML'])){print $TDBFPARAM['customParam']['printButtonHTML'];}?>
					</div>
					<div class="divRecordFooterPrint col-lg-12 col-md-12 col-sm-12 hidden-lg hidden-md hidden-sm">
						<div align="right"><i>Printed on <?php print date('Y-m-d H:i:s');?> by user account : <?php print $TDBFPARAM['USERINFO']['loginUsername'];?></i></div>
					</div>
				</div><!-- /.box-body -->
			</div><!-- /.box -->

		</div><!-- /.col -->
		<?php
		//print '<pre>';
		//print_r($TDBFPARAM);
		//print '</pre>';
		?>
	</div><!-- /.row -->
	<script type="text/javascript">
		<?php
		print $TDBFPARAM['CTLID'].'.myPrepare();';
		?>
	</script>
</section><!-- /.content -->