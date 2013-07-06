<?php 
$form_target = "http://localhost/writerdb/controllers/EntryController.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Enter thought blast here...</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="ckeditor.js"></script>
	<script src="editor.js" type="text/javascript"></script>
	<link href="editor.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<h1 class="samples">
		Enter thought blast here...
	</h1>
	<a href="views/entries.php">See entries</a>
	
	<!-- This <div> holds alert messages to be display in the sample page. -->
	<div id="alerts">
		<noscript>
			<p>
				<strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
				support, like yours, you should still see the contents (HTML data) and you should
				be able to edit it normally, without a rich editor interface.
			</p>
		</noscript>
	</div>
	<form action="<?=$form_target?>" method="post">
		<label>Entry Title <input type="text" name="_id"/></label>
		
		<label>Entry Date <input type="text" name="entryDate"/></label>
		
		<label>Entry Type
		<select name="entryType">
		    <option value="Story Idea">Story Idea</option>
		    <option value="Character">Character</option>
		    <option value="Experience">Experience</option>
		    <option value="non prose">non prose</option>
		    <option value="rant">Rant</option>
		    <option value="random thought">random thought</option>
		</select>
		</label>
		<label>Tags <input type="text" name="entryTags"/><br/>
		    <sub style="padding-left: 35px;">comma delimited</sub>
		</label>
		<input type="submit" value="Submit" />
			<textarea cols="80" id="editor1" name="editor1" rows="10"></textarea>
			<script type="text/javascript">
			//<![CDATA[

			CKEDITOR.replace( 'editor1',
				{
				    extraPlugins : 'docprops'
				});

		//]]>
			</script>
		<p>
			<input type="submit" value="Submit" />
		</p>
	</form>
	<div id="footer">
		<hr />
		<p>
			CKEditor - The text editor for the Internet - <a class="samples" href="http://ckeditor.com/">http://ckeditor.com</a>
		</p>
		<p id="copy">
			Copyright &copy; 2003-2011, <a class="samples" href="http://cksource.com/">CKSource</a> - Frederico
			Knabben. All rights reserved.
		</p>
	</div>
</body>
</html>
