<h4>type (number of entries categorized as this type)</h4>
<?php
    echo $error;
    foreach ($types as $type){
    	$kv = (array) $type;
    	$k = $kv['key'];
    	$v = $kv['value'];
    	$search_key = html_entity_decode($k);
    	$src = "inquisitor/by_type/" . $search_key;
    	$anch = anchor($src, "$k ($v)", array("style"=>"margin-right: 15px;"));
    	echo $anch; 
    }
?>