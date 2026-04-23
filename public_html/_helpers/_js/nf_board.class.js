var nf_board = function() {

	this.comment_comment_no = 0;

	this.auth = function(el, code, bo_table, no) {
		$.post(root+"/board/regist.php", "mode=auth_check&code="+code+"&bo_table="+bo_table+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.is_good = function(el, bo_table, no, code) {
		$.post(root+"/board/regist.php", "mode=click_board_is_good&bo_table="+bo_table+"&no="+no+"&code="+code, function(data) {
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.good = function(el, bo_table, no, code) {
		$.post(root+"/board/regist.php", "mode=click_board_good&bo_table="+bo_table+"&no="+no+"&code="+code, function(data) {
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.click_comment_comment = function(el, no) {
		this.comment_comment_no = no;
		$(".comment_li-").find(".reply_con_write").css({"display":"none"});
		$("#comment_write-"+no).css({"display":"block"});
	}

	this.comment_comment_insert = function(el) {
		var form = document.forms['fcomment_comment'];
		var obj = $(el).closest(".comment_li-").find(".reply_con_write");
		form.code.value = "comment_comment_insert";
		form.comment_id.value = this.comment_comment_no;
		form.wr_name.value = obj.find(".wr_name-").val();
		form.wr_password.value = obj.find(".wr_password-").val();
		form.rand_number.value = obj.find(".rand_number-").val();
		form.wr_name.value = obj.find(".wr_name-").val();
		form.wr_content.value = obj.find(".wr_content-").val();
		nf_util.ajax_submit(form);
	}

	this.comment_comment_view = function(el, bo_table, no) {
		$.post(root+"/board/regist.php", "mode=comment_comment_view&bo_table="+bo_table+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.click_delete = function(el, bo_table, no) {
		if(confirm("삭제하시겠습니까?")) {
			$.post(root+"/board/regist.php", "mode=delete_board&bo_table="+bo_table+"&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.click_report = function(el, bo_table, no) {
		if(confirm("신고하시겠습니까?")) {
			$.post(root+"/board/regist.php", "mode=report_board&bo_table="+bo_table+"&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
				if(data.js) eval(data.js);
			});
		}
	}

	this.add_attach = function(el, bo_table) {
		var cnt = $(el).closest("form").find(".attach-item-").length;
		$.post(root+"/board/regist.php", "mode=add_attach&bo_table="+bo_table+"&cnt="+cnt, function(data) {
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}

	this.download = function(el, no) {
		if(confirm("다운로드하시겠습니까?")) {
			$.get(root+"/board/regist.php", "mode=file_download_check&no="+no, function(data){
				data = $.parseJSON(data);
				if(data.msg) alert(data.msg);
				if(data.move) location.href = data.move;
			});
		}
	}

	this.attach_item_remove = function(el) {
		var length = $(el).closest("form").find(".attach-item-").length;
		if(length>1) {
			$(el).closest(".attach-item-").remove();
		} else {
			$(el).closest(".attach-item-").find(".attach-info-").remove();
			$(el).closest(".attach-item-").find("[name=\'attach_hidd[]\']").val("");
		}
	}

	this.file_delete = function(el, no) {
		if(confirm("파일을 삭제하시겠습니까?")) {
			if(!no) {
				nf_board.attach_item_remove(el);
			} else {
				$.post(root+"/board/regist.php", "mode=file_download_delete&no="+no, function(data){
					data = $.parseJSON(data);
					if(data.msg) alert(data.msg);
					if(data.move) location.href = data.move;
					if(data.js) eval(data.js);
				});
			}
		}
	}
}

var nf_board = new nf_board();