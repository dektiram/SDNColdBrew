<script type="text/javascript" src="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url().'assets/'; ?>plugins/vis/dist/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div id="divChart"></div>
				<div class="row">
					<?php
					foreach ($otherParam['INTFS'] as $intf => $dtIntf) {
						print '<div class="col-md-1">';
						print '<label>';
						print '<input type="checkbox" id="'.$intf.'" checked="true" class="seriesCheckbox">'.$intf;
						print '</label>';
						print '</div>';
					}
					?>
				</div>
				<div>
					<button id="btnStartStopRequest">Stop Request</button>
				</div>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
	var mIntfs = {};
	<?php
	foreach ($otherParam['INTFS'] as $intf => $dtIntf) {
		?>
		mIntfs[<?php print chr(39).$intf.chr(39); ?>] = {};
		mIntfs[<?php print chr(39).$intf.chr(39); ?>]['bw'] = <?php print $dtIntf['bw']; ?>;
		mIntfs[<?php print chr(39).$intf.chr(39); ?>]['threshold'] = <?php print $dtIntf['threshold']; ?>;
		
		
		<?php
	}
	?>
	//console.log(mIntfs);
	
</script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/viewLoadGraph.js"></script>