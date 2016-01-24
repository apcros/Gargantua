<?php
include_once("graphics.php");
include_once("vbox-funcs.php");
include_once("isos-funcs.php");

HTMLheader();
HTMLnavbar(1);
HTMLcontainerOpen();

if(isset($_POST["vm_name"]) && isset($_POST["vm_os"]) && isset($_POST["vm_iso"]) && isset($_POST["vm_ram"]) && isset($_POST["vm_disk"])) {
	$vmName = htmlspecialchars($_POST["vm_name"]);
	$vmOs = htmlspecialchars($_POST["vm_os"]);
	$vmRam = htmlspecialchars($_POST["vm_ram"]);
	$vmIso = htmlspecialchars($_POST["vm_iso"]);
	$vmDisk = htmlspecialchars($_POST["vm_disk"]);

	//TODO differentiate success and fail to show the right display
	HTMLsuccess(addVm($vmName,$vmOs,$vmDisk,$vmRam,"isos/".$vmIso));
}


HTMLcreatevm(listIsos(),getOsTypes());


?>
  <script type="text/javascript">
     $(document).ready(function() {
    $('select').material_select();
  });
  </script>
<?php
HTMLcontainerClose();
HTMLfooter();

?>