        <?php
        	$mViewID='tdbfDataModelEditor_001';
			//$pDataModelServerPath=base_url().'data_models/';
        ?>
        <script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfDataModelEditor.js"></script>
        <script type="text/javascript">
        	<?php
        		print 'var '.$mViewID.'=new tdbfDataModelEditor("tdbfDataModelEditor_001");';
			?>
			//tdbfDataModelEditor_001.myPrepare(); 
        </script>
        <section class="content" id="<?php print $mViewID; ?>_section">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
              	<div class="box-body">
              		<div class="myAlertBox"></div>
              		<input type="file" id="<?php print $mViewID; ?>_fileToLoad" style="visibility:hidden" onchange="<?php print $mViewID; ?>.myLoadDataModelFromFile(this.files[0])">
	              	<button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.mySelectDataModelFile()">Load From Local File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.mySaveDataModelToFile()">Save To Local File</button>
	              	<button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myShowDlgServerFile('load')" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgServerFile">Load From Server File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myShowDlgServerFile('save')" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgServerFile">Save To Server File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myViewDataModel()" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgViewDataModel">View Data Model</button>
	                <button type="button" class="btn btn-xs" onclick="<?php print $mViewID; ?>.myReorderColumnPositionIndex()">Reorder Column Position Index</button>
               	</div>
                <div class="box-body">
                  <h4>Identity</h4>
				  <table>
				  	<tr>
				  		<td>Data Model Name</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtDataModelName" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Database Config Name</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtDatabaseConfigName" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Database Table Name (Create)</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtTableNameCreate" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Database Table Name (Read)</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtTableNameRead" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Database Table Name (Update)</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtTableNameUpdate" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Database Table Name (Delete)</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtTableNameDelete" type="text" /></td>
				  	</tr>
				  </table>
                </div><!-- /.box-body -->
                <div class="box-body">
                  <h4>Database Field Column</h4>
                  <table id="<?php print $mViewID; ?>_tblField" class="table-bordered table-hover">
                    <thead class="tableheader-xs">
                      <tr>
                        <th></th>
                        <th>Field Attribute</th>
                        <th>On Create Form</th>
                        <th>On Update Form</th>
                        <th>On Data View</th>
                      </tr>
                    </thead>
                    <tbody class="tablebody-xs">
                      <tr class="tabledatarow" id="<?php print $mViewID; ?>_tblField_row">
                      	<td class="tabledatacell">
                      		<span class="btnDelete glyphicon glyphicon-trash btn btn-xs" onclick="<?php print $mViewID; ?>.myRemoveDataFieldRow(this)"></span><br />
                      		<span class="btnMoveUp glyphicon glyphicon-arrow-up btn btn-xs" onclick="<?php print $mViewID; ?>.myMoveUpDataFieldRow(this)"></span><br />
                      		<span class="btnMoveDown glyphicon glyphicon-arrow-down btn btn-xs" onclick="<?php print $mViewID; ?>.myMoveDownDataFieldRow(this)"></span><br />
                      	</td>
                        <td class="tabledatacell">
							<label><input type="checkbox" class="chkIsKey" value="isKey">Is Key</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkNotNull" value="notNull" checked="checked">Not Null</label><br />fsdf
                        	Name :<br />
                        	<input class="txtFieldName form-control input-xs" type="text" />
                        	Caption :<br />
                        	<input class="txtFieldCaption form-control input-xs" type="text" />
                        	Data type :<br />
                        	<select class="cmbFieldDataType form-control input-xs">
							    <option value="bigint">Big Integer</option>
							    <option value="bit">Bit</option>
							    <option value="blob">Blob</option>
							    <option value="boolean">Boolean</option>
							    <option value="char">Char</option>
							    <option value="date">Date</option>
							    <option value="datetime">Date Time</option>
							    <option value="decimal">Decimal</option>
							    <option value="double">Double</option>
							    <option value="float">Float</option>
							    <option value="int">Integer</option>
							    <option value="time">Time</option>
							    <option value="timestamp">Timestamp</option>
							    <option value="varchar" selected="selected">Varchar</option>
							</select>
							Data length :<br />
							<input class="txtFieldDataLength form-control input-xs" type="text" value="255"/>
                        	Default value :<br />
                        	<input class="txtFieldDefaultValue form-control input-xs" type="text" />
                        </td>
                        <td class="tabledatacell">
							<label><input type="checkbox" class="chkCFUsed" value="" checked="checked">Used</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkCFReadOnly" value="">Read only</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkCFNotNull" value="" checked="checked">Not null</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkCFVisible" value="" checked="checked">Visible</label><br />
							Input control object :<br />
							<select class="cmbCFObjCtl form-control input-xs">
							    <option value="txtString">Text Field, String</option>
							    <option value="txtInteger">Text Field, Integer</option>
							    <option value="txtFloat">Text Field, Float</option>
							    <option value="txtCurrency">Text Field, Currency</option>
							    <option value="txtPassword">Text Field, Password</option>
							    <option value="dropdown">Dropdown</option>
							    <option value="list">List/Menu</option>
							    <option value="txtArea">Text Area</option>
							    <option value="pickTime">Time Picker</option>
							    <option value="pickDate">Date Picker</option>
							    <option value="pickDateTime">Date Time Picker</option>
							    <option value="pickColor">Color Picker</option>
							    <option value="checkbox">Checkbox</option>
							    <option value="wysiwyg">Wysiwyg</option>
							</select>
							Default value :<br />
							<input class="txtCFDefaultValue form-control input-xs" type="text" />
							Lookup value :<br />
							<input class="txtCFLookupValue form-control input-xs" type="text" />
							Additional attribute :<br />
							<input class="txtCFExtendedAttribute form-control input-xs" type="text" />
							Notes (allow HTML) :<br />
							<input class="txtCFNote form-control input-xs" type="text" />
						</td>
                        <td class="tabledatacell">
							<label><input type="checkbox" class="chkUFUsed" value="" checked="checked">Used</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkUFReadOnly" value="">Read only</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkUFNotNull" value="" checked="checked">Not null</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkUFVisible" value="" checked="checked">Visible</label><br />
							Input control object :<br />
							<select class="cmbUFObjCtl form-control input-xs">
							    <option value="txtString">Text Field, String</option>
							    <option value="txtInteger">Text Field, Integer</option>
							    <option value="txtFloat">Text Field, Float</option>
							    <option value="txtCurrency">Text Field, Currency</option>
							    <option value="txtPassword">Text Field, Password</option>
							    <option value="dropdown">Dropdown</option>
							    <option value="list">List/Menu</option>
							    <option value="txtArea">Text Area</option>
							    <option value="pickTime">Time Picker</option>
							    <option value="pickDate">Date Picker</option>
							    <option value="pickDateTime">Date Time Picker</option>
							    <option value="pickColor">Color Picker</option>
							    <option value="checkbox">Checkbox</option>
							    <option value="wysiwyg">Wysiwyg</option>
							</select>
							Default value :<br />
							<input class="txtUFDefaultValue form-control input-xs" type="text" />
							Lookup value :<br />
							<input class="txtUFLookupValue form-control input-xs" type="text" />
							Additional attribute :<br />
							<input class="txtUFExtendedAttribute form-control input-xs" type="text" />
							Notes (allow HTML) :<br />
							<input class="txtUFNote form-control input-xs" type="text" />
						</td>
                        <td class="tabledatacell">
							<label><input type="checkbox" class="chkVTSqlSelect" value="" checked="checked">SQL select</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkVTVisible" value="" checked="checked">Visible</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkVTListValue" value="">List value</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkVTSelectReturn" value="">As select return</label>&nbsp;&nbsp;
							<label><input type="checkbox" class="chkVTExportExcel" value="" checked="checked">Export excel</label><br />
							Text before (allow HTML) :<br />
							<input class="txtVTTextBefore form-control input-xs" type="text" />
							Text after (allow HTML) :<br />
							<input class="txtVTTextAfter form-control input-xs" type="text" />
							Lookup value :<br />
							<input class="txtVTLookupValue form-control input-xs" type="text" />
							Column position index :<br />
							<input class="txtVTColPos form-control input-xs" type="text" />
							Cell attribute :<br />
							<input class="txtVTCellExtendedAttribute form-control input-xs" type="text" />
						</td>
                      </tr>
                    </tbody>
                  </table>
                  <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myAddDataFieldRow()">Add</button>
                </div><!-- /.box-body -->
                <div class="box-body">
                  <h4>Extended Column</h4>
                  <table id="<?php print $mViewID; ?>_tblExtCol" class="table-bordered table-hover">
                    <thead class="tableheader-xs">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Name</th>
                        <th>Caption</th>
                        <th>Visible</th>
                        <th>Export Excel</th>
                        <th>Column Pos</th>
                        <th>Cell Attribute</th>
                      </tr>
                    </thead>
                    <tbody class="tablebody-xs">
                      <tr id="<?php print $mViewID; ?>_tblExtCol_row" class="tabledatarow">
                      	<td class="tabledatacell"><span class="btnDelete glyphicon glyphicon-trash btn btn-xs" onclick="<?php print $mViewID; ?>.myRemoveExtColRow(this)"></span></td>
                      	<td class="tabledatacell"><span class="btnMoveUp glyphicon glyphicon-arrow-up btn btn-xs" onclick="<?php print $mViewID; ?>.myMoveUpExtColRow(this)"></span></td>
                      	<td class="tabledatacell"><span class="btnMoveDown glyphicon glyphicon-arrow-down btn btn-xs" onclick="<?php print $mViewID; ?>.myMoveDownExtColRow(this)"></span></td>
                      	<td class="tabledatacell"><input class="txtExtColName form-control input-xs" type="text" /></td>
                      	<td class="tabledatacell"><input class="txtExtColCaption form-control input-xs" type="text" /></td>
                        <td class="tabledatacell"><input type="checkbox" class="chkExtColVisible" value=""></td>
                        <td class="tabledatacell"><input type="checkbox" class="chkExtColExportExcel" value=""></td>
                      	<td class="tabledatacell"><input class="txtExtColPos form-control input-xs" type="text" /></td>
                      	<td class="tabledatacell"><input class="txtExtColCellExtendedAttribute form-control input-xs" type="text" /></td>
                      </tr>
                    </tbody>
                  </table>
                  <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myAddExtColRow()">Add</button>
                </div><!-- /.box-body -->
                <div class="box-body">
                  <h4>Feature</h4>
                  <div class="checkbox">
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkView">View</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkCreate">Create</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkUpdate">Update</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkDelete">Delete</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkImportExcel">Import Excel</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkImportExcelUpdateOnDuplicate">Update On Dupplicate (Import Excel)</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkExportExcel">Export Excel</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkDeleteExcel">Delete From Excel</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkSelect">Select Record</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkFiterPanel">Filter Panel</label>&nbsp;&nbsp;
  					<label><input type="checkbox" id="<?php print $mViewID; ?>_chkViewPreference">View Preference</label>&nbsp;&nbsp;
				  </div>
				  <h4>Other Parameter</h4>
				  <table>
				  	<tr>
				  		<td>Section Header</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtSectionHeader" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Section Note</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtSectionNote" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Pre filter SQL</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtPreFilterSql" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Pre filter note</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtPreFilterNote" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Edit column pos</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtEditColPos" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Delete column pos</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtDeleteColPos" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Default section</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td>
				  			<select class="form-control input-xs" id="<?php print $mViewID; ?>_cmbDefaultSection">
							    <option value="view">View data</option>
							    <option value="createForm">Create Form</option>
							    <option value="importExcel">Import Excel</option>
							</select>
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>Default Record per page</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtDefaultRecordPerPage" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>Default Order by</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtDefaultOrderBy" type="text" /></td>
				  	</tr>
				  	<tr>
				  		<td>DefaultOrder mode</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td>
				  			<select class="form-control input-xs" id="<?php print $mViewID; ?>_cmbDefaultOrderMode">
							    <option value="asc">Ascending</option>
							    <option value="desc">Descending</option>
							</select>
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>View table row extended attribute</td>
				  		<td>&nbsp;:&nbsp;</td>
				  		<td><input class="form-control input-xs" id="<?php print $mViewID; ?>_txtViewTableRowExtendedAttribute" type="text" /></td>
				  	</tr>
				  </table>
                </div><!-- /.box-body -->
			  	<div class="box-body">
	              	<button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.mySelectDataModelFile()">Load From Local File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.mySaveDataModelToFile()">Save To Local File</button>
	              	<button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myShowDlgServerFile('load')" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgServerFile">Load From Server File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myShowDlgServerFile('save')" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgServerFile">Save To Server File</button>
	                <button type="button" class="btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myViewDataModel()" data-toggle="modal" data-target="#<?php print $mViewID; ?>_divDlgViewDataModel">View Data Model</button>
	                <button type="button" class="btn btn-xs" onclick="<?php print $mViewID; ?>.myReorderColumnPositionIndex()">Reorder Column Position Index</button>
               	</div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          
          <!-- Setting View Modal -->
		  <div id="<?php print $mViewID; ?>_divDlgViewDataModel" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					    <h4 class="modal-title">View Data Model</h4>
					</div>
					<div class="modal-body">
			    	</div>
			    	<div class="modal-footer">
			    	</div>
				</div>
			</div>
		  </div>
          
          <!-- Server File View Modal -->
		  <div id="<?php print $mViewID; ?>_divDlgServerFile" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					    <h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						Server file name :<br />
						<input class="txtServerFileName form-control input-xs" type="text" />
			    	</div>
			    	<div class="modal-footer">
			    		<button type="button" class="btnDlgServerFileOk btn btn-xs" data-dismiss="modal" onclick="<?php print $mViewID; ?>.myDlgServerFileOk(this)">Load From Local File</button>
			    	</div>
				</div>
			</div>
		  </div>
		  <script type="text/javascript">
        	<?php
        		print $mViewID.'.myPrepare();';
			?>
          </script>
        </section><!-- /.content -->