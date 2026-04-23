<!-- 게시판권한설정 팝업-->
<div class="layer_pop conbox board-auth-" style="top:600px;">
	<div class="h6wrap">
		<h6>게시판권한설정</h6>
		<button type="button" onClick="open_box(this, 'board-auth-', 'none')" class="close">X 창닫기</button>
	</div>
	<form name="flevel" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
	<input type="hidden" name="mode" value="board_level_update" />
	<input type="hidden" name="no" value="" />
	<table>
		<colgroup>
			<col width="16%">
			<col width="%">
			<col width="16%">
			<col width="%">
		</colgroup>
		<tr>
			<th>리스트접근</th>
			<td>
				<select name="bo_level[list]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_list_level']);
					?>
				</select>
			</td>
			<th>게시물읽기</th>
			<td>
				<select name="bo_level[read]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_read_level']);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>게시물쓰기</th>
			<td>
				<select name="bo_level[write]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_write_level']);
					?>
				</select>
			</td>
			<th>답변글쓰기</th>
			<td>
				<select name="bo_level[reply]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_reply_level']);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>댓글쓰기</th>
			<td>
				<select name="bo_level[comment]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_comment_level']);
					?>
				</select>
			</td>
			<th>비밀글쓰기</th>
			<td>
				<select name="bo_level[secret]">
					<option value="">손님(1레벨)</option>
					<?php
					echo $nf_member->level_option($bo_row['bo_secret_level']);
					?>
				</select>
			</td>
		</tr>
	</table>
	<div class="pop_btn">
		<button class=" blue">저장하기</button>
		<button type="button" onClick="open_box(this, 'board-auth-', 'none')" class="gray">창닫기</button>
	</div>
	</form>
</div>
<!--//게시판권한설정 팝업-->