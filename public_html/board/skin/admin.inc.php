<?php
// tr에 bg_blue 클래스 입히면 공지
?>
<tr class="tac <?php echo $row['wr_del'] ? 'del-' : '';?>">
	<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['wr_no'];?>"></td>
	<td class="tal">
		<?php if($_GET['bo_table']=='service_advert') {?>
		<div style="border:1px solid #eee; background:#f8f8f8; padding:5px; margin-bottom:5px;">
			<b class="orange">업체명</b> : <?php echo $row['wr_1'];?>
			<b class="orange" style="margin-left:10px;"> Tell</b> : <?php echo $row['wr_2'];?>
			<b class="orange" style="margin-left:10px;"> Email</b> : <?php echo $row['wr_email'];?>
		</div>
		<?php }?>
		<?php if($board_info['bo_category_list'] && !$row['wr_reply'] && $row['wr_category']) {?><b class="MAR5">[<?php echo $row['wr_category'];?>]</b><?php }?><a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $_GET['bo_table'];?>&no=<?php echo $row['wr_no'];?>" class="blue" target="_blank"><?php echo $b_info['reply_depth'].$row['wr_subject'];?></a>
	</td>
	<td><button class="common gray" type="button" onClick="click_modify(this, '<?php echo $row['wr_no'];?>', 'reply')">Re 답변</button></td>
	<td><?php echo $nf_util->get_text($b_info['get_name']);?></td>
	<td><?php echo number_format(intval($row['wr_hit']));?></td>
	<td><?php echo substr($row['wr_datetime'],0,10);?></td>
	<td>
		<button class="gray common" type="button" onClick="click_modify(this, '<?php echo $row['wr_no'];?>')"><i class="axi axi-plus2"></i> 수정</button>
		<button class="gray common" type="button" onClick="nf_board.click_delete(this, '<?php echo $bo_table;?>', '<?php echo intval($row['wr_no']);?>');"><i class="axi axi-minus2"></i> 삭제</button>
	</td>
</tr>