<?php
$cate_k = $qna_row['wr_type']==='1' ? 'concert' : 'on2on';
?>
<div class="h6wrap">
	<h6>고객문의 글</h6>
	<button type="button" class="close" onClick="open_box(this, 'cs-', 'none')">X 창닫기</button>
</div>
<table>
	<colgroup>
		<col width="16%">
		<col width="%">
		<col width="16%">
		<col width="%">
	</colgroup>
	<tr>
		<th>제목</th>
		<td colspan="3" class="qna-subject-"><?php echo $nf_util->get_text($qna_row['wr_subject']);?></td>
	</tr>
	<?php
	if($qna_row['wr_type']==='1') {
	?>
	<th>회사명</th>
		<td><?php echo $qna_row['wr_biz_name'];?></td>
	</tr>
	<?php
	}?>
	<tr>
		<th>문의분류</th>
		<td><?php echo $nf_util->get_text($cate_p_array[$cate_k][0][$qna_row['wr_cate']]['wr_name']);?></td>
		<th>작성일</th>
		<td><?php echo $qna_row['wr_date'];?></td>
	</tr>
	<tr>
		<th>작성자</th>
		<td><?php echo $nf_util->get_text($qna_row['wr_name']);?></td>
		<th>연락처</th>
		<td colspan="3"><?php echo $qna_row['wr_phone'];?> / <?php echo $qna_row['wr_hphone'];?></td>
	</tr>
	<tr>
		<th>고객ID</th>
		<td>
			<?php
			$mb_id = $qna_row['wr_id'] ? $qna_row['wr_id'] : "";
			echo $nf_util->get_text($mb_id);
			?>
		</td>
		<th>이메일</th>
		<td><?php echo $nf_util->get_text($qna_row['wr_email']);?></td>
	</tr>
	<tr>
		<th>내용</th>
		<td colspan="3"><?php echo stripslashes($qna_row['wr_content']);?></td>
	</tr>
</table>