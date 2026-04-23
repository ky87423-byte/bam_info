<?php
$cnt = 0;
if($env['use_shop_guide'] && $env['use_shop_guide_best']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_guide_date'])." day"));
	$order = "order by ng.`visit` desc";
	if($env['use_shop_guide_sort']=='good') $order = "order by good_bad desc";
	$best_query = $db->query_fetch_rows("select *, (ng.good-ng.bad) as good_bad from nf_shop as ns right join nf_guide as ng force index(rdate) on ns.`no`=ng.`pno` where ng.`rdate`>='".$date_cnt." 00:00:00'".$order." limit 0, ".intval($env['use_shop_guide_best_int']));
	$guide_best_cnt = count($best_query);
	$cnt += $guide_best_cnt;
}
if($env['use_shop_top']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_top_date'])." day"));
	$hit_query = $db->query_fetch_rows("select *, sum(cnt) as sum_cnt from nf_board_int force index(basic) where code='hit' and `wdate`>='".$date_cnt." 00:00:00' group by `bo_table`, `pno` order by sum_cnt desc limit 0, ".intval($env['use_shop_top_int']));
	$board_top_cnt = count($hit_query);
	$cnt += $board_top_cnt;
}
if($env['use_shop_chu']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_chu_date'])." day"));
	$good_query = $db->query_fetch_rows("select *, sum(cnt) as sum_cnt from nf_board_int force index(basic) where code='good' and `wdate`>='".$date_cnt." 00:00:00' group by `bo_table`, `pno` order by sum_cnt desc limit 0, ".intval($env['use_shop_chu_int']));
	$board_chu_cnt = count($good_query);
	$cnt += $board_chu_cnt;
}
if($cnt<=0) return false;
?>

<h2>커뮤니티<a href="<?php echo NFE_URL;?>/board/index.php"><span>MORE</span><em></em></a></h2>
<div class="all_commu">
	<?php
	if($env['use_shop_guide_best']) {
	?>
	<ul class="best_review">
		<?php
		for($i=0; $i<$guide_best_cnt; $i++) {
			$num_txt = $i<3 ? '<img src="../images/'.($i+1).'st.png" alt="메달">' : $i+1;
		?>
		<li class="<?php echo $i===0 ? 'first' : '';?>">
			<?php if($i===0) {?><h3>BEST후기<a href="<?php echo NFE_URL;?>/include/review.php">more</a></h3><?php }?>
			<a href="<?php echo NFE_URL;?>/board/review_view.php?no=<?php echo $best_query[$i]['no'];?>">
				<div class="info_wrap">
					<div class="wrap1">
						<?php if($i===0) {?><p class="shop_name"><?php echo $nf_util->get_text($best_query[$i]['wr_company']);?></p><?php }?>
						<div>
							<p class="num"><?php echo $num_txt;?></p>
							<dl>
								<dt class="line1"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $nf_util->get_text($best_query[$i]['subject']);?></dt>
								<dd class="line2"><?php echo $nf_util->get_text($best_query[$i]['content']);?></dd>
							</dl>
						</div>
					</div>
					<div class="wrap2">
						<dl>
							<dt><i class="axi axi-heart2"></i></dt>
							<dd><?php echo intval($best_query[$i]['good_bad']);?></dd>
						</dl>
					</div>
				</div>
			</a>
		</li>
		<?php
		}?>
	</ul>
	<?php
	}
		
		
	if($env['use_shop_top']) {
	?>
	<ul class="top_post">
		<?php
		for($i=0; $i<$board_top_cnt; $i++) {
			$b_row = $db->query_fetch("select * from nf_write_".$hit_query[$i]['bo_table']." where `wr_no`=".intval($hit_query[$i]['pno']));
			$num_txt = $i<3 ? '<img src="../images/'.($i+1).'st.png" alt="메달">' : $i+1;
			if(!$b_row) continue;
		?>
		<li class="<?php echo $i===0 ? 'first' : '';?>">
			<?php if($i===0) {?><h3>TOP조회 게시물<a href="<?php echo NFE_URL;?>/board/inquiry_board.php">more</a></h3><?php }?>
			<a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $hit_query[$i]['bo_table'];?>&no=<?php echo $hit_query[$i]['pno'];?>">
				<div class="info_wrap">
					<div class="wrap1">
						<?php if($i===0) {?><p class="shop_name"><?php echo $nf_board->board_table_arr[$hit_query[$i]['bo_table']]['bo_subject'];?></p><?php }?>
						<div>
							<p class="num"><?php echo $num_txt;?></p>
							<dl>
								<dt class="line1"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $nf_util->get_text($b_row['wr_subject']);?></dt>
								<dd class="line2"><?php echo $nf_util->get_text($b_row['wr_content']);?></dd>
							</dl>
						</div>
					</div>
					<div class="wrap2">
						<dl>
							<dt><i class="axi axi-remove-red-eye"></i></dt>
							<dd><?php echo intval($hit_query[$i]['sum_cnt']);?></dd>
						</dl>
					</div>
				</div>
			</a>
		</li>
		<?php
		}?>
	</ul>
	<?php
	}


	if($env['use_shop_chu']) {
	?>
	<ul class="recommend_post">
		<?php
		for($i=0; $i<$board_chu_cnt; $i++) {
			$b_row = $db->query_fetch("select * from nf_write_".$good_query[$i]['bo_table']." where `wr_no`=".intval($good_query[$i]['pno']));
			$num_txt = $i<3 ? '<img src="../images/'.($i+1).'st.png" alt="">' : $i+1;
			if(!$b_row) continue;
		?>
		<li class="<?php echo $i===0 ? 'first' : '';?>">
			<?php if($i===0) {?><h3>추천 게시물<a href="<?php echo NFE_URL;?>/board/chu_board.php">more</a></h3><?php }?>
			<a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $good_query[$i]['bo_table'];?>&no=<?php echo $good_query[$i]['pno'];?>">
				<div class="info_wrap">
					<div class="wrap1">
						<?php if($i===0) {?><p class="shop_name"><?php echo $nf_board->board_table_arr[$good_query[$i]['bo_table']]['bo_subject'];?></p><?php }?>
						<div>
							<p class="num"><?php echo $num_txt;?></p>
							<dl>
								<dt class="line1"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $nf_util->get_text($b_row['wr_subject']);?></dt>
								<dd class="line2"><?php echo $nf_util->get_text($b_row['wr_content']);?></dd>
							</dl>
						</div>
					</div>
					<div class="wrap2">
						<dl>
							<dt><i class="axi axi-thumb-up"></i></dt>
							<dd><?php echo number_format($good_query[$i]['sum_cnt']);?></dd>
						</dl>
					</div>
				</div>
			</a>
		</li>
		<?php
		}?>
	</ul>
	<?php
	}?>
</div>

