<?php
$add_cate_arr = array('job_tema');
include './include/header_meta.php';
include './include/header.php';

$main_icon = $db->_query("select * from nf_icon where `wr_code`=? and `wr_use`=1 order by `wr_rank` asc limit 0, 8", array("main_icon"));
$main_icon_cnt = $db->num_rows($main_icon);
?>



<!--비주얼-->
<?php
$main_banner_row = $db->query_fetch("select * from nf_banner where `wr_position`='main_A' and `wr_view`=1 limit 1");
$_order = $main_banner_row['wr_roll_turn'] ? "rand()" : "`wr_rank` asc";
$banner_query = $db->_query("select * from nf_banner where `wr_position`='main_A'  and `wr_position`='main_A' and `wr_view`=1 order by ".$_order);
?>
<div class="main_visual">
	<div class="slider cycle-slideshow"
		<?php if($main_banner_row['wr_roll_type']) {?>
		data-cycle-fx=<?php echo $nf_banner->roll_direction_txt[$main_banner_row['wr_roll_direction']];?>
		<?php if(in_array($main_banner_row['wr_roll_direction'], array(1, 2))) {?>data-reverse="true"<?php }?>
		data-cycle-fx=scrollHorz
		data-cycle-timeout=<?php echo intval($main_banner_row['wr_roll_time'])*1000;?>
		data-cycle-slides="> .slItem"
		<?php }?>
	>
		<?php
		while($row=$db->afetch($banner_query)) {
			$get_banner = $nf_banner->get_banner($row);
		?>
		<div class="slItem" style="background-image:url('<?php echo NFE_URL.$nf_banner->attach.'/'.$row['wr_content'];?>')">
		<?php if($row['wr_url']) {?><a href="<?php echo $nf_util->get_http($row['wr_url']);?>" <?php echo $get_banner['target'];?>><?php }?>
		<?php if($row['wr_url']) {?></a><?php }?>
		</div>
		<?php
		}?>

		<?php
		/* 2번째 이미지가 이상하게 나옵니다.
		<div class="slItem" style="background-image:url('../images/visual01.png')"></div>
		<div class="slItem" style="background-image:url('../images/visual01.png')"></div>
		*/
		?>

		<?php if($main_banner_row['wr_roll_type']) {?>
		<div class="cycle-pager"></div>
		<?php }?>
	</div>

	<div class="wrap1260">
		<form name="fsearch_main" action="<?php echo NFE_URL;?>/include/location_view.php">
		<input type="hidden" name="code" value="location" />
		<div class="quick_search">
			<p class="title"><span>Q</span>uick Search</p>
            <?php
				if($env['use_shop_industry']) {
					for($j=1; $j<=$job_part_depth; $j++) {
						$cate_k = $j===1 ? 0 : $_GET['category'][$j-1];
					?>
					<select class="check_seconds" name="category[]" hname="업종" needed <?php if($j<$job_part_depth) {?>onChange="nf_category.ch_category(this, <?php echo $j;?>);"<?php }?>>
						<option value="">업종</option>
						<?php
						if(is_array($cate_p_array['job_part'][$cate_k])) { foreach($cate_p_array['job_part'][$cate_k] as $k=>$v) {
							$selected = $_GET['category'][$j-1]==$v['no'] ? 'selected' : '';
						?>
						<option value="<?php echo $v['no'];?>" no="<?php echo $v['no'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
						<?php
						} }?>
					</select>
					<?php
					}
				}?>
				<?php
				for($i=1; $i<=$area_depth; $i++) {
					$area1 = $shop_info['area_arr'][0];
					if($i>1) $area2 = $shop_info['area_arr'][1];
					$nf_category->get_area($area1, $area2);
					$area_array = array();
					if($i===1) $area_array = $cate_p_array['area'][0];
					else if($i===2 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['SI'][$area1];
					else if($i===3 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['GU'][$area1][$area2];
				?>
				<select name="wr_area[]" wr_type="area" hname="<?php echo $i;?>차지역" needed <?php if($i<$area_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
					<option value=""><?php echo $i;?>차 지역</option>
					<?php
					if(is_array($area_array)) { foreach($area_array as $k=>$v) {
						$selected = $shop_info['area_arr'][$i-1]==$v['wr_name'] ? 'selected' : '';
					?>
					<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?> no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
					<?php
					} }?>
				</select>
				<?php
				}?>
			<div class="key">
				<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>" placeholder="키워드 입력">
				<button type="submit">검색</button>
			</div>
		</div>
		</form>
	</div>
</div>

<?php if($env['main_icon_type']) {?>
<div class="main_quick">
	<div class="wrap1260">
		<!--n1 에서 n8까지 li 갯수 / 관리자에서 지정 가능하게-->
		<ul class="n<?php echo intval($main_icon_cnt);?>">
			<?php
			while($icon_row=$db->afetch($main_icon)) {
			?>
			<li>
				<a href="<?php echo $nf_util->get_domain($icon_row['wr_link']);?>">
					<p class="img"><img src="<?php echo NFE_URL.$nf_shop->attach_dir['icon'].$icon_row['wr_image'];?>" alt="<?php echo $nf_util->get_html($icon_row['wr_name']);?>"></p>
					<p class="title line1"><?php echo $nf_util->get_text($icon_row['wr_name']);?></p>
				</a>
			</li>
			<?php
			}
			?>
		</ul>
	</div>
</div>
<?php }?>


<div class="main_con_full">
	<?php
		// : 스크롤 광고
		include NFE_PATH.'/include/scroll_banner.php';
		?>
	<!--상품 영역-->
	<div class="main_con_wrap wrap1400">
		

		<?php
		$service_k = '0_0';
		?>
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_B');
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		if($env['service_config_arr']['shop'][$service_k]['use']) {
			include NFE_PATH.'/include/adver/shop_01.php';
		}
		?>

		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_C');
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		$service_k = '0_1';
		if($env['service_config_arr']['shop'][$service_k]['use']) {
			include NFE_PATH.'/include/adver/shop_02.php';
		}
		?>

		<?php
		$service_k = '0_2';
		?>
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_D');
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		if($env['service_config_arr']['shop'][$service_k]['use']) {
			include NFE_PATH.'/include/adver/shop_03.php';
		}
		?>

		<?php
		$service_k = '0_3';
		?>
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_E');
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		if($env['service_config_arr']['shop'][$service_k]['use']) {
			include NFE_PATH.'/include/adver/shop_04.php';
		}
		?>

		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_F');
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		if($env['service_config_arr']['shop']['job_part']['use'] && $env['use_shop_industry'] && is_array($cate_array['job_part'])) { foreach($cate_array['job_part'] as $part_k=>$part_v) {
			$job_part_val = $part_k;
			$job_part_txt = $part_v;
			include NFE_PATH.'/include/adver/shop_06.php';
		?>
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('main_category_'.$part_k);
			echo $banner_arr['tag'];
			?>
		</div>
		<?php
		} }
		?>

	</div>
	<!--//main_con_wrap-->

	<div class="main_con_wrap2">
		<div class="wrap1400">
			<div class="banner" style="overflow:hidden;">
				<?php
				$banner_arr = $nf_banner->banner_view('main_G');
				echo $banner_arr['tag'];
				?>
			</div>
			<?php

			// : 게시물 조회수, 추천수 쿼리문
			$cnt_cumm = 0;
			$txt_comm = '';
			$link_comm = '';
			if($env['use_shop_guide'] && $env['use_shop_guide_best']) { $txt_comm = 'BEST후기'; $cnt_cumm++; }
			if($env['use_shop_top']) { $txt_comm = 'TOP조회 게시물'; $cnt_cumm++; }
			if($env['use_shop_chu']) { $txt_comm = '추천 게시물'; $cnt_cumm++; }

			if($cnt_cumm>0) {
				// 2개 이상일때  main_commu1.php display:none;
				if($cnt_cumm>1) {
					include NFE_PATH.'/include/main_commu1.php';
				} else {
					include NFE_PATH.'/include/main_commu2.php';
				}
			}?>
			

			<!--커뮤니티 게시판영역-->
			<?php include NFE_PATH.'/include/board.php'; ?>

			<div class="banner" style="overflow:hidden;">
				<?php
				$banner_arr = $nf_banner->banner_view('main_H');
				echo $banner_arr['tag'];
				?>
			</div>
		</div>
	</div>
</div>


<button type="button" id="topButton" class="topButton">
	TOP<br>
	<i class="axi-ion-arrow-up-c"></i>
</button>

<script>
$(document).ready(function() {

	$(window).scroll(function() {
	    // top button controll
	    if ($(this).scrollTop() > 800) {
	        $('#topButton').fadeIn();
	    } else {
	        $('#topButton').fadeOut();
	    }
	});

	$("#topButton").click(function() {
		$('html, body').animate({scrollTop:0}, '300');
	});

});
</script>

<?php include NFE_PATH.'/include/footer.php'; ?>