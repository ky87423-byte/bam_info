<tr class="tac">
	<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"><input type="hidden" name="hidd[]" value="<?php echo $row['no'];?>" /></td>
	<td><input type="text" name="rank[]" value="<?php echo $row['rank'];?>"></td>
	<td><?php echo $parent0['wr_name'];?> > <?php echo $parent1['wr_name'];?></td>
	<td>
		<?php echo $nf_util->get_text($row['bo_subject']);?> (<?php echo $row['bo_table'];?>)
	</td>
	<td><?php echo number_format(intval($row['bo_write_count']));?></td>
	<td>
		<select name="bo_type[]" onChange="ch_bo_type(this, '<?php echo $row['bo_table'];?>')">
		<?php
		foreach($nf_board->bo_type as $k=>$v) {
			$selected = $k===$row['bo_type'] ? 'selected' : '';
		?>
		<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
		<?php
		}?>
		</select>
	</td>
	<td><button type="button" onClick="open_box(this, 'board-auth-')" no="<?php echo $row['no'];?>" class="common gray"><strong class="blue">O 권한</strong></button></td>
	<td><a href="<?php echo NFE_URL;?>/board/list.php?bo_table=<?php echo $row['bo_table'];?>" target="_blank"><button type="button" class="common gray">미리보기</button></a></td>
	<td>
		<a href="<?php echo NFE_URL;?>/admini/board/board_add.php?no=<?php echo $row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a>
		<button type="button" onClick="delete_board(this, '<?php echo $row['no'];?>')" class="gray common"><i class="axi axi-minus2"></i> 삭제</button>
	</td>
</tr>