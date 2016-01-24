<?php
include_once("graphics.php");


HTMLheader();
HTMLnavbar(0);
HTMLcontainerOpen();
HTMLsnapshotsmodal();
?>


<pre style="display: none"></pre>
<div id='vmlist'></div>

<script type="text/javascript">
	$(document).ready(function(){
		loadVMList();

	});

	setInterval(function(){ loadVMList(); }, 1000);

</script>

<?php
HTMLcontainerClose();
HTMLfooter();

?>