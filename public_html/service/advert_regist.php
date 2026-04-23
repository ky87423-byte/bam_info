<?php
$_SERVER['__USE_API__'] = array('editor');
$_site_title_ = '광고 · 제휴 문의';
$add_cate_arr = array('concert', 'email');
include '../include/header_meta.php';
include '../include/header.php';
$nf_member->check_login('company');

$bo_table = 'service_advert';

$get_phone = $member['mb_phone'] ? $member['mb_hphone'] : $member['mb_phone'];
$phone_arr = explode("-", $get_phone);
$mem_email_arr = explode("@", $member['mb_email']);
?>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>입점문의<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="register">
	<div class="wrap1400 sub">
		<form name="fwrite" action="../board/regist.php" method="post" enctype="multipart/form-data" onSubmit="return nf_util.ajax_submit(this)">
		<input type="hidden" name="mode" value="board_write" />
		<input type="hidden" name="code" value="insert" />
		<input type="hidden" name="bo_table" value="<?php echo $nf_util->get_html($bo_table);?>" />
		<div class="subcon_area">
			<p class="s_title">입점문의</p>
			<div class="box_wrap">
				<ul class="help_text">
					<li>성공적인 비즈니스 사업을 위하여 귀사의 소중한 의견이나 제안을 받습니다.</li>
					<li>담당자 확인 후 이메일 혹은 연락처로 연락드리겠습니다.</li>
					<li>한번 등록한 내용은 수정이 불가능합니다.</li>
				</ul>


				<table class="style1 MAT30">
					<colgroup>
					</colgroup>
					<tbody>
						<tr>
							<th><i class="axi axi-ion-android-checkmark"></i> 이름</th>
							<td><input type="text" name="wr_name" hname="이름" needed value="<?php echo $nf_util->get_html($member['mb_name']);?>"></td>
						</tr>
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
							<td><input class="long100" type="text" name="wr_1" hname="업체명" needed value="<?php echo $nf_util->get_html($member_ex['mb_company_name']);?>"></td>
						</tr>
						<tr>
							<th><i class="axi axi-ion-android-checkmark"></i> 제목</th>
							<td><input type="text" name="wr_subject" hname="제목" needed style="max-width:100%"></td>
						</tr>
						<tr>
							<th colspan="2" style="padding:1rem; text-align:center;"><i class="axi axi-ion-android-checkmark"></i> 내용</th>
						</tr>
						<tr>
							<td colspan="2"><textarea type="editor" hname="내용" needed name="wr_content" style="height:200px;"></textarea></td>
						</tr>
						<?php
							include NFE_PATH.'/include/kcaptcha.php';
						?>
					</tbody>
				</table>
				<div class="next_btn">
					<a href="<?php echo $nf_util->sess_page("service_advert");?>"><button type="button" class="base graybtn">돌아가기</button></a>
					<button type="submit" class="base">등록하기</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</section>

<!--푸터영역-->
<?php //include '../include/footer.php'; ?>