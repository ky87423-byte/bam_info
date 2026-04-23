var nf_member = function() {

	this.check_agree = function(el) {
		var form = document.forms['fagree'];
		$(form).find("._chk").prop("checked", el.checked);
	}

	this.member_regist_move = function(code) {
		var form = document.forms['fagree'];
		var msg = "";
		$(form).find("._chk").each(function(){
			if(!$(this)[0].checked && !msg) {
				msg = $(this).attr("txt");
			}
		});
		if(msg) {
			alert(msg+"에 체크해주셔야합니다.");
			return;
		} else {
			form.code.value = code;
			form.submit();
		}
	}

	this.check_nick = function(id) {
		var val = $("#"+id).val();
		if(!val) {
			alert("닉네임을 입력해주시기 바랍니다.");
			$("#"+id)[0].focus();
			return;
		} else {
			var form = document.forms['fmember'];
			var para = $(form).serialize();
			$.post(root+"/include/regist.php", para+"&mode=check_nick&val="+encodeURIComponent(val), function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.check_uid = function(id) {
		var val = $("#"+id).val();
		if(!val) {
			alert("아이디를 입력해주시기 바랍니다.");
			$("#"+id)[0].focus();
			return;
		} else {
			$.post(root+"/include/regist.php", "mode=check_uid&val="+encodeURIComponent(val), function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.delete_my_photo = function(no) {
		no = no ? no : '';
		if(confirm("사진을 삭제하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=delete_my_photo&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
			});
		}
	}


	this.delete_my_logo = function(no) {
		no = no ? no : '';
		if(confirm("로고를 삭제하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=delete_my_logo&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
			});
		}
	}


	this.delete_my_biz_attach = function(no) {
		no = no ? no : '';
		if(confirm("사업자등록증 첨부파일을 삭제하시겠습니까?")) {
			$.post(root+"/include/regist.php", "mode=delete_my_biz_attach&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
			});
		}
	}
}

var nf_member = new nf_member();