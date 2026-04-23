<!-- 관리자메모 -->
<form name="fmemo" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="member_memo" />
<input type="hidden" name="mb_no" value="" />
<div class="layer_pop conbox memo- popup_box-">
	<div class="h6wrap">
		<h6>관리자메모 <em style="font-weight:normal;"><em class="name--"></em><em class="mb_id--"></em></em></h6>
		<button type="button" onClick="open_box(this, 'memo-', 'none')" class="close">X 창닫기</button>
	</div>
	<textarea name="mb_memo" cols="30" rows="10"></textarea>
	<div class="pop_btn">
		<button type="submit" class="blue">저장하기</button>
		<button type="button" onClick="open_box(this, 'memo-', 'none')" class="gray">X 창닫기</button>
	</div>
</div>
</form>
<!-- //관리자메모 -->