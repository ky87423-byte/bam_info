<?php
$PATH = $_SERVER['DOCUMENT_ROOT'];
include_once $PATH."/engine/_core.php";
$body_bg = '#fff;';
include NFE_PATH.'/include/header_meta.php';
$iframe_name = explode("[", $_GET['iframe_name']);
$iframe_k = substr($iframe_name[1],0,1);
$lat = $_GET['lat'];
$lng = $_GET['lng'];
if(!$lat) $lat = 37.5666805;
if(!$lng) $lng = 126.9784147;

include NFE_PATH.'/plugin/map/load_map.js.php';
?>
<div id="map-view" style="width:100%;height:300px;"></div>
<div class="_map_input">
	<input type="hidden" name="map_int[]" value="<?php echo $lat;?>" />
	<input type="hidden" name="map_int[]" value="<?php echo $lng;?>" />
</div>
<script type="text/javascript">
nf_map.map_start("map-view", {'lat':"<?php echo $lat;?>", 'lng':"<?php echo $lng;?>"});
nf_map.get_location('parent.nf_job.iframe_click_marker(re, <?php echo $iframe_k;?>)');
nf_map.address_move_marker("<?php echo $lat;?>", "<?php echo $lng;?>", {});
</script>