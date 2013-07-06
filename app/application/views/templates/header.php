
<html>
<head>
<title>Enter thought blast here...</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- TODO: dynamically load these, we don't need them most of the time -->
<link href="<?=base_url('ckeditor/editor.css'); ?>" rel="stylesheet"
	type="text/css" />

<script type="text/javascript"
	src="<?=base_url('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?=base_url('ckeditor/editor.js'); ?>"></script>

 <link rel="stylesheet" href="<?=base_url('css/jquery.ui.all.css'); ?>">
	<script src="<?=base_url('js/jquery-1.7.2.min.js'); ?>"></script>
	<script src="<?=base_url('js/jquery.ui.core.js'); ?>"></script>
	<script src="<?=base_url('js/jquery.ui.widget.js'); ?>"></script>
	<script src="<?=base_url('js/jquery.ui.datepicker.js'); ?>"></script>
	<script>
	$(function() {
		$( "#js_dp" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1980:c+01"
			});
	});
	</script>
</head>
<body>
	<?php $this->load->view('nav');?>