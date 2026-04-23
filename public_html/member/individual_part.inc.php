<?php
$is_auth = ($_SESSION['certify_info'] || $my_member['mb_auth_di'] || $my_member['mb_auth_ci']) ? true : false;
?>
<table class="style1">
	<colgroup>
		<col width="10%">
	</colgroup>
	<tbody>
		<?php if((!$my_member['no'] && strpos($_SERVER['PHP_SELF'], "/member/update_form.php")===false) || $admin_page) {?>
		<tr>
			<th><?php echo $icon_need;?>회원아이디</th>
			<td>
				<?php if($mem_row) {?>
				<div><?php echo $mem_row['mb_id'];?></div>
				<?php } else {?>
				<input type="text" name="mb_id" id="fmember_mb_id" value="<?php echo $mem_row['mb_id'];?>" hname="아이디" needed option="userid" minbyte="5" maxbyte="20" maxlength="20" class="input10" onkeyup="nf_util.input_text(this)"><input type="hidden" class="check_mb_id- dupl-hidden-" name="check_mb_id" value="1" message="아이디를 중복확인해주시기 바랍니다." needed /><button type="button" onClick="nf_member.check_uid('fmember_mb_id')" class="base2 basebtn gray MAL5">중복확인</button>
				<?php }?>
			</td>
		</tr>
		<?php if(!$member['mb_is_sns']) {?>
		<tr>
			<th><?php echo $icon_need;?>비밀번호</th>
			<td><input type="password" name="mb_password" hname="비밀번호" <?php echo $admin_page ? '' : 'needed';?> minbyte="5" maxbyte="20" option="userpw" maxlength="20" class="input10"><span><em>* 5~20자 사이의 영문, 숫자, 특수문자중 최소 2가지 이상 조합해주세요.</em></span></td>
		</tr>
		<?php }?>
		<?php if($admin_page) {?>
		<tr>
			<th><?php echo $icon_need;?>회원등급</th>
			<td>
				<select name="mb_level" hname="회원등급" needed>
					<option value="">회원등급 선택</option>
					<?php
					if(is_array($env['member_level_arr'])) { foreach($env['member_level_arr'] as $k=>$v) {
						if($k<=0) continue;
						$selected = $mem_row['mb_level']==$k ? 'selected' : '';
					?>
					<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['name'];?> (<?php echo $k+1;?>레벨)</option>
					<?php
					} }
					?>
				</select>
			</td>
		</tr>
		<?php
		} else {
			if(!$member['mb_is_sns']) {
		?>
		<tr>
			<th><?php echo $icon_need;?>비밀번호 확인</th>
			<td><input type="password" name="mb_passwd2" hname="비밀번호 확인" needed minbyte="5" maxbyte="20" option="userpw" class="input10" maxlength="20" matching="mb_password"><em> * 비밀번호를 한번 더 입력해 주십시오.</em></td>
		</tr>
		<?php
			}
		}?>
		<?php
		}?>
		<tr>
			<th><?php echo $icon_need;?>이름</th>
			<td><input type="text" name="mb_name" value="<?php echo $nf_util->get_html($my_member['mb_name']);?>" <?php echo $is_auth && $my_member['mb_name'] ? 'readOnly' : '';?> hname="이름" needed></td>
		</tr>
		<tr>
			<th><?php echo $icon_need;?>닉네임</th>
			<td>
				<input type="text" name="mb_nick" id="fmember_mb_nick" value="<?php echo $nf_util->get_html($my_member['mb_nick']);?>" onkeyup="nf_util.input_text(this)" hname="닉네임" needed><input type="hidden" class="check_mb_nick- dupl-hidden-" name="check_mb_nick" value="<?php echo $my_member['mb_nick'] ? '1' : '';?>" message="닉네임을 중복확인해주시기 바랍니다." needed /><button type="button" onClick="nf_member.check_nick('fmember_mb_nick')" class="base2 basebtn gray MAL5">중복확인</button><span><em> * 커뮤니티(게시판)등 익명성이 필요한 곳에서 사용됩니다.</em></span>
			</td>
		</tr>
		<?php if($env['use_message']) {?>
		<tr>
			<th>쪽지수신동의</th>
			<td><input type="checkbox" name="mb_receive[]" value="message" <?php echo $my_member['mb_message_view'] ? 'checked' : '';?> id="consent3"><label for="consent3" class="checkstyle1" ></label>동의</td>
		</tr>
		<?php
		}

		if($admin_page) {
		?>
		<tr>
			<th>관리자메모</th>
			<td><textarea name="mb_memo" cols="30" rows="10"><?php echo stripslashes($mem_row['mb_memo']);?></textarea></td>
		</tr>
		<?php
		}?>
	</tbody>
</table>