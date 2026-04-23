<div class="main_con_wrap main_board">

	<section class="board">
		<?php
		if(is_array($nf_board->board_mrank_arr)) {
			asort($nf_board->board_mrank_arr);
			foreach($nf_board->board_mrank_arr as $bo_table=>$rank) {
				$bo_row = $nf_board->board_table_arr[$bo_table];
				$board_info = $nf_board->board_info($bo_row);
				$bo_print = $nf_board->main_row['print_main_un'][$bo_table];
				if(!$bo_print['view']) continue;

				$bo_type = $bo_print['print_type']=='talk' ? 'text' : $bo_print['print_type'];
				$cnt = $bo_print['print_cnt'];
				$img_width = $bo_print['img_width'];
				$img_height = $bo_print['img_height'];

				$_table = $nf_board->get_table($bo_table);
				$order = " order by wr_num, wr_reply";
				$q = $_table." as nwb where wr_reply='' ".$nf_board->list_where;
				$b_query = $db->_query("select * from ".$q.$order." limit 0, ".intval($cnt));

				include NFE_PATH.'/board/skin/main_'.$bo_type.'.inc.php';
			}
		}
		?>
	</section>
	<!--//board-->
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('main_L');
		echo $banner_arr['tag'];
		?>
	</div>

	<!--necessary 하단필수박스-->
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('main_M');
		echo $banner_arr['tag'];
		?>
	</div>
</div>