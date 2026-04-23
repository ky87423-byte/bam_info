<?php
$q = "nf_shop as ns where 1 " . $nf_shop->service_where2 . $nf_shop->shop_where . $search_where['where'];
$order = " order by `wr_jdate` desc";

$total = $db->query_fetch("select count(*) as c from " . $q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
$_arr['var'] = 'page05';
$_arr['anchor'] = '#shop05';
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
<section class="partner">
	<h2>제휴업체<b>총 <em><?php echo number_format($total['c']); ?></em>개의 업체가 검색되었습니다.</b></h2>
	<table class="style2" style="table-layout:fixed;">
		<colgroup>
			<col style="width:10%;">
			<col style="width:20%">
			<col style="">
			<col style="width:20%">
			<?php if ($env['use_shop_price']) { ?>
				<col style="width:11%">
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<th class="bln brn">지역</th>
				<th class="bln brn">업체명</th>
				<th class="bln brn">제목</th>
				<th class="bln brn">이용정보</th>
				<?php if ($env['use_shop_price']) { ?>
					<th class="bln brn">금액</th>
				<?php } ?>
			</tr>
		</thead>
	</table>
	<ul class="partner_list">
		<?php
		switch ($total['c'] <= 0) {
			case true:
				?>
				<li class="no_info">제휴업체 내역이 없습니다.</li>
				<?php
				break;

			default:
				$cnt = 0;
				$shop_li_arr = array(); // 🔥 li 저장 배열
		
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
					<li onClick="location.href='<?php echo NFE_URL; ?>/shop/index.php?no=<?php echo $shop_row['no']; ?>';">
						<div class="area">
							<p class="line1"><?php echo $shop_info['area_end']; ?></p>
						</div>

						<div class="c_name">
							<p class="line1"><?php echo $nf_util->get_text($shop_row['wr_company']); ?></p>
						</div>

						<div class="title <?php echo $env['use_shop_price'] ? '' : 'price-none-'; ?>">
							<p class="line1"><?php echo $nf_util->get_text($shop_row['wr_subject']); ?></p>
						</div>

						<div class="review">
							<ul class="ev">
								<?php if ($env['use_shop_point']) { ?>
									<li class="star"><i class="axi axi-star3"></i><?php echo $shop_row['wr_avg_point']; ?></li>
								<?php } ?>
								<li class="heart"><i class="axi axi-heart2"></i><?php echo $shop_row['wr_good']; ?></li>
								<?php if ($env['use_shop_guide']) { ?>
									<li class="commu">
										<i class="axi axi-ion-chatbubble-working"></i><?php echo $shop_row['wr_guide_int']; ?>
									</li>
								<?php } ?>
								<li class="location location-latlng">
									<i class="axi axi-location-on"></i><?php echo $nf_util->get_distance_int($get_km); ?>
								</li>
							</ul>
						</div>

						<?php if ($env['use_shop_price']) { ?>
							<div class="amount">
								<span class="line1"><?php echo number_format($shop_row['wr_price']); ?>원</span>
							</div>
						<?php } ?>
					</li>
					<?php

					$shop_li_arr[] = ob_get_clean(); // 🔥 HTML 저장
					$cnt++;
				}

				// 🎲 랜덤 섞기
				shuffle($shop_li_arr);

				// 🔥 출력
				foreach ($shop_li_arr as $li) {
					echo $li;
				}
				break;
		}
		?>
	</ul>
	<div><?php echo $paging['paging']; ?></div>
</section>