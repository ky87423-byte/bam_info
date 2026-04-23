<!-- 답글/게시물보기 팝업-->
<form name="fca" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="cs_qna_write" />
<input type="hidden" name="no" value="" />
<div class="layer_pop conbox popup_box- cs-">
	<div class="paste-body-">
		
	</div>

	<div class="h6wrap">
		<h6>고객문의 답변 글</h6>
	</div>
	<table>
		<colgroup>
			<col width="16%">
			<col width="%">
		</colgroup>
		<tr>
			<th>작성자</th>
			<td><input type="text" name="wr_aname" class="input10"></td>
		</tr>
		<tr>
			<th>내용</th>
			<td>
				<textarea type="editor" name="wr_acontent" style="width:100%;height:300px;"></textarea>
			</td>
		</tr>
	</table>
	<div class="pop_btn">
		<button class=" blue">저장하기</button>
		<button type="button" onClick="open_box(this, 'cs-', 'none')" class="gray">창닫기</button>
	</div>
</div>
</form>
<!--//답글/게시물보기 팝업-->