var nf_shop = function() {

	this.qna_modify = function(el, no) {
		var get_code = $(el).attr("code");
		if(!get_code) get_code = "";

		var allow = true;
		if(get_code=='delete') {
			allow = false;
			if(confirm("삭제하시겠습니까?")) {
				allow = true;
			}
		}

		if(allow) {
			$.post(root+"/include/regist.php", "mode=qna_check&code="+get_code+"&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.click_tab = function(el, code, v) {
		var obj = $(el).find("ul");
		var ulObj;
		$(el).parent().find("li").each(function(i){
			if($(this)[0]==$(el)[0]) {
				ulObj = $(".deps2").find("ul").eq(i);
			}
		});
		var get_val = {'area':'wr_area', 'category':'category', 'tema':'tema'};
		switch(!!ulObj[0]) {
			case true:
				$(el).parent().find("li").removeClass("on");
				$(el).addClass("on");
				$(".deps2").find("ul").css({"display":"none"});
				$(".deps2").find("ul").removeClass("on");

				ulObj.css({"display":"flex"});
				ulObj.addClass("on");
			break;

			default:
				location.href = root+"/include/category_view.php?code="+code+"&"+get_val[code]+"[]="+encodeURIComponent(v);
			break;
		}
	}

	this.click_tel = function(code, no) {
		$.post(root+"/include/regist.php", "mode=click_tel&code="+code+"&no="+no, function(data){
			data = $.parseJSON(data);
		});
	}

	this.get_distance_func = function(this_lat, this_lng, lat, lng, c) {
		var latlng = {'this_lat':this_lat, 'this_lng':this_lng, 'lat':lat, 'lng':lng};
		$.post(root+"/include/regist.php", "mode=get_distance_int&zoom="+map_zoom+"&width="+map_width+"&latlng="+JSON.stringify(latlng), function(data){
			data = $.parseJSON(data);
			if(data.js) eval(data.js);
		});
	}

	this.this_location = function() {
		// : this_location 소스는 상단에 있습니다.
		if(confirm("현재위치로 재정렬하시겠습니까?")) {
			navigator.geolocation.getCurrentPosition(function(pos){
				this_location.lat = pos.coords.latitude;
				this_location.lng = pos.coords.longitude;
				var latlng = this_location.lat+"@"+this_location.lng;
				todayDate.setMinutes(todayDate.getMinutes() + 10);
				document.cookie = 'this_area_pos_here=' + latlng + '; path=/; expires=' + todayDate.toUTCString() + ';'
				if(location.href.indexOf("/include/location_view.php")>=0) {
					location.href = "/include/location_view.php";
				} else {
					var json = '$(".my_location").find(".this-loc-txt-").html(detailAddr)';
					nf_map.get_latlng_address(this_location.lat, this_location.lng, json);
				}
			}, function(){
				this_location = basic_location;
				if(location.href.indexOf("/include/location_view.php")>=0) {
					location.href = "/include/location_view.php";
				} else {
					nf_map.get_latlng_address(this_location.lat, this_location.lng, json);
				}
			});
		}
	}

	this.get_latlag = function() {
		var latlng_no = {};
		$(".location-latlng").each(function(i){
			var get_no = $(this).attr("no");
			if(!latlng_no[get_no]) latlng_no[get_no] = get_no;
		});
		var jsonData = JSON.stringify(latlng_no);
		$.post(root+"/include/regist.php", "mode=get_list_latlng&lat="+this_location.lat+"&lng="+this_location.lng+"&json="+jsonData, function(data){
			data = $.parseJSON(data);
			$(".location-latlng").each(function(i){
				var get_no = $(this).attr("no");
				if(!data.km[get_no][1]) $(this).css({"display":"none"});
				else $(this).html(data.km[get_no][0]);
			});
		});
	}

	this.sale_price_calc = function(el) {

		var get_name = $(el).attr("name");
		if(get_name.indexOf("[]")>=0) {
			var get_name_pos = get_name.indexOf("[");
			var get_name_arr_txt = get_name.substr(get_name_pos);

			var index_int = 0;
			$("[name='"+el.name+"']").each(function(i){
				if(el==$(this)[0]) index_int = i;
			});
			var price1 = $(el).closest(".parent-price-").find("[name='price_ori_inp"+get_name_arr_txt+"']").eq(index_int);
			var price = $(el).closest(".parent-price-").find("[name='price_price_inp"+get_name_arr_txt+"']").eq(index_int);
			var sale = $(el).closest(".parent-price-").find("[name='price_sale_inp"+get_name_arr_txt+"']").eq(index_int);
		} else {
			var price1 = $(el).closest(".parent-price-").find(".price1--");
			var price = $(el).closest(".parent-price-").find(".price--");
			var sale = $(el).closest(".parent-price-").find(".sale--");
		}

		price.val(price.val().replace(/(\s*)/gi, ""));
		price1.val(price1.val().replace(/(\s*)/gi, ""));

		var _price = price.val();
		var _price1 = price1.val();

		//var _sale_price = Math.ceil(_price1 - (_price1*_sale/100));
		var _sale_price = Math.ceil(100-(_price/_price1*100));
		if(!_price1 || _price1<=0) _sale_price = 0;
		sale.val(_sale_price);
	}

	this.good = function(el, code, no) {
		var good = $(el).attr("good");
		if(!good) good = 1;
		$.post("../include/regist.php", "mode=click_guide_good&code="+code+"&good="+good+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.click_guide = function(no) {
		var obj = $(".guide-"+no+"-").find(".answer");
		var display = obj.css("display")=="none" ? "block" : "none";
		obj.css({"display":display});
	}

	this.click_jump = function(code, no) {
		if(confirm("점프하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=jump_process&code="+code+"&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.click_memo = function(no) {
		$.post(root+"/include/regist.php", "mode=click_memo&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.jump_use = function(el, code, no) {
		var txt = el.value==='1' ? '사용' : '미사용';
		$.post(root+"/include/regist.php", "mode=click_jump_use&code="+code+"&val="+el.value+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}

	this.click_photo = function(el) {
		$(el).parent().find('[type=checkbox]').click();
	}

	this.price_head_create = function(el) {
		var price_head = $(el).closest(".price-group-parent-").find(".price-head-inp-").val();
		if(!price_head) {
			$(el).closest(".price-group-parent-").find(".price-head-inp-")[0].focus();
			alert("구분값을 입력해주시기 바랍니다.");
			return;
		}
		var price_head_arr = price_head.split(",");
		var obj = $(el).closest(".price-group-parent-").find(".price-head-ori-").eq(0).clone(true);
		$(el).closest(".price-group-parent-").find(".price-head-tbody-").find("tr").remove();

		var len = price_head_arr.length;
		for(var i=1; i<len; i++) {
			$(el).closest(".price-group-parent-").find(".price-head-tbody-").append(obj.html());
		}

		// : 요금부분 단어 붙이는곳
		$(el).closest(".price-group-parent-").find(".price-head-table-").each(function(i){
			$(this).find(".price-head-txt-").each(function(j){
				$(this).html(price_head_arr[j]);
			});
		});

		// : 요금구분에서 [0][k_1]의 두번째 키값 재설정하기
		$(el).closest(".price-group-parent-").find(".price-parent-body-").find(".price-paste-").find(".price-copy-").each(function(i){
			$(this).find(".price-ch-inp-").each(function(j){
				var get_name_ori = $(this).attr("name");
				var get_name_arr = $(this).attr("name").split("][k_");
				var get_name = get_name_arr[0]+'][k_'+(i)+'][]';
				$(this).attr("name", get_name);
			});
		});
	}


	this.price_use_check = function(el) {
		var val = el.checked ? 1 : 0;
		$(el).closest("th").find(".price-use-inp-").val(val);
	}


	this.upload = function(el, code) {
		var form = document.forms['fupload'];
		form.code.value = code;
		var tag = $(form).find("span").html();
		$(form).find("span").html(tag);
		form.upload.click();
		return false;
	}

	this.upload_process = function(el) {
		var form = document.forms['fupload'];
		nf_util.ajax_submit(form);
		return false;
	}

	this.select_info = function(el) {
		var loc = location.href.split("?");
		location.href = loc[0]+"?info_no="+el.value;
	}

	this.photo_delete = function(el, code) {
		var form = el.form;
		var txt = form.no.value ? '수정' : '삭제';
		switch(code) {
			case "shop":
				var len = $(form).find("[name='shop_photo_chk[]']:checked").length;
				if(len<=0) {
					alert("삭제할 업체이미지를 하나이상 선택해주시기 바랍니다.");
					return;
				}

				if(confirm("삭제하시겠습니까?\n최종 "+txt+"하셔야 삭제됩니다.")) {
					$(form).find("[name='shop_photo_chk[]']:checked").each(function(){
						$(this).closest(".photo-item-").remove();
					});
				}
				if($(el).closest("td").find(".photo-item-").length<=0) {
					$(el).closest("td").find(".not-image-").css({"display":"inline"});
					$("."+code+"-photo-paste-").find(".check_photo-").val("");
				}
			break;

			case "photo":
				if(confirm("삭제하시겠습니까?\n최종 "+txt+"하셔야 삭제됩니다.")) {
					$(el).closest("td").find(".photo-item-").remove();
					$(el).closest("td").find(".not-image-").css({"display":"inline"});
					$("."+code+"-photo-paste-").find(".check_photo-").val("");
				}
			break;
		}
	}
}

var nf_shop = new nf_shop();

$(window).ready(function(){
	if($(".scrap-star-")[0] && $(".scrap-star-").length>0) {
		$(".scrap-star-").click(function(){
			var no = $(this).attr("no");
			var code = $(this).attr("code");
			nf_util.scrap($(this)[0], code, no);
			return false;
		});
	};
});