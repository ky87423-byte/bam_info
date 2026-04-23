<?php
include_once "../engine/_core.php";
$nf_member->check_login();

include '../include/header_meta.php';
include '../include/header.php';

$m_title = '비밀번호 변경';
include NFE_PATH.'/include/m_title.inc.php';
?>
<section class="sub">
	<div class="wrap1400 my_sub">
		<!--개인서비스 왼쪽 메뉴-->
		<?php
		$left_on['password_form'] = 'on';
		if($member['mb_type']=='individual') include '../include/indi_leftmenu.php';
		else include '../include/company_leftmenu.php';
		?>
		<div class="subcon_area">
			<section class="password_form common_table">
				<p class="s_title">비밀번호 변경</p>
				<ul class="help_text">
					<li>비밀번호는 주기적(최소 6개월)으로 변경해 주시기 바랍니다.</li>
				</ul>
				<form name="fmember" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
				<input type="hidden" name="mode" value="password_modify" />
				<table class="style1">
					<tr>
						<th>현재 비밀번호 <i class="axi axi-ion-android-checkmark"></i></th>
						<td><input type="password" name="this_passwd" hname="현재 비밀번호" needed minbyte="5" maxbyte="20" option="userpw" maxlength="20"></td>
					</tr>
					<tr>
						<th>새로운 비밀번호</th>
						<td><input type="password" name="passwd" hname="새로운 비밀번호" needed minbyte="5" maxbyte="20" option="userpw" maxlength="20"><em>* 5~20자 사이의 영문, 숫자, 특수문자중 최소 2가지 이상 조합</em></td>
					</tr>
					<tr>
						<th>새로운 비밀번호 확인</th>
						<td><input type="password" name="ch_passwd" hname="새로운 비밀번호 확인" needed minbyte="5" maxbyte="20" option="userpw" maxlength="20" matching="passwd"></td>
					</tr>
				</table>
				<div class="next_btn">
					<button class="base">수정하기</button>
				</div>
				</form>
			</section>
		</div>
	</div>
</section>
<!--푸터영역-->
<?php include '../include/footer.php'; ?>
