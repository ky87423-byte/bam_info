<?php
$add_cate_arr = array('job_tema');
include_once "../engine/_core.php";

$m_title = "지역별";
if($_GET['code']=='tema') $m_title = "테마별";
if($_GET['code']=='category') $m_title = "업종별";

$get_search_txt = $nf_shop->get_serach_text($_GET);

if($_GET['top_keyword']) $_GET['search_keyword'] = $_GET['top_keyword'];
$nf_search->insert(array('code'=>'search', 'content'=>$_GET['search_keyword']));

// : 다른데도 이렇게 해서 여기는 관리자글을 앞으로 하기로 했음.
$_ch_site_title_ = $get_search_txt ? $get_search_txt.' '.$m_title.' - '.$env['site_title'] : $m_title.' - '.$env['site_title'];
$_ch_site_content_ = $get_search_txt ? $get_search_txt.' '.$m_title.' - '.$env['meta_description'] : $m_title.' - '.$env['meta_description'];

include '../include/header_meta.php';
include '../include/header.php';

$search_where = $nf_search->shop();

include NFE_PATH.'/include/m_title.inc.php';
?>
<div class="category_view_wrap">
	<?php
		// : 스크롤 광고
		include NFE_PATH.'/include/scroll_banner.php';
		?>
	<div class="category_view wrap1400">
		
		<!--모바일에서 m_fiter_btn 클릭시 클래스filter_wrap 값 display:block 으로 노출시키기 -->
		<div class="m_fiter_btn">
			<button type="button" onClick="click_filter('block')"><i class="axi axi-ion-android-mixer"></i>상세검색</button>
		</div>
	<!--필터-->
	<?php include NFE_PATH.'/include/filter.php'; ?>

		<div class="wrap1070">
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
			<?php
			if($env['use_shop_list']) {
				include NFE_PATH.'/include/adver/shop_05.php';
			}
			?>
			<div class="banner" style="overflow:hidden;">
				<?php
				$banner_arr = $nf_banner->banner_view('sub_F');
				echo $banner_arr['tag'];
				?>
			</div>
		</div>
	</div>
</div><!--//category_view_wrap-->

<?php
include NFE_PATH.'/include/footer.php';
?>