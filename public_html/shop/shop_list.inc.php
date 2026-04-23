<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_A');
	echo $banner_arr['tag'];
	?>
</div>
<!--category_view 안에 있는 프리미엄 업체와 추천업체의 상품 가로 갯수는 3~4개만 되게 : 클래스값 n3과 n4만 되게-->
<?php
$service_k = '0_0';
if($env['service_config_arr']['shop'][$service_k]['use']) 
	include NFE_PATH.'/include/adver/shop_01.php';
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_B');
	echo $banner_arr['tag'];
	?>
</div>
<?php
$service_k = '0_1';
if($env['service_config_arr']['shop'][$service_k]['use'])
	include NFE_PATH.'/include/adver/shop_02.php';
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_C');
	echo $banner_arr['tag'];
	?>
</div>
<?php
$service_k = '0_2';
if($env['service_config_arr']['shop'][$service_k]['use'])
	include NFE_PATH.'/include/adver/shop_03.php';
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_D');
	echo $banner_arr['tag'];
	?>
</div>
<?php
$service_k = '0_3';
if($env['service_config_arr']['shop'][$service_k]['use'])
	include NFE_PATH.'/include/adver/shop_04.php';
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_E');
	echo $banner_arr['tag'];
	?>
</div>
<?php
/*
$cnt = 0;
if($env['service_config_arr']['shop']['job_part']['use'] && $env['use_shop_industry'] && is_array($cate_array['job_part'])) { foreach($cate_array['job_part'] as $part_k=>$part_v) {
	$job_part_val = $part_k;
	$job_part_txt = $part_v;
	include NFE_PATH.'/include/adver/shop_06.php';
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_category_'.$part_k);
	echo $banner_arr['tag'];
	?>
</div>
<?php
	$cnt++;
} }
*/
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_F');
	echo $banner_arr['tag'];
	?>
</div>
<?php
if($env['use_shop_list']) {
	include NFE_PATH.'/include/adver/shop_05.php';
}
?>
<div class="banner" style="overflow:hidden;">
	<?php
	$banner_arr = $nf_banner->banner_view('sub_G');
	echo $banner_arr['tag'];
	?>
</div>