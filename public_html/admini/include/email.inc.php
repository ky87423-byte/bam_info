<script type="text/javascript">
var select_email_box = function(el, code, display) {
	var femail = document.forms['femail'];
	femail.code.value = code;
	var code_arr = code.split("email-");
	if(code!='email-') code = 'email-';
	var code2 = code_arr[1];
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	if(display=='none') {
		obj.css({"display":display});
		return;
	}

	var form = document.forms['flist'];

	var len = $(form).find("[name='chk[]']:checked").length;
	if(len<=0) {
		alert("하나 이상 선택해주시기 바랍니다.");
		return;
	}

	var para = $(form).serialize(form);
	$.post("../regist.php", para+"&mode=select_email_box&code2="+code2, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 메일보내기 팝업 -->
<form name="femail" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="member_email_send" />
<input type="hidden" name="code" value="" />
<div class="layer_pop conbox email- popup_box-" style="width:850px;display:none;">
	<div class="h6wrap">
		<h6>메일보내기 <em style="font-weight:normal;"><em class="name--"></em><em class="mb_id--"></em></em></h6>
		<button type="button" class="close" onClick="$(this).closest('.email-').css({'display':'none'})">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="13%">
		</colgroup>
		<tbody>
			<tr>
				<th>제목</th>
				<td><input class="long100" type="text" name="subject" hname="제목" needed></td>
			</tr>
			<tr>
				<th>받는사람</th>
				<td>
					<span>여러명 발송시 콤마(,) 로 구분</span>
					<textarea name="receive_mail" hname="받는사람" needed style="height:60px;"></textarea>
					<textarea name="receive_no" style="display:none;"></textarea>
				</td>
			</tr>
			<tr>
				<th>내용</th>	
				<td><span>※ 메일 기본 내용은 <a href="" class="blue">[디자인관리] - MAIL스킨관리 - 회원 메일링</a> 에서 설정 가능합니다.</span><br><textarea type="editor" name="email_content" style="height:200px;"><?php echo stripslashes($mail_content);?></textarea></td>
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="submit" class="blue">메일보내기</button>
		<button type="button" onClick="$(this).closest('.email-').css({'display':'none'})" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!-- //메일보내기 팝업 -->