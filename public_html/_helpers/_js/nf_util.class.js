var nf_util = function() {

	this.page_check = location.href.indexOf("/m/")>=0 ? 'mobile' : 'pc';

	this.back = function() {
		history.back();
	}

	/* : 쿠키 저장하기*/
	this.setCookie = function( name, value, expiredays )
	{
		var todayDate = new Date();
		todayDate.setDate(todayDate.getDate() + expiredays);
		document.cookie = name + '=' + escape( value ) + '; path=/; expires=' + todayDate.toGMTString() + ';'
	};
	this.getCookie = function(name) {
		var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
		return value? value[2] : null;
	};
	/* : 닫기버튼*/
	this.cookie_noneWin = function(el, name, value)
	{
		this.setCookie(name, value , 1);
		$(el).css('display', 'none');
	};
	/* : 닫기버튼*/
	this.noneWin = function(el)
	{
		$(el).css('display', 'none');
	};
	/*열기버튼*/
	this.openWin = function(c, display)
	{
		if(!display) {
			var display = $(c).css("display");
			display = display=='none' ? 'block' : 'none';
		}
		$(c).css('display', display);
	};
	/*즐겨찾기*/
	this.favorite = function(){
		$('.favorite').on('click', function(e) {
			var bookmarkURL = window.location.href;
			var bookmarkTitle = document.title;
			var triggerDefault = false;
			
			if (window.sidebar && window.sidebar.addPanel) {
				// Firefox version &lt; 23
				window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
			} else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') < -1)) || (window.opera && window.print)) {
				// Firefox version &gt;= 23 and Opera Hotlist
				var $this = $(this);
				$this.attr('href', bookmarkURL);
				$this.attr('title', bookmarkTitle);
				$this.attr('rel', 'sidebar');
				$this.off(e);
				triggerDefault = true;
			} else if (window.external && ('AddFavorite' in window.external)) {
				// IE Favorite
				window.external.AddFavorite(bookmarkURL, bookmarkTitle);
			} else {
				// WebKit - Safari/Chrome
				alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 를 이용해 이 페이지를 즐겨찾기에 추가할 수 있습니다.');
			}
			return triggerDefault;
		});
	}

	this.open_group = function(c, display) {
		var display2 = $(c).css("display");
		$(".popup_layer").css({"display":"none"});
		display2 = display2=='none' ? 'block' : 'none';
		if(display) display2 = display;
		$(".popup_layer"+c).css({"display":display2});
	}

	this.date_search_click = function(el, date1, date2) {
		var form = el.form;
		$(form).find("[name='date1']").val(date1);
		$(form).find("[name='date2']").val(date2);
	};

	this.ch_email = function(el, c) {
		var form = el.form;
		var obj = $(form).find("[name='wr_email[]']").eq(1);
		if(c) obj = $(form).find(c);
		obj.val(el.value);
	}

	this.ch_range = function(el, c) {
		$(c).html(el.value);
	}

	this.child_allcheck = function(el) {
		$(el).closest(".chk-body-").find("[type=checkbox]").prop("checked", el.checked);
	}

	this.all_check = function(el, c) {
		if(el.type=='checkbox') {
			is = el.checked;
		} else {
			is = $(el)[0].checked;
			is = is ? false : true;
			$(el).prop("checked", is);
		}

		$(".all_chk").prop("checked", is);
		
		$(c).prop("checked", is);
	}

	this.all_check2 = function(el, c) {
		$(".check_body_").find("[type=checkbox]").prop("checked", el.checked);
	}

	this.one_check = function(el) {
		if(!el.checked) el.checked = false;
		else {
			$(el.form).find("[name='"+el.name+"']").prop("checked", false);
			el.checked = true;
		}
	}

	this.put_text = function(el, obj) {
		obj.val(el.value);
	};

	this.email_select = function(el, name) {
		$(el).closest("td").find("[name='"+name+"']").eq(1).val(el.value);
	}

	this.spectrum_func = function(el){
		$(el).spectrum({
			preferredFormat: "name",
			allowEmpty:true,
			showInput: true,
			showInitial: true,
			showPalette: true,
			cancelText: "닫기",
			chooseText: "선택",
			palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]]
		});
	};

	this.spectrum_put = function(el) {
		$(el).spectrum({
			color:el.value,
			preferredFormat: "name",
			allowEmpty:true,
			showInput: true,
			showInitial: true,
			showPalette: true,
			cancelText: "닫기",
			chooseText: "선택",
			palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]]
		});
	}

	this.clone_tag = function(el, c, nums) {
		var txt_length = $(el).text().indexOf("추가");
		var this_child = $(el).attr("this_child"); // : 추가버튼속에 또다른 추가버튼 있는경우
		switch(txt_length>=0) {
			case true:
				var length = $(el).closest(c).find(">.parent-").length;
				if(length>=nums) {
					alert(nums+"개까지 추가 가능합니다.");
					return;
				}
				var obj = $(el).closest(c).find(">.parent-").eq(0).clone(true).wrapAll("<div/>").parent();
				obj.find("input[type=text]").val("");
				if(obj.find(".btn--")[0]) {
					obj.find(".btn--").removeClass("black");
					obj.find(".btn--").addClass("gray");
				}
				obj.find(".not_copy").html("");
				obj.find("select").val("");
				obj.find("textarea").val("");
				obj.find("[type=checkbox]").prop("checked", false);
				obj.find("[type=radio]").prop("checked", false);
				if(this_child) {
					obj.find("[this_child='"+this_child+"']").html("삭제");
					obj.find("."+this_child).find(".parent-").each(function(i){
						if(i>0) $(this).remove();
					});
				}
				var html = obj.html();

				if(!this_child) html = html.replace(/추가/gi, '삭제');

				// : 1번지역, 2번지역같은 여러개 같은정보를 받는경우 [ 지역, 직종같은 것들 ] - 변환할 name은 모두 check_seconds클래스 넣어야함
				var is_tag = obj.find(".check_seconds");
				if(is_tag.length>0) {
					is_tag.each(function(){
						html = html.replace(/\[0\]/gi, '['+length+']');
					});
				}
				$(el).closest(c).append(html);
			break;

			default:
				if(confirm("삭제하시겠습니까?")) {
					var length = $(el).closest(c).find(">.parent-").length;
					var obj = $(el).closest(c).find(">.parent-");
					var parentObj = $(el).closest(".parent-");
					$(el).closest(".parent-").remove();

					// : 1번지역, 2번지역같은 여러개 같은정보를 받는경우 1차키값 재정렬 [ 지역 3개, 직종 3개 등등] - 변환할 name은 모두 check_seconds클래스 넣어야함
					var is_tag = obj.find(".check_seconds");
					if(is_tag.length>0) {
						var k_num = 0;
						obj.each(function(i){
							if($(this)[0]!=parentObj[0]) {
								$(this).find(".check_seconds").each(function(j){
									var get_name = $(this).attr("name").split("[");
									var get_name_word1 = parseInt(get_name[1].substr(0,1));
									var arr_txt = $(this).attr("name").indexOf("[]")>=0 ? '[]' : '';
									if(Number.isNaN(get_name_word1)) {
										$(this).attr("name", get_name[0]+'['+get_name[1]+"["+k_num+"]"+arr_txt);
									} else {
										$(this).attr("name", get_name[0]+"["+k_num+"]"+arr_txt);
									}
								});
								k_num++;
							}
						});
					}
				}
			break;
		}
	}

	this.clone2_tag = function(el, c, c2, c3) {
		var txt_length = $(el).text().indexOf("추가");
		var ch_name = $(el).attr("ch_name");
		if(c3) {
			var obj_length = $(el).closest(c).find(c2).find(c3).length;
		} else {
			var obj_length = $(c).find(c2).length;
		}

		if(txt_length<0 && obj_length<=1) {
			alert("하나만 남은경우에는 삭제할 수 없습니다.");
			return;
		}

		switch(txt_length>=0) {
			case true:
				var length = $(c).find(c2).length;

				if(c3) {
					var obj = $(el).closest(c).find(c2).find(c3).eq(0).clone(true).wrapAll("<div/>").parent();
				} else {
					var obj = $(c).find(c2).eq(0).clone(true).wrapAll("<div/>").parent();
				}

				// : 요금추가버튼
				if(!c3) {
					obj.find(".price-head-tbody-").html("");
					obj.find(".price-head-txt-").html("");
					obj.find(".price-copy-").each(function(i){
						if(i>0) {
							obj.find(".price-copy-").eq(1).remove();
						}
					});
				}

				var html = obj.html();

				// : 1번지역, 2번지역같은 여러개 같은정보를 받는경우 [ 지역, 직종같은 것들 ] - 변환할 name은 모두 check_seconds클래스 넣어야함
				if(ch_name=='yes') {
					var is_tag = obj.find(".check_seconds");
					if(is_tag.length>0) {
						is_tag.each(function(){
							html = html.replace(/\[0\]/gi, '['+length+']');
						});
					}
				}

				if(c3) {
					$(el).closest(c).find(c2).append(html);
					var obj_len = $(el).closest(c).find(c2).find(c3).length;
					$(el).closest(c).find(c2).find(c3).eq(obj_len-1).find("input[type=text]").val("");
					$(el).closest(c).find(c2).find(c3).eq(obj_len-1).find("input[type=hidden]").val("");
					$(el).closest(c).find(c2).find(c3).eq(obj_len-1).find("input[type=checkbox]").prop("checked", true);
					if($(el).closest(c).find(c2).find(c3).eq(obj_len-1).find(".price-use-inp-")[0])
						$(el).closest(c).find(c2).find(c3).eq(obj_len-1).find(".price-use-inp-").val(1);
				} else {
					$(c).append(html);
					var obj_len = $(c).find(c2).length;
					$(c).find(c2).eq(obj_len-1).find("input[type=text]").val("");
					$(c).find(c2).eq(obj_len-1).find("input[type=hidden]").val("");
					$(c).find(c2).eq(obj_len-1).find("input[type=checkbox]").prop("checked", true);
					$(c).find(c2).eq(obj_len-1).find("[name='price_check[]']").prop("checked", false);
					if($(c).find(c2).eq(obj_len-1).find(".price-use-inp-")[0]) $(c).find(c2).eq(obj_len-1).find(".price-use-inp-").val(1);
				}

				// : 요금구분에서 추가버튼 누를시
				if(c3) {
					$(el).closest(c).find(c2).find(".price-copy-").each(function(i){
						$(this).find(".price-ch-inp-").each(function(j){
							var get_name_ori = $(this).attr("name");
							var get_name_arr = $(this).attr("name").split("][k_");
							var get_name = get_name_arr[0]+'][k_'+(i)+'][]';
							$(this).attr("name", get_name);
						});
					});
				}

			break;

			default:
				if(confirm("삭제하시겠습니까?")) {
					if(c3)
						$(el).closest(c).find(c2).find(c3).eq(obj_length-1).remove();
					else
						$(c).find(c2).eq(obj_length-1).remove();
				}
			break;
		}
	}

	this.clone2_group_remove = function(el, c, c2, c3) {
		var chk_len = $("[name='price_check[]']:checked").length;
		if(chk_len<=0) {
			alert("삭제할 요금그룹을 선택해주시기 바랍니다.");
			return;
		}

		if(confirm("삭제하시겠습니까?")) {
			$("[name='price_check[]']:checked").each(function(){
				$(this).closest(".price-group-parent-").remove();
			});
		}

		var length = $(c).find(c2).length;
		var obj = $(c).find(c2);

		// : 1번지역, 2번지역같은 여러개 같은정보를 받는경우 1차키값 재정렬 [ 지역 3개, 직종 3개 등등] - 변환할 name은 모두 check_seconds클래스 넣어야함
		var is_tag = obj.find(".check_seconds");
		if(is_tag.length>0) {
			var k_num = 0;
			obj.each(function(i){
				try{
					$(this).find(".check_seconds").each(function(j){
						if($(this).attr("name")) {
							var get_name = $(this).attr("name").split("[");
							var get_name_word1 = parseInt(get_name[1].substr(0,1));
							var arr_txt = $(this).attr("name").indexOf("[]")>=0 ? '[]' : '';
							if(Number.isNaN(get_name_word1)) {
								$(this).attr("name", get_name[0]+'['+get_name[1]+"["+k_num+"]"+arr_txt);
							} else {
								$(this).attr("name", get_name[0]+"["+k_num+"]"+arr_txt);
							}
						}
					});
					k_num++;
				}catch(e){
					alert(e.message);
				}
			});
		}
	}

	this.clone_tr = function(c) {
		var obj = $(c).find("tr").eq(0).clone();
		obj.find("[type=checkbox]").prop("checked", false);
		obj.find("[type=text]").val("");

		$(c).append(obj);
	}

	this.clone_paste = function(el, tag) {
		var type = $(el).text().indexOf("추가")>=0 ? 'add' : 'del';
		switch(type) {
			case "add":
				var length = $(el).closest(".paste-body-").find(tag).length;
				var obj = $(el).closest(".paste-body-").find(tag).eq(0).clone();
				var sun_is = obj.find(".count-")[0];
				if(sun_is) obj.find(".count-").html(length+1);
				obj.find("[type=checkbox]").prop("checked", false);
				obj.find("[type=text]").val("");
				obj.find("button").html("- 제거");
				if(obj.find(".color_picker").length>0) {
					obj.find(".color_picker").each(function(){
						obj.find(".sp-replacer").remove();
						nf_util.spectrum_func($(this)[0]);
					});
				}
				$(el).closest(".paste-body-").append(obj);
			break;

			default:
				var obj = $(el).closest(".paste-body-").find(tag);
				var sun_is = obj.eq(0).find(".count-")[0];
				var len = obj.length;
				if(len<=1) {
					alert("2개이상 있을경우 삭제가 가능합니다.");
					return;
				}
				if(confirm("삭제하시겠습니까?")) {
					$(el).closest(tag).remove();
					if(sun_is) {
						$(".paste-body-").find(tag).each(function(i){
							$(this).find(".count-").html(i+1);
						});
					}
				}
			break;
		}
	}

	this.clone_paste2 = function(c, tag) {
		var tr_length = $(c).find(tag).length;
		var end_rank = $(c).find(tag).eq(tr_length-1).find("[name='rank[]']");
		var obj = $(c).find(tag).eq(0).clone();
		var file_tag = obj.find("[type=file]").parent().html();
		obj.find("[type=text]").val("");
		obj.find("[type=checkbox]").prop("checked", false);
		obj.find("[type=radio]").prop("checked", false);
		obj.find("[type=file]").parent().html(file_tag);

		if(end_rank) {
			var rank = obj.find("[name='rank[]']").val(parseInt(end_rank.val())+1);
		}

		$(c).append(obj);
	}


	this.ch_value = function(el, c) {
		$(c).val(el.value);
	}


	this.ajax_submit = function(el, noneObj) {
		var form = el;
		if(validate(form)) {
			$(form).ajaxSubmit({
				//보내기전 validation check가 필요할경우
				beforeSubmit: function (data, frm, opt) {
					//alert("전송전!!");
					return true;
				},
				//submit이후의 처리
				success: function(data, statusText) {
					data = $.parseJSON(data);
					if(data.msg) alert(data.msg);
					if(data.js) eval(data.js);
					if(data.move) location.href = data.move;
					if(noneObj) noneObj.css('display', 'none');
					return false;
				},
				//ajax error
				error: function(data,status,error){
					alert("에러발생!!");
					return false;
				}
			});
		}
		return false;
	}

	this.ajax_submit2 = function(el, noneObj) {
		var form = el;
		$(form).ajaxSubmit({
			//보내기전 validation check가 필요할경우
			beforeSubmit: function (data, frm, opt) {
				//alert("전송전!!");
				return true;
			},
			//submit이후의 처리
			success: function(data, statusText) {
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.js) eval(data.js);
				if(data.move) location.href = data.move;
				if(noneObj) noneObj.css('display', 'none');
				return false;
			},
			//ajax error
			error: function(data,status,error){
				alert("에러발생!!");
				return false;
			}
		});
		return false;
	}

	this.date_calc = function(date, sel_date) {
		var ch_date = new Date(date);
		var sel_date_arr = sel_date.split(" ");
		switch(sel_date_arr[1]) {
			case "day":
				ch_date.setDate(ch_date.getDate()-sel_date[0]);
				break;

			case "month":
				ch_date.setDate(ch_date.getMonth()-sel_date[0]);
				break;

			case "year":
				ch_date.setDate(ch_date.getFullYear()-sel_date[0]);
				break;
		}
		return ch_date.getFullYear()+'-'+('0'+(ch_date.getMonth() + 1)).slice(-2)+'-'+('0'+ch_date.getDate()).slice(-2);
	}


	this.put_date = function(el) {
		var sel_date = $(el).attr('date');
		var d_start = $(el).attr("d_start");
		var d_end = $(el).attr("d_end");

		var todate = new Date();
		var day_txt = todate.getFullYear()+'-'+('0'+(todate.getMonth() + 1)).slice(-2)+'-'+('0'+todate.getDate()).slice(-2);

		switch(sel_date) {
			case 'today':
				$('#'+d_start).val(day_txt);
				$('#'+d_end).val(day_txt);
				break;

			default:
				var ch_date = nf_util.date_calc(day_txt, sel_date);
				$('#'+d_start).datepicker('setDate', '-'+sel_date);
				$('#'+d_end).val(day_txt);
				break;
		}
	}


	this.editor_start = function(id) {
		if(id) var obj = $(id);
		else var obj = $("textarea");
		obj.each(function(){
			var _type = $(this).attr("type");
			if(_type=='editor') {
				var _name = $(this).attr("name");
				var _width = $(this).css("width");
				var _height = $(this).css("height");
				if(!$(this).attr("id")) $(this).attr("id", "tx_"+_name);
				try{
					_editor_use[_name] = new cheditor('ed_'+_name);
					_editor_use[_name].config.editorHeight = _height ? _height : '250px';
					_editor_use[_name].config.editorWidth = _width ? _width : '100%';
					_editor_use[_name].inputForm = 'tx_'+_name;
					_editor_use[_name].run();
				}catch(e){
					alert(e.message);
				}
			}
		});
	}

	this.reset_form = function(form) {
		form.reset();
	}


	this.get_date = function(val) {

		var val_arr = val.split(" ");
		val_arr[0] = parseInt(val_arr[0]);
		if(val_arr[1]=='week') {
			val_arr[0] = val_arr[0]*7;
			val_arr[1] = 'day';
		}

		// : 오늘날짜
		var today = new Date();
		var today_dd = (today.getDate()+1 < 10) ? '0' + (today.getDate()+1) : today.getDate()+1;
		var today_mm = (today.getMonth()+1 < 10) ? '0' + (today.getMonth()+1) : today.getMonth()+1;

		var yy = today.getFullYear();
		var mm = today.getMonth();
		var dd = today.getDate();
		if(val_arr[1]=='year') yy = yy - val_arr[0];
		if(val_arr[1]=='month') mm = mm - val_arr[0];
		if(val_arr[1]=='day') dd = dd - val_arr[0];

		// : 클릭날짜
		var prev_day = new Date(yy, mm, dd);
		var prev_mm = (prev_day.getMonth()+1 < 10) ? '0' + (prev_day.getMonth()+1) : prev_day.getMonth()+1;
		var prev_dd = (prev_day.getDate() < 10) ? '0' + (prev_day.getDate()+1) : prev_day.getDate()+1;

		// : 결과
		var day1 = prev_day.getFullYear()+'-'+(prev_mm)+'-'+prev_dd;
		var day2 = today.getFullYear()+'-'+(today_mm)+'-'+today_dd;

		return [day1, day2];
	}

	this.window_open = function(url, name, w, h) {
		window.open(url, name, "width="+w+",height="+h+",status=yes,scrollbars=yes");
	}

	this.window_open2 = function(url, name, w, h) {
		window.open(url, name, "width="+w+",height="+h+",status=no,scrollbars=no");
	}

	this.parent_close = function(el, c) {
		$(el).closest(c).css({"display":"none"});
	}

	this.print = function() {
		window.print();
	}

	this.url_copy = function(dummy) {
		if(!dummy) {
			var dummy = document.createElement("input");
			var text = location.href;
			document.body.appendChild(dummy);
			dummy.value = text;
		}
		dummy.select();
		document.execCommand("copy");
		if(!dummy) document.body.removeChild(dummy);

		alert("URL이 클립보드에 복사되었습니다"); 
	}

	this.text_copy = function(obj, msg) {
		try{
			var $textarea = document.createElement("textarea");

			// body 요소에 존재해야 복사가 진행됨
			document.body.appendChild($textarea);

			// 복사할 특정 텍스트를 임시의 textarea에 넣어주고 모두 셀렉션 상태
			$textarea.value = obj.value;
			$textarea.select();

			// 복사 후 textarea 지우기
			document.execCommand('copy');
			document.body.removeChild($textarea);
			alert(msg);
		}catch(e){
			alert(e.message);
		}
	}

	this.scrap = function(el, code, no) {
		var tag = el.tagName.toLowerCase();
		$.post(root+"/include/regist.php", "mode=scrap&tag="+tag+"&code="+code+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.interest = function(el, code, no) {
		$.post(root+"/include/regist.php", "mode=interest&code="+code+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.ajax_post = function(el, msg) {
		var no = $(el).attr("no");
		var mode = $(el).attr("mode");
		var url = $(el).attr("url");
		var para = $(el).attr("para");
		para = para ? para : "";

		if(!mode || !url) {
			alert("정상적인 방식으로 이용해주시기 바랍니다.");
			return;
		}

		if(msg && !confirm(msg)) {
			return;
		}

		$.post(url, para+"&ajax=1&mode="+mode+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}


	this.ajax_select_confirm = function(el, fname, msg) {
		var form = document.forms[fname];
		var form_para = $(form).serialize();
		var url = $(el).attr("url");
		var mode = $(el).attr("mode");
		var para = $(el).attr("para");
		var check_code = $(el).attr("check_code");
		var tag = $(el).attr("tag");
		var hname = $(el).attr("hname");

		switch(check_code) {
			case "checkbox":
				var length = $(form).find("[name='"+tag+"']:checked").length;
				if(length<=0) {
					alert("하나이상 선택해주시기 바랍니다.");
					return;
				}
				break;

			case "input":
				var obj;
				$(form).find("[name='"+tag+"']").each(function(){
					if(!$(this).val() && !obj) obj = $(this);
				});
				if(obj) {
					alert(hname+"값을 입력해주시기 바랍니다.");
					obj[0].focus();
					return;
				}
				break;
		}

		if(confirm(msg)) {
			var form = document.forms[fname];
			var form_para = $(form).serialize();
			var url = $(el).attr("url");
			var mode = $(el).attr("mode");
			var para = $(el).attr("para");
			$.post(url, form_para+"&"+para+"&mode="+mode, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}


	this.click_display = function(id, display) {
		$("#"+id).css({"display":display});
	}

	this.click_display2 = function(c, val) {
		var display = $(c).css("display");
		if(!val) val = 'block';
		display = display=='none' ? val : 'none';
		$(c).css({"display":display});
	}


	this.input_text = function(el) {
		$(el).closest("td").find(".dupl-hidden-").val("");
	}

	this.click_block = function(el, id) {
		var display = el.checked ? 'block' : 'none';
		$(id).css({"display":display});
	}



	// : 레이어 가운데로 이동
	this.initLayerPosition = function(id, width, height, border, bcolor, loca){
		if(!id) return false;

		if(!loca) loca = 'middle';
		$(".center_div").each(function(){
			$(this).css("display", "none");
			if(loca=='not') $(this).css("top", "10px");
		});

		bcolor = bcolor ? bcolor : '#158fe7';
		border = border>0 ? border : 0;

		var element_layer = typeof id=='object' ? $(id)[0] : $("#"+id)[0];
		element_layer.style.display = "block";
		if(String(width).indexOf("%")==-1) {
			width = width>0 ? width : $(element_layer)[0].offsetWidth;
			width = String(width);
		}
		borderWidth = border;

		// 위에서 선언한 값들을 실제 element에 넣는다.
		element_layer.style.width = width.indexOf("%")>=0 ? width : width + 'px';
		element_layer.style.border = borderWidth + 'px solid';
		element_layer.style.borderColor = bcolor;

		height = height>0 ? height : element_layer.offsetHeight;

		element_layer.style.maxHeight = height + 'px';
		// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.

		element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
		if(loca=='middle')
			element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
		
	};
	this.initLayerPosition_close = function(id) {
		var element_layer = typeof id=='object' ? $(id)[0] : $("#"+id)[0];
		element_layer.style.display = "none";
	};


	this.ch_photo = function(input, c, func) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$(c).attr('src', e.target.result);
				if(func) eval(func);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}


	this.read_statistics = function() {
		$.post(root+"/include/regist.php", "mode=read_statistics", function(data){
			data = $.parseJSON(data);
		});
	}


	this.ch_page_row = function(el, f) {
		if(f) var form = document.forms[f];
		else var form = document.forms['fsearch'];
		form.page_row.value = el.value;
		form.submit();
	}

	this.click_sort = function(sort, sort_lo) {
		var form = document.forms['fsearch'];
		form.sort.value = sort;
		form.sort_lo.value = sort_lo;
		form.submit();
	}


	this.length_count = function(obj) {
		var str; 
		var str_count = 0; 
		var cut_count = 0; 
		var str_length = obj.value.length; 
		for (k=0;k<str_length;k++) { 
			str = obj.value.charAt(k); 
			if (escape(str).length > 4) { 
				str_count += 2; 
			} else { 
				// (\r\n은 1byte 처리) 
				if (escape(str) == '%0A') { 
				} else { 
					str_count++; 
				} 
			}
		}
		return str_count;
	}


	this.length_counts = function(obj, max_count, lengObj) {
		var str; 
		var str_count = 0; 
		var cut_count = 0; 
		var max_length = max_count; 
		var str_length = obj.value.length; 
		for (k=0;k<str_length;k++) { 
			str = obj.value.charAt(k); 
			if (escape(str).length > 4) { 
				str_count += 2; 
				max_length -= 2; 
			} else { 
				// (\r\n은 1byte 처리) 
				if (escape(str) == '%0A') { 
				} else { 
					str_count++; 
					max_length--; 
				} 
			} 
			if (max_count < str_count) { 
				alert("글자수가 "+ max_count +" byte 이상은 사용불가능합니다"); 
				if (escape(str).length > 4) { 
					str_count -= 2; 
					max_length += 2; 
				} else { 
					str_count--; 
					max_length++; 
				} 
				obj.value = obj.value.substring(0,k); 
				break; 
			} 
		}
		max_length=max_count-max_length;

		if(lengObj)
			$(lengObj).html(max_length);
		else
			$(obj).closest("div").find(".span_bytes").html(max_length);
	}

	this.doBlink = function() {
		if($(".service-blink-").length>0) {
			if($('.service-blink-').css('visibility')=='hidden') $('.service-blink-').css('visibility','visible');
			else $('.service-blink-').css('visibility','hidden');
		}
	};

	this.ajax_page_num = {};
	this.ajax_page_num_anchor = {};
	this.ajax_paging = function(el, page_var, page, num, code) {
		switch(code) {
			case "map":
				map_load(page);
			break;

			default:
				var code_txt = code;
				var code_arr = code.split("#");
				code = code_arr[0];
				if(this.ajax_page_num[code]) this.ajax_page_num[code]++;
				else this.ajax_page_num[code] = 2;
				if(page<=0) page = this.ajax_page_num[code];
				$.post(root+"/include/regist.php", "mode=get_ajax_paging&page_var="+page_var+"&page="+page+"&num="+num+"&code="+code_txt, function(data){
					data = $.parseJSON(data);
					if(data.js) eval(data.js);
					if(netfu_util.ajax_page_num_anchor[code]) location.hash = netfu_util.ajax_page_num_anchor[code];
				});
			break;
		}
	}

	this.click_tab = function(c) {
		$(c).click(function(){
			var k = $(this).attr("k");
			var parent = $(this).attr("parent");
			var display = $(this).attr("display");
			var index = $(this).index();
			if(k && k.length>0) index = k;
			nf_util.setCookie(c, index);

			$(c).removeClass("on");
			$(this).addClass("on");

			var child_len = $(this).closest(parent).find(c+'child-').length;
			if(child_len>0) {
				$(this).closest(parent).find(c+'child-').css({"display":"none"});
				$(this).closest(parent).find(c+'child-').eq(index).css({"display":"block"});
				if(display) $(this).closest(parent).find(c+'child-').eq(index).css({"display":display});
			} else {
				$(c+'child-').css({"display":"none"});
				$(c+'child-').eq(index).css({"display":"block"});
				if(display) $(c+'child-').eq(index).css({"display":display});
			}
		});
	}

	this.share_sns = function(el, code) {
		var _subject = $(el).attr("txt_");
		var _link = location.href;
		if(!_subject) _subject = document.title;
		var _img = $("meta[property='og\\:image']").attr("content");
		switch(code) {
			case "kakao_talk":
				// 카카오링크 버튼 생성
				Kakao.Link.createDefaultButton({
					container: '#btn_sns_'+code, // HTML에서 작성한 ID값
					objectType: 'feed',
					content: {
					title: _subject, // 보여질 제목
					description: _subject, // 보여질 설명
					imageUrl: _img, // 콘텐츠 URL
					link: {
						mobileWebUrl: _link,
						webUrl: _link
					}
					}
				});
			break;

			case "kakao_story":
				Kakao.Story.share({
					url: _link,
					text: _subject
				});
				break;

			case "facebook":
				window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(_link) ,'sharer_facebook','toolbar=0,status=0,width=626,height=436');
				break;

			case "twitter":
				var url   = "http://twitter.com/share?text="+encodeURIComponent(_subject)+"&url="+escape(_link); 
				window.open(url ,'sharer_twitter','toolbar=0,status=0,width=626,height=436');
				break;

			case "google":
				var url = "https://www.google.co.kr/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&ved=&url=https://plus.google.com/share?url="+_link;
				window.open(url ,'sharer_google','toolbar=0,status=0,width=626,height=436');
				break;

			case "naver_band":
				window.open('http://band.us/plugin/share?body='+encodeURIComponent(_subject+" - "+_link)+'&route='+encodeURIComponent(url), 'share_band', 'width=410, height=540, resizable=no');
				break;

			case "naver_blog":
				window.open('https://share.naver.com/web/shareView?title='+encodeURIComponent(_subject+" - "+_link)+'&url='+encodeURIComponent(_link), 'share_naver_blog', 'width=410, height=540, resizable=no');
				break;
		}
	}
}


var nf_util = new nf_util();




var date_val = new Date();
var datepicker_json = {
	dateFormat: "yy-mm-dd",    /* 날짜 포맷 */ 
	prevText: '이전달',
	nextText: '다음달',
	showButtonPanel: true,    /* 버튼 패널 사용 */ 
	changeMonth: true,        /* 월 선택박스 사용 */ 
	changeYear: true,        /* 년 선택박스 사용 */ 
	showOtherMonths: false,    /* 이전/다음 달 일수 보이기 */ 
	selectOtherMonths: true,    /* 이전/다음 달 일 선택하기 */ 
	yearSuffix: '년',
	closeText: '닫기', 
	currentText: '오늘', 
	showMonthAfterYear: true,        /* 년과 달의 위치 바꾸기 */ 
	/* 한글화 */ 
	monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
	monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
	dayNames : ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
	dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
	dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],
	weekHeader: 'Wk',
	yearRange :"-100:+0",
	firstDay: 0,
	isRTL: false,
	showAnim: 'slideDown',
	onSelect:function(dateText, inst){
	}
};

// : 마감일
var datepicker_json_enddate = $.extend(true, {}, datepicker_json);
datepicker_json_enddate.minDate = date_val;
datepicker_json_enddate.yearRange = date_val.getFullYear()+":"+(date_val.getFullYear()+10);

// : 마감일
var datepicker_json_enddate2 = $.extend(true, {}, datepicker_json);
datepicker_json_enddate2.minDate = new Date('2000-01-01');
datepicker_json_enddate2.yearRange = "2000:"+(date_val.getFullYear()+10);

// : 쿠폰
var datepicker_json_coupon = $.extend(true, {}, datepicker_json);
datepicker_json_coupon.minDate = date_val;
datepicker_json_coupon.yearRange = date_val.getFullYear()+":"+(date_val.getFullYear()+10);


var _editor_use = {};

var sns_kind_arr = {'kakao':'카카오', 'naver':'네이버', 'facebook':'페이스북', 'twitter':'트위터'};

$(function(){

	// : 화살표 스크롤링
	if($(".scroll_nav_").length>0) {
		$(".scroll_nav_").each(function() {
			var obj = $(this);
			var prevObj = $(obj.attr("prev"));
			var nextObj = $(obj.attr("next"));
			var slideObj = obj.find(obj.attr("slide"));
			var this_eq = 0;
			var length = slideObj.length;
			var this_pos = 0;

			var tabs_width = obj.width();
			var end_num = 0;
			var end_width = 0;

			slideObj.each(function(){
				var width = $(this).width();
				var offset = $(this).offset();
				if((offset.left+width)<tabs_width) {
					end_width += offset.left+width;
					end_num++;
				}
			});

			var dfd = slideObj.eq(0).offset();

			prevObj.click(function(){
				if(this_eq>=0) {
					var width = slideObj.eq(this_eq).width(); //선택한 태그의 위치를 반환
					var offset = slideObj.eq(this_eq).offset();
					this_pos -= width;
					if((this_eq-1)<=0) {
						offset.left = 0;
						this_pos = 0;
					}
					obj.animate({scrollLeft : this_pos}, 400);
					if(this_eq>0) {
						this_eq--;
						end_num--;
					}
				}
			});

			nextObj.click(function(){
				if(end_num<length) {
					var width = slideObj.eq(end_num-1).width(); //선택한 태그의 위치를 반환
					this_pos += width;
					obj.animate({scrollLeft : this_pos}, 400);
					this_eq++;
					end_num++;
				}
			});
		});
	}

	if($(".sns_login_button_").length>0) {
		$(".sns_login_button_").click(function(){
			var k = $(this).attr("k");
			switch(k) {
				case "kakao":
					loginWithKakao();
					break;

				case "naver":
					start_naver_login();
					break;

				case "facebook":
					start_facebook_login();
					break;

				case "twitter":
					start_twitter_login();
					break;
			}
		});
	}

	if(location.href.indexOf("/m/page/view.php")>=0) {
		
		$(".news_body").find("iframe").css({"max-width":($( window ).width()-30)+"px"});
		$(".news_body").find("img").css({"max-width":($( window ).width()-30)+"px"});

	}

	nf_util.editor_start();

	//setInterval("nf_util.doBlink()", 500);

	if($('.service-blink-').length>0) {
		var colour;
		setInterval(function(){
			colour = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
			$('.service-blink-').animate({
				color: colour
			});
			//alert(colour);
		}, 500);
	}

	if($(".ui-draggable").length>0) {
		$(".ui-draggable").each(function(){
			$(this).draggable();
		});
	}

	// : 날짜
	if($(".datepicker_inp").length>0) {
		$(".datepicker_inp").datepicker(datepicker_json).keyup(function(e) {
			if(e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
	}

	if($( ".datepicker_inp_enddate" ).length>0) {
		$( ".datepicker_inp_enddate" ).datepicker(datepicker_json_enddate).keyup(function(e) {
			if(e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
	}

	if($( ".datepicker_inp_enddate2" ).length>0) {
		$( ".datepicker_inp_enddate2" ).datepicker(datepicker_json_enddate2).keyup(function(e) {
			if(e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
	}

	if($( ".datepicker_json_coupon" ).length>0) {
		$( ".datepicker_json_coupon" ).datepicker(datepicker_json_coupon).keyup(function(e) {
			if(e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
	}


	if($('.set_day')[0]) {
		$('.set_day').click(function() {
			$(this).closest("ol").find("button").removeClass("on");
			$(this).parent().addClass("on");
			nf_util.put_date($(this)[0]);
		});
	}


	if($(".slide_nav_menu1_")[0]) {
		$(".slide_nav_menu1_").hover(function(){
			$(".slide_nav").find(".sub_nav").css({"display":"none"});
			$(this).find(".sub_nav").css({"display":"block"});
		}, function(){
		});
	}


	if($(".hit_new_tab")[0]) {
		$(".hit_new_tab").find("button").click(function(){
			var k = $(this).index();
			$(".hit_new_tab").find("button").removeClass("on");
			$(this).addClass("on");
			$(".hit_new_ul").css({"display":"none"});
			$(".hit_new_ul").eq(k).css({"display":"block"});
		});
	}

	if($(".hit_tab_click")[0]) {
		$(".hit_tab_click").find("button").click(function(){
			var k = $(this).index();
			$(".hit_tab_click").find("button").removeClass("on");
			$(this).addClass("on");
			$(".hit_box_").css({"display":"none"});
			$(".hit_box_").eq(k).css({"display":"block"});
		});
	}


	if($(".login_sns_btn_").length>0) {
		$(".login_sns_btn_").click(function(){
			$(".login_sns_btn_").removeClass("on");
			$(this).addClass("on");
			var index = $(this).index();
			$(".login_sns_tab").css({"display":"none"});
			$(".login_sns_tab").eq(index).css({"display":"block"});
		});
	}

	if($(".view_contents").length>0) {
		$(".view_contents").find("img").each(function(){
			$(this).css({"width":"auto", "height":"auto"});
		});
	}

	if($(".not_allow").length>0) {
		$(".not_allow").find("textarea").click(function(){
			var msg = !is_member ? '로그인후 댓글을 작성할수 있습니다.' : "댓글을 입력할 권한이 없습니다.";
			alert(msg);
			if(!is_member) location.href = "/include/login.php";
		});
		$(".not_allow").find("textarea").keyup(function(){
			var msg = !is_member ? '로그인후 댓글을 작성할수 있습니다.' : "댓글을 입력할 권한이 없습니다.";
			alert(msg);
			if(!is_member) location.href = "/include/login.php";
		});
	}

	// iframe바깥에 div감싸기
	//if($(".view_contents").length>0 && location.href.indexOf("/m/")>=0) {
	if($(".view_contents").length>0) {
		var html = $(".view_contents").html().replace(/<iframe/gi, '<div class="video-wrap"><iframe');
		var html = html.replace(/<\/iframe>/gi, '</iframe></div>');
		$(".view_contents").html(html);
	}


	if($(".color_picker").length>0) {
		nf_util.spectrum_func(".color_picker");
	}


	if($(".drag-skin-").length>0) {
		$(".drag-skin-").draggable();
	}

	nf_util.favorite();
});