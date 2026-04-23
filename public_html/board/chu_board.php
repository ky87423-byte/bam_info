<?php
$_site_title_ = "커뮤니티";
include "../engine/_core.php";

if(!$_GET['cno']) $_GET['cno'] = $nf_board->board_botable_k_arr[0];

include '../include/header_meta.php';
include '../include/header.php';

if(trim($_GET['search_keyword'])) {
	$_table = $nf_board->get_table($_GET['bo_table']);
	$nf_search->insert(array('code'=>'board', 'content'=>trim($_GET['search_keyword'])));
}

$m_title = $nf_board->board_menu[0][$_GET['cno']]['wr_name'];
include NFE_PATH.'/include/m_title.inc.php';
?>

	<section class="my_sub wrap1400">
		<!--개인서비스 왼쪽 메뉴-->
		<?php include '../include/board_leftmenu.php'; ?>
		<div class="my_con">
			<?php if(stripslashes($bo_row['bo_content_head'])) {?>
			<div class="commu_t_txt"><?php echo stripslashes($bo_row['bo_content_head']);?></div>
			<?php }?>
			<section class="commu board_list">
				<div class="side_con">
					<ul class="fl title_btn">
						<li><a href="<?php echo NFE_URL;?>/board/inquiry_board.php?cno=<?php echo $_GET['cno'];?>">TOP조회<em> 게시물</em></a></li>
						<li class="on"><a href="<?php echo NFE_URL;?>/board/chu_board.php?cno=<?php echo $_GET['cno'];?>">TOP추천<em> 게시물</em></a></li>
						<li><a href="<?php echo NFE_URL;?>/board/index.php?cno=<?php echo $_GET['cno'];?>">커뮤니티 홈</a></li>
					</ul>
					<form name="fbsearch" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<input type="hidden" name="bo_table" value="<?php echo $nf_util->get_html($bo_table);?>" />
					<input type="hidden" name="cno" value="<?php echo $nf_util->get_html($_GET['cno']);?>" />
					<input type="hidden" name="bunru" value="<?php echo $nf_util->get_html($_GET['bunru']);?>" />
					<ul class="fr">
						<li>
							<select name="search_field">
								<option value="">선택</option>
								<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>제목</option>
								<option value="sub+con" <?php echo $_GET['search_field']=='sub+con' ? 'selected' : '';?>>제목+내용</option>
							</select>
							<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
							<button>검색</button>
						</li>
						<li>
							<select name="page_row" onChange="nf_util.ch_page_row(this, 'fbsearch')">
								<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
								<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
								<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
								<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
							</select>
						</li>
					</ul>
					</form>
				</div>


				

				<?php if($bo_row['bo_category_list']) {?>
				<ul class="tab_menu">
					<li class="<?php echo $_GET['bunru'] ? '' : 'on';?>"><a href="./list.php?bo_table=<?php echo $bo_table;?>">전체</a></li>
					<?php
					if(is_Array($board_info['bo_category_list_arr'])) { foreach($board_info['bo_category_list_arr'] as $k=>$v) {
						$on = $v==$_GET['bunru'] ? 'on' : '';
					?>
					<li class="<?php echo $on;?>"><a href="./list.php?bo_table=<?php echo $bo_table;?>&bunru=<?php echo $v;?>"><?php echo $nf_util->get_text($v);?></a></li>
					<?php
					} }?>
				</ul>
				<?php }?>

				<?php
				// : 게시판 리스트
				$skin = 'text';
				$list_skin = $skin;
				$cno = $_GET['cno'];

				$get_union_all = $nf_board->union_all($_GET['cno']);

				$_table = $nf_board->get_table($_GET['bo_table']);
				$board_q = $nf_board->board_query($_GET['bo_table']);
				$date_cnt = date("Y-m-d", strtotime("-".intval($env['use_shop_chu_date'])." day"));
				$q = "nf_board_int force index(basic) where code='good' and `wdate`>='".$date_cnt." 00:00:00'";
				$order = " order by sum_cnt desc";
				$group = " group by `bo_table`, `pno`";

				$total = $db->query_fetch("select count(distinct `bo_table`, `pno`) as c from ".$q);

				$_arr = array();
				if($skin!='admin') $_arr['tema'] = 'B';
				$_arr['num'] = 15;
				if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
				$_arr['total'] = $total['c'];
				$paging = $nf_util->_paging_($_arr);

				$b_q = "select *, sum(cnt) as sum_cnt from ".$q.$group.$order." limit ".$paging['start'].", ".$_arr['num'];
				$query = $db->_query($b_q);

				include './chu_list.inc.php';
				?>

				<?php if(stripslashes($bo_row['bo_content_tail'])) {?>
				<div class="commu_b_txt"><?php echo stripslashes($bo_row['bo_content_tail']);?></div>
				<?php }?>

			</section>
		</div>
	</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>
