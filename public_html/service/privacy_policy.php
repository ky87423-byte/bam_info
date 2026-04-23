<?php
$_site_title_ = $m_title = "개인정보처리방침";
include '../include/header_meta.php';
include '../include/header.php';
include NFE_PATH.'/include/m_title.inc.php';
?>

<div class="etc wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_on['privacy_policy'] = 'on';
	include '../include/etc_leftmenu.php';
	?>
	<div class="etc_con">
		<?php echo stripslashes($env['content_privacy']);?>
	</div>
</div>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>