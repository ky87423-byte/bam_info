<?php
$add_cate_arr = array('member_left_reason');
include_once "../engine/_core.php";
$nf_member->check_login();

include '../include/header_meta.php';
include '../include/header.php';
?>
<style type="text/css">
.etc-content- { display:none; }
</style>
<script type="text/javascript">
var click_reason = function(el) {
	var form = document.forms['fmember'];
	$(".etc-content-").css({"display":"none"});
	$(form).find("[name='content']").removeAttr("needed");
	if(el.value=='etc') {
		$(".etc-content-").css({"display":"block"});
		$(form).find("[name='content']").attr({"needed":"needed"});
	}
}
</script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>회원탈퇴<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
	<section class="my_sub wrap1400">
		<!--개인서비스 왼쪽 메뉴-->
		<?php
		$left_on['update_form'] = 'on';
		include '../include/my_leftmenu.php';
		?>
		<div class="my_con">
			<section class="user_out common_table">
				<p class="s_title">회원탈퇴 신청</p>
				<ul class="help_text">
					<li>1. 사용하고 계신 아이디 <?php echo $member['mb_id'];?>는 재사용 및 복구가 불가능합니다.</li>
					<li>2. 추후 재가입 시 동일한 아이디로 재가입하실 수 없습니다.</li>
				</ul>
				<form name="fmember" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
				<input type="hidden" name="mode" value="delete_member" />
				<table class="style1">
					<tr>
						<th>회원구분</th>
						<td><?php echo $nf_member->mb_type[$member['mb_type']];?>회원</td>
					</tr>
					<tr>
						<th>아이디</th>
						<td><?php echo $member['mb_id'];?></td>
					</tr>
					<tr>
						<th>비밀번호입력 <i class="axi axi-ion-android-checkmark"></i></th>
						<td><input type="text" name="passwd" hname="비밀번호" needed></td>
					</tr>
					<tr>
						<th>이메일 <i class="axi axi-ion-android-checkmark"></i></th>
						<td><input type="text" name="email" hname="이메일" needed></td>
					</tr>
					<tr>
						<th>탈퇴사유 <i class="axi axi-ion-android-checkmark"></i></th>
						<td>
							<ul>
								<?php
								if(is_array($cate_p_array['member_left_reason'][0])) { foreach($cate_p_array['member_left_reason'][0] as $k=>$v) {
									$selected = $company_row['mb_biz_success']==$v['wr_name'] ? 'selected' : '';
								?>
								<li><label><input type="radio" name="left_reason" class="left_reason-" onClick="click_reason(this)" hname="탈퇴사유" needed value="<?php echo $nf_util->get_text($v['wr_name']);?>"> <?php echo $nf_util->get_text($v['wr_name']);?></label></li>
								<?php
								} }?>
								<li><label><input type="radio" name="left_reason" class="left_reason-" onClick="click_reason(this)" hname="탈퇴사유" needed value="etc">기타</label></li>
							</ul>
							<div class="etc-content-"><textarea name="content" cols="30" rows="10" hname="탈퇴사유" ></textarea></div>
						</td>
					</tr>
				</table>
				<div class="next_btn">
					<a href="/"><button type="button" class="base graybtn">취소</button></a>
					<button class="base">탈퇴신청</button>
				</div>
				</form>
			</section>
		</div>
	</section>
<!--푸터영역-->
<?php include '../include/footer.php'; ?>
