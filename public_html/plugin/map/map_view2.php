<?php
include NFE_PATH.'/plugin/map/load_map.js.php';
?>
<div id="map_div" style="width:100%;height:328px;"></div>

<script type="text/javascript">
// 주소-좌표 변환 객체를 생성합니다
var geocoder = new kakao.maps.services.Geocoder();

// 주소로 좌표를 검색합니다
geocoder.addressSearch('<?php echo $address_txt;?>', function(result, status) {
	// 정상적으로 검색이 완료됐으면 
	if(status === kakao.maps.services.Status.OK) {
		nf_map.map_start("map_div", {'lat':result[0].y, 'lng':result[0].x});
		nf_map.address_move_marker(result[0].y, result[0].x, {});
	}
});
</script>