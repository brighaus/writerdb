<?php
if (!empty( $delete_anchor) ) {
echo <<< EOT
<div style='font-weight:bold; color: red;'>You are about to permanently delete this entry. Click the 'delete' link below if you really want to do this.</div>   
EOT;
echo $delete_anchor . "<br/>";
}

echo $edit_anchor;
echo "<h2>$title</h2>";
echo "<h5>id: $doc_id</h5>";
echo "<h5>tags: $tags</h5>";
echo "<div style='width:100%; height: 1px; border-top:1px solid black;'>&#160;</div>";
echo $content;
echo $edit_anchor;
?>
