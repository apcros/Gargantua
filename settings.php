<?php
include_once("graphics.php");
include_once("vbox-funcs.php");

HTMLheader();
HTMLnavbar(2);
HTMLcontainerOpen();

echo "<h3> Host information : </h3>";
echo getHostInfo();

echo "<div class='divider'></div>";

HTMLcontainerClose();
HTMLfooter();

?>