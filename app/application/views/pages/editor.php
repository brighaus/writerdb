
<?php
$d_id = (empty($doc_id)) ? "": $doc_id;
$d_rev = (empty($doc_rev)) ? "": $doc_rev;
$d_ttl = (empty($doc_title)) ? "": $doc_title;
$e_dt = (empty($entryDate)) ? "": $entryDate;
$e_tgs = (empty($entryTags)) ? "": $entryTags;
$type = (empty($entryType)) ? null: $entryType;
$types = (empty($entryTypes)) ? null: $entryTypes;
$cnt = (empty($content)) ? "": $content;
$teln = (empty($type_edit_launch)) ? "": $type_edit_launch;

$options = array ("Story Idea"=>"Story Idea",
		"Character"=>"Character",
		"Experience"=>"Experience",
		"non prose"=>"non prose",
		"rant"=>"Rant",
		"dream"=>"dream",
		"random thought"=>"random thought");
$type_select = $types;
?>
<h1 class="samples">Enter thought blast here...</h1>

<!-- This <div> holds alert messages to be display in the sample page. -->
<div id="alerts">
	<?=$error?>
	<noscript>
		<p>
			<strong>CKEditor requires JavaScript to run</strong>. In a browser
			with no JavaScript support, like yours, you should still see the
			contents (HTML data) and you should be able to edit it normally,
			without a rich editor interface.
		</p>
	</noscript>
</div>
<form action="<?=$form_target?>" method="post">
	<?=$d_id?>
	<?=$d_rev?>
	<label>Entry Title <input type="text" name="entryTitle"
		value="<?=$d_ttl?>" />
	</label> <label>Entry Date <input type="text" id="js_dp" name="entryDate" value="<?=$e_dt?>" />
	</label> <label>Entry Type <?=$type_select?> <?=$teln;?>
	</label> <label>Tags <input type="text" name="entryTags"
		value="<?=$e_tgs?>" /><br /> <sub style="padding-left: 35px;">comma
			delimited</sub>
	</label> <input type="submit" value="Submit" />
	<textarea cols="80" id="content" name="content" rows="10">
	<?=$cnt?>
	</textarea>
	<script type="text/javascript">
			//<![CDATA[

			CKEDITOR.replace( 'content',
				{
				    extraPlugins : 'docprops'
				});

		//]]>
			</script>
	<div>
		<input type="submit" value="Submit" />
	</div>
</form>
