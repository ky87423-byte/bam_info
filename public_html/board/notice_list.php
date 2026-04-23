<?php
include_once "../engine/_core.php";

$nf_util->sess_page_save("notice_list");

$_site_title_ = '공지사항';
include '../include/header_meta.php';
include '../include/header.php';

$_where = "";
if($_GET['bunru']) $_where .= " and `wr_type`='".addslashes($_GET['bunru'])."'";
if($_GET['search_input']) {
	$_search['subject'] = "wr_subject like '%".addslashes($_GET['search_input'])."%'";
	$_search['sub+con'] = "(wr_subject like '%".addslashes($_GET['search_input'])."%' or wr_content like '%".addslashes($_GET['search_input'])."%')";
	$_where = " and (".$_search[$_GET['search_k']].")";
}

$q = "nf_notice as nn where 1 ".$_where;
$order = " order by nn.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 10;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = $_GET['page_row'];
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$notice_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

$m_title = '공지사항';
include NFE_PATH.'/include/m_title.inc.php';
?>

	<section class="my_sub wrap1400">
		<!--커뮤 왼쪽 메뉴-->
		<?php include '../include/board_leftmenu.php'; ?>
		<div class="my_con">
			<section class="commu board_list">
				<div class="side_con">
					<ul class="fl">
						<li><h6>공지사항</h6></li>
					</ul>
					<form name="fsearch1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
					<ul class="fr">
						<li>
							<select name="search_k">
								<option value="subject" <?php echo $_GET['search_k']=='subject' ? 'selected' : '';?>>제목</option>
								<option value="sub+con" <?php echo $_GET['search_k']=='sub+con' ? 'selected' : '';?>>제목+내용</option>
							</select>
							<input type="text" name="search_input" value="<?php echo $nf_util->get_html($_GET['search_input']);?>">
							<button>검색</button>
						</li>
						<li>
							<select name="page_row" onChange="nf_util.ch_page_row(this, 'fsearch1')">
								<option value="10" <?php echo $_GET['page_row']=='10' ? 'selected' : '';?>>10개출력</option>
								<option value="20" <?php echo $_GET['page_row']=='20' ? 'selected' : '';?>>20개출력</option>
							</select>
						</li>
					</ul>
					</form>
				</div>
				<!--//side_con-->

				<?php if(count($cate_p_array['notice'][0])>0) {?>
				<ul class="tab_menu">
					<li class="<?php echo $_GET['bunru'] ? '' : 'on';?>"><a href="./notice_list.php">전체</a></li>
					<?php
					if(is_Array($cate_p_array['notice'][0])) { foreach($cate_p_array['notice'][0] as $k=>$v) {
						$on = $v['wr_name']==$_GET['bunru'] ? 'on' : '';
					?>
					<li class="<?php echo $on;?>"><a href="./notice_list.php?bunru=<?php echo $v['wr_name'];?>"><?php echo $nf_util->get_text($v['wr_name']);?></a></li>
					<?php
					} }?>
				</ul>
				<?php }?>

				<?php
				include NFE_PATH.'/include/etc/notice_list.inc.php';
				?>
				<div><?php echo $paging['paging'];?></div>
			</section>
		</div>
	</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>
