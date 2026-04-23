<?php
$_site_title_ = "서비스 안내";
include '../include/header_meta.php';
include '../include/header.php';

$_GET['code'] = 'shop';
?>
<style type="text/css">
.pay-view- { display:none; }
</style>
<script type="text/javascript">
var click_service_tab = function(k) {
	$(".top_tab").find("li").removeClass("on");
	$(".top_tab").find("li").eq(k).addClass("on");
	$(".service_body-").css({"display":"none"});
	$(".service_body-").eq(k).css({"display":"block"});
}
</script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>서비스 안내<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<div class="wrap1400">
	 <div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('sub_etc_H');
		echo $banner_arr['tag'];
		?>
	</div>
	<section class="service_sub">
		<div class="sub service wrap1400" style="margin-bottom:50px;">
			<?php
			if($_GET['code']=='shop') include NFE_PATH.'/include/service.shop.inc.php';

			if(in_array($_GET['code'], array('read', 'jump'))) {
				$code = 'shop';
				include NFE_PATH.'/include/service/'.$_GET['code'].'.service.inc.php';
			}
			?>
		</div>
	</section>
	 <div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('sub_etc_I');
		echo $banner_arr['tag'];
		?>
	</div>
</div>



<!--푸터영역-->
<?php
include NFE_PATH.'/include/service_intro.box.php';
//include NFE_PATH.'/include/footer.php';
?>

