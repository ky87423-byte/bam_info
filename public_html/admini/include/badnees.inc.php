<!-- 불량회원 등록 팝업 -->
<form name="fbadness" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="member_badness" />
<input type="hidden" name="mb_no" value="" />
<div class="layer_pop conbox badness- popup_box-">
	<div class="h6wrap">
		<h6>불량회원 등록</h6>
		<button type="button" class="close" onClick="open_box(this, 'badness-', 'none')">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="12%">
		</colgroup>
		<tbody>
			<!--
			<tr>
				<th>불량회원</th>
				<td>
					<label><input type="radio" name="mb_badness" value="1">등록</label>
					<label><input type="radio" name="mb_badness" value="0">미등록</label>
				</td>
			</tr>
			<tr>
				<th>차단유무</th>
				<td>
					<label><input type="radio" name="mb_denied" value="1">차단</label>
					<label><input type="radio" name="mb_denied" value="0">접근</label><span>차단 설정 하시면 `<em class="name--">이름</em><em class="mb_id--">아이디</em>` 회원은 사이트에 접근 할수 없습니다.</span>
				</td>
			</tr>
			-->
			<tr>
				<th>불량회원</th>
				<td>
					<label><input type="radio" name="mb_badness" value="1">불량회원으로 등록</label>  
					<label><input type="radio" name="mb_badness" value="0">미등록</label>
					<br><span>'불량회원으로 등록' 설정 하시면 `<em class="name--">이름</em><em class="mb_id--">아이디</em>` 회원은 사이트에 로그인이 불가능하며, 등록한 업체는 사이트에 출력되지 않습니다.</span>
				</td>
			</tr>
			<tr>
				<th>메모</th>
				<td><textarea name="mb_memo" cols="30" rows="10"></textarea></td>
			</tr>	
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="submit" class="blue">저장하기</button>
		<button type="button" onClick="open_box(this, 'badness-', 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!--//불량회원 등록 팝업-->