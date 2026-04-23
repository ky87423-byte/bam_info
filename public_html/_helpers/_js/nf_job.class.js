var img_obj = "";
var job_form = "";

var nf_job = function() {

	this.move_service = function(k) {
		var offset = $('#'+k).offset();
		var scrollto = offset.top - 100; // minus fixed header height
		$('html, body').animate({scrollTop:scrollto}, 0);
	}

	this.click_pay_conference = function(el) {
		$(el).closest("td").find("select").prop("disabled", el.checked);
		$(el).closest("td").find("input[type=text]").prop("disabled", el.checked);
		if(el.checked) {
			$(el).closest("td").find("select").removeAttr("needed");
			$(el).closest("td").find("input[type=text]").removeAttr("needed");
		} else {
			$(el).closest("td").find("select").attr({"needed":"needed"});
			$(el).closest("td").find("input[type=text]").attr({"needed":"needed"});
		}
	}

	this.click_calltime_always = function(el) {
		$(el).closest("td").find("select").prop("disabled", el.checked);
		if(el.checked) {
			$(el).closest("td").find("select").removeAttr("needed");
		} else {
			$(el).closest("td").find("select").attr({"needed":"needed"});
		}
	}

	this.click_school_chk = function(el) {
		$(".school_level").each(function(){
			var school_level = $(this).attr("school_level");
			if(el.value==school_level) {
				var display = el.checked ? 'table-row' : 'none';
				$(this).css({"display":display});
			}
		});
	}

	this.click_volume = function(el) {
		var len = $("[name='"+el.name+"']:checked").length;
		if(len>0) {
			$("[name='wr_person']").val("");
			$("[name='wr_person']").removeAttr("needed");
			$("[name='wr_person']").attr("disabled", "disabled");
			$("[name='wr_person']").addClass("disabled");
		} else {
			$("[name='wr_person']").removeAttr("disabled");
			$("[name='wr_person']").removeClass("disabled");
			$("[name='wr_person']").attr("needed", "needed");
		}
	}

	this.click_end_date_check = function(el) {
		var len = $("[name='"+el.name+"']:checked").length;
		if(len>0) {
			$("[name='wr_end_date']").val("");
			$("[name='wr_end_date']").removeAttr("needed");
			$("[name='wr_end_date']").attr("disabled", "disabled");
			$("[name='wr_end_date']").addClass("disabled");

		} else {
			$("[name='wr_end_date']").removeAttr("disabled");
			$("[name='wr_end_date']").removeClass("disabled");
			$("[name='wr_end_date']").attr("needed", "needed");
		}
	}

	this.click_time_conference = function(el) {
		$(el).closest("td").find("select").prop("disabled", el.checked);
		if(el.checked) {
			$(el).closest("td").find("select").removeAttr("needed");
		} else {
			$(el).closest("td").find("select").attr({"needed":"needed"});
		}
	}

	this.click_requisition = function(el) {
		var txt = 'requisition_'+el.value+'-';
		var display = el.checked ? 'block' : 'none';
		$("."+txt).css({"display":display});
	}

	this.click_file_delete = function(el, code, eq) {
		if(!eq) eq = 0;
		if(confirm("삭제하시겠습니까?")) {
			var photo_img = $(el).closest(".parent_file_upload-").find(".put_image-").eq(eq).val();
			$.post(root+"/include/regist.php", "mode=employ_img_delete&photo_img="+photo_img, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.ch_logo = function(el) {
		var id = $(el).attr("id");
		nf_util.ajax_submit(el.form);
	}

	this.click_photo = function(el, no) {
		var form = document.forms['ffile'];
		form.file_upload.click();
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

	this.click_file = function(el, id, code) {
		var form = document.forms['ffile'];
		if(code=='company') {
			var len = 0;
			$(el).closest(".parent_file_upload-").find(".put_image-").each(function(){
				if(!$(this).val()) len++;
			});
			if(len<=0) {
				alert("이미 4개의 이미지가 다 찼습니다.\n이미지를 삭제하고 새로 등록해주시기 바랍니다.");
				return;
			}
		}
		img_obj = el;
		form.code.value = code;
		$("#"+id).click();
	}

	this.ch_career = function(el) {
		$(".wr_career-").find("select").removeAttr("needed");
		$(".wr_career-").css({"display":"none"});
		if(el.value=='2' && el.checked) {
			var display = $(".wr_career-")[0].tagName;
			display = display=='SPAN' ? 'inline' : 'block';
			$(".wr_career-").css({"display":display});
			$(".wr_career-").find("select").attr("needed", "needed");
		}
	}

	this.ch_school = function(el) {
		var val = parseInt(el.value);
		$(".school_level").each(function(){
			var school_level = parseInt($(this).attr("school_level"));
			$(this).css({"display":"none"});
			if(val>=school_level) $(this).css({"display":"table-row"});
		});

		$("[name='wr_school_type[]']").each(function(){
			var school_type = parseInt($(this).val());
			$(this).prop("checked", false);
			$(this).prop("disabled", false);
			if(val>=school_type) $(this).prop("checked", true);
			else $(this).prop("disabled", true);
		});
	}

	this.click_career = function(el) {
		$(".check_career-").css({"display":"none"});
		if(el.checked) $(".check_career-").css({"display":"table-row-group"});
	}

	this.click_language_level = function(el) {
		$(el).closest(".parent-").find("[name='wr_language[level][]']").val(el.value);
	}

	this.click_resume_select = function(el) {
		var obj = $(".check_"+el.value+"_opener-");
		obj.css({"display":"none"});
		if(el.checked) obj.css({"display":"block"});
	}

	this.click_veteran = function(el) {
		$(".veteran-").css({"display":"none"});
		if(el.value==='1') $(".veteran-").css({"display":"inline"});
	}

	this.click_treatment_use = function(el) {
		$(".treatment_service-").css({"display":"none"});
		if(el.value==='1') $(".treatment_service-").css({"display":"block"});
	}

	this.click_military_use = function(el) {
		$(".wr_militray_1-").css({"display":"none"});
		$(".wr_militray_2-").css({"display":"none"});
		if(el.value!=='0') $(".wr_militray_"+el.value+"-").css({"display":"block"});
	}

	this.click_impediment_use = function(el) {
		$(".impediment_use-").css({"display":"none"});
		if(el.value==='1') $(".impediment_use-").css({"display":"block"});
	}

	this.click_language_study = function(el) {
		$(".language_study-").css({"display":"none"});
		if(el.checked) $(".language_study-").css({"display":"inline"});
	}

	this.click_pay_conference = function(el) {
		var form = document.forms['fwrite'];
		var len = $(form).find("[name='wr_pay_conference']:checked").length;
		switch(len<=0) {
			case true:
				$(form).find("[name='wr_pay_type']").attr({"needed":"needed"});
				$(form).find("[name='wr_pay']").attr({"needed":"needed"});
				$(form).find("[name='wr_pay_type']").removeAttr("disabled");
				$(form).find("[name='wr_pay']").removeAttr("disabled");
			break;

			default:
				$(form).find("[name='wr_pay_type']").attr({"disabled":"disabled"});
				$(form).find("[name='wr_pay']").attr({"disabled":"disabled"});
				$(form).find("[name='wr_pay_type']").removeAttr("needed");
				$(form).find("[name='wr_pay']").removeAttr("needed");
			break;
		}
	}

	this.click_end_date = function(no) {
		if(confirm("마감으로 변경하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=ch_end_date&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
			});
		}
	}

	this.ch_company = function(el) {
		var form = document.forms['fwrite'];
		$.post(root+"/include/regist.php", "mode=ch_company&no="+el.value, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.ch_manager = function(el) {
		var form = document.forms['fwrite'];
		$.post(root+"/include/regist.php", "mode=ch_manager&no="+el.value, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.rep_company = function(no) {
		if(confirm("대표기업으로 설정하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=rep_company&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.not_open = function(el, no) {
		$.post(root+"/include/regist.php", "mode=not_open&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}


	this.iframe_map_obj = {};
	this.move_map = function(el, id, k) {
		var index = $(el).closest(".area-address-").index();
		var body = $(el).closest(".area-address-");
		var obj = $("[name='iframe_map["+index+"]']");

		switch(!k) {
			case true:
				var i=0;
				body.find(".wr-area-").each(function(){
					if($(this).val()) {
						switch($(this)[0].tagName) {
							case "SELECT":
								area_text_arr[i] = $(this).find(":selected").text();
							break;

							case "INPUT":
								area_text_arr[i] = $(this).val();
							break;
						}
						i++;
					}
				});

				var area_text = area_text_arr.join(" ");
				alert(area_text);
				obj[0].contentWindow.nf_map.address_move(area_text, {iframe_func:'parent.nf_job.iframe_put_marker('+index+')'});
			break;

			default:
				sample2_execDaumPostcode(el, {code:'latlng_move', obj:obj, index:index});
			break;
		}

		var area_text_arr = [];
	}

	this.iframe_click_marker = function(get_json, k) {
		var form = document.forms['fwrite'];
		$("[name='iframe_map["+k+"]']")[0].contentWindow.nf_map.marker_put(get_json, {});
		switch(map_engine) {
			case "google":
				$(form).find("[name='map_int["+k+"][]']").eq(0).val(get_json.latlng.lat());
				$(form).find("[name='map_int["+k+"][]']").eq(1).val(get_json.latlng.lng());
			break;

			default:
				$(form).find("[name='map_int["+k+"][]']").eq(0).val(get_json.latlng.getLat());
				$(form).find("[name='map_int["+k+"][]']").eq(1).val(get_json.latlng.getLng());
			break;
		}
	}

	this.iframe_put_marker = function(k) {
		var form = document.forms['fwrite'];
		var map_obj = $("[name='iframe_map["+k+"]']")[0].contentWindow.nf_map;
		$(form).find("[name='map_int["+k+"][]']").eq(0).val(map_obj.this_latlng.lat);
		$(form).find("[name='map_int["+k+"][]']").eq(1).val(map_obj.this_latlng.lng);
	}

	this.jump_use = function(el, code, no) {
		var txt = el.value==='1' ? '사용' : '미사용';
		$.post(root+"/include/regist.php", "mode=click_jump_use&code="+code+"&val="+el.value+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}

	this.ch_sort = function(el, fname) {
		var form = document.forms[fname];
		var code = $(el).attr("code");
		code = code ? code : 'employ';
		$(form).find("[name='"+el.name+"']").val(el.value);
		form.search_list.value = code+'_list-';
		form.submit();
	}

	this.ch_page_row = function(el, fname) {
		var form = document.forms[fname];
		var code = $(el).attr("code");
		code = code ? code : 'employ';
		$(form).find("[name='"+el.name+"']").val(el.value);
		form.search_list.value = code+'_list-';
		form.submit();
	}

	this.select_info = function(el) {
		var loc = location.href.split("?");
		location.href = loc[0]+"?info_no="+el.value;
	}

	this.put_skin_content = function(el) {
		var form = document.forms['fwrite'];
		var prev_mode = form.mode.value;
		form.mode.value = "put_skin_content";
		nf_util.ajax_submit2(form);
		form.mode.value = prev_mode;
		return;
	}
}


var nf_job = new nf_job();

var click_skin_detail_html = {};
var prev_click_detail_skin = "";

$(window).ready(function(){

	if($(".scrap-star-")[0] && $(".scrap-star-").length>0) {
		$(".scrap-star-").click(function(){
			var no = $(this).attr("no");
			var code = $(this).attr("code");
			nf_util.scrap($(this)[0], code, no);
			return false;
		});
	}

	var allow = true;
	if(location.href.indexOf("map_write.inc.php")>=0) allow = false;
	if(allow) {
		job_form = document.forms['fwrite'];

		if(job_form) {
			if($(".click-skin-").find("ul > li")[0]) {
				$(".click-skin-").find("ul > li").click(function(){
					var index = $(this).index();
					var get_skin = $(this).attr("skin");
					$(".click-skin-").find("ul > li").removeClass("on");
					$(job_form).find("[name='wr_content_skin']").val(get_skin);
					$(this).addClass("on");
					var no = $(this).closest("form").find("[name='no']").val();
					if(!get_skin) {
						_editor_use["wr_content"].putContents("");
					} else if(!no && !get_skin) {
						_editor_use["wr_content"].putContents("");
					} else {
						click_skin_detail_html[prev_click_detail_skin] = _editor_use["wr_content"].outputBodyHTML();

						if(!click_skin_detail_html[get_skin]) {
							$.post(root+"/include/regist.php", "mode=get_employ_skin&skin="+get_skin, function(data){
								data = $.parseJSON(data);
								if(data.js) eval(data.js);
							});
						} else {
							_editor_use["wr_content"].putContents(click_skin_detail_html[get_skin]);
						}
						prev_click_detail_skin = get_skin;
					}
				});
			}

			if($(".click-logo-").find("[name='wr_logo_type']")[0]) {
				$(".click-logo-").find("[name='wr_logo_type']").click(function(){
					var form = document.forms['fwrite'];
					var index = $(this).index();
					var val = $(this).val();
					var obj = $(".click-logo-td-").find("[val='"+val+"']");
					$(".click-logo-td- > div").css({"display":"none"});
					obj.eq(index).css({"display":"block"});

					switch(val) {
						case "text":
							$(form).find("[name='wr_logo_text']").attr({"needed":"needed"});
							$(form).find("[name='wr_logo']").removeAttr("needed");
							$(form).find("[name='wr_logo_bg']").removeAttr("needed");
						break;

						case "logo":
							$(form).find("[name='wr_logo_text']").removeAttr("needed");
							$(form).find("[name='wr_logo']").attr({"needed":"needed"});
							$(form).find("[name='wr_logo_bg']").removeAttr("needed");
						break;

						case "bg":
							$(form).find("[name='wr_logo_text']").removeAttr("needed");
							$(form).find("[name='wr_logo']").removeAttr("needed");
							$(form).find("[name='wr_logo_bg']").attr({"needed":"needed"});
						break;
					}
				});
			}
		}
	}
});