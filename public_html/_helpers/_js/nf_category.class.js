var nf_category = function() {

	this.depth_pos = 0;
	this.var_category = {};
	this.var_parent = []; // : 버튼형 카테고리 부모값


	this.multi_category_cnt = 5; // : 5개까지 선택가능

	this.insert = function(el, no) {
		var cate = $(el).attr("cate");
		var depth = $(el).closest("tr").attr("depth");
		var pno = $(el).closest(".category_in-").attr("pno");
		no = no ? no : "";
		pno = pno ? pno : "";

		var allow = true;
		var hname = "";
		var needed = "";
		var obj = "";
		$(el).closest("tr").find("input").each(function(){
			if(!$(this).val() && allow) {
				allow = false;
				obj = $(this)[0];
				needed = $(this).attr("needed");
			}
		});

		if(!allow && needed!==null) {
			obj.focus();
		}

		var obj = $(el).closest("tr").clone(true);
		$(".put_category_form").html("");
		$(".put_category_form").append(obj);

		thisObj = el;
		var form = document.forms['fwrite'];
		form.action = root+"/admini/regist.php";
		form.no.value = no;
		form.pno.value = pno;
		form.mode.value = "category_insert";

		var not_insert = false;
		switch(cate) {
			case "one":
				$(el).closest('tr').find("input[type=text]").each(function(){
					var hname = $(this).attr("hname");
					if(hname && !$(this).val() && !not_insert) {
						alert(hname+" 정보를 입력해주시기 바랍니다.");
						not_insert = true;
					}
				});
			break;

			default:
				if(form.subject && !form.subject.value) {
					alert("항목을 입력해주시기 바랍니다.");
					return false;
				}
			break;
		}

		if(not_insert) return false;

		nf_util.ajax_submit(form);
		form.action = "";
		return false;
	}


	this.view = function(el, fcode) {
		var form = document.forms['fwrite'];
		var val = el.checked ? 1 : 0;
		var para_fcode = fcode ? '&fcode='+fcode : '';
		$.post(root+"/admini/regist.php", "ajax=1&mode=category_view&no="+el.value+"&val="+val+para_fcode+"&wr_type="+form.wr_type.value, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}

	// : 결과값
	this.search_result_text = function() {
		$(".search-result-text-").html("");
		for(a in nf_category.var_category) {
			for(b in nf_category.var_category[a]) {
				for(c in nf_category.var_category[a][b]) {
					var cate_cnt = nf_category.var_category[a][b][c].length;
					var cate_text = nf_category.var_category[a][b][c].join(">");
					var cate_value = b+','+c;
					if(cate_text.indexOf(" 전체")>=0) {
						if(cate_cnt==2) cate_text = nf_category.var_category[a][b][c][0]+' 전체';
						if(cate_cnt==3) cate_text = nf_category.var_category[a][b][c][0]+'>'+nf_category.var_category[a][b][c][1]+' 전체';
						cate_value = b;
					}
					$(".search-result-text-").append('<li>'+cate_text+'<input type="hidden" name="'+a+'_multi[]" value="'+cate_value+'" code="'+a+'" pno="'+b+'" no="'+c+'" /><input type="hidden" name="'+a+'_text_multi[]" value="'+cate_text+'" /><button><i class="axi axi-close" onClick="nf_category.btn_category_delete(this)"></i></button></li>');
				}
			}
		}
	}

	// : 페이지 로드시 on시키기 
	this.search_put_on = function(form) {
		var get_json = {};
		var para_arr = location.href.split("?");
		var get_arr = para_arr[1].split("&");

		var cnt = get_arr.length;
		for(var i=0; i<cnt; i++) {
			var get_var_arr = get_arr[i].split("=");
			var get_name = decodeURIComponent(get_var_arr[0]);
			var get_name_arr = get_name.split("_multi[]");

			if(!get_json[get_name]) get_json[get_name] = [];
			get_json[get_name].push(decodeURIComponent(get_var_arr[1]));
		}

		for(get_name in get_json) {
			if(get_name && get_name.indexOf("_multi[]")>=0 && get_name.indexOf("_text_multi[]")==-1) {
				var get_name_arr = get_name.split("_multi[]");
				var cnt = get_json[get_name].length;
				for(i=0; i<cnt; i++) {
					var cate_text = get_json[get_name_arr[0]+'_text_multi[]'][i];
					var cate_text_arr = cate_text.split(">");
					var cate_value = get_json[get_name][i];
					var cate_value_arr = cate_value.split(",");
					var cate_value_arr_cnt = cate_value_arr.length;
					var pno_arr = [];
					for(j=0; j<cate_value_arr_cnt-1; j++) pno_arr[j] = cate_value_arr[j];
					var pno = pno_arr.join(",");
					var no = cate_value_arr[cate_value_arr.length-1];

					if(!nf_category.var_category[get_name_arr[0]]) nf_category.var_category[get_name_arr[0]] = {};
					if(!nf_category.var_category[get_name_arr[0]][pno]) nf_category.var_category[get_name_arr[0]][pno] = {};
					if(!nf_category.var_category[get_name_arr[0]][pno][no]) nf_category.var_category[get_name_arr[0]][pno][no] = [];
					nf_category.var_category[get_name_arr[0]][pno][no] = cate_text_arr;
					$(".search-result-text-").append('<li>'+cate_text+'<input type="hidden" name="'+get_name_arr[0]+'_multi[]" value="'+cate_value+'" code="'+get_name_arr[0]+'" pno="'+pno+'" no="'+no+'" /><input type="hidden" name="'+get_name_arr[0]+'_text_multi[]" value="'+cate_text+'" /><button><i class="axi axi-close" onClick="nf_category.btn_category_delete(this)"></i></button></li>');
				}
			}
		}
	}

	this.btn_category_reset = function(el) {
		nf_category.var_category = {};
		$(".search-result-text-").html("");

		var form = el.form;
		$(form).find("[type=checkbox]").prop("checked", false);
		$(form).find("[type=radio]").prop("checked", false);
		$(form).find("[type=text]").val("");
		$(form).find("select").each(function(){
			$(this).find("option").eq(0).prop("selected", true);
		});
	}

	this.btn_category_delete = function(el) {
		var thisUl;
		var obj = $(el).closest("li").find("[type=hidden]");
		var code = obj.attr("code");
		var pno = obj.attr("pno");
		var no = obj.attr("no");

		delete nf_category.var_category[code][pno][no];
		$(el).closest("li").remove();

		$(".btn_category-[code='"+code+"']").find("ul").each(function(){
			var display = $(this).css("display");
			if(display=='block') thisUl = $(this);
		});

		nf_category.put_var_category_on(code, thisUl);
	}

	this.btn_category_cnt = function(el) {
		var get_code = $(el).closest(".btn_category-").attr("code");
		var get_txt = $(el).closest(".btn_category-").attr("txt");
		var cnt = 0;
		if(nf_category.var_category[get_code]) {
			for(a in nf_category.var_category[get_code]) {
				for(b in nf_category.var_category[get_code][a]) {
					cnt++;
				}
			}
		}
		if(cnt>=nf_category.multi_category_cnt) {
			alert(get_txt+"(은)는 "+nf_category.multi_category_cnt+"개까지 선택 가능합니다.");
			return false;
		}
		return true;
	}

	this.put_var_category = function(el, no) {
		var get_code = $(el).closest(".btn_category-").attr("code");
		var index = $(el).closest("ul").index();
		var text_arr = [];
		$(el).closest(".btn_category-").find("ul").find("li.on").each(function(i){
			if(i<index) {
				text_arr[i] = $(this).text();
			}
		});
		text_arr.push($(el).text());
		var var_parent_txt = nf_category.var_parent.join(",");
		if(!nf_category.var_category[get_code]) nf_category.var_category[get_code] = {};
		if(!nf_category.var_category[get_code][var_parent_txt]) nf_category.var_category[get_code][var_parent_txt] = {};

		// : 개수체크
		if(!nf_category.var_category[get_code][var_parent_txt][no]) {
			var result = nf_category.btn_category_cnt(el);
			if(!result) return false;
		}

		if(nf_category.var_category[get_code][var_parent_txt][no]) delete nf_category.var_category[get_code][var_parent_txt][no];
		else {
			if($(el).text().indexOf(" 전체")>=0) {
				for(a in nf_category.var_category[get_code]) {
					if(a.indexOf(var_parent_txt)>=0) {
						for(b in nf_category.var_category[get_code][a])
							delete nf_category.var_category[get_code][a][b];
					}
				}
			}
			nf_category.var_category[get_code][var_parent_txt][no] = text_arr;
		}
	}

	this.put_var_category_on = function(get_code, obj) {
		if(typeof get_code=='object') get_code = $(get_code).closest(".btn_category-").attr("code");
		var var_parent_txt = nf_category.var_parent.join(",");
		if(obj && obj.find("li").length>0) {
			obj.find("li").removeClass("on");
			obj.find("li").each(function() {
				var no = $(this).find("button").attr("no");
				if(nf_category.var_category[get_code] && nf_category.var_category[get_code][var_parent_txt] && nf_category.var_category[get_code][var_parent_txt][no]) {
					$(this).addClass("on");
				}
			});
		}
	}

	this.btn_category_all_check = function(el) {
		var get_code = $(el).closest(".btn_category-").attr("code");
		var index = $(el).closest("ul").index();
		var var_parent_txt = nf_category.var_parent.join(",");

		if(nf_category.var_category[get_code]) {
			var gu = nf_category.var_category[get_code][nf_category.var_parent[0]];
			var dong = nf_category.var_category[get_code][nf_category.var_parent[0]+','+nf_category.var_parent[1]];
			for(a in gu) {
				var gu_text = gu[a].join(">");
				if(gu_text.indexOf(" 전체")>=0) {
					alert(gu_text+"를 선택하셨으므로 선택할 수 없습니다.");
					return false;
				}
			}
			for(a in dong) {
				var dong_text = dong[a].join(">");
				if(dong_text.indexOf(" 전체")>=0) {
					alert(dong_text+"를 선택하셨으므로 선택할 수 없습니다.");
					return false;
				}
			}
		}
		return true;
	}

	this.btn_category = function(el, num) {
		var no = $(el).attr("no");
		var index = $(el).closest("ul").index();
		var ul_cnt = $(el).closest(".btn_category-").find("ul").length;
		var get_code = $(el).closest(".btn_category-").attr("code");

		if(!num) {
			if(index==ul_cnt-2 && nf_category.var_parent[index-1]!=no) nf_category.var_parent.pop();
			var result = nf_category.btn_category_all_check(el);
			if(result) {
				nf_category.put_var_category(el, no);
				nf_category.put_var_category_on(el, $(el).closest("ul"));
				$(el).closest(".btn_category-").find("ul").eq(index+1).css({"display":"none"});

				nf_category.search_result_text();
			}
			return;
		}

		if(num===1) nf_category.var_parent = [];
		nf_category.var_parent[num-1] = no;

		var ul_cnt = $(el).closest(".btn_category-").find("ul").length;

		$(el).closest("ul").find("li").removeClass("on");
		$(el).closest("li").addClass("on");

		$(el).closest(".btn_category-").find("ul").each(function(i){
			$(this).css({"display":"none"});
			if(num>i) $(this).css({"display":"block"});
		});

		$.post("/include/regist.php", "mode=btn_category&wr_type="+get_code+"&code="+el.form.code.value+"&no="+no+"&num="+num+"&ul_cnt="+ul_cnt, function(data){
			data = $.parseJSON(data);
			if(data.js) eval(data.js);
		});
	}

	// : el - 내객체, num - 몇번째, depth - 몇차배열
	this.ch_category = function(el, num) {
		var wr_type = $(el).attr("wr_type");
		wr_type = wr_type ? wr_type : "";
		var array_depth = el.name.split("[");
		var obj = $(el.form).find("[name='"+el.name+"']");
		var first_txt = obj.eq(num).find("option").eq(0).text();
		obj.each(function(i){
			var txt0 = $(this).find("option").eq(0).text();
			if(i>num) $(this).html('<option value="">'+txt0+'</option>');
		});

		var no = el.value;
		if($(el).find("option:selected").attr("no")) no = $(el).find("option:selected").attr("no");

		$.post(root+"/include/regist.php", "mode=get_category_list&no="+no+"&wr_type="+wr_type, function(data) {
			data = $.parseJSON(data);
			if(data.js) eval(data.js);
		});
	}


	this.insert_tr = function(el) {
		var form = document.forms['fwrite'];
		var index = parseInt($(el).closest("div.category_in-").attr("index"));
		var pno = parseInt($(el).closest("div.category_in-").attr("pno"));
		if(nf_category.depth_pos<index) {
			alert((index)+"차 카테고리를 먼저 선택해주시기 바랍니다.");
			return;
		}

		$(el).closest(".category_in-").find(".add_form_view").css({"display":"table-row"});
		$(el).closest(".category_in-").find(".add_form_view").find("[type=text]")[0].focus();
	}


	this.text_click = function(el) {
		var form = document.forms['fwrite'];
		var length = $(".category_in-").length;
		var index = parseInt($(el).closest(".category_in-").attr("index"));

		for(var i=(index); i<length; i++)
			$(".category_in-").eq(i).find(".cate_list").find("tr").removeClass("on");
		$(el).closest("tr").addClass("on");

		if((index+1)===length) {
			$.post(root+"/admini/regist.php", "mode=get_next_category&depth=end&wr_type="+form.wr_type.value, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
			return;
		}

		var no = $(el).closest("tr").attr("no");
		no = no ? no : "";

		form.pno.value = no;
		form.mode.value = "get_next_category";
		var para = $(form).serialize();
		$.post(root+"/admini/regist.php", para, function(data) {
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}


	this.ch_rank = function(el, code) {
		var form = document.forms['fwrite'];
		var obj = $(el).closest(".category_in-").find("tr.on");
		if(!obj[0]) {
			alert("순위를 변경할 항목을 선택해주시기 바랍니다.");
			return;
		}
		var tbodyObj = $(el).closest(".category_in-").find(".cate_list");
		var clone_tr = obj.clone();
		var no = obj.attr("no");
		no = no ? no : "";
		$.post(root+"/admini/regist.php", "mode=ch_rank_category&no="+no+"&code="+code+"&wr_type="+form.wr_type.value, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.click_checkbox_put = function(el, c) {
		var c_len = c.length;
		for(var i=0; i<c_len; i++) {
			var check_text = [];
			var get_name = c[i].substr(5);
			$(el).closest(".popup_box-").find("[name='"+get_name+"[]']:checked").each(function(i){
				check_text[i] = $(this).closest("label").text();
			});
			$(c[i]).html(check_text.join(", "));
		}
		$(el).closest(".popup_box-").css({"display":"none"});
	}
}

var nf_category = new nf_category();