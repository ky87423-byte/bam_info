<?php
$coupon_number = "";
for($i=0; $i<=10000; $i++) {
	$coupon_number = $nf_util->coupon_generator();
	$dupl_number = $db->query_fetch("select * from nf_coupon_use where `number`='".$coupon_number."'");
	if(!$dupl_number) break;
}
$_SESSION['_coupon_number_'.$shop_info['no']] = $coupon_number;


$kakao_title_arr = array();
$kakao_title_arr[] = '['.$shop_info['wr_company'].'] '.$shop_info['area_end'];
$kakao_title_arr[] = $shop_info['address_in'];
$kakao_title = @implode("\\n", $kakao_title_arr);

$kakao_description_arr = array();
$kakao_description_arr[] = '￦'.number_format($shop_info['coupon_price']).'원 / '.$coupon_number;
$kakao_description_arr[] = $shop_info['coupon_date1'].'~'.$shop_info['coupon_date2'];
$kakao_description = @implode("\\n", $kakao_description_arr);
?>

<script type='text/javascript'>
var get_coupon_func = function() {
	$.post("/include/regist.php", "mode=get_coupon&no=<?php echo $shop_info['no'];?>", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>


<?php
if($shop_info['coupon_use']) {
	$coupon_chk = $nf_shop->coupon_down_allow($shop_info['no']);
	if($member['mb_id'] && $member['mb_type']=='individual' && !$coupon_chk['row']) {

	ob_start();
	?>
	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript">
	//<![CDATA[
	// // 사용할 앱의 JavaScript 키를 설정해 주세요.
	Kakao.init("<?php echo $env['kakao'];?>");
	// // 카카오링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
	Kakao.Link.createDefaultButton({
		container: '.kakao-link-btn-is',
		objectType: 'feed',
		content: {
			title: "<?php echo $kakao_title;?>",
			description: "<?php echo $kakao_description;?>",
			imageUrl: "<?php echo domain;?>/<?=$shop_info['main_photo_url'];?>",
			link: {
				mobileWebUrl: "<?php echo domain;?>/include/event.php?no=<?php echo $shop_info['no'];?>",
				webUrl: "<?php echo domain;?>/include/event.php?no=<?php echo $shop_info['no'];?>"
			}
		}
	});
	//]]>
	</script>
	<?php
	$kakao_process = ob_get_clean();
	}
}?>