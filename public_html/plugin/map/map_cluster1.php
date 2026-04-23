<!-- <div id="map_div" style="width:100%;height:<?php //echo $map_height;?>px;z-index:1;"></div> -->

<?php
switch($env['map_engine']) {
	case "daum":
?>

<?php
	break;

	case "google":
?>

<?php
	break;
}
?>