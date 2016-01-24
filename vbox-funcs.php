<?php 


/*
Todo : 
Install VBOX ext auto
Execute this : VBoxManage setproperty vrdeextpack "Oracle VM VirtualBox Extension Pack" (For VRD)
*/
function getInfo($paramName, $uuid) {
	$paramClean = $paramName."=";


	$vboxExec = shell_exec("VBoxManage showvminfo ".$uuid." --machinereadable | grep '".$paramClean."'"); //TODO : Grep correct value


	if($vboxExec != "") {
		$explodedVbox = explode($paramClean, $vboxExec);
		return str_replace("\"", "", $explodedVbox[1]);
	} else {
		return "ERROR I01 : Failed to get ".$paramClean." for ".$uuid;
	}

}

function getHostInfo() {
	$vboxExec = shell_exec("VBoxManage list hostinfo 2>&1");
	return str_replace("\n", "</br>", str_replace("Host Information:\n", "", $vboxExec));
}

function deleteVM($UID) {
	$vboxExec = shell_exec("VBoxManage unregistervm ".$UID." --delete");
	return $vboxExec;
}

function modifyVM($KeyValueArray, $uuid) {

	$argsString = "";

	if(sizeof($KeyValueArray) == 0) {
		return "ERROR M01 : No args specified";
	}

	foreach ($KeyValueArray as $key => $value) {
		$argsString .= "--".$key." ".$value." ";
	}

	$vboxExec = shell_exec("VBoxManage modifyvm ".$uuid." ".$argsString." 2>&1");

	if($vboxExec != "") { //TODO : Check what is the value returned for a success
		return "ERROR M02 : An error occured while trying to edit ".$uuid." ".$vboxExec;
	} else {
		return 1;
	}

}

function getSnapshots($uid) {
	$vboxExec = shell_exec("VBoxManage snapshot ".$uid." list --machinereadable 2>&1");
	$all_lines = explode("\n", $vboxExec);
	$snapshots = array();

	$curr = 0;
	foreach ($all_lines as $key => $line) {
		$cols = explode("=", $line);
		if(strstr($cols[0], "SnapshotName")){
			$snapshots[$curr]["name"] = str_replace("\"", "", $cols[1])." [".str_replace("SnapshotName", "", $cols[0])."]"; 
		}
		if(strstr($cols[0], "SnapshotUUID")){
			$snapshots[$curr-1]["id"] = str_replace("\"", "", $cols[1]);
		}

		$curr++;
	}

	return $snapshots;
}

function newSnapshot($uid, $name) {
	$vboxExec = shell_exec("VBoxManage snapshot ".$uid." take ".$name);
	return $vboxExec;
}

function delSnapshot($uid,$snapuid) {
	return shell_exec("VBoxManage snapshot ".$uid. " delete ".$snapuid);
}
function powerVM($act, $uid) {
	if($act == "on") {
		$vboxExec = shell_exec("VBoxManage startvm ".$uid." --type headless");
		shell_exec("VBoxManage metrics setup");
		return $vboxExec;
	} 
	if($act == "off") {
		$vboxExec = shell_exec("VBoxManage controlvm ".$uid." poweroff");
		return $vboxExec;
	}
}

function addVM($vmName, $osType, $diskSize, $ramSize, $isoName) {
	$returnValue = "</br> <b>VM Log : </b>";
	$returnValue .= "</br> Create HD ".exec("VBoxManage createhd --filename ".getcwd()."/vdi/".$vmName.".vdi --size ".$diskSize." 2>&1");
	$returnValue .= "</br> Create VM ".exec("VBoxManage createvm --name ".$vmName." --ostype ".$osType." --register 2>&1");
	$returnValue .= "</br> Storage CTL (SATA) ".exec("VBoxManage storagectl ".$vmName." --name 'SATA Controller' --add sata --controller IntelAHCI 2>&1");
	$returnValue .= "</br> Storage Attach (SATA) ".exec("VBoxManage storageattach ".$vmName." --storagectl 'SATA Controller' --port 0 --device 0 --type hdd --medium ".getcwd()."/vdi/".$vmName.".vdi 2>&1");
	$returnValue .= "</br> Storage CTL (IDE) ".exec("VBoxManage storagectl ".$vmName." --name 'IDE Controller' --add ide 2>&1");
	$returnValue .= "</br> Storage Attach (IDE)".exec("VBoxManage storageattach ".$vmName." --storagectl 'IDE Controller' --port 0 --device 0 --type dvddrive --medium ".getcwd()."/".$isoName." 2>&1");
	$returnValue .= "</br> Modify VM (ioapic)".exec("VBoxManage modifyvm ".$vmName." --ioapic on 2>&1");
	$returnValue .= "</br> Modify VM (boot)".exec("VBoxManage modifyvm ".$vmName." --boot1 dvd --boot2 disk --boot3 none --boot4 none 2>&1");
	$returnValue .= "</br> Modify VM (memory)".exec("VBoxManage modifyvm ".$vmName." --memory ".$ramSize." --vram 128 2>&1");
	$returnValue .= "</br> Modify VM (ethernet)".exec("VBoxManage modifyvm ".$vmName." --nic1 bridged --bridgeadapter1 eth0 2>&1");

return $returnValue;
}

function getAllInfos($uid) {
	$allinfos = array();

	$allinfos["CPU"] = getMetric($uid,"CPU/Load/User");
	$allinfos["RAM_TOTAL"] = getInfo("memory",$uid);
	$allinfos["RAM_USED"] = round(getMetric($uid,"RAM/Usage/Used")/1024);
	$allinfos["RAM_LOAD"] = round(($allinfos["RAM_USED"]/($allinfos["RAM_TOTAL"]+0.1)*100))."%";

	return $allinfos;
}

function getMetric($uid,$metric) {

	$output = shell_exec("VBoxManage metrics query ".$uid." ".$metric." | grep ".$metric." | awk '{print $3}' 2>&1");
	return $output;
}

function getOsTypes() {
	$output = shell_exec("VBoxManage list ostypes | grep ID: | awk '{print $2}' | grep -v ID 2>&1");
	return split("\n", $output);
}
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function listVMS() {
	$vboxExec = shell_exec("VBoxManage list vms");
	$VMSArray = explode("\n", $vboxExec);
	$VMClean = array();

	foreach ($VMSArray as $key => $value) {
		if(strlen($value) >= 5){
			$VMClean[]["NAME"] = get_string_between($value,"\"","\"");
			$VMClean[count($VMClean)-1]["UID"] = get_string_between($value,"{","}");
		}
	}
	return $VMClean;

}

?>