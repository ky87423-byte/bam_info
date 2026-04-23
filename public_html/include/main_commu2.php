<?php
if($env['use_shop_guide'] && $env['use_shop_guide_best']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_guide_date'])." day"));
	$order = "order by ng.`visit` desc";
	if($env['use_shop_guide_sort']=='good') $order = "order by good_bad desc";
	$int_query = $db->query_fetch_rows("select *, (ng.good-ng.bad) as good_bad from nf_shop as ns right join nf_guide as ng force index(rdate) on ns.`no`=ng.`pno` where ng.`rdate`>='".$date_cnt." 00:00:00' ".$order." limit 0, ".intval($env['use_shop_guide_best_int']));
	$cnt = count($int_query);
	$comm_code = 'shop_guide';
}
if($env['use_shop_top']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_top_date'])." day"));
	$int_query = $db->query_fetch_rows("select *, sum(cnt) as sum_cnt from nf_board_int force index(basic) where code='hit' and `wdate`>='".$date_cnt." 00:00:00' group by `bo_table`, `pno` order by sum_cnt desc limit 0, ".intval($env['use_shop_top_int']));
	$cnt = count($int_query);
	$comm_code = 'board';
}
if($env['use_shop_chu']) {
	$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_chu_date'])." day"));
	$int_query = $db->query_fetch_rows("select *, sum(cnt) as sum_cnt from nf_board_int force index(basic) where code='good' and `wdate`>='".$date_cnt." 00:00:00' group by `bo_table`, `pno` order by sum_cnt desc limit 0, ".intval($env['use_shop_chu_int']));
	$cnt = count($int_query);
	$comm_code = 'board';
}
if($cnt<=0) return false;
?>
<h2><?php echo $txt_comm;?> <?php if($txt_comm=='BEST후기') {?><a href="<?php echo NFE_URL;?>/include/review.php"><span>MORE</span><em></em></a><?php }?></h2>
<div class="only_review">
	<ul>
		<?php
		for($i=0; $i<$cnt; $i++) {

			switch($comm_code) {
				case "shop_guide":
					$b_row = $int_query[$i];
					$num_txt = $i<3 ? '<img src="../images/'.($i+1).'st.png" alt="메달">' : $i+1;

					$cate = $nf_util->get_text($int_query[$i]['wr_company']);
					$link = NFE_URL.'/board/view.php?bo_table='.$hit_query[$i]['bo_table'].'&no='.$int_query[$i]['pno'];
					$link = NFE_URL.'/shop/index.php?no='.$int_query[$i]['pno'];
					$subject = $nf_util->get_text($b_row['wr_subject']);
					$content = $nf_util->get_text($b_row['wr_content']);
				break;

				case "board":
					$b_row = $db->query_fetch("select * from nf_write_".$int_query[$i]['bo_table']." where `wr_no`=".intval($int_query[$i]['pno']));
					$num_txt = $i<3 ? '<img src="../images/'.($i+1).'st.png" alt="메달">' : $i+1;

					$cate = $nf_board->board_table_arr[$int_query[$i]['bo_table']]['bo_subject'];
					$link = NFE_URL.'/board/view.php?bo_table='.$hit_query[$i]['bo_table'].'&no='.$int_query[$i]['pno'];
					$link = NFE_URL.'/shop/index.php?no='.$int_query[$i]['pno'];
					$subject = $nf_util->get_text($b_row['wr_subject']);
					$content = $nf_util->get_text($b_row['wr_content']);
				break;
			}
			if(!$b_row) continue;
		?>
		<li class="<?php echo $i<=2 ? 'first' : '';?>">
			<?php if($i<=2) {?><p class="line1 title"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $subject;?></p><?php }?>
			<a href="<?php echo $link;?>">
				<div class="info_wrap">
					<div class="wrap1">
						<p class="shop_name"><?php echo $cate;?></p>
						<div>
							<p class="num"><?php echo $num_txt;?></p>
							<dl>
								<dt class="line1"><span><img src="../images/user_icon.png" alt="유저등급별아이콘"></span><?php echo $subject;?></dt>
								<dd class="line2"><?php echo $content;?></dd>
							</dl>
						</div>
					</div>
					<div class="wrap2">
						<dl>
							<dt><i class="axi axi-heart2"></i></dt>
							<dd><?php echo ($comm_code = 'shop_guide') ? intval($int_query[$i]['good_bad']) : intval($int_query[$i]['cnt']);?></dd>
						</dl>
					</div>
				</div>
			</a>
		</li>
		<?php
		}?>
	</ul>

</div>