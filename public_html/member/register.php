<?php
$_site_title_ = '회원가입';
include_once dirname(__DIR__)."/engine/_core.php";
$nf_member->check_not_login();

include dirname(__DIR__) . '/include/header_meta.php';
include dirname(__DIR__) . '/include/header.php';
?>
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_member.class.js"></script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>회원가입<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="register sub">
	<form name="fagree" action="<?php echo NFE_URL;?>/member/member_regist.php" method="get">
	<input type="hidden" name="code" value="" />
	<div class="wrap1400 MAT20">
		<p class="s_title">회원가입</p>
		<div class="box_wrap">
			<ul class="order">
				<li class="on"><span>1</span>가입분류</li>
				<li><span>2</span>회원정보 입력</li>
				<li><span>3</span>가입완료</li>
			</ul>

			<div class="next_btn">
				<button type="button" onClick="nf_member.member_regist_move('individual')" class="base darkbluebtn">개인회원 가입</button>
				<button type="button" onClick="nf_member.member_regist_move('company')" class="base">업체회원 가입</button>
			</div>
		</div>
	</div>
	</form>
</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>