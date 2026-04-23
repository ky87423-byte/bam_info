<?php
$q = "nf_shop as ns where `wr_service" . $service_k . "`>='" . today . "'" . $nf_shop->shop_where . $search_where['where'];
$order = " order by `wr_jdate` desc";

$total = $db->query_fetch("select count(*) as c from " . $q);

$_arr = array();
$_arr['num'] = $env['service_config_arr']['shop'][$service_k]['height'];
if (strpos($_SERVER['PHP_SELF'], '/include/category_view.php') !== false)
	$_arr['num'] = $env['service_config_arr']['shop'][$service_k]['sub_height'];
$_arr['tema'] = 'B';
$_arr['anchor'] = '#shop04';
if ($_GET['page_row'] > 0)
	$_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

if ($loc_field)
	$shop_query = $db->_query("select *, " . $loc_field . " from " . $q . " order by map_distance asc limit " . $paging['start'] . ", " . $_arr['num']);
else
	$shop_query = $db->_query("select * from " . $q . $order . " limit " . $paging['start'] . ", " . $_arr['num']);
?>
<div class="anchor-class-" id="<?php echo substr($_arr['anchor'], 1); ?>"></div>
<section class="grand">
	<h2><?php echo $nf_shop->service_name['shop']['main'][3]; ?><b>많은 고객님께서 찾으시는 업체입니다.</b></h2>
	<ul class="list_item">
		<?php
		switch ($total['c'] <= 0) {
			case true:
				?>
				<li class="no_info">그랜드 업체 내역이 없습니다.</li>
				<?php
				break;

			default:
				$cnt = 0;
				$shop_li_arr = array();

				while ($shop_row = $db->afetch($shop_query)) {
					$shop_info = $nf_shop->shop_info($shop_row);

					if ($_GET['code'] == 'location') {
						$get_km = $nf_util->get_distance(array(
							'this_lat' => $nf_shop->this_area_pos_arr['lat'],
							'this_lng' => $nf_shop->this_area_pos_arr['lng'],
							'lat' => $shop_row['wr_lat'],
							'lng' => $shop_row['wr_lng']
						));
					}

					ob_start(); // 🔥 출력 버퍼 시작
					?>
					<li>
						<a href="<?php echo NFE_URL; ?>/shop/index.php?no=<?php echo $shop_row['no']; ?>">
							<div class="wrap1">
								<span class="area">
									<em class="line1"><?php echo $shop_info['area_end']; ?></em>
								</span>
								<p class="line1">
									<span class="shop_name"><?php echo $nf_util->get_text($shop_row['wr_company']); ?></span>
									<?php echo $nf_util->get_text($shop_row['wr_subject']); ?>
								</p>
							</div>

							<div class="wrap2">
								<ul class="ev">
									<?php if ($env['use_shop_point']) { ?>
										<li class="star"><i class="axi axi-star3"></i><?php echo $shop_row['wr_avg_point']; ?></li>
									<?php } ?>
									<li class="heart"><i class="axi axi-heart2"></i><?php echo $shop_row['wr_good']; ?></li>
									<?php if ($env['use_shop_guide']) { ?>
										<li class="commu"><i
												class="axi axi-ion-chatbubble-working"></i><?php echo $shop_row['wr_guide_int']; ?></li>
									<?php } ?>
									<li class="location location-latlng">
										<i class="axi axi-location-on"></i>
										<?php echo $nf_util->get_distance_int($get_km); ?>
									</li>
								</ul>

								<?php if ($env['use_shop_price']) { ?>
									<span class="price"><?php echo number_format($shop_row['wr_price']); ?>원</span>
								<?php } ?>
							</div>
						</a>
					</li>
					<?php
					$shop_li_arr[] = ob_get_clean(); // 🔥 HTML 저장
				}
				shuffle($shop_li_arr); // 🎲 랜덤 섞기
		
				foreach ($shop_li_arr as $li) {
					echo $li;
				}
				break;
		} ?>
	</ul>

	<!--페이징-->
	<div><?php echo $paging['paging']; ?></div>
</section>