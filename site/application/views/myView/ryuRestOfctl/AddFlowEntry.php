<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<link rel="stylesheet" href="<?php print base_url().'assets/'; ?>plugins/bootstrap-select/dist/css/bootstrap-select.min.css" />
<link rel="stylesheet" href="<?php print base_url().'assets/'; ?>plugins/json-forms/dist/css/brutusin-json-forms.min.css" />
<script src="<?php print base_url().'assets/'; ?>plugins/markdown-js/lib/markdown.js"></script>
<script src="<?php print base_url().'assets/'; ?>plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="<?php print base_url().'assets/'; ?>plugins/bootstrap-select/dist/js/i18n/defaults-en_US.min.js"></script>
<script src="<?php print base_url().'assets/'; ?>plugins/json-forms/src/js/brutusin-json-forms.js"></script>
<script src="<?php print base_url().'assets/'; ?>plugins/json-forms/dist/js/brutusin-json-forms-bootstrap.min.js"></script>
<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/ofctlRestApi/addFlowEntry.js"></script>

<!-- 
<pre>
	<?php
	print_r($otherParam)
	?>
</pre>
-->
<script type="text/javascript">
	var dpid = "<?php print $otherParam['dpid'];?>";
	var mSchema = JSON.parse(<?php print chr(39).$otherParam['formSchema'].chr(39);?>);
	var mData = JSON.parse(<?php print chr(39).$otherParam['formData'].chr(39);?>);
	var mFormUrl = "<?php print $otherParam['formUrl'];?>";
</script>

<div id="formContainer"></div>
<?php
if($otherParam['withFormButton']){
	?>
	<button class="btnValidate">Validate</button>
	<button class="btnGetData">Get Data</button>
	<button class="btnSubmit">Submit</button>
	<?php
}
?>

