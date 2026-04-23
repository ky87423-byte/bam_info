<?php
$first_cate = $nf_board->first_cate;
if ($_GET['cno'])
	$first_cate = $_GET['cno'];
$board_menu_arr = $cate_p_array['board_menu'][$first_cate];
?>
<div class="board_leftmenu left_menu">
	<div class="sub_mymenu">
		<h2><?php echo $m_title; ?></h2>
		<?php
		$notice_que = $db->_query("select * from nf_notice as nn where 1 order by nn.`no` desc limit 0, 5");
		?>
		<div class="sub_mymenu notice">
			<a href="<?php echo NFE_URL; ?>/board/notice_list.php">
				<h2>공지사항</h2>
			</a>
			<ul>
				<?php
				while ($n_row = $db->afetch($notice_que)) {
					?>
					<li class="line1"><a
							href="<?php echo NFE_URL; ?>/board/notice_view.php?no=<?php echo $n_row['no']; ?>"><?php echo $n_row['wr_subject']; ?></a>
					</li>
					<?php
				} ?>
			</ul>
		</div>
		<?php
		if (is_array($board_menu_arr)) {
			foreach ($board_menu_arr as $no => $row) {
				if (!$row['wr_view'])
					continue;
				$board_sub_menu = $nf_board->board_code_arr[$first_cate][$no];
				$cnt = count($board_sub_menu);
				if ($cnt <= 0)
					continue;
				?>
				<dl>
					<dt><a href="#none"><?php echo $nf_util->get_text($row['wr_name']); ?><i
								class="axi axi-keyboard-arrow-down"></i></a></dt>
					<dd>
						<ul>
							<?php
							if (is_array($board_sub_menu)) {
								foreach ($board_sub_menu as $k => $left_row) {
									$on = $left_row['bo_table'] == $_GET['bo_table'] ? 'on' : '';
									?>
									<li class="<?php echo $on; ?>"><a
											href="<?php echo NFE_URL; ?>/board/list.php?bo_table=<?php echo $left_row['bo_table']; ?>"><?php echo $nf_util->get_text($left_row['bo_subject']); ?></a>
									</li>
									<?php
								}
							} ?>
						</ul>
					</dd>
				</dl>
				<?php
			}
		} ?>
	</div>
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('board_A');
		echo $banner_arr['tag'];
		?>
	</div>
</div>