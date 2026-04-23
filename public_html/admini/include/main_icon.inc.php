<script type="text/javascript">
var open_icon = function(el, no) {
	var form = document.forms['fservice'];
	$(".layer_pop.popup_box-").css({"display":"none"});
	if(no!="none") {
		$(".layer_pop.main-icon_service-").css({"display":"block"});
		if(no) {
			form.no.value = no;
			form.name.value = $(el).closest("tr").find("td.name--").html();
			form.link.value = $(el).closest("tr").find("td.link--").html();
		}
	}
}
</script>
<form name="fservice" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="icon_write" />
<input type="hidden" name="code" value="main_icon" />
<input type="hidden" name="no" value="" />
<div class="layer_pop conbox popup_box- main-icon_service-" style="display:none;">
	<div class="h6wrap">
		<h6>메인 중앙 아이콘 등록</h6>
		<button type="button" onClick="open_icon(this, 'none')" class="close">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="15%">
		</colgroup>
		<tbody>
			<tr>
				<th>아이콘 이미지</th>
				<td><input type="file" name="file"><span>* 확장자 *.jpg, *.gif, *.png, *.swf 만 가능</span></td>
			</tr>
			<tr>
				<th>아이콘 이름</th>
				<td><input class="long100" type="text" name="name" class=""></td>
			</tr>
			<tr>
				<th>링크 연결주소</th>
				<td><input class="long100" type="text" name="link" class=""></td>
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button class="blue">저장하기</button>
		<button type="button" onClick="open_icon(this, 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!--//열람서비스기간 팝업-->