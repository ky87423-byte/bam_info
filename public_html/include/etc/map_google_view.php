<?php
include "../../engine/_core.php";
$shop_row = $db->query_fetch("select * from nf_shop as ns where `no`=".intval($_GET['no']));
$address_arr = explode("||", $shop_row['wr_address']);
?>
<style type="text/css">
body { margin:0px; }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $env['map_google_key'];?>&callback=initMap&region=kr"></script>
<div class="map_view" id="map-view-" scrolling="no" frameborder="0" style="height: 563px;"></div>
<script type="text/javascript">
var mapContainer = document.getElementById("map-view-");
var mapCenter = new google.maps.LatLng(38.9041, -77.0171);
var map_option = {
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	maxZoom : 18,
	minZoom : 3,
	scrollwheel: true,
	zoom: 18,
	zoomControl: true,
	//draggableCursor:'crosshair',
	zoomControlOptions: {
		style:google.maps.MapTypeControlStyle.DROPDOWN_MENU,
		position: google.maps.ControlPosition.RIGHT_CENTER
	}
};
var map = new google.maps.Map(mapContainer, map_option);

var geocoder = new google.maps.Geocoder();
geocoder.geocode({'address':"<?php echo implode(" ", $address_arr);?>"},
	function(results, status){
		if(results!=""){
			var location=results[0].geometry.location;
			var iwPosition = new google.maps.LatLng(location.lat(), location.lng());
			var marker = new google.maps.Marker({
				// 지도 중심좌표에 마커를 생성합니다 
				position: iwPosition
			});
			marker.setMap(map);
			map.setCenter(iwPosition);
		} else {
			alert("위도와 경도를 찾을 수 없습니다.");
		}
	}
);
</script>