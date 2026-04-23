<?php
$_site_title_ = $m_title = "위치정보수집";
include '../include/header_meta.php';
include '../include/header.php';
include NFE_PATH.'/include/m_title.inc.php';
?>

<div class="etc wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_on['location_policy'] = 'on';
	include '../include/etc_leftmenu.php';
	?>
	<div class="etc_con">
		<?php echo stripslashes($env['content_young_info']);?>
	</div>
</div>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>