<table class="style1">
	<tr>
		<th>사업자번호 <i class="axi axi-ion-android-checkmark"></i></th>
		<td class="phone">
			<input type="text" name="biz_no[]" value="<?php echo $nf_util->get_html($mb_biz_no_arr[0]);?>" hname="사업자번호" needed> -
			<input type="text" name="biz_no[]" value="<?php echo $nf_util->get_html($mb_biz_no_arr[1]);?>" hname="사업자번호" needed> -
			<input type="text" name="biz_no[]" value="<?php echo $nf_util->get_html($mb_biz_no_arr[2]);?>" hname="사업자번호" needed>
		</td>
	</tr>
	<tr>
		<th>업체명 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="company_name" value="<?php echo $nf_util->get_html($member_tax['wr_company_name'] ? $member_tax['wr_company_name'] : $member_ex['mb_company_name']);?>" hname="회사명" needed></td>
	</tr>
	<tr>
		<th>대표자명 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="ceo_name" value="<?php echo $nf_util->get_html($member_tax['wr_ceo_name'] ? $member_tax['wr_ceo_name'] : $member_ex['mb_ceo_name']);?>" hname="대표자명" needed></td>
	</tr>
	<tr>
		<th>사업장 주소 <i class="axi axi-ion-android-checkmark"></i></th>
		<td class="area-address-">
			<input type="text" class="post-" name="zipcode" id="sample2_postcode" value="<?php echo $nf_util->get_html($member_tax['wr_zipcode'] ? $member_tax['wr_zipcode'] : $member['mb_zipcode']);?>" hname="우편번호" needed><button type="button" class="basebtn base2 gray MAL5" onClick="sample2_execDaumPostcode(this)">우편번호 검색</button><br>
			<input class="MAT5" type="text" name="address0" id="sample2_address" value="<?php echo $nf_util->get_html($member_tax['wr_address0'] ? $member_tax['wr_address0'] : $member['mb_address0']);?>" hname="주소" needed class="long address1-">
			<input class="MAT5" type="text" name="address1" id="sample2_extraAddress" value="<?php echo $nf_util->get_html($member_tax['wr_address1'] ? $member_tax['wr_address1'] : $member['mb_address1']);?>" hname="상세주소" needed>
			<?php
			include NFE_PATH.'/include/post.daum.php';
			?>
		</td>
	</tr>
	<tr>
		<th>업태 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="condition" value="<?php echo $nf_util->get_html($member_tax['wr_condition']);?>" hname="업태" needed placeholder="예) 서비스"></td>
	</tr>
	<tr>
		<th>종목 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="item" value="<?php echo $nf_util->get_html($member_tax['wr_item']);?>" hname="종목" needed placeholder="예) 소프트웨어 개발"></td>
	</tr>
	<tr>
		<th>이메일 <i class="axi axi-ion-android-checkmark"></i></th>
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
		<th>담당자명 <i class="axi axi-ion-android-checkmark"></i></th>
		<td><input type="text" name="manager" value="<?php echo $nf_util->get_html($member_tax['wr_manager'] ? $member_tax['wr_manager'] : $member['mb_name']);?>" hname="담당자명" needed></td>
	</tr>
	<tr>
		<th>담당자연락처 <i class="axi axi-ion-android-checkmark"></i></th>
		<td class="phone">
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[0]);?>" hname="담당자연락처" needed> -
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[1]);?>" hname="담당자연락처" needed> -
			<input type="text" name="wr_phone[]" value="<?php echo $nf_util->get_html($mb_phone_arr[2]);?>" hname="담당자연락처" needed>
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