<?php
include '../include/header_meta.php';
include '../include/header.php';
?>
<!--사이드 배너-->
<?php include '../include/scroll_banner.php'; ?>
<script type="text/javascript">
var click_dormancy = function() {
	$.post(root+"/include/regist.php", "mode=ch_dormancy", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>로그인<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="login_sub sub">
	<div class="wrap1400 MAT20">
		<div class="loginborder">
			<div class="centerwrap">
				<h3>휴면 계정 해제 안내</h3>
				<div class="sleep">
					<p class="blue"><?php echo $member['mb_name'];?></p>
					<p>회원님의 계정이 1년동안 로그인되지 않아 휴면 계정으로 전환되었습니다.</p>
					<p class="bb"><b>휴면 상태를 해제 하시겠습니까?</b></p>
					<p class="gray"><i class="axi axi-info"></i> 정보통신망법에 따라, 회원님의 소중한 개인정보를 보호하기 위해<br>1년동안 로그인 기록이 없는 계정을 휴면전환하고 개인정보를 별도 분리하여 보관합니다.</p>
					<div class="next_btn">
						<button onClick="click_dormancy()" type="button" class="base">휴면상태를 해제 합니다</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>