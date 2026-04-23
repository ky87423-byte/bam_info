<!-- 맞춤정보 수신 회원 -->
<form name="" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="" />
<div class="layer_pop conbox popup_box-" style="width:850px;">
	<div class="h6wrap">
		<h6>맞춤정보 수신 회원<span>수신동의 회원에게 발송</span> <em style="font-weight:normal;"><em class="name--"></em><em class="mb_id--"></em></em></h6>
		<button type="button" class="close" onClick="open_box(this, 'email-', 'none')">X 창닫기</button>
	</div>
	<table>
		<colgroup>
		</colgroup>
		<tbody>
			<tr>
				<th class="tac"><input type="checkbox"></th>
				<th class="tac">회원구분</th>
				<th class="tac">회원아이디</th>
				<th class="tac">이름/대표자 (성별/나이)</th>
			</tr>
			<tr class="tac">
				<td><input type="checkbox"></td>	
				<td>기업회원</td>	
				<td>netfu</td>	
				<td>김범/넷퓨 (남/28)</td>	
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="submit" class="blue">발송하기</button>
		<button type="button" onClick="open_box(this, 'email-', 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!-- //맞춤정보 수신 회원 -->