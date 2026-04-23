<tr align="center">
	<td><input type="checkbox" class="chk_" value="<?php echo $row['no'];?>" name="chk[]"><input type="hidden" name="hidd[]" value="<?php echo $row['no'];?>" /></td>
	<td><input type="text" value="<?php echo $row['wr_rank'];?>" name="rank[]" hname="순서" needed></td>
	<td><input type="checkbox" value="<?php echo $row['no'];?>" onClick="nf_category.view(this)" <?php echo $row['wr_view'] ? 'checked' : '';?>></td>
	<td><input type="text" value="<?php echo $wr_name_arr[0];?>" name="subject[]" hname="은행명" needed></td>
	<td><input type="text" value="<?php echo $wr_name_arr[1];?>" name="subject[]" hname="계좌번호" needed></td>
	<td><input type="text" value="<?php echo $wr_name_arr[2];?>" name="subject[]" hname="예금주" needed></td>
	<td><button type="button" class="gray common" onClick="nf_category.insert(this, '<?php echo $row['no'];?>')"><i class="axi axi-ion-android-checkmark"></i> 수정</button></td>
	<td><button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_category" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button></td>
</tr>