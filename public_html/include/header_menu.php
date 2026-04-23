<style type="text/css">
.menu_open.none- { display:none; }
.area-tab- { display:none !important; }
.area-tab-.on { display:flex !important; }
</style>
<div class="header_menu">
	<div class="wrap1400">
		<h1 class="logo"><a href="<?php echo main_page;?>" style="text-decoration:none;">
<span class="bt-logo-text"><span class="bam">BAM</span><span class="tube">tube</span></span>
</a></h1>
		<ul class="main_menu">
			<li><a href="<?php echo NFE_URL;?>/board/chulsuk.php">출석체크</a></li>
			<li class="<?php echo $_GET['code']=='area' ? 'on' : '';?> over- area-" code="area"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=area">지역별</a></li>
			<?php if($env['use_shop_tema']) {?><li class="<?php echo $_GET['code']=='tema' ? 'on' : '';?> over-" code="tema"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=tema">테마별</a></li><?php }?>
			<?php if($env['use_shop_industry']) {?><li class="<?php echo $_GET['code']=='category' ? 'on' : '';?>over- category-" code="category"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=category">업종별</a></li><?php }?>
			<?php // <li><a href="<?php echo NFE_URL;/include/location_view.php?code=location">출석체크</a></li> ?>
			<?php if($env['use_shop_menu_map']) {?><li><a href="<?php echo NFE_URL;?>/map/index.php">지도검색</a></li><?php }?>
			<?php if($env['use_shop_guide']) {?><li><a href="<?php echo NFE_URL;?>/include/review.php">이용후기</a></li><?php }?>
			<?php
			if(is_array($nf_board->board_menu_view[0])) { foreach($nf_board->board_menu_view[0] as $k=>$v) {
				$child_bo_table = $nf_board->board_botable_arr[$k];
				if(count($child_bo_table)<=0) continue;
			?>
			<li><a href="<?php echo NFE_URL;?>/board/index.php?cno=<?php echo intval($k);?>"><?php echo $v['wr_name'];?></a></li>
			<?php
			} }?>
			<li><a href="<?php echo NFE_URL;?>/board/ranking.php">랭킹</a></li>
			<?php //<li><a href="<?php echo NFE_URL;/service/event_list.php">할인쿠폰</a></li> ?>
		</ul>
		<ul class="s_menu">
			<li class="note style1"><a href="<?php echo NFE_URL;?>/member/mail.php"><i class="axi axi-mail"></i><?php if($my_message_cnt) {?><span class="new_note">NEW</span><?php }?></a></li>
			<li class="my style1"><a href="<?php echo NFE_URL;?>/mypage/index.php"><i class="axi axi-perm-identity"></i></a></li>
			<li class="enter_store"><a href="<?php echo NFE_URL;?>/service/advert.php">입점문의</a></li>
			<li class="search_icon style1"><a href="#none" onClick="nf_util.openWin('.m_search_bg', 'block')"><i class="axi axi-search"></i></a></li> <!--header.php에 클래스값 m_search_bg 로 영역 만들어져있음. 클릭시 m_search_bg 영역 나오게.-->
			<li class="m_menubtn style1"><a href="#none" onClick="nf_util.openWin('.m-menu-body-', 'block')"><i class="axi axi-menu2"></i></a></li>
		</ul>
	</div>
	<div class="menu_open skin1 none- area-">
		<div class="deps1">
			<ul class="wrap1400 parent-ul-">
				<?php
				if(is_Array($cate_p_array['area'][0])) { foreach($cate_p_array['area'][0] as $k=>$v) {
					$on = $v['wr_name']==$_GET['wr_area'][0] ? 'on' : '';
				?>
				<li class="<?php echo $on;?>" onClick="nf_shop.click_tab(this, 'area', '<?php echo $v['wr_name'];?>')"><a href="#none"><?php echo $v['wr_name'];?></a></li>
				<?php

					// : 2차지역 ############
					ob_start();
					?>
					<ul class="wrap1400 area-tab- ul-tabs- area-tab-<?php echo $v['no']?>- <?php echo $on;?>" style="display:none;">
						<li class="<?php echo $_GET['wr_area'][0]==$v['wr_name'] && !$_GET['wr_area'][1] ? 'on' : '';?>"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=area&wr_area[]=<?php echo $v['wr_name'];?>"><?php echo $v['wr_name'];?>전체</a></li>
						<?php
						$area1 = $v['wr_name'];
						$nf_category->get_area($area1);
						$area_array = $cate_area_array['SI'][$area1];
						if(is_Array($area_array)) { foreach($area_array as $k2=>$v2) {
							$on = $v2['wr_name']==$_GET['wr_area'][1] ? 'on' : '';
						?>
						<li class="<?php echo $on;?>"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=area&wr_area[]=<?php echo $v['wr_name'];?>&wr_area[]=<?php echo $v2['wr_name'];?>"><?php echo $v2['wr_name'];?></a></li>
						<?php
						} }?>
					</ul>
					<?php
					$area_second .= ob_get_clean();
					###################
				} }?>
			</ul>
		</div>
		<div class="deps2">
			<?php
			echo $area_second;
			?>
		</div>
	</div>
	<div class="menu_open skin2 none- category-">
		<div class="deps1">
			<ul class="wrap1400">
				<?php
				if(is_Array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
					$on = $v['no']==$_GET['area'][0] ? 'on' : '';
				?>
				<li class="on"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=category&category[]=<?php echo $k;?>"><?php echo $v['wr_name'];?>(<?php echo number_format($job_part_cnt[$k]['c']);?>)</a></li>
				<?php
				} }?>
			</ul>
		</div>
	</div>
	<div class="menu_open skin2 none- tema-">
		<div class="deps1">
			<ul class="wrap1400">
				<?php
				if(is_Array($cate_p_array['job_tema'][0])) { foreach($cate_p_array['job_tema'][0] as $k=>$v) {
					$on = $v['no']==$_GET['tema'][0] ? 'on' : '';
				?>
				<li class="on"><a href="<?php echo NFE_URL;?>/include/category_view.php?code=tema&tema[]=<?php echo $k;?>"><?php echo $v['wr_name'];?>(<?php echo number_format($job_tema_cnt[$k]['c']);?>)</a></li>
				<?php
				} }?>
			</ul>
		</div>
	</div>
</div>


<?php
// : 스크롤 광고
include NFE_PATH.'/include/scroll_banner.php';
?>

<script type="text/javascript">
var hover_item = '';
$(".main_menu").find("li").hover(function(){
	$(".menu_open").addClass("none-");
	var get_code = $(this).attr("code");
	hover_item = get_code;
	$(".menu_open."+get_code+"-").removeClass("none-");
}, function(){
	$(".menu_open").hover(function(){
		$(".menu_open").addClass("none-");
		$(".menu_open."+hover_item+"-").removeClass("none-");
	}, function(){
		$(".menu_open").addClass("none-");
	});

	$(".header_menu").hover(function(){
	}, function(){
		$(".menu_open").addClass("none-");
	});
});
</script>