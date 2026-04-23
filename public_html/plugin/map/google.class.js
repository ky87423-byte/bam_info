var nf_map = function() {

	this.marker_position = '';
	this.basic_int = {}; // : 위도,경도,줌 기본값.
	this.basic_road = {}; // : 로드뷰 기본값
	this.rand_int = Math.floor(Math.random() * 100) + 1;
	this.view_loadview = false;

	this.map;
	this.mapCenter;
	this.marker = {};
	this.customoverlay = new Array();
	this.infoWindow = {};
	this.geocoder;
	this.markerCluster = null;
	this.cluster_event = null;

	this.rvResetValue = {} //로드뷰의 초기화 값을 저장할 변수
	this.mapWalker = null;
	this.basic_int = {'lat':37.5666805, 'lng':126.9784147, 'zoom':3}; // : 위도,경도,줌 기본값.
	this.viewpoint_init = {'pan':-1, 'tilt':0, 'zoom':0};

	this.map_start = function(id, arr) {
		if(!arr) arr = this.basic_int;
		if(!arr['lat']) arr['lat'] = this.basic_int['lat'];
		if(!arr['lng']) arr['lng'] = this.basic_int['lng'];
		var zoom = arr['zoom']>0 ? arr['zoom'] : 17;

		this.mapContainer = document.getElementById(id)
		this.map_width = $(this.mapContainer).width();

		nf_map.mapCenter = new google.maps.LatLng(arr['lat'],arr['lng']);
		var map_option = {
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: nf_map.mapCenter,
			maxZoom : 18, //최대 줌
			minZoom : 3, //최소 줌
			scrollwheel: true,
			zoom: 10, // : 이상하게 변수로 하면 오류남
			zoomControl: true,
			//draggableCursor:'crosshair',
			zoomControlOptions: {
				style:google.maps.MapTypeControlStyle.DROPDOWN_MENU,
				position: google.maps.ControlPosition.RIGHT_CENTER
			}
		};

		if(nf_map.minzoom) map_option.minZoom = nf_map.minzoom;
		if(nf_map.maxzoom) map_option.maxZoom = nf_map.maxzoom;
		nf_map.map = new google.maps.Map(this.mapContainer, map_option);

		this.geocoder = new google.maps.Geocoder();
	}


// : 로드뷰 불러오기
	this.load_start = function(id, arr, position) {
		if(!position) position = nf_map.mapCenter;
		arr['pan'] = arr['pan'] ? parseFloat(arr['pan']) : parseFloat(nf_map.basic_road['pan']);
		arr['tilt'] = arr['tilt'] ? parseFloat(arr['tilt']) : parseFloat(nf_map.basic_road['tilt']);
		arr['zoom'] = arr['zoom'] ? parseFloat(arr['zoom']) : parseFloat(nf_map.basic_road['zoom']);

		var streetviewService = new google.maps.StreetViewService;
		streetviewService.getPanorama(
			{location: {lat: position.lat(), lng: position.lng()}},
			function(result, status) {
				if (status === google.maps.StreetViewStatus.OK) {
					var outsideGoogle = result;
					nf_map.load_obj(id, arr, position);
					nf_map.view_loadview = true;
				}
			}
		);
	}

	this.load_obj = function(id, arr, position) {
		nf_map.rv = new google.maps.StreetViewPanorama(
			document.getElementById(id), {
				position: position,
				pov: {
					heading: arr['pan'],
					pitch: arr['tilt']
				},
				panoProvider: function(pano) {
				},
				visible: true
			}
		);
		nf_map.map.setStreetView(nf_map.rv);
		nf_map.viewpoint_changed();
	}



/*############################################
basic 기능
############################################*/
// : 지도에 마커표시
	this.markerObj= function(key, json) {
		nf_map.marker[key] = new google.maps.Marker({
			position: nf_map.mapCenter,
			map: nf_map.map,
			title: 'Hello World!'
		  });
		// To add the marker to the map, call setMap();
		nf_map.marker[key].setMap(nf_map.map);
		if(json && json.loadview && nf_map.rv) {
			setTimeout(function(){
			nf_map.rv.setStreetView(nf_map.rv);
			},200);
		}
	}


/*############################################
함수모음
############################################*/
	this.get_latlng = function() {
		var center = nf_map.map.getCenter();
		return center.toJSON();
	}

	this.get_zoom = function() {
		return nf_map.map.zoom;
	}

	this.relayout = function() {
		//nf_map.map.relayout();
	}

// : 지도등록시 사용
	this.marker_put = function(re, json) {
		var map_input = '';

		if(nf_map.marker[0]) nf_map.marker[0].setMap(null);
		nf_map.markerObj(0, json);
		nf_map.marker[0].setPosition(re['latlng']);
		if(nf_map.rv) nf_map.load_toggle(re['latlng']);

		if($("._map_input")[0]) {
			map_input += '<input type="hidden" name="map_int[]" value="'+re['latlng'].lat()+'" />';
			map_input += '<input type="hidden" name="map_int[]" value="'+re['latlng'].lng()+'" />';
			$("._map_input").html(map_input);
		}
	}

	// : 주소로 지도이동후 마커찍기
	this.address_move = function(address, json) {
		nf_map.geocoder.geocode({'address':address},
			function(results, status){
				if(results!=""){
					var location=results[0].geometry.location;
					nf_map.address_move_marker(location.lat(), location.lng(), json);
				}
				else alert("위도와 경도를 찾을 수 없습니다.");
			}
		)

	};

	this.address_move_marker = function(lat, lng, json) {
		var coords = new google.maps.LatLng(lat, lng);
		var _json = {'latLng':coords, 'lat':lat, 'lng':lng};
		var re = nf_map.get_json(_json, lat, lng);
		nf_map.marker_put(re, json);
		nf_map.map.setZoom(15);
		nf_map.map.setCenter(coords);
	}
	
	// : 좌표로 주소값 가져오기
	this.get_latlng_address = function(lat, lng, json) {
		var geocoder = new google.maps.Geocoder;
		var latlng = {lat: lat, lng: lng};
		var aa = '';
		geocoder.geocode({'location': latlng}, function(results, status) {
			if(status==google.maps.GeocoderStatus.OK) {
				var agent = window.navigator.userAgent.toLowerCase();
				var detailAddr = results[1].formatted_address.replace(/대한민국/gi, '');
				if(agent.indexOf("edg")>=0) detailAddr = detailAddr.substr(7); // : 엣지
				eval(json);
				if(location.href.indexOf("/map/index.php")>=0) {
					nf_map.move(lat, lng);
					map_load();
				}
			}
		});
	};

	this.move = function(lat, lng) {
		var coords = new google.maps.LatLng(lat, lng);
		nf_map.map.setCenter(coords);
	}




/*############################################
이벤트 함수모음
############################################*/
/*
지도 이벤트
*/
// : 이벤트 실행시 값 가져오기
	this.get_json = function(event, lat, lng) {
		var result = {};
		if(lat && lng) {
			result['latlng'] = new google.maps.LatLng(lat, lng);
		} else {
			result['latlng'] = event.latLng;
		}

		if($("#roadview")[0]) {
			nf_map.load_start('roadview', nf_map.viewpoint_init, result['latlng']);
			nf_map.input_loadview_put(nf_map.viewpoint_init);
		}
		return result;
	}


	this.get_load_json = function() {
		var road_input='';
		/*
		//각 뷰포인트 값을 초기화를 위해 저장해 놓습니다.
		nf_map.rvResetValue.pan = viewpoint.pan; //  : 좌우
		nf_map.rvResetValue.tilt = viewpoint.tilt; // : 높낮이
		nf_map.rvResetValue.zoom = viewpoint.zoom; // : 줌
		*/
		var result = {};
		result['vp'] = {};

		var pano = nf_map.rv;

		// : 로드뷰 위치 가져오기
		result['vp']['pan'] = pano.getPov().heading;
		result['vp']['tilt'] = pano.getPov().pitch;
		result['vp']['zoom'] = pano.getPov().zoom;
		result['vp']['pano'] = pano.getPano();

		nf_map.input_loadview_put(result['vp']);

		return result;
	}

	this.input_loadview_put = function(arr) {
		var road_input='';
		if($("._map_input")[0]) {
			road_input += '<input type="hidden" name="road_int[pan]" value="'+arr['pan']+'" />';
			road_input += '<input type="hidden" name="road_int[tilt]" value="'+arr['tilt']+'" />';
			road_input += '<input type="hidden" name="road_int[zoom]" value="'+arr['zoom']+'" />';
			road_input += '<input type="hidden" name="road_int[pano]" value="'+arr['pano']+'" />';

			$("._road_input").html(road_input);
		}
	}


// : 좌표클릭시 지도위치 가져오기
// : lat : 위도, lng : 경도
	this.get_location = function(func) {
		google.maps.event.addListener(nf_map.map, 'click', function(event) {
			var re = nf_map.get_json(event);
			if(func) eval(func);
		});
	}


// : 지도 확대 축소시 지도정보 가져오기
	this.zoom_change = function(func) {
		google.maps.event.addListener(nf_map.map, 'zoom_changed', function(mouseEvent) {
			eval(func);
		});
	}
	
	
	// : 드래그가 끝날 때 발생한다.
	this.dragend = function(func) {
		google.maps.event.addListener(nf_map.map, 'dragend', function(mouseEvent) {
			eval(func);
		});
	}


// : 지도 중심좌표가 변경되면 지도 정보가 표출됩니다
	this.center_changed = function(func) {
		nf_map.addListener('center_changed',function() {
			window.setTimeout(function() {
				if(func) eval(func);
			},3000);
		});
	}

	this.idle = function() {
		nf_map.addListener('idle',function() {
			window.setTimeout(function() {
				if(func) eval(func);
			},3000);
		});
	}


// : 영역 변경 이벤트 등록하기
	this.bounds_changed = function(func) {
		nf_map.addListener('bounds_changed', (function () {
			var timer;
			return function() {
				clearTimeout(timer);
				timer = setTimeout(function() {
					// here goes an ajax call
					if(func) eval(func);
				}, 3000);
			}
		}));
	}


// : 타일로드 이벤트 등록하기
	this.tilesloaded = function(func) {
	}


	this.info_window = function(con, lat, lng) {
		var _pos = new google.maps.LatLng(lat, lng);

		for(x in nf_map.infoWindow) {
			nf_map.infoWindow[x].close();
		}
		//if (nf_map.infoWindow[lat+'_'+lng]) nf_map.infoWindow[lat+'_'+lng].close();
		nf_map.infoWindow[lat+'_'+lng] = new google.maps.InfoWindow();
		nf_map.infoWindow[lat+'_'+lng].setContent(con);
		nf_map.infoWindow[lat+'_'+lng].setPosition(_pos);
		nf_map.infoWindow[lat+'_'+lng].open(nf_map.map);
	}

	// : lat : 위도, lng : 경도
	this.cluster_click = function(func) {
		// 마커 클러스터러에 클릭이벤트를 등록합니다
		var _arr = {};
		if(func) {
			setTimeout(function(){
				eval(func);
			},100);
		}
		google.maps.event.addListener(nf_map.markerCluster, 'clusterclick', function(cluster) {
			nf_map.cluster_event = cluster;
			//util.obj_table(cluster);
		});
	}


	this.cluster_made = function() {
	}

	// 데이터를 가져오기 위해 jQuery를 사용합니다
	// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
	this.set_cluster = function(url, page) {

		var center = nf_map.map.getCenter();
		lat = center.lat();
		lng = center.lng();

		var page = page ? page : '';
		var width = $(this.mapContainer).width();

		if(nf_map.markerCluster) nf_map.markerCluster.clearMarkers();
		$.get(url+'&width='+width+'&lat='+lat+'&lng='+lng+'&zoom='+this.get_zoom()+"&page="+page, function(data) {
			data = $.parseJSON(data);

			if(!page) {
				// Add some markers to the map.
				// Note: The code uses the JavaScript Array.prototype.map() method to
				// create an array of markers based on a given "locations" array.
				// The map() method here has nothing to do with the Google Maps API.
				var markers = data.positions.map(function(location, i) {
					var latLng = new google.maps.LatLng(location.lat, location.lng);
					var marker = new google.maps.Marker({
						position: latLng,
						icon:root+'images/marker.png?num='+nf_map.rand_int,
						title: location.title
						//labelContent: location.title+'(20)'
					});

					marker.addListener('click', function() {
						//nf_map.map.setCenter(marker.getPosition());
						nf_map.info_window(location.msg, location.lat, location.lng);
					});
					return marker;
				});


				if(data.cluster_is!='N') {
					var clusterStyles = [
						{
							textColor: 'white',
							textSize: 18,
							url: root+'images/cluster.png?num='+nf_map.rand_int,
							height: 50,
							width: 50
						}
					];

					var _option = {
						gridSize: 50,
						styles: clusterStyles,
						maxZoom: 19,
						zoomOnClick:true,
						averageCenter:true
					};
				}

				// Add a marker clusterer to manage the markers.
				nf_map.markerCluster = new MarkerClusterer(
					nf_map.map,
					markers,
					_option
				);

				nf_map.cluster_click(func);
			}


			if($(".map_list")[0]) $(".map_list").html(data.tag);
		});
	}


	this.icon_func = function(icon_url) {
		var image = new google.maps.MarkerImage(icon_url,
			// This marker is 20 pixels wide by 32 pixels tall.
			new google.maps.Size(23,32),
			// The origin for this image is 0,0.
			new google.maps.Point(0,0),
			// The anchor for this image is the base of the flagpole at 0,32.
			new google.maps.Point(0, 32)
		);
		return image;
	}
	
	this.customoverlay_delete = function() {
		var len = nf_map.customoverlay.length;
		for(var i=0; i<len; i++) {
			nf_map.customoverlay[i].setMap(null);
		}
	}
	
	
	// 데이터를 가져오기 위해 jQuery를 사용합니다
	// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
	this.set_marker1 = function(url, para, func) {
		
		para = para ? para : '';
		var center = nf_map.map.getCenter();
		lat = center.lat();
		lng = center.lng();

		var width = $(this.mapContainer).width();
		var height = $(this.mapContainer).height();
		var push_size = width>height ? width : height;

		var url_value = url+'&width='+push_size+'&lat='+lat+'&lng='+lng+'&zoom='+this.get_zoom()+"&"+para;

		$.get(url_value, function(data) {
			data = $.parseJSON(data);
			// 데이터에서 좌표 값을 가지고 마커를 표시합니다
			// 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다

			if(data.detail_view=='Y') {
				var markers = $(data.positions).map(function(i, position) {
					var latLng = new google.maps.LatLng(position.lat, position.lng);
					var marker = new google.maps.Marker({
						position: latLng,
						title: position.content
					});
					marker.setMap(nf_map.map);

					marker.addListener('click', function() {
						nf_map.info_window(position.map_content, position.lat, position.lng);
					});
				});
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
		var markers = $(data.positions).map(function(i, position, stats) {
			return new google.maps.Marker({
				position : new google.maps.LatLng(position.lat, position.lng),
				icon: nf_map.icon_func(root+'/images/icon/map_blue_point.png')
			});
		});

		clusterer = new MarkerClusterer(
			nf_map.map,
			markers,
			{imagePath:'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m', gridSize:100 }
		);
	}


/*
로드뷰 이벤트
*/
	this.load_init = function(func, position) {
	}


	this.viewpoint_changed = function(func, position) {
		nf_map.rv.addListener('pov_changed', function() {
			nf_map.get_load_json();
			eval(func);
		});
	}


	this.load_toggle = function(position) {
		//nf_map.rc.getNearestPanoId(position, 50, function(panoId) {
			//nf_map.rv.setPanoId(panoId, position);
		//});
	}






	// : 지도 이동 이벤트 핸들러
	this.map_big = function(el, txt) {
		var center = nf_map.map.getCenter();
		lat = center.lat();
		lng = center.lng();

		el.href = 'http://map.daum.net/link/map/' + encodeURIComponent(txt) + ',' + lat + ',' + lng; //Daum 지도로 보내는 링크
	}

	// : 로드뷰 이동 이벤트 핸들러
	this.load_big = function(el) {
		var panoId = nf_map.rv.getPanoId(); //현 로드뷰의 panoId값을 가져옵니다.
		var viewpoint = nf_map.rv.getViewpoint(); //현 로드뷰의 viewpoint(pan,tilt,zoom)값을 가져옵니다.
		el.href = 'http://map.daum.net/?panoid='+panoId+'&pan='+viewpoint.pan+'&tilt='+viewpoint.tilt+'&zoom='+viewpoint.zoom; //Daum 지도 로드뷰로 보내는 링크
	}
}


var nf_map = new nf_map();