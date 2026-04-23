<?php
switch($env['map_engine']) {
	case "daum":
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?php echo $env['map_daum_key'];?>&libraries=services,clusterer"></script>
<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/map/daum.class.js"></script>
<?php
	break;

	case "google":
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $env['map_google_key'];?>&callback=initMap"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/map/google.class.js"></script>
<?php
	break;
}
?>