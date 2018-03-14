<?php
foreach($TDBFPARAM['ExtIncludeFile'] as $pFileType => $pArFiles){
	foreach($pArFiles as $pFile){
		if(file_exists(base_url().'assets/'.$pFile)){
			switch($pFileType){
				case 'js':
					?><script type="text/javascript" src="<?php print base_url().'assets/'.$pFile; ?>myjs/tdbfEntry.js"></script><?php
					break;
				case 'css':
					?><link href="<?php print base_url().'assets/'.$pFile; ?>" rel="stylesheet" type="text/css" /><?php
					break;
			}
		}
	}
}
?>
        <script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfEntry.js"></script>
        <script type="text/javascript">
        	<?php
        		print 'var '.$TDBFPARAM['CTLID'].'=new tdbfEntry("'.$TDBFPARAM['CTLID'].'","'.$TDBFPARAM['CTLURI'].'");';
			?>
        </script>
        <section class="content" id="<?php print $TDBFPARAM['CTLID']; ?>_section">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php print $TDBFPARAM['otherParam']['sectionHeader']; ?></h3>
                  <h5><?php print $TDBFPARAM['otherParam']['sectionNote']; ?></h5>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<div class="myAlertBox">
	                	<?php
	                	$pFlashData=$this->session->flashdata($TDBFPARAM['CTLID'].'.flashData');
	                	?>
	                	<?php	if(sizeof($pFlashData['success'])>0){ ?>
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
						<?php	if(sizeof($pFlashData['info'])>0){ ?>
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
						<?php	if(sizeof($pFlashData['warning'])>0){ ?>
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
						<?php	if(sizeof($pFlashData['danger'])>0){ ?>
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
					<div class="form-group col-sm-6">
	                <table id="<?php print $TDBFPARAM['CTLID']; ?>_tblEntry" class="tblEntry table no-padding no-margin no-footer no-border table-hover">
	                	<tbody class="tablebody-xs">
	                		<?php 
	                		for($i=0;$i<sizeof($TDBFPARAM['fields']);$i++){
	                			$pFieldName=$TDBFPARAM['fields'][$i]['name'];
	                			$pSectionMode=$TDBFPARAM['customParam']['sectionMode'];
								if($pSectionMode=='add'){
									$pCtlObj=$TDBFPARAM['fields'][$i]['createForm']['ctlObj'];
									$pCtlUsed=$TDBFPARAM['fields'][$i]['createForm']['used'];
									$pCtlVisible=$TDBFPARAM['fields'][$i]['createForm']['visible'];
									$pCtlDefaultValue=$TDBFPARAM['fields'][$i]['createForm']['defaultValue'];
									$pCtlExtAttr=$TDBFPARAM['fields'][$i]['createForm']['extAttr'];
									$pCtlNote=$TDBFPARAM['fields'][$i]['createForm']['note'];
									$pCtlNotNull=$TDBFPARAM['fields'][$i]['createForm']['notNull'];
									if(isset($TDBFPARAM['fields'][$i]['createForm']['lookupList'])){
										$pCtlLookupList=$TDBFPARAM['fields'][$i]['createForm']['lookupList'];
									}else{$pCtlLookupList=array();}					
								}else{
									$pCtlObj=$TDBFPARAM['fields'][$i]['updateForm']['ctlObj'];
									$pCtlUsed=$TDBFPARAM['fields'][$i]['updateForm']['used'];
									$pCtlVisible=$TDBFPARAM['fields'][$i]['updateForm']['visible'];
									$pCtlDefaultValue=$TDBFPARAM['fields'][$i]['updateForm']['defaultValue'];
									$pCtlExtAttr=$TDBFPARAM['fields'][$i]['updateForm']['extAttr'];
									$pCtlNote=$TDBFPARAM['fields'][$i]['updateForm']['note'];
									$pCtlNotNull=$TDBFPARAM['fields'][$i]['updateForm']['notNull'];
									if(isset($TDBFPARAM['fields'][$i]['updateForm']['lookupList'])){
										$pCtlLookupList=$TDBFPARAM['fields'][$i]['updateForm']['lookupList'];
									}else{$pCtlLookupList=array();}	
								}
								//print $pCtlDefaultValue;
	                			if($pCtlUsed){
	                				//add hidden input before edit value
						    		if($pSectionMode=='edit'){
						    			?>
						    			<input type="hidden" id="ctlObjRefValue_<?php print $pFieldName;?>" class="ctlObjRefValue ctlObjRefValue_<?php print $pFieldName;?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" >
						    			<?php
						    		}
	                				if($pCtlVisible){
	                		?>
					  		<tr>
					    		<td>
					    			<?php 
					    			if($pCtlNotNull){
										print '*)';
									}
					    			print $TDBFPARAM['fields'][$i]['caption'];
					    			?>
					    		</td>
					    		<td>:</td>
					    		<td width="70%">
					    		<?php
					    		if($pCtlNotNull){$pNotNullClassStr='notNull';}else{$pNotNullClassStr='';}
					    		switch($pCtlObj){
									case 'txtString':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs" aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'txtInteger':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs" aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'txtFloat':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs" aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'txtCurrency':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs" aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'txtPassword':?>
										<input type="password" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs" aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'dropdown':?>
										<!-- <select id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ui-combobox-input ui-autocomplete-input ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs "  aria-describedby="sizing-addon4" <?php print $pCtlExtAttr;?> > -->
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs "  aria-describedby="sizing-addon4" <?php print $pCtlExtAttr;?> list="datalist_<?php print $pFieldName;?>" value="<?php print $pCtlDefaultValue;?>">
											
											<datalist id="datalist_<?php print $pFieldName;?>">
											<?php
											foreach($pCtlLookupList as $pCtlLookupListKey => $pCtlLookupListValue){
												if($pCtlLookupListKey==$pCtlDefaultValue){
													?>
													<option value="<?php print $pCtlLookupListKey; ?>" selected="selected"><?php print $pCtlLookupListValue; ?></option>
													<?php
												}else{
													?>
													<option value="<?php print $pCtlLookupListKey; ?>"><?php print $pCtlLookupListValue; ?></option>
													<?php
												}
											}
											?>
											</datalist>
										<!-- </select> -->
										<script type="text/javascript">
											//$('#ctlObj_<?php print $pFieldName;?>').combify();
										</script>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'list':?>
										<select id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ui-combobox-input ui-autocomplete-input ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs "  aria-describedby="sizing-addon4" size="5" multiple="multiple" <?php print $pCtlExtAttr;?> >
											<?php
											$pCtlArDefaultValue=explode(chr(10).chr(13),$pCtlDefaultValue);
											foreach($pCtlLookupList as $pCtlLookupListKey => $pCtlLookupListValue){
												if(in_array($pCtlLookupListKey,$pCtlArDefaultValue)){
													?>
													<option value="<?php print $pCtlLookupListKey; ?>" selected="selected"><?php print $pCtlLookupListValue; ?></option>
													<?php
												}else{
													?>
													<option value="<?php print $pCtlLookupListKey; ?>"><?php print $pCtlLookupListValue; ?></option>
													<?php
												}
											}
											?>
										</select>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'txtArea':?>
										<textarea type="text" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs"  aria-describedby="sizing-addon4" <?php print $pCtlExtAttr;?> ><?php print $pCtlDefaultValue;?></textarea>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'pickTime':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<script type="text/javascript">
											$('#ctlObj_<?php print $pFieldName;?>').timepicker({
												timeFormat: 'HH:mm:ss'
											});
										</script>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'pickDate':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<script type="text/javascript">
											$('#ctlObj_<?php print $pFieldName;?>').datepicker({
												dateFormat: 'yy-mm-dd'
											});
										</script>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'pickDateTime':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<script type="text/javascript">
											$('#ctlObj_<?php print $pFieldName;?>').datetimepicker({
												dateFormat: 'yy-mm-dd',
												timeFormat: 'HH:mm:ss'
											});
										</script>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'pickColor':?>
										<input type="text" id="ctlObj_<?php print $pFieldName;?>" name="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName; print ' '.$pNotNullClassStr.' '; ?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" <?php print $pCtlExtAttr;?> >
										<script type="text/javascript">
											$('.ctlObj_<?php print $pFieldName;?>').colorpicker();
										</script>
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
									case 'checkbox':
										if((strtolower($pCtlDefaultValue)=='true')or
											(strtolower($pCtlDefaultValue)=='checked')or
											(strtolower($pCtlDefaultValue)=='1')
										){
											$pCtlChecked='checked="checked"';
										}else{
											$pCtlChecked='';
										}
										?>
										<input type="checkbox" id="ctlObj_<?php print $pFieldName;?>" <?php print $pCtlChecked;?> class="ctlObj ctlObj_<?php print $pFieldName;?> input-xs"  aria-describedby="sizing-addon4" value="1" <?php print $pCtlExtAttr;?> >
										<?php if(trim($pCtlNote)!=''){print $pCtlNote;} break;
					    		}
					    		?>
					    		</td>
					  		</tr>
					  		<?php
					  				}else{
										?>
										<input type="hidden" id="ctlObj_<?php print $pFieldName;?>" class="ctlObj ctlObj_<?php print $pFieldName;?> form-control input-xs"  aria-describedby="sizing-addon4" value="<?php print $pCtlDefaultValue;?>" >
										<?php
										if(trim($pCtlNote)!=''){print $pCtlNote;} 
									}
								}
							}
					  		?>
					  	</tbody>
					</table>
                  <br />
                  <div">
                  	<?php 
                  	if($TDBFPARAM['customParam']['sectionMode']=='add'){
                  		if($TDBFPARAM['features']['create']){
                  			print $TDBFPARAM['customParam']['saveButtonHTML'];
						}
					}
					?>
                  	<?php 
                  	if($TDBFPARAM['customParam']['sectionMode']=='edit'){
                  		if($TDBFPARAM['features']['update']){
                  			print $TDBFPARAM['customParam']['updateButtonHTML'];
						}
					}
					?>
                  	<?php print $TDBFPARAM['customParam']['cancelButtonHTML'];?>
                  	<?php print $TDBFPARAM['customParam']['viewButtonHTML'];?>
                  </div>
					</div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
          <script type="text/javascript">
        	<?php
        		print $TDBFPARAM['CTLID'].'.myPrepare();';
			?>
          </script>
        <?php
        //print '<pre>';
        //print_r($TDBFPARAM);
		//print '</pre>';
        ?>
        </section><!-- /.content -->