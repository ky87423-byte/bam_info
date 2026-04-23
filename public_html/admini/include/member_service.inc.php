<!-- 열람서비스기간 팝업 -->
<form name="fservice" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="member_service_write" />
<input type="hidden" name="mno" value="" />
<div class="layer_pop conbox popup_box- member_service-">
	<div class="h6wrap">
		<h6>서비스 정보</h6>
		<button type="button" onClick="open_box(this, 'member_service-', 'none')" class="close">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="12%">
		</colgroup>
		<tbody>
			<tr>
				<th colspan="2">열람서비스</th>
			</tr>
			<tr>
				<th>서비스 기간</th>
				<td><input type="text" name="read" class="datepicker_inp"><span>* 기간을 지정하시면 열람서비스가 부여됩니다.</span></td>
			</tr>
			<tr>
				<th>서비스 건수</th>
				<td><input type="text" name="read_int" class="input10"> 건</td>
			</tr>
			<tr>
				<th colspan="2">점프서비스</th>
			</tr>
			<tr>
				<th>서비스 건수</th>
				<td><input type="text" name="jump_int" class="input10"> 건</td>
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button class="blue">저장하기</button>
		<button type="button" onClick="open_box(this, 'member_service-', 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!--//열람서비스기간 팝업-->