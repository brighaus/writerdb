<?php

?>
<script>
function countit(){
    var formcontent=document.wordcount.wordcount2.value;
    formcontent=formcontent.split(" ");
    document.wordcount.wordcount3.value=formcontent.length;
}
</script>
<form name="wordcount">

	<div>
		<textarea rows="12" name="wordcount2" cols="60" wrap="soft"></textarea>
	</div>
	<div>
		<input type="button" value="Calculate Words" onclick="countit()"> <input
			type="text" name="wordcount3" size="20">
	</div>
</form>
