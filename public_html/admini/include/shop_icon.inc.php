<script type="text/javascript">
var open_icon = function(el, no) {
	var form = document.forms['fservice'];
	$(".layer_pop.popup_box-").css({"display":"none"});
	if(no!="none") {
		$(".layer_pop.icon_box-").css({"display":"block"});
		if(no) {
			form.no.value = no;
			form.name.value = $(el).closest("tr").find("td.name--").html();
		}
	}
}
</script>
<form name="fservice" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="icon_write" />
<input type="hidden" name="code" value="shop_icon" />
<input type="hidden" name="no" value="" />
<div class="layer_pop conbox popup_box- icon_box-" style="display:none;">
	<div class="h6wrap">
		<h6>상품 아이콘 등록</h6>
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
		</tbody>
	</table>
	<div class="pop_btn">
		<button class="blue">저장하기</button>
		<button type="button" onClick="open_icon(this, 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!--//열람서비스기간 팝업-->