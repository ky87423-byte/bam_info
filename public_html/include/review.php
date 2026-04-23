<?php
$add_cate_arr = array('job_tema');
include_once "../engine/_core.php";

$m_title = "이용후기";

// : 다른데도 이렇게 해서 여기는 관리자글을 앞으로 하기로 했음.
$_ch_site_title_ = $env['site_title'].' '.$m_title;
$_ch_site_content_ = $env['meta_description'].' '.$m_title;

if(!$env['use_shop_guide']) {
	die($nf_util->move_url($nf_util->page_back(), "사용하지 않는 기능입니다."));
}

include '../include/header_meta.php';
include '../include/header.php';

include NFE_PATH.'/include/m_title.inc.php';

$search_where = $nf_search->shop();

$_where = "";
if($_GET['pno']) $_where .= " and ng.`pno`=".intval($_GET['pno']);

$order = " order by ng.visit desc";
if($env['use_shop_guide_sort']=='good') $order = " order by point_sum desc";
$best_query = $db->_query("select *, (ng.`good`-ng.`bad`) as point_sum from nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`view`=1 ".$nf_shop->shop_where.$_where." ".$order." limit 0, 5");

$q = "nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`view`=1 ".$nf_shop->shop_where.$nf_shop->service_where2.$search_where['where'].$_where;
$order = " order by ng.`rdate` desc";
if($_GET['order']=='point') $order = " order by ng.`point` desc, ng.`no` desc";
if($_GET['order']=='good') $order = " order by ng.`good` desc, ng.`no` desc";
if($_GET['order']=='visit') $order = " order by ng.`visit` desc, ng.`no` desc";


