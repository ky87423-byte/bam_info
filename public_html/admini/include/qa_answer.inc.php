<?php
// : qa_answer.inc.php로 include함
?>
<script type="text/javascript">
var open_qna_box = function(el, no) {
	var form = document.forms['fguide_write'];
	var get_code = $(el).attr("code");
	form.code.value = get_code;
	if(no!='none') display = 'block';
	else display = no;
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox.guide-");
	if(display=='none') {
		obj.css({"display":display});
		return;
	}

	$.post("../regist.php", "&mode=open_qna_box&no="+no+"&code="+get_code, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!--답변레이아웃-->
<style type="text/css">
.answer_intable th {background:#f2f2f2 !important}
.answer_intable th, .answer_intable td {padding:5px !important;}
</style>
<form name="fguide_write" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="qna_answer_write" />
<input type="hidden" name="no" value="" />
<input type="hidden" name="code" value="" />
<div class="layer_pop conbox popup_box- guide-" style="display:none;">
	<div class="h6wrap">
		<h6>상품문의 답변</h6>
		<button type="button" class="close" onClick="open_qna_box(this, 'none')">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="17%">
			<col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>업체정보</th>
				<td>
					<span class="img-"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" style="width:80px;height:80px; float:left; margin-right:20px;"></span>
					<table class="answer_intable bt" style="width:calc(100% - 110px); float:left;">
						<colgroup>
							<col style="width:15%">
							<col style="">
						</colgroup>
						<tr>
							<th>업체명</th>
							<td class="shop-company--"><?php echo $nf_util->get_text($shop_row['wr_company']);?></td>
						</tr>
						<tr>
							<th>타이틀</th>
							<td class="shop-subject--"><?php echo $nf_util->get_text($shop_row['wr_subject']);?></td>
						</tr>
						<tr>
							<th>아이디</th>
							<td class="shop-id--"><?php echo $nf_util->get_text($shop_row['wr_id']);?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<th>작성자 이름</th>
				<td class="guide-name--"><?php echo $nf_util->get_text($mem_row['mb_name']);?></td>
			</tr>
			<tr>
				<th>작성자 이메일</th>
				<td class="guide-email--"><?php echo $nf_util->get_text($mem_row['mb_email']);?></td>
			</tr>
			<tr>
				<th>작성자 제목</th>
				<td><input type="text" class="long100" name="subject" value="<?php echo $nf_util->get_html($guide_row['subject']);?>"></td>
			</tr>
			<tr>
				<th>작성자 내용</th>
				<td><textarea type="editor" name="guide_content" style="width:100%;height:150px;"></textarea></td>
			</tr>
			<tr>
				<th>답변내용</th>
				<td><textarea type="editor" name="guide_a_content" style="width:100%;height:150px;"></textarea></td>
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="submit" class="blue">등록하기</button>
		<button type="button" class="gray" onClick="open_qna_box(this, 'none')">창닫기</button>
	</div>
</div>
</form>