var nf_auth = function() {

// : 실명인증방법
	this.auth_func = function(val) {
		
		/*var form = document.forms['flogin'];
		if(!val) var val = $(form).find("[name='auth_kind']:checked").val();
		if(!val) {
			alert("인증종류를 선택해주시기 바랍니다.");
			return;
		}*/

		var val_txt = '';
		if(typeof val=='object') val_txt = val.value;
		else val_txt = val;

		switch(val_txt) {
			case 'ipin':
				this.ipin_func();
				break;
			case 'sms':
				this.sms_func();
				break;
			case 'bbaton':
				this.bbaton_sms_func();
				break;
			case 'bbaton_nick':
				this.bbaton_nick_func();
				break;
			default:
				this.etc_func(val);
				break;
		}
		return true;
	}

// : 아이핀 인증창
	this.ipin_func = function() {
		var form = document.forms['fauth_api'];
		var action = form.action_ipin.value;

		window.name ="Parent_window";
		window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.fauth_api.target = "popupIPIN2";
		document.fauth_api.action = action;
		document.fauth_api.submit();
	}

// : SMS 인증
	this.sms_func = function() {
		var form = document.forms['fauth_api'];
		var action = form.action_sms.value;

		window.name ="Parent_window";
		window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.fauth_api.action = action;
		document.fauth_api.target = "popupChk";
		document.fauth_api.submit();
	}

	this.bbaton_nick_func = function() {
		window.name = "parent_auth";
		window.open('/plugin/auth/bbaton/auth.php', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
	}

	this.bbaton_sms_func = function() {
		window.open('/plugin/auth/bbaton/auth.php', 'popupChk', 'width=430, height=700, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
	}

	this.etc_func = function(el) {
		
	}

// : 가입체크
	this.regist_check = function() {
		setTimeout(function(){
			alert("이미 가입한 회원입니다.");
		});
	}

// : 인증성공후 이동페이지
	this.move_page = function() {
		try{
		var form = document.forms['form_auth'];
		if(form.param_r1.value=='member_join') {
			setTimeout(function(){
				alert("본인인증에 성공하였습니다.\n회원가입 종류에 맞게 버튼을 누르셔서 다음을 진행해주시기 바랍니다.");
			},100);
		} else {
			if(location.href.indexOf("auth.php")>=0) {
				var prev_url = $("[name='prev_url']").val();
				if(prev_url && prev_url.indexOf(document.domain)>=0) {
					location.replace(decodeURIComponent(prev_url));
				} else {
					location.href = root;
				}
			}
		}
		}catch(e){
			alert(e.message);
		}
	}
}

var nf_auth = new nf_auth();
