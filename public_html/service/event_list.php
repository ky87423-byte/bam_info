<?php
$add_cate_arr = array('job_tema');
include_once "../engine/_core.php";
$m_title = "할인쿠폰";

// : 다른데도 이렇게 해서 여기는 관리자글을 앞으로 하기로 했음.
$_ch_site_title_ = $env['site_title'].' '.$m_title;
$_ch_site_content_ = $env['meta_description'].' '.$m_title;

include '../include/header_meta.php';
include '../include/header.php';

$search_where = $nf_search->shop();

$_where = "";
$StartDate = date("Y-m-d");
$EndDate = date("Y-m-d");
$_where .= " and (('$StartDate' <= coupon_date1 AND '{$EndDate}' >= coupon_date1) OR ('{$StartDate}' <= coupon_date2 AND '{$EndDate}' >= coupon_date2) OR (coupon_date1 <= '{$EndDate}' AND coupon_date2 >= '{$EndDate}'))";

$q = "nf_shop as ns where ns.`coupon_use`=1 and ((ns.`coupon_allow_int`-ns.`coupon_use_int`)>0) ".$nf_shop->service_where2.$nf_shop->shop_where.$_where.$search_where['where'];
$order = " order by ns.`no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 20;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$coupon_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

include NFE_PATH.'/include/m_title.inc.php';
?>
<div class="event_list wrap1400">
	<!--모바일에서 m_fiter_btn 클릭시 클래스filter_wrap 값 display:block 으로 노출시키기 -->
	<div class="m_fiter_btn">
		<button type="button" onClick="click_filter('block')"><i class="axi axi-ion-android-mixer"></i>상세검색</button>
	</div>
<!--필터-->
<?php include NFE_PATH.'/include/filter.php'; ?>

	<div class="wrap1070">
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('sub_etc_D');
			echo $banner_arr['tag'];
			?>
		</div>
		<section class="">
			<div class="coupon_info">
				<img src="../images/icon/coupon_img.png" alt="할이쿠폰 아이콘 이미지">	
				<ul>
					<li>* 원하시는 할인쿠폰 리스트에 들어가 <b>[쿠폰 다운받기]</b>를 클릭해주세요.</li>
					<li>* 다운 받은 쿠폰은 <b>[내정보 > 마이쿠폰]</b>에서 확인할 수 있습니다.</li>
					<li>* 샵 이용시 쿠폰을 꼭 제시해주세요.</li>
				</ul>
			</div>
			<div class="coupon_list">
				<ul>
					<?php
					switch($total['c']<=0) {
						case true:
					?>
					<li class="no_info">할인쿠폰 내역이 없습니다.</li>
					<?php
						break;

						default:
							while($coupon_row=$db->afetch($coupon_query)) {
								$shop_info = $nf_shop->shop_info($coupon_row);
					?>
					<li>
						<a href="<?php echo NFE_URL;?>/include/event.php?no=<?php echo $coupon_row['no'];?>">
							<p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p>
							<div class="shadow">
								<ul>
									<?php
									$tema_arr = explode(",", $coupon_row['wr_tema']);
									if(is_array($tema_arr)) $tema_arr = array_diff($tema_arr, array(""));
									if(is_Array($tema_arr)) { foreach($tema_arr as $k=>$v) {
									?>
									<li><?php echo $cate_array['job_tema'][$v];?></li>
									<?php
									} }?>
								</ul>
							</div>
							<div class="item_info">
								<ul class="tag">
									<li class="area"><?php echo $shop_info['area_end'];?></li>
								</ul>
								<h3><span class="line1"><?php echo $nf_util->get_text($coupon_row['wr_company']);?></span></h3>
								<p class="title line1"><?php echo $nf_util->get_text($coupon_row['coupon_subject']);?></p>
								<div class="flex_wrap">
									<ul class="ev">
										<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $coupon_row['wr_avg_point'];?></li><?php }?>
										<li class="heart"><i class="axi axi-heart2"></i><?php echo $coupon_row['wr_good'];?></li>
										<li class="commu"><i class="axi axi-ion-chatbubble-working"></i><?php echo $coupon_row['wr_guide_int'];?></li>
									</ul>
									<ul class="product">
										<li class="sale"><span>쿠폰할인</span></li>
										<li class="price">￦<?php echo number_format($shop_info['coupon_price']);?></li>
									</ul>
								</div>
							</div>
						</a>
					</li>
					<?php
							}
						break;
					}
					?>
				</ul>
				<div><?php echo $paging['paging'];?></div>
			</div>
		</section>
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('sub_etc_E');
			echo $banner_arr['tag'];
			?>
		</div>
	</div>
</div>


<?php
//include NFE_PATH.'/include/footer.php';
?>