$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$guide_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var click_order = function(code) {
	var form = document.forms['fsearch1'];
	form.order.value = code;
	form.submit();
}
</script>
<section class="review_pg wrap1400">
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('sub_etc_A');
		echo $banner_arr['tag'];
		?>
	</div>
	<div class="best_r_wrap">
		<h2><img src="../images/icon/review_deco.png" alt="best후기 데코 아이콘">BEST후기</h2>
		<ul class="best_r">
			<?php
			$cnt = 1;
			while($best_row=$db->afetch($best_query)) {
				$shop_info = $nf_shop->shop_info($best_row);
				$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($best_row['mno']));
			?>
			<li>
				<a href="<?php echo NFE_URL;?>/board/review_view.php?no=<?php echo $best_row['no'];?>">
					<p class="class"><?php echo $cnt;?>위</p>
					<div class="shop_img">
						<img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="샵이미지">
						<p class="shop_name line1"><?php echo $nf_util->get_text($best_row['wr_company']);?></p>
					</div>
					<div class="ev_wrap">
						<ul class="ev">
							<?php if($env['use_shop_guide_sort']=='visit') {?>
							<li class="see"><i class="axi axi-visibility"></i><b><?php echo $best_row['visit'];?></b></li>
							<?php } else {?>
							<li class="see"><i class="axi axi-thumbs-o-up"></i><b><?php echo $best_row['point_sum'];?></b></li> <!--best기준이 공감순일때 출력-->
							<?php }?>
							<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $best_row['wr_avg_point'];?></li><?php }?>
						</ul>
						<p class="line1"><?php echo $nf_util->get_text(/*$best_row['nick']*/"익명");?></p>
					</div>
					<?php if(!$env['use_shop_guide_view_auth'] || ($env['use_shop_guide_view_auth'] && $member['no'])) {?>
					<dl>
						<dt class="line2"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $nf_util->get_text($best_row['subject']);?></dt>
						<dd class="line2"><?php echo $nf_util->get_text($best_row['content']);?></dd>
					</dl>
					<?php } else {?>
					<div class="ess_login">
						로그인 후 이용 후기를 보실 수 있습니다.
					</div>
					<?php }?>
				</a>
			</li>
			<?php
				$cnt++;
			}?>
		</ul>
	</div>
	
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('sub_etc_B');
		echo $banner_arr['tag'];
		?>
	</div>

	<form name="fsearch1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
	<input type="hidden" name="order" value="" />
	<div class="r_head">
		<div>
			<ul class="order">
				<li class="<?php echo $_GET['order'] ? '' : 'on';?>"><a href="#none" onClick="click_order('')">최신순</a></li>
				<li class="<?php echo $_GET['order']=='good' ? 'on' : '';?>"><a href="#none" onClick="click_order('good')">공감순</a></li>
				<li class="<?php echo $_GET['order']=='visit' ? 'on' : '';?>"><a href="#none" onClick="click_order('visit')">조회순</a></li>
				<?php if($env['use_shop_point']) {?><li class="<?php echo $_GET['order']=='point' ? 'on' : '';?>"><a href="#none" onClick="click_order('point')">평점순</a></li><?php }?>
			</ul>
			<p class="t_num">총 <b><?php echo number_format($_arr['total']);?></b>개의 후기</p>
		</div>
		<ul class="select parent-">
			<?php
			for($i=1; $i<=$area_depth; $i++) {
				$area1 = $_GET['wr_area'][0];
				if($i>1) $area2 = $_GET['wr_area'][1];
				$nf_category->get_area($area1, $area2);
				$area_array = array();
				if($i===1) $area_array = $cate_p_array['area'][0];
				else if($i===2 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['SI'][$area1];
				else if($i===3 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['GU'][$area1][$area2];
			?>
			<li class="area_search">
				<select name="wr_area[]" wr_type="area" hname="<?php echo $i;?>차지역" needed <?php if($i<$area_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
					<option value=""><?php echo $i;?>차 지역</option>
					<?php
					if(is_array($area_array)) { foreach($area_array as $k=>$v) {
						$selected = $_GET['wr_area'][$i-1]==$v['wr_name'] ? 'selected' : '';
					?>
					<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?> no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
					<?php
					} }?>
				</select>
			</li>
			<?php
			}?>
			<li class="search_input">
				<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>" placeholder="검색어입력"><button type="submit" class="rv_search"><i class="axi axi-search"></i></button>
			</li>
		</ul>
	</div>
	</form>

	<ul class="r_body">
		<?php
		switch($_arr['total']<=0) {
			case true:
		?>
		<li class="no_info">검색된 이용후기 내역이 없습니다.</li>
		<?php
			break;

			default:
				while($guide_row=$db->afetch($guide_query)) {
					$shop_info = $nf_shop->shop_info($guide_row);
					$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($guide_row['mno']));
		?>
		<li>
			<a href="<?php echo NFE_URL;?>/board/review_view.php?no=<?php echo $guide_row['no'];?>">
				<div class="shop_info">
					<p><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="샵이미지"></p>
					<div>
						<p class="shop_name line1"><?php echo $nf_util->get_text($guide_row['wr_company']);?></p>
						<p class="shop_title line2"><?php echo $nf_util->get_text($guide_row['wr_subject']);?></p>
						<ul class="ev">
							<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i>평점 <?php echo intval($guide_row['wr_avg_point']);?></li><?php }?>
							<li class="heart line1"><i class="axi axi-heart2"></i>좋아요 <?php echo intval($guide_row['wr_good']);?></li>
							<li class="location line1"><i class="axi axi-location-on"></i><?php echo $shop_info['area_end'];?></li>
						</ul>
					</div>
				</div>
				<div class="r_con">
					<?php if(!$env['use_shop_guide_view_auth'] || ($env['use_shop_guide_view_auth'] && $member['no'])) {?>
						<dl>
							<dt class="line1"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $nf_util->get_text($guide_row['subject']);?></dt>
							<dd class="line2"><?php echo $nf_util->get_text($guide_row['content']);?></dd>
						</dl>
						<ul>
							<li><?php echo date("Y.m.d", strtotime($guide_row['rdate']));?></li>
							<li class="line1"><?php echo "익명";?></li>
							<li><i class="axi axi-visibility"></i> <?php echo number_format($guide_row['visit']);?></li>
						</ul>
					<?php } else {?>
						<div class="ess_login">
							로그인 후 이용 후기를 보실 수 있습니다.
						</div>
					<?php }?>
				</div>
			</a>
			<div class="ev2">
				<dl class="pointer" onClick="nf_shop.good(this, 'guide', '<?php echo $guide_row['no'];?>')" good="1">
					<dt>공감</dt>
					<dd><i class="axi axi-thumbs-o-up"></i><em class="int--"><?php echo $guide_row['good']-$guide_row['bad'];?></em></dd>
				</dl>
				<?php if($env['use_shop_point']) {?>
				<dl>
					<dt>평점</dt>
					<dd><i class="axi axi-star3"></i><?php echo intval($guide_row['point']);?></dd>
				</dl>
				<?php }?>
			</div>
		</li>
		<?php
				}
			break;
		}
		?>
	</ul>
	<div><?php echo $paging['paging'];?></div>

	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('sub_etc_C');
		echo $banner_arr['tag'];
		?>
	</div>
</section>


<?php
include NFE_PATH.'/include/footer.php';
?>