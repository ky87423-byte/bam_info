<?php
$q = "nf_shop as ns where `wr_service".$service_k."`>='".today."'".$nf_shop->shop_where.$search_where['where'];
$order = " order by `wr_jdate` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = $env['service_config_arr']['shop'][$service_k]['height'];
if(strpos($_SERVER['PHP_SELF'], '/include/')!==false) $_arr['num'] = $env['service_config_arr']['shop'][$service_k]['sub_height'];

$n_cnt = 3;
if($env['service_config_arr']['shop'][$service_k]['width']>$n_cnt) $n_cnt = $env['service_config_arr']['shop'][$service_k]['width'];
if(strpos($_SERVER['PHP_SELF'], '/include/')!==false) $n_cnt = $env['service_config_arr']['shop'][$service_k]['sub_width'];

$_arr['tema'] = 'B';
$_arr['anchor'] = '#shop02';
$_arr['var'] = 'page02';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

if($loc_field) $shop_query = $db->_query("select *, ".$loc_field." from ".$q." order by map_distance asc limit ".$paging['start'].", ".$_arr['num']);
else $shop_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<div class="anchor-class-" id="<?php echo substr($_arr['anchor'],1);?>"></div>
<section class="chu">
	<h2><img src="../images/icon/sub_h2_img02.png" alt="아이콘"><?php echo $nf_shop->service_name['shop']['main'][1];?><b>많은 고객님께서 추천하는 업체입니다.</b></h2>
	<!--n4 ~ n5 한줄 가로 li 갯수 / 관리자에서 지정-->
	<ul class="list_item chu_m n<?php echo $n_cnt;?>">
		<?php
		switch($total['c']<=0) {
			case true:
		?>
		<li class="no_info">추천 업체 내역이 없습니다.</li>
		<?php
			break;

			default:
				$cnt = 0;
				while($shop_row=$db->afetch($shop_query)) {
					$shop_info = $nf_shop->shop_info($shop_row);

					if($_GET['code']=='location') {
						$get_km = $nf_util->get_distance(array('this_lat'=>$nf_shop->this_area_pos_arr['lat'], 'this_lng'=>$nf_shop->this_area_pos_arr['lng'], 'lat'=>$shop_row['wr_lat'], 'lng'=>$shop_row['wr_lng']));
					}
		?>
		<li class="common" onClick="location.href='<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>'">
			<div>
				<div class="item_img">
					<p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p>
					<?php if($shop_row['wr_best']) {?><p class="best_icon"><img src="../images/best_icon.png" alt="best아이콘"></p><?php }?>
					<?php if($env['use_shop_tema'] && $shop_info['tema_cnt']>0) {?>
					<div class="shadow">
						<ul>
							<?php
							if(is_Array($shop_info['tema_arr'])) { foreach($shop_info['tema_arr'] as $k=>$v) {
							?>
							<li><?php echo $cate_array['job_tema'][$v];?></li>
							<?php
							} }?>
						</ul>
					</div>
					<?php }?>
					<p class="location location-latlng"><i class="axi axi-location-on"></i><?php echo $nf_util->get_distance_int($get_km);?></p>
				</div>
				<div class="item_info">
					<ul class="tag">
						<li class="area"><?php echo $shop_info['area_end'];?></li>
						<?php if($shop_row['wr_rdate']>=$shop_new_time) {?><li class="sticker">NEW</li><?php }?>
						<?php if($shop_info['coupon_use_check']) {?><li class="sticker c_p">쿠폰</li><?php }?>
					</ul>
					<button type="button" class="heart_tap scrap-star-" code="shop" no="<?php echo $shop_row['no'];?>"><i class="axi <?php echo $nf_util->is_scrap($shop_row['no'], 'shop') ? 'axi-heart2' : 'axi-heart';?>"></i></button>
					<h3><span class="line1"><?php echo $nf_util->get_text($shop_row['wr_company']);?></span></h3>
					<ul class="ev">
						<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $shop_row['wr_avg_point'];?></li><?php }?>
						<li class="heart"><i class="axi axi-heart2"></i><?php echo $shop_row['wr_good'];?></li>
						<?php if($env['use_shop_guide']) {?><li class="commu"><i class="axi axi-ion-chatbubble-working"></i><a href="<?php echo NFE_URL;?>/include/review.php?pno=<?php echo $shop_row['no'];?>" ><?php echo $shop_row['wr_guide_int'];?> <em>/ 후기</em></a></li><?php }?>
					</ul>
					<?php
					if($env['use_shop_price']) {
					?>
					<ul class="product">
						<?php if($shop_row['wr_sale']>0) {?><li class="sale"><span><?php echo $shop_row['wr_sale'];?></span>%</li><?php }?>
						<li class="price"><span><?php echo number_format($shop_row['wr_price']);?></span>원</li>
						<?php if($shop_row['wr_sale']>0) {?><li class="d_price"><?php echo number_format($shop_row['wr_price1']);?>원</li><?php }?>
					</ul>
					<?php
					}?>
				</div>
			</div>
		</li>
		<?php
					$cnt++;
				}
			break;
		}?>
	</ul>
	<div><?php echo $paging['paging'];?></div>
</section>