<?php
switch($wr_type) {
	case "board_menu":
		$cate_tr_onclick = "board_click(this,'".$row['no']."')";
	break;
}
?>
<tr class="tac <?php echo $insert_tag ? 'add_form_view' : '';?>" no="<?php echo $row['no'];?>" onClick="<?php echo $cate_tr_onclick;?>">
	<td><input type="checkbox" name="view" value="<?php echo $row['no'];?>" <?php if(!$insert_tag) {?>onClick="nf_category.view(this)"<?php }?> <?php echo $insert_tag || $row['wr_view'] ? 'checked' : '';?>></td>
	<?php if(in_array($wr_type, array('job_part')) && !$_POST['pno']) {?><th>성인</th><?php }?>
	<td><input type="text" name="subject" value="<?php echo $nf_util->get_html($row['wr_name']);?>" <?php if(!$insert_tag) {?>onClick="nf_category.text_click(this)"<?php }?> onKeypress="if(event.keyCode==13) return nf_category.insert(this, '<?php echo $row['no'];?>')"></td>
	<td>
		<button type="button" class="s_basebtn gray" onClick="nf_category.insert(this, '<?php echo $row['no'];?>')"><?php echo $insert_tag ? '등록' : '수정';?></button>
		<button type="button" class="s_basebtn gray" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_category" para="wr_type=<?php echo $row['wr_type'];?>" url="../regist.php">삭제</button>
	</td>
</tr>