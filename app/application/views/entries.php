<?php
if(empty($error)) {
	$error = "";
}
echo $error;
if(isset($entries)){
	foreach ($entries as $entry) {
		$date = $entry->key;
		$id = $entry->id;
		$ent_val = (array)$entry->value;
		$title = !empty($ent_val['title']) ? $ent_val['title']: $id;
		$url = site_url("entry/view/$id");
		echo "<a href='$url' style='margin-right: 15px;'>$date :: $title</a>";
		echo anchor('entry/edit/' . $id, "[edit]", array('style'=>'margin-right: 15px;'));
		echo anchor('entry/delete/' . $id, "[delete]", null /*attributes*/) . "<br/>";
	}
}
?>
