<?php
$_site_title_ = '고객센터';
$add_cate_arr = array('on2on', 'email');
$_SERVER['__USE_API__'] = array('editor');
include '../include/header_meta.php';
include '../include/header.php';

$phone_arr = explode("-", $member['mb_phone']);
$hphone_arr = explode("-", $member['mb_hphone']);
$mem_email_arr = explode("@", $member['mb_email']);

$m_title = '고객센터';
include NFE_PATH.'/include/m_title.inc.php';
?>

<section class="register">
	<div class="wrap1400 sub">
		<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="cs_center_write" />
		<input type="hidden" name="type" value="0" />
		<?php
		include NFE_PATH.'/include/etc/google_recaptcha3.inc.php';
		?>
		<div class="subcon_area">
			<p class="s_title">고객센터</p>
			<div class="box_wrap"><!--class="box_wrap"-->
				<ul class="help_text">
					<li>문의사항,불편사항 및 기타의견을 보내주시면 담당자 확인 후 이메일 혹은 연락처로 연락드리겠습니다.</li>
					<li>한번 등록한 상담내용은 수정이 불가능합니다.</li>
				</ul>
				
				<div class="terms">
				<h2>개인정보수집이용안내</h2>
					<div class="terms_box">
						<p><?php echo stripslashes($env['content_privacy_info']);?></p>
					</div>
					<p>
						<label for="indiagree" class="checkstyle1"><input type="checkbox" name="indiagree" message="개인정보수집 및 이용안내에 동의해주시기 바랍니다." needed id="indiagree">개인정보 수집 및 이용안내에 동의합니다.</label>
					</p>
				</div>

				<table class="style1 MAT30">
					<colgroup>
					</colgroup>
					<tbody>
						<tr>
							<th><i class="axi axi-ion-android-checkmark"></i> 문의분류</th>
							<td>
								<ul class="li_float">
									<?php
									if(is_array($cate_p_array['on2on'][0])) { foreach($cate_p_array['on2on'][0] as $k=>$v) {
									?>
									<li><label for="wr_cate_<?php echo $k;?>" class="radiostyle1" ><input type="radio" id="wr_cate_<?php echo $k;?>" name="wr_cate" value="<?php echo $k;?>" hname="문의분류" needed><?php echo stripslashes($v['wr_name']);?></label></li>
									<?php
									} }?>
								</ul>
							</td>
						</tr>
						<tr>
							<th><i class="axi axi-ion-android-checkmark"></i> 이름</th>
							<td><input type="text" name="wr_name" hname="이름" needed value="<?php echo $nf_util->get_html($member['mb_name']);?>"></td>
						</tr>
						<tr>
							<th>연락처</th>
							<td class="size1">
								<input type="text" name="wr_phone[]" hname="연락처" value="<?php echo $nf_util->get_html($phone_arr[0]);?>"> -
								<input type="text" name="wr_phone[]" hname="연락처" value="<?php echo $nf_util->get_html($phone_arr[1]);?>"> -
								<input type="text" name="wr_phone[]" hname="연락처" value="<?php echo $nf_util->get_html($phone_arr[2]);?>">
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
							<th>업체명</th>
							<td><input class="long100" type="text" name="wr_biz_name" value="<?php echo $nf_util->get_html($member_ex['mb_company_name']);?>"></td>
						</tr>
						<tr>
							<th><i class="axi axi-ion-android-checkmark"></i> 제목</th>
							<td><input type="text" name="wr_subject" hname="제목" needed style="max-width:100%"></td>
						</tr>
						<tr>
							<th colspan="2" style="padding:1rem; text-align:center;"><i class="axi axi-ion-android-checkmark"></i> 내용</th>
						</tr>
						<tr>							
							<td colspan="2" style="padding:0"><textarea type="editor" hname="내용" needed name="wr_content" style="height:200px;"></textarea></td>
						</tr>
						<?php
							include NFE_PATH.'/include/kcaptcha.php';
						?>
					</tbody>
				</table>
				<div class="next_btn">
					<button type="button" onClick="history.back()" class="base graybtn">돌아가기</button>
					<button type="submit" class="base">등록하기</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>