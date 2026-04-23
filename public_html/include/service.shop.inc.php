<!--div class="button_area">
	<button class="base">서비스 신청</button>
</div-->

<?php
// : 리스트 메인
	$code = 'shop';
	$type = '0';
	$code_sub = 'main';
	$service_ahref = NFE_URL.'/mypage/regist.php';
	include NFE_PATH.'/include/service/list.service.inc.php';
?>


<?php
$code = 'shop';
$service_ahref = NFE_URL.'/service/product_payment.php?code=jump';
include NFE_PATH.'/include/service/jump.service.inc.php';
?>

<?php if(strpos($_SERVER['PHP_SELF'], '/service/product_payment.php')===false && strpos($_SERVER['PHP_SELF'], '/service/index.php')===false) {?>
<div class="next_btn">
	<button class="base" onClick="location.href='<?php echo NFE_URL;?>/mypage/regist.php'">업체정보 등록</button>
</div>
<?php }?>