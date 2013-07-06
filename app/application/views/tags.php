<?php 
    echo $byNumLink;
    echo $byTagLink;
    echo '<br/>';
?>
<h4>tag (number of entries using this tag)</h4>
<?php
    echo $error;
    // render sort by tag/number controls here
     //var_dump($tags);
    
    foreach ($tags as $tag){
    	$kv = (array) $tag;
    	$k = $kv['key'];
    	$v = $kv['value'];
    	$search_key = html_entity_decode($k);
    	$src = "inquisitor/by_tag/" . $search_key;
    	$anch = anchor($src, "$k ($v)", array("style"=>"margin-right: 15px;"));
    	echo $anch . "<br/>"; 
    }
?>