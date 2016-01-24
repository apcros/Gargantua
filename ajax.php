<?php
include_once("graphics.php");
include_once("vbox-funcs.php");
if(isset($_GET["getvmlist"])) {

	$AllVms = listVMS();
	foreach ($AllVms as $key => $VM) {

		HTMLvmcard(getInfo("name",$VM["UID"]), getInfo("ostype",$VM["UID"]),getInfo("memory",$VM["UID"]), getInfo("VMState",$VM["UID"]),getInfo("vrde",$VM["UID"]),$VM["UID"],getAllInfos($VM["UID"]));
	}
	if(count($AllVms) < 1) {
		HTMLfail("No Vms, please create one first");
	}

}

if(isset($_GET["VRDE"]) && isset($_GET["UID"])) {
	$params = array(	'vrde' => $_GET["VRDE"],
						'vrdeport' => "5000",
						'vrdeauthtype' => "null" );

  	echo modifyVM($params,$_GET["UID"]);
}

if(isset($_GET["pON"])) {
	$power_result = powerVM("on", $_GET["pON"]);
	if(strstr($power_result, "started")) {
		echo 1;
	} else {
		echo "Error : ".$power_result;
	}
}

if(isset($_GET["pOFF"])) {
	$power_result = powerVM("off", $_GET["pOFF"]);
		echo 1;
}

if(isset($_GET["pDEL"])) {
	deleteVM($_GET["pDEL"]);
	echo 1;
}

if(isset($_POST["GET_SNAPS"])) {
	$uid = htmlspecialchars($_POST["GET_SNAPS"]);
	HTMLsnapshotsli(getSnapshots($uid),$uid);
}

if(isset($_POST["ADD_SNAP"]) && isset($_POST["UID"])) {
	$snapname = htmlspecialchars($_POST["ADD_SNAP"]);
	$vmuid = $_POST["UID"];
	newSnapshot($vmuid,$snapname);
	echo 1;
}

if(isset($_POST["DEL_SNAP"]) && isset($_POST["UID"])) {
	$snapid = htmlspecialchars($_POST["DEL_SNAP"]);
	$vmuid = $_POST["UID"];

	delSnapshot($vmuid,$snapid);
	echo 1;
}
?>