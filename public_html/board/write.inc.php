<table class="style1">
	<colgroup>
		<col style="width:16%">	
	</colgroup>
	<?php if($bo_row['bo_use_secret'] && $bo_row['bo_type']!='talk') {?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 비밀글 </th>
		<td><label><input type="checkbox" name="wr_secret" value="1" <?php echo $b_row['wr_secret'] ? 'checked' : '';?>>비밀글</label></td>
	</tr>
	<?php
	}?>
	<?php if(admin_no) {?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 공지글 </th>
		<td>
			<label><input type="checkbox" name="wr_option" value="notice" <?php echo $b_row['wr_option']=='notice' ? 'checked' : '';?>>공지글</label>
		</td>
	</tr>
	<?php
	}?>
	<?php if($is_write_name) {?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 작성자 </th>
		<td><input type="text" name="wr_name" hname="작성자" needed value="<?php if($_GET['code']!='reply') echo $nf_util->get_html($b_row['wr_name']);?>"></td>
	</tr>
	<?php if(!$member['no'] && strpos($_SERVER['PHP_SELF'], '/admini/')===false) {?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 비밀번호 </th>
		<td><input type="password" name="wr_password" hname="비밀번호" needed><span style="vertical-align:middle;"><em>* 비회원의 경우 비밀번호를 꼭 입력하고 기억하셔야 합니다.</em></span></td>
	</tr>
	<?php
			}
	}
	if($board_info['bo_category_list'] && $_GET['code']=='insert') {
	?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 분류 </th>
		<td>
			<select name="wr_category" hname="분류" needed>
				<option value="">분류선택</option>
				<?php
				if(is_array($board_info['bo_category_list_arr'])) { foreach($board_info['bo_category_list_arr'] as $k=>$v) {
					if($_GET['code']!='reply') $selected = $v==$b_row['wr_category'] ? 'selected' : '';
				?>
				<option value="<?php echo trim($v);?>" <?php echo $selected;?>><?php echo trim($v);?></option>
				<?php
				} }?>
			</select>
		</td>
	</tr>
	<?php
	}?>
	<?php
	if($bo_table=='service_advert') {
		$phone_arr = explode("-", $b_row['wr_2']);
		$mem_email_arr = explode("@", $b_row['wr_email']);
	?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 연락처</i></th>
		<td class="size1">
			<input type="text" name="wr_2[]" hname="연락처" needed value="<?php echo $nf_util->get_html($phone_arr[0]);?>"> -
			<input type="text" name="wr_2[]" hname="연락처" needed value="<?php echo $nf_util->get_html($phone_arr[1]);?>"> -
			<input type="text" name="wr_2[]" hname="연락처" value="<?php echo $nf_util->get_html($phone_arr[2]);?>">
		</td>
	</tr>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 이메일</th>
		<td>
			<input type="text" name="wr_email[]" value="<?php echo $mem_email_arr[0];?>" hname="이메일" needed> @
			<input type="text" name="wr_email[]" value="<?php echo $mem_email_arr[1];?>" hname="이메일" needed id="wr_email_" hname="이메일" needed>
			<select onChange="nf_util.ch_value(this, '#wr_email_')">
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
		<th><i class="axi axi-ion-android-checkmark"></i> 업체명</th>
		<td><input class="long100" type="text" name="wr_1" hname="업체명" needed value="<?php echo $nf_util->get_html($b_row['wr_1']);?>"></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 제목 </th>
		<td><input type="text" name="wr_subject" hname="제목" needed value="<?php if($_GET['code']!='reply') echo $nf_util->get_html($b_row['wr_subject']);?>" class="long100"></td>
	</tr>
	<tr>
		<th><i class="axi axi-ion-android-checkmark"></i> 내용 </th>
		<td>
			<?php
			$wr_content = stripslashes($b_row['wr_content']);
			if(!$wr_content) $wr_content = stripslashes($bo_row['bo_insert_content']);
			?>
			<textarea type="editor" name="wr_content" hname="내용" needed style="height:300px;"><?php if($_GET['code']!='reply') echo $wr_content;?></textarea>
		</td>
	</tr>
	<?php
	$bo_upload_count = intval($bo_row['bo_upload_count']);
	if($bo_row['bo_use_upload'] && $bo_upload_count>0) {
	?>
	<tr>
		<th>
			첨부파일
			<p style=" font-size:13px; color:#555; margin-top:5px;">
				<button type="button" onClick="nf_board.add_attach(this, '<?php echo $bo_table;?>')" style="border:1px solid #d4d4d4; padding:1px 4px;background:#fff;">추가</button>
			</p>
		</th>
		<td class="attach-item-td-">
			<?php
			if($_GET['code']!='reply') {
				$file_query = $db->_query("select * from nf_board_file where `bo_table`=? and `wr_no`=? and `file_del`=0", array($bo_table, $b_row['wr_no']));
				$file_length = $db->num_rows($file_query);
			}
			if($file_length<=0) $file_length = 1;
			for($i=0; $i<$file_length; $i++) {
				if($file_query) $file_row = $db->afetch($file_query);
				$is_file = false;
				if(is_file(NFE_PATH.'/data/board/'.$bo_table.'/'.$file_row['file_name'])) $is_file = true;
			?>
			<div class="attach-item-">
				<input type="file" name="attach[]" hname="첨부파일" ><input type="hidden" name="attach_hidd[]" value="<?php echo $file_row['no'];?>" />
				<p style="font-size:13px; border-top:1px solid #eee; line-height:initial; padding:5px 0;">
					<button type="button" onClick="nf_board.file_delete(this, '<?php echo $file_row['no'];?>')" style="border:1px solid #d4d4d4; padding:1px 4px; margin-right:5px; color:#555;">파일삭제</button>
					<?php if($is_file) {?>
					<a href="javascript:void(0)" class="attach-info-" onClick="nf_board.download(this, '<?php echo $file_row['no'];?>')"><img src="<?php echo NFE_URL;?>/images/ic/file.gif" alt="이미지다운" style=" vertical-align:middle;"><span class="blue" style="margin:0 5px;"><?php echo $file_row['file_source'];?></span><span class="orange">[<?php echo $nf_util->get_filesize($file_row['file_filesize'],'KB');?>]</span><span class="gray_txt">DATE : <?php echo $file_row['file_datetime'];?></span></a>
					<?php }?>
				</p>
			</div>
			<?php
			}?>
		</td>
	</tr>
	<?php
	}?>
	<?php
	if($skin!='admin')
		include NFE_PATH.'/include/kcaptcha.php';
	?>
</table>