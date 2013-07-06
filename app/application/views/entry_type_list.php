<?php
	echo "<div style='margin-bottom:15px;'>";
	echo anchor('entry_type/edit', 'edit list');
	echo "</div>";
    foreach ($entry_types as $type) {
    	echo "$type<br/>";
    }
    echo "<div style='margin-top:15px;'>";
    echo anchor('entry_type/edit', 'edit list');
    echo "</div>";
?>