<table class="style1">
	<tr>
		<th>신청자명 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="wr_name" value="<?php echo $nf_util->get_html($member_tax['wr_name'] ? $member_tax['wr_name'] : $member['mb_name']);?>" hname="신청자명" needed></td>
	</tr>
	<tr>
		<th>휴대폰번호 <i class="axi axi-ion-android-checkmark""></i></th>
		<td class="phone">
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[0]);?>" hname="휴대폰번호" needed> -
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[1]);?>" hname="휴대폰번호" needed> -
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[2]);?>" hname="휴대폰번호" needed>
		</td>
	</tr>
	<tr>
		<th>이메일 <i class="axi axi-ion-android-checkmark""></i></th>
		<td>
			<input type="text" name="wr_email[]" value="<?php echo $nf_util->get_html($mb_email_arr[0]);?>" hname="이메일" needed class="input10"> @
			<input type="text" name="wr_email[]" value="<?php echo $nf_util->get_html($mb_email_arr[1]);?>" id="_email_" hname="이메일" needed class="input10">
			<select onChange="nf_util.ch_value(this, '#_email_')">
				<option value="">직접입력</option>
				<?php
				if(is_array($cate_p_array['email'][0])) { foreach($cate_p_array['email'][0] as $k=>$v) {
				?>
				<option value="<?php echo $nf_util->get_html($v['wr_name']);?>"><?php echo $v['wr_name'];?></option>
				<?php
				} }
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th>내용</th>
		<td><textarea name="wr_content"><?php echo stripslashes($member_tax['wr_content']);?></textarea></td>
	</tr>
	<?php
	if($admin_page) {
	?>
	<tr>
		<th>관리자메모</th>
		<td><textarea name="wr_memo"><?php echo stripslashes($member_tax['wr_memo']);?></textarea></td>
	</tr>
	<?php
	}?>
</table>