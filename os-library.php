<?php
include_once("graphics.php");
include_once("isos-funcs.php");

HTMLheader();
HTMLnavbar(3);
HTMLcontainerOpen();
HTMLsnapshotsmodal();

HTMLisodownload();

HTMLisos(listIsos());

HTMLcontainerClose();
HTMLfooter();

?>