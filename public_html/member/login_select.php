<?php
$not_mb_type_check = true;
$_site_title_ = '로그인';
include '../include/header_meta.php';
include '../include/header.php';
?>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>로그인<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="login_sub sub">
	<div class="wrap1400 MAT20">
		<div class="loginborder">
			<div class="centerwrap">
				<div class="help_txt"><b><?php echo $nf_util->get_text($env['site_name']);?></b>을(를) 이용하시기 위해서는 회원종류를 선택하셔야 합니다. <br>
				선택후 <span>회원정보수정을 마치셔야 이용이 가능</span>합니다.</div>
				<h3>회원선택</h3>
				<div>
					<div class="individual_s m_box">
						<ul>
							<li><i class="axi axi-people-outline"></i></li>
							<li><a href="<?php echo NFE_URL;?>/member/member_regist.php?code=individual"><button type="button">개인회원</button></a></li>
						</ul>
					</div>
					<!--loginbox-->
					<div class="corporation_s m_box">
						<ul>
							<li><i class="axi axi-domain"></i></li>
							<li><a href="<?php echo NFE_URL;?>/member/member_regist.php"><button type="button">기업회원</button></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>