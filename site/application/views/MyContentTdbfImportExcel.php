<?php
//print 'hahahahahah';
print '<pre>';
//print_r($TDBFPARAM['customParam']);
foreach($TDBFPARAM['customParam']['importExcelResult'] as $pResult){
	print $pResult.'<br />';
}
print '</pre>';
?>