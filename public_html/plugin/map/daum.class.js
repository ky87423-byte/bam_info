/*
// 지도에 마커를 표시합니다
marker.setMap(nf_map.map);
*/
var func = '';
var nf_map = function() {

	this.zoom_use = false;
	this.map_width = 0;
	this.view_loadview = false;

	//basic_location : header_meta.php에서 실행합니다.
	this.basic_road = {'pan':0, 'tilt':0, 'zoom':0}; // : 로드뷰 기본값

	this.map;
	this.mapCenter;
	this.marker = {};
	this.customoverlay = new Array();
	this.geocoder;
	this.clusterer;

	this.this_latlng = {};

	this.rvResetValue = {} //로드뷰의 초기화 값을 저장할 변수
	this.mapWalker = null;
	this.viewpoint_init = {'pan':0, 'tilt':0, 'zoom':0};

	// 지도 타입 정보를 가지고 있을 객체입니다
	// map.addOverlayMapTypeId 함수로 추가된 지도 타입은
	// 가장 나중에 추가된 지도 타입이 가장 앞에 표시됩니다
	// 이 예제에서는 지도 타입을 추가할 때 지적편집도, 지형정보, 교통정보, 자전거도로 정보 순으로 추가하므로
	// 자전거 도로 정보가 가장 앞에 표시됩니다
	this.mapTypes = {
		terrain : kakao.maps.MapTypeId.TERRAIN,
		traffic :  kakao.maps.MapTypeId.TRAFFIC,
		bicycle : kakao.maps.MapTypeId.BICYCLE,
		useDistrict : kakao.maps.MapTypeId.USE_DISTRICT
	};

// : 지도불러오기
	this.map_start = function(id, arr) {
		if(!arr) arr = basic_location;
		if(!arr['lat']) arr['lat'] = basic_location['lat'];
		if(!arr['lng']) arr['lng'] = basic_location['lng'];
		this.mapContainer = document.getElementById(id); // 지도를 표시할 div 
		this.map_width = $(this.mapContainer).width();
		nf_map.mapCenter = new kakao.maps.LatLng(arr['lat'], arr['lng']), // 지도의 중심 좌표

		mapOption = { 
			center: nf_map.mapCenter, // 지도의 중심좌표
			keyboardShortcuts: true,
			scrollwheel:true,
			draggable:true,
			level: arr['zoom'] // 지도의 확대 레벨
		};

		// 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
		this.map = new kakao.maps.Map(this.mapContainer, mapOption);
		//this.map.addOverlayMapTypeId(kakao.maps.MapTypeId.TRAFFIC); // : 교통정보
		//this.map.addOverlayMapTypeId(kakao.maps.MapTypeId.ROADVIEW); // : 로드뷰 도로
		//this.map.addOverlayMapTypeId(kakao.maps.MapTypeId.TERRAIN); // : 지형정보

		// 지도 확대 축소를 제어할 수 있는  줌 컨트롤을 생성합니다
		/*
		if(nf_map.zoom_use) {
			var zoomControl = new kakao.maps.ZoomControl();
			this.map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);
		}
		*/

		this.geocoder = new kakao.maps.services.Geocoder();
	}

// : 로드뷰 불러오기
	this.load_start = function(id, arr) {
		var re = '';
		var _arr = {};
		_arr['pan'] = arr['pan'] ? arr['pan'] : nf_map.basic_road['pan'];
		_arr['tilt'] = arr['tilt'] ? arr['tilt'] : nf_map.basic_road['tilt'];
		_arr['zoom'] = arr['zoom'] ? arr['zoom'] : nf_map.basic_road['zoom'];

		try{
			nf_map.rvContainer = document.getElementById(id); // 로드뷰를 표시할 div
			nf_map.rv = new kakao.maps.Roadview(nf_map.rvContainer); // 로드뷰 객체 생성
			nf_map.rc = new kakao.maps.RoadviewClient(); // 좌표를 통한 로드뷰의 panoid를 추출하기 위한 로드뷰 help객체 생성
			nf_map.viewpoint_changed();

			nf_map.rc.getNearestPanoId(nf_map.mapCenter, 50, function(panoId) {
				nf_map.rv.setPanoId(panoId, nf_map.mapCenter);//좌표에 근접한 panoId를 통해 로드뷰를 실행합니다.
				if(_arr.pan>0) {
					nf_map.rv.setViewpoint(_arr);
					nf_map.view_loadview = true;
				}
				if(panoId) nf_map.view_loadview = true;
				//nf_map.rvResetValue.panoId = panoId;
			});
			
		}catch(e){
			if($(".not_loadview_msg")[0]) $(".not_loadview_msg").css({"display":"block"});
			nf_map.view_loadview = false;
		}
		return nf_map.view_loadview;
	}

/*############################################
basic 기능
############################################*/
// : 지도에 마커표시
	this.markerObj= function(key, json) {
		nf_map.marker[key] = new kakao.maps.Marker({ 
			// 지도 중심좌표에 마커를 생성합니다 
			position: nf_map.map.getCenter()
		});
		nf_map.marker[key].setMap(nf_map.map);
		if(json && json.loadview && nf_map.rv) nf_map.rv.setViewpoint(nf_map.viewpoint_init);
	}



/*############################################
함수모음
############################################*/
	this.get_latlng = function() {
		var center = nf_map.map.getCenter();
		lat = center.getLat();
		lng = center.getLng();
		return {lat:lat, lng:lng};
	}

	this.get_zoom = function() {
		return nf_map.map.getLevel();
	}

	this.relayout = function() {
		nf_map.map.relayout();
	}

// : 지도등록시 사용
	this.marker_put = function(re, json) {
		var map_input = '';

		if(nf_map.marker[0]) nf_map.marker[0].setMap(null);
		nf_map.markerObj(0, json);
		nf_map.marker[0].setPosition(re['latlng']);
		if(nf_map.rv) nf_map.load_toggle(re['latlng']);
		nf_map.map.relayout();

		nf_map.this_latlng = {'lat':re['latlng'].getLat(), 'lng':re['latlng'].getLng()};

		if($("._map_input")[0]) {
			map_input += '<input type="hidden" name="map_int[]" value="'+re['latlng'].getLat()+'" />';
			map_input += '<input type="hidden" name="map_int[]" value="'+re['latlng'].getLng()+'" />';
			$("._map_input").html(map_input);
		}

		if(json.iframe_func) {
			eval(json.iframe_func);
		}
	}

	this.customoverlay_delete = function() {
		var len = nf_map.customoverlay.length;
		for(var i=0; i<len; i++) {
			nf_map.customoverlay[i].setMap(null);
		}
	}

// : 주소로 지도이동후 마커찍기
	this.address_move = function(address, json) {
		if(!nf_map.geocoder) nf_map.geocoder = new kakao.maps.services.Geocoder();
		nf_map.geocoder.addressSearch(address, function(result, status) {
			// 정상적으로 검색이 완료됐으면 
			if(status === kakao.maps.services.Status.OK) {
				var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
				nf_map.map.setCenter(coords);
				nf_map.map.setLevel(3);
				if(json && !json.not_marker) {
					nf_map.address_move_marker(result[0].y, result[0].x, json);
				}
				if(json && json.roadview===true) {
					nf_map.mapCenter = new kakao.maps.LatLng(result[0].y, result[0].x), // 지도의 중심 좌표
					nf_map.load_start('roadview', {});
				}
			}
		});
	};

// : 좌표로 주소값 가져오기
	this.get_latlng_address = function(lat, lng, json) {
		if(!nf_map.geocoder) nf_map.geocoder = new kakao.maps.services.Geocoder();
		nf_map.geocoder.coord2Address(lng, lat, function(result, status) {
			if (status === kakao.maps.services.Status.OK) {
				// : 도로명 우선
				var detailAddr = !!result[0].road_address ? result[0].road_address.address_name : result[0].address.address_name;
				eval(json);
				if(location.href.indexOf("/map/index.php")>=0) {
					nf_map.move(lat, lng);
					map_load();
				}
			}
		});
	};


	this.move = function(lat, lng) {
		var coords = new kakao.maps.LatLng(lat, lng);
		nf_map.map.setCenter(coords);
	}


	this.address_move_marker = function(lat, lng, json) {

		if(!lat) lat = basic_location['lat'];
		if(!lng) lng = basic_location['lng'];

		var coords = new kakao.maps.LatLng(lat, lng);
		nf_map.map.setCenter(coords);
		nf_map.map.setLevel(3);

		if(json && !json.not_marker) {
			setTimeout(function(){
				var _json = {'latLng':coords, 'lat':lat, 'lng':lng};
				var re = nf_map.get_json(_json, lat, lng);
				nf_map.marker_put(re, json);
			},100);
		}
		/*
		// 인포윈도우로 장소에 대한 설명을 표시합니다
		var infowindow = new kakao.maps.InfoWindow({
			content: '<div style="width:150px;text-align:center;padding:6px 0;">우리회사</div>'
		});
		
		infowindow.open(nf_map.map, marker);
		*/
	}




/*############################################
이벤트 함수모음
############################################*/
/*
지도 이벤트
*/
// : 이벤트 실행시 값 가져오기
	this.get_json = function(mouseEvent) {
		var result = {};
		
		// 지도 영역정보를 얻어옵니다 
		var bounds = nf_map.map.getBounds();

		// 영역정보의 남서쪽 정보를 얻어옵니다 
		var swLatlng = bounds.getSouthWest();
		// 영역정보의 북동쪽 정보를 얻어옵니다 
		var neLatlng = bounds.getNorthEast();

		// 마커의 위치를 지도중심으로 설정합니다 
		//marker.setPosition(map.getCenter()); 
		//marker.setMap(map);

		result['sw'] = swLatlng;
		result['ne'] = neLatlng;
		

		result['level'] = nf_map.map.getLevel();
		result['latlng'] = mouseEvent.latLng;

		if($("#roadview")[0] && nf_map.rv) {
			nf_map.rv.setViewpoint(nf_map.viewpoint_init);
		}

		return result;
	}


	this.get_load_json = function(position, panoId) {
		var road_input='';
		/*
		//각 뷰포인트 값을 초기화를 위해 저장해 놓습니다.
		nf_map.rvResetValue.pan = viewpoint.pan; //  : 좌우
		nf_map.rvResetValue.tilt = viewpoint.tilt; // : 높낮이
		nf_map.rvResetValue.zoom = viewpoint.zoom; // : 줌
		*/
		var result = {};

		var viewpoint = nf_map.rv.getViewpoint();

		// : 로드뷰 위치 가져오기
		result['vp_pan'] = viewpoint.pan;
		result['vp_tilt'] = viewpoint.tilt;
		result['vp_zoom'] = viewpoint.zoom;

		if($("._road_input")[0]) {
			road_input += '<input type="hidden" name="road_int[pan]" value="'+viewpoint.pan+'" />';
			road_input += '<input type="hidden" name="road_int[tilt]" value="'+viewpoint.tilt+'" />';
			road_input += '<input type="hidden" name="road_int[zoom]" value="'+viewpoint.zoom+'" />';

			$("._road_input").html(road_input);
		}

		$(window).load(function(){
			$("#roadview").find("div").eq(0).find("div").eq(0).find("div").eq(0).css({"z-index":"10"});
		});

		return result;
	}

	// 체크 박스를 선택하면 호출되는 함수입니다
	this.setOverlayMapTypeId = function(el) {

		var _k = $(el).attr("k");
		if(in_array(_k, ['map','skyview'])) $(el).closest("span").find("a").removeClass("on");

		var _on = $(el).attr("class").indexOf("on")>=0 ? true : false;
		if(_on===true) $(el).removeClass("on");
		else $(el).addClass("on");

		// 지적편집도정보 체크박스가 체크되어있으면 지도에 지적편집도정보 지도타입을 추가합니다
		if (_k=='map') {
			nf_map.map.setMapTypeId(kakao.maps.MapTypeId.ROADMAP);
		}

		// 지적편집도정보 체크박스가 체크되어있으면 지도에 지적편집도정보 지도타입을 추가합니다
		if (_k=='skyview') {
			nf_map.map.setMapTypeId(kakao.maps.MapTypeId.HYBRID);
		}

		// 지적편집도정보 체크박스가 체크되어있으면 지도에 지적편집도정보 지도타입을 추가합니다
		if (_k=='chkUseDistrict') {
			if(_on) nf_map.map.removeOverlayMapTypeId(nf_map.mapTypes.useDistrict);
			else nf_map.map.addOverlayMapTypeId(nf_map.mapTypes.useDistrict);
		}
		
		// 지형정보 체크박스가 체크되어있으면 지도에 지형정보 지도타입을 추가합니다
		if (_k=='chkTerrain') {
			if(_on) nf_map.map.removeOverlayMapTypeId(nf_map.mapTypes.terrain);
			else nf_map.map.addOverlayMapTypeId(nf_map.mapTypes.terrain);
		}
		
		// 교통정보 체크박스가 체크되어있으면 지도에 교통정보 지도타입을 추가합니다
		if (_k=='chkTraffic') {
			if(_on) nf_map.map.removeOverlayMapTypeId(nf_map.mapTypes.traffic);
			else nf_map.map.addOverlayMapTypeId(nf_map.mapTypes.traffic);
		}
		
		// 자전거도로정보 체크박스가 체크되어있으면 지도에 자전거도로정보 지도타입을 추가합니다
		if (_k=='chkBicycle') {
			if(_on) nf_map.map.removeOverlayMapTypeId(nf_map.mapTypes.bicycle);
			else nf_map.map.addOverlayMapTypeId(nf_map.mapTypes.bicycle);
		}
	}


// : 좌표클릭시 지도위치 가져오기
// : lat : 위도, lng : 경도
	this.get_location = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'click', function(mouseEvent) {
			var re = nf_map.get_json(mouseEvent);
			eval(func);
		});
	}


