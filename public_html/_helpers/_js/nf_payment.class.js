var nf_payment =  function() {

	this.submit = function() {
		var form = document.forms['fpayment'];

		var service_len = nf_payment.check_service();
		if(!service_len) {
			alert("서비스를 하나이상 선택해주시기 바랍니다.");
			return false;
		}

		if(form.code.value=='resume' && $("[name='service[resume][0_0]']:checked").length>0) {
			var len = $("[name='service_icon[resume][0_0][]']:checked").length;
			if(len>3) {
				alert("3개까지 선택 가능합니다.");
				return false;
			}
		}

		var len = $(form).find("[name='pay_methods']:checked").length;
		if(len<=0) {
			alert("결제방법을 선택해주시기 바랍니다.");
			$(form).find("[name='pay_methods']").eq(0).focus();
			return false;
		}
		if(form.pay_methods.value=='bank' && !form.depositor.value) {
			alert("입금자명을 입력해주시기 바랍니다.");
			form.depositor.focus();
			return false;
		}

		nf_util.ajax_submit(form);
		return false;
	}

	this.check_service = function() {
		var form = document.forms['fpayment'];
		var len = $(form).find(".service_click-").length;
		var len2 = $(form).find(".service_click-:checked").length;
		if(len>0) {
			if(len2<=0) {
				return false;
			}
		}
		return true;
	}

	this.click_pay_methods = function(el) {
		var display = el.value=='bank' ? 'table-row' : 'none';
		$(".bank-tr-").css({"display":display});
	}

	this.click_tax = function(el) {
		$(".tax-c-").each(function(){
			var tag = $(this)[0].tagName;
			var display_block = (tag=='TR') ? 'table-row' : 'block';
			var display = $(this).css("display")=='none' ? display_block : 'none';
			$(this).css({"display":display});
		});
	}

	this.tax_type = function(el) {
		$("tr.tax-tr-").css({"display":"none"});
		$("tr.tax-tr-").eq(el.value-1).css({"display":"table-row"});
	}

	this.click_service_func = function() {
		var form = document.forms['fpayment'];
		var para = $(form).serialize();
		$.post(root+"/include/regist.php", para+"&mode=click_service", function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}


	this.click_service = function() {
		var form = document.forms['fpayment'];
		$(".service_click-").click(function(){
			nf_payment.click_service_func();
		});
	}


	this.click_point = function(name) {
		var form = document.forms['fpayment'];
		var len = $(".service_click-:checked").length;
		if(len<=0) {
			alert("서비스를 하나이상 선택하시기 바랍니다.");
		} else {
			nf_payment.click_service_func();
		}
	}


	this.click_service_child = function(el, k) {
		var obj = $(".service_"+k+"_child-").find("[type=checkbox]");
		if(el.checked) obj.attr("needed", "needed");
		else {
			obj.removeAttr("needed");
			obj.prop("checked", false);
		}
	}


	this.click_option_child = function(el, option) {
		var obj = $(".option_"+option+"_child_rado-").find("[type=radio]");
		if(el.checked) obj.attr("needed", "needed");
		else {
			obj.removeAttr("needed");
			obj.prop("checked", false);
		}
	}

	this.click_service_icon_len = function(el, len) {
		var service_name = el.name;
		var check_len = $("[name='"+service_name+"']:checked").length;
		if(check_len>len) {
			alert(len+"개까지 선택 가능합니다.");
			el.checked = false;
		}
	}
}

var nf_payment = new nf_payment();