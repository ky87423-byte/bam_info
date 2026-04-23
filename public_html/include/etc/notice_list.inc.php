<table class="style3 text_list">
	<colgroup>
		<col width="7%">
		<col width="60%">
		<col width="15%" class="m_no">
		<col width="10%" class="m_no">
		<col width="8%" class="m_no">
	</colgroup>
	<tr>
		<th>번호</th>
		<th>제목</th>
		<th class="m_no">작성자</th>
		<th class="m_no">등록일</th>
		<th class="m_no">조회수</th>
	</tr>
	<?php
	switch($_arr['total']<=0) {
		case true:
	?>
	<tr><td colspan="5" align="center">검색된 정보가 없습니다.</td></tr>
	<?php
		break;


		default:
			while($row=$db->afetch($notice_query)) {
	?>
	<tr>
		<td><?php echo $paging['bunho']--;?></td>
		<td class="tal line1"><?php if($row['wr_type']) {?><span class="orange">[<?php echo $row['wr_type'];?>]</span> <?php }?><a href="<?php echo NFE_URL;?>/board/notice_view.php?<?php echo $paging['parameter'];?>&no=<?php echo $row['no'];?>"><?php echo $nf_util->get_text($row['wr_subject']);?></a>
		<ul class="pc_no">
			<li><?php echo $nf_util->get_text($row['wr_name']);?></li>
			<li><?php echo date("y.m.d", strtotime($row['wr_date']));?></li>
			<li><i class="axi axi-visibility"></i> <?php echo number_format(intval($row['wr_hit']));?></li>
		</ul>
		</td>
		<td class="m_no"><?php echo $nf_util->get_text($row['wr_name']);?></td>
		<td class="m_no"><?php echo date("y.m.d", strtotime($row['wr_date']));?></td>
		<td class="m_no"><?php echo number_format(intval($row['wr_hit']));?></td>
	</tr>
	<?php
			}
		break;
	}
	?>
</table>