// : 지도 확대 축소시 지도정보 가져오기
	this.zoom_change = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'zoom_changed', function(mouseEvent) {
			eval(func);
		});
	}


// : 지도 중심좌표가 변경되면 지도 정보가 표출됩니다
	this.center_changed = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'center_changed', function(mouseEvent) {
			var re = nf_map.get_json(mouseEvent);
			eval(func);
		});
	}


// : 영역 변경 이벤트 등록하기
	this.bounds_changed = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'bounds_changed', function(mouseEvent) {
			var re = nf_map.get_json(mouseEvent);
			eval(func);
		});
	}


// : 타일로드 이벤트 등록하기
	this.tilesloaded = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'tilesloaded', function(mouseEvent) {
			var re = nf_map.get_json(mouseEvent);
			eval(func);
		});
	}

// : 드래그가 끝날 때 발생한다.
	this.dragend = function(func) {
		kakao.maps.event.addListener(nf_map.map, 'dragend', function(mouseEvent) {
			eval(func);
		});
	}

	// : arr [1,5]이면 1이 최소, 5가 최대
	this.zoom_zoomable = function(arr) {
		if(arr[1]>=nf_map.map.getLevel())
			nf_map.map.setZoomable(false);
		else
			nf_map.map.setZoomable(true);
	}


	this.info_window = function(con, lat, lng) {
		var iwContent = con; // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
		var iwPosition = new kakao.maps.LatLng(lat, lng); //인포윈도우 표시 위치입니다

		// 마커를 생성합니다
		var marker = new kakao.maps.Marker({
			position: iwPosition
		});

		// 인포윈도우를 생성합니다
		var infowindow = new kakao.maps.InfoWindow({
			position : iwPosition, 
			content : iwContent,
			removable : true
		});

		// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
		infowindow.open(nf_map.map, marker);

		$(function(){
			$(".customoverlay").parent().parent().removeClass("map_box_wrap");
			$(".customoverlay").parent().parent().addClass("map_box_wrap");
		});
	}

	// 인포윈도우를 표시하는 클로저를 만드는 함수입니다 
	this.makeOverListener = function(map, marker, infowindow) {
		return function() {
			infowindow.open(map, marker);
		};
	}

	// 인포윈도우를 닫는 클로저를 만드는 함수입니다 
	this.makeOutListener = function(infowindow) {
		return function() {
			infowindow.close();
		};
	}

	// : lat : 위도, lng : 경도
	this.cluster_click = function() {
		// 마커 클러스터러에 클릭이벤트를 등록합니다
		// 마커 클러스터러를 생성할 때 disableClickZoom을 true로 설정하지 않은 경우
		// 이벤트 헨들러로 cluster 객체가 넘어오지 않을 수도 있습니다
		var _arr = {};
		kakao.maps.event.addListener(nf_map.clusterer, 'clusterclick', function(cluster) {
			alert(cluster.getBounds());
		});
	}


	this.cluster_made = function() {
		nf_map.clusterer = new kakao.maps.MarkerClusterer({
			map: nf_map.map, // 마커들을 클러스터로 관리하고 표시할 지도 객체 
			averageCenter: true, // 클러스터에 포함된 마커들의 평균 위치를 클러스터 마커 위치로 설정 
			minLevel: 1, // 클러스터 할 최소 지도 레벨 
		});

		if(func) {
			setTimeout(function(){
				eval(func);
			},100);
		}
	}

	// 데이터를 가져오기 위해 jQuery를 사용합니다
	// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
	this.set_cluster = function(url, page) {
		var center = nf_map.map.getCenter();
		lat = center.getLat();
		lng = center.getLng();

		var width = this.map_width;
		var page = page ? page : '';

		$.get(url+'&width='+width+'&lat='+lat+'&lng='+lng+'&zoom='+this.get_zoom()+"&page="+page, function(data) {
			data = $.parseJSON(data);
			// 데이터에서 좌표 값을 가지고 마커를 표시합니다
			// 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다
			if(!page) {
				var markers = $(data.positions).map(function(i, position) {
					return new kakao.maps.Marker({
						position : new kakao.maps.LatLng(position.lat, position.lng)
					});
				});

				// 클러스터러에 마커들을 추가합니다
				nf_map.clusterer.clear();
				nf_map.clusterer.addMarkers(markers);
			}

			$(".map_list").html(data.tag);
			//nf_map.cluster_click();
		});

		return true;
	}



	// 데이터를 가져오기 위해 jQuery를 사용합니다
	// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
	this.set_marker1 = function(url, para, func) {
		para = para ? para : '';
		var center = nf_map.map.getCenter();
		lat = center.getLat();
		lng = center.getLng();

		var width = $(this.mapContainer).width();
		var height = $(this.mapContainer).height();
		var push_size = width>height ? width : height;

		nf_map.customoverlay_delete();

		var url_value = url+'&width='+push_size+'&lat='+lat+'&lng='+lng+'&zoom='+this.get_zoom()+"&"+para;

		$.get(url_value, function(data) {
			data = $.parseJSON(data);
			// 데이터에서 좌표 값을 가지고 마커를 표시합니다
			// 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다
			nf_map.clusterer.clear();

			if(data.detail_view=='Y') {
				var markers = $(data.positions).map(function(i, position) {
					var marker = new daum.maps.Marker({
						position : new daum.maps.LatLng(position.lat, position.lng),
						clickable: true
					});
					kakao.maps.event.addListener(marker, 'click', function(){
						nf_map.info_window(position.map_content, position.lat, position.lng);
					});
					return marker;
				});
				// 클러스터러에 마커들을 추가합니다
				nf_map.clusterer.addMarkers(markers);
			} else {
				var markers = $(data.positions).map(function(i, position) {
					var latlng = new daum.maps.LatLng(position.lat, position.lng);

					var _txt = position.title;
					if(position.count) _txt += '('+position.count+')';

					nf_map.customoverlay[i] = new daum.maps.CustomOverlay({
						position: latlng,
						map:nf_map.map,
						clickable: true,
						xAnchor: 0.5,
						yAnchor: 1,
						zIndex: 3,
						content: '<div style="background-color:#000;color:#fff;padding:5px;border-radius:5px;">'+_txt+'</div>'
					});
					nf_map.customoverlay[i].setMap(nf_map.map);
				});
			}

			if(data.js) eval(data.js);
			if(func) eval(func);
		});

		return true;
	}


	this.ajax_clusterer = function(data) {
		// 데이터에서 좌표 값을 가지고 마커를 표시합니다
		// 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다
		nf_map.customoverlay_delete();

		var markers = $(data.positions).map(function(i, position) {
			var latlng = new kakao.maps.LatLng(position.lat, position.lng);
			var marker = new kakao.maps.Marker({
				position : latlng
			});

			nf_map.customoverlay[i] = new kakao.maps.CustomOverlay({
				position: latlng,
				map:nf_map.map,
				clickable: true,
				xAnchor: 0.5,
				yAnchor: 1,
				zIndex: 3,
				content: position.content
			});
			nf_map.customoverlay[i].setMap(nf_map.map);

			return marker;
		});

		// 클러스터러에 마커들을 추가합니다
		clusterer.clear();
		clusterer.addMarkers(markers);
	}


	this.close = function(el) {
		$(el).closest(".map_box-").removeClass("on");
	}


