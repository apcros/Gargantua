<?php

function listIsos() {
	$all_files = scandir("isos/");
	$all_isos = array();

	foreach ($all_files as $key => $file) {
		if(substr($file, -3) == "iso") {
			$all_isos[] = $file;
		}
	}
	return $all_isos;
}

?>