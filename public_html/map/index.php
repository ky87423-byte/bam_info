<?php
$_SERVER['__USE_API__'] = array('map');
$PATH = $_SERVER['DOCUMENT_ROOT'];
include_once $PATH."/engine/_core.php";

$m_title = '지도검색';

// : 다른데도 이렇게 해서 여기는 관리자글을 앞으로 하기로 했음.
$_ch_site_title_ = $env['site_title'].' '.$m_title;
$_ch_site_content_ = $env['meta_description'].' '.$m_title;

include '../include/header_meta.php';
include '../include/header.php';

$m_title = '지도검색';
include NFE_PATH.'/include/m_title.inc.php';
include NFE_PATH.'/plugin/map/load_map.js.php';
?>
<style type="text/css">
.map_box- { display:none; }
.map_box-.on { display:block; }
.gm-style-iw-t .gm-style-iw.gm-style-iw-c { margin-top:-30px; }
.gm-style-iw-t .gm-style-iw-tc { margin-top:-30px; }
</style>
<script type="text/javascript">
var close_filter = function(el) {
	var display = $(".filter_wrap").css("display");
	display = display=='none' ? 'block' : 'none';
	if(display=='none') $(el).addClass("close-");
	else $(el).removeClass("close-");
	$(".filter_wrap").css({"display":display});
}
</script>
<!-- 지도검색 -->
<section class="map">
	<?php
	include NFE_PATH.'/include/shop/location_txt.inc.php';
	?>
	<div class="map_wrap">
		<div class="map_area0">
			<!--필터-->
			<?php include NFE_PATH.'/include/filter.php'; ?>
			<div class="map_list">
				<!--button 클릭시 .filter_wrap 열고 닫기-->
				<p>총 <b class="cnt-shop-total-">0</b>건의 검색결과 <button type="button" onClick="click_filter('block')"><i class="axi axi-ion-android-mixer"></i>상세검색</button></p>
				<ul class="shop_list" id="map-shop-paste-">
					<?php
					/*
					<li>
						<p class="img"><img src="../images/test_img.png" alt="업체이미지"></p>
						<div class="shop_info">
							<p class="shop_name line1">선릉역 아로마테라피선릉역 아로마테라피</p>
							<ul class="ev">
								<li class="star"><i class="axi axi-star3"></i>5</li>
								<li class="heart"><i class="axi axi-heart2"></i>55</li>
								<li class="location"><i class="axi axi-location-on"></i>451km</li>
							</ul>
							<ul class="product">
								<li class="sale"><span>20</span>%</li>
								<li class="price"><span>70,000</span>원</li>
								<li class="d_price">100,000원</li>
							</ul>
						</div>
					</li>

					<li>
						<p class="img"><img src="../images/test_img.png" alt="업체이미지"></p>
						<div class="shop_info">
							<p class="shop_name line1">선릉역 아로마테라피선릉역 아로마테라피</p>
							<ul class="ev">
								<li class="star"><i class="axi axi-star3"></i>5</li>
								<li class="heart"><i class="axi axi-heart2"></i>55</li>
								<li class="location"><i class="axi axi-location-on"></i>451km</li>
							</ul>
							<ul class="product">
								<li class="sale"><span>20</span>%</li>
								<li class="price"><span>70,000</span>원</li>
								<li class="d_price">100,000원</li>
							</ul>
						</div>
					</li>
					<li>
						<p class="img"><img src="../images/test_img.png" alt="업체이미지"></p>
						<div class="shop_info">
							<p class="shop_name line1">선릉역 아로마테라피선릉역 아로마테라피</p>
							<ul class="ev">
								<li class="star"><i class="axi axi-star3"></i>5</li>
								<li class="heart"><i class="axi axi-heart2"></i>55</li>
								<li class="location"><i class="axi axi-location-on"></i>451km</li>
							</ul>
							<ul class="product">
								<li class="sale"><span>20</span>%</li>
								<li class="price"><span>70,000</span>원</li>
								<li class="d_price">100,000원</li>
							</ul>
						</div>
					</li>
					*/?>
				</ul>
				<!--페이징-->
				<div class="map-shop-paging-"></div>
			</div>
			
			<button type="button" class="toggle_btn" onClick="close_filter(this)"><img src="../images/ic/side_right.png" alt="필터 여닫기 버튼"></button>
		</div>
		<div class="map_area">
			<?php
			$map_height = 900;
				include NFE_PATH.'/plugin/map/load_map.js.php';
			?>
			<div id="map_div" style="width:100%;height:<?php echo $map_height;?>px;z-index:1;"></div>
			<?php
			if($env['map_engine']=='google') {
			?>
			<script type="text/javascript">
			var map;
			var infoWindows = {};

			async function initMap() {
			  // Request needed libraries.
			  const { Map, InfoWindow } = await google.maps.importLibrary("maps");
			  const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
				"marker",
			  );
			  map = new google.maps.Map(document.getElementById("map_div"), {
				zoom: 4,
				center: { lat: 37, lng: 127 }, // : 중심좌표
				mapId: "DEMO_MAP_ID",
			  });
			  const infoWindow = new google.maps.InfoWindow({
				content: "",
				disableAutoPan: true,
			  });
			  // Create an array of alphabetical characters used to label the markers.
			  const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			  // Add some markers to the map.
			}


			var info_close = function(lat, lng) {
				infoWindows[lat+'_'+lng].close();
				return false;
			}

			var info_window = function(location) {
				var lat = location.lat;
				var lng = location.lng;
				var _pos = new google.maps.LatLng(location.lat, location.lng);

				for(x in infoWindows) {
					infoWindows[x].close();
				}
				//if (map.infoWindow[lat+'_'+lng]) map.infoWindow[lat+'_'+lng].close();
				/*infoWindows[lat+'_'+lng] = new google.maps.InfoWindow({
					content:'<div class="gm-style-iw-content"><a href="/sub/product_view.php?no='+location.no+'"><div><img src="/data/product/'+location.photo+'" /></div><div class="con--"><img class="close--" src="/images/icon/icon_close.png" onClick="return info_close(\''+location.lat+'\', \''+location.lng+'\')" /><div class="name--">'+location.name+'</div><div class="addr--">'+location.addr+'</div></div></a></div>',
					position:_pos,
					map:map
				});*/
				infoWindows[lat+'_'+lng] = new google.maps.InfoWindow({
					content:'<div class="map_box_wrap"><div><div class="customoverlay"><div class="img"><img src="/data/shop/202401/photo_1706675972.jpg" alt="업체이미지"><span class="shop_type">경락마사지</span><ul class="ev"><li class="star"><i class="axi axi-star3"></i>4.8</li><li class="heart"><i class="axi axi-heart2"></i>2</li><li class="location"><i class="axi axi-location-on"></i>13km</li></ul></div><div class="info_wrap"><p class="title">광주아뜰리에</p><p class="add">(066)광주광산구~</p><ul class="product"><li class="sale"><span>30<spam>%</li><li class="price"><span>40,000<spam>원</li><li class="d_price">60,000원</li></ul><button type="button" onClick="">상세페이지</button></div></div></div></div>',
					position:_pos,
					map:nf_map
				});
			}

			initMap();
			</script>
			<?php
			}
			
			include NFE_PATH.'/plugin/map/map_cluster1.php';
			?>
			<div class="map_search area-address- <?php echo $env['map_engine'];?>-input-" style="z-index:2;">
				<label>
					<input type="text" name="map_address" class="full_address-" onClick="sample2_execDaumPostcode(this)">
					<button type="button"><i class="axi axi-search3"></i></button>
				</label>
			</div>
			<script type="text/javascript">
			var map_load = function(page) {
				var href = location.href.split("?");
				page = page ? page : 1;
				nf_map.set_marker1(root+"/include/regist.php?mode=get_map_shop&page="+page, href[1], "");
			}
			</script>
			<?php
			$address_move_latlng_nor_marker = true;
			include NFE_PATH.'/include/post.daum.php';
			?>
		</div>
	</div>
</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>