/*
로드뷰 이벤트
*/
	this.load_init = function(func, position) {
		// 로드뷰 초기화 이벤트
		if(!nf_map.rv) return false;
		kakao.maps.event.addListener(nf_map.rv, 'init', function(panoId) {
			nf_map.get_load_json(position, panoId);
			eval(func);
		});
	}


	this.viewpoint_changed = function(func, position) {
		if(!nf_map.rv) return false;
		kakao.maps.event.addListener(nf_map.rv, 'viewpoint_changed', function(panoId){
			nf_map.get_load_json(position, panoId);
			eval(func);
		});
	}


	this.load_toggle = function(position) {
		nf_map.rc.getNearestPanoId(position, 50, function(panoId) {
			nf_map.rv.setPanoId(panoId, position);
		});
	}

	// 지도 확대, 축소 컨트롤에서 확대 버튼을 누르면 호출되어 지도를 확대하는 함수입니다
	this.zoom_control = function(type) {
		if(type=='-') nf_map.map.setLevel(nf_map.map.getLevel() + 1);
		else nf_map.map.setLevel(nf_map.map.getLevel() - 1);
	}






	// : 지도 이동 이벤트 핸들러
	this.map_big = function(el, txt) {
		var center = nf_map.map.getCenter();
		lat = center.getLat();
		lng = center.getLng();

		el.href = 'http://map.kakao.net/link/map/' + encodeURIComponent(txt) + ',' + lat + ',' + lng; //Daum 지도로 보내는 링크
	}

	// : 로드뷰 이동 이벤트 핸들러
	this.load_big = function(el) {
		var panoId = nf_map.rv.getPanoId(); //현 로드뷰의 panoId값을 가져옵니다.
		var viewpoint = nf_map.rv.getViewpoint(); //현 로드뷰의 viewpoint(pan,tilt,zoom)값을 가져옵니다.
		el.href = 'http://map.kakao.net/?panoid='+panoId+'&pan='+viewpoint.pan+'&tilt='+viewpoint.tilt+'&zoom='+viewpoint.zoom; //Daum 지도 로드뷰로 보내는 링크
	}
}

var nf_map = new nf_map();