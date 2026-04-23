<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = "600103";
include '../include/header.php';

$cnt = 0;
$first_bo_table = "";
if(is_array($nf_board->board_table_arr)) { foreach($nf_board->board_table_arr as $k=>$v) {
	if($cnt===0) $first_bo_table = $v['bo_table'];
	$cnt++;
} }

if(!$first_bo_table) {
	die($nf_util->move_url(NFE_URL."/admini/board/index.php", "게시판을 추가해주시기 바랍니다."));
}

$bo_table = trim($_GET['bo_table']);	
if(!$bo_table) $bo_table = $first_bo_table;
$_SESSION['board_admin_list_'.$bo_table] = $_SERVER['REQUEST_URI'];
$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
$board_info = $nf_board->board_info($bo_row);

// : 개수 자동계산
$_table = $nf_board->get_table($bo_table);
$board_cnt = $db->query_fetch("select count(*) as c from ".$_table." where 1 ".$nf_board->list_where);
$update = $db->_query("update nf_board set `bo_write_count`=".intval($board_cnt['c'])." where `bo_table`=?", array($bo_table));
?>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_board.class.js"></script>
<style type="text/css">
.board-write- { display:none; }
.tac.del- td { background-color:#f1f1f1; }
</style>
<script type="text/javascript">
var click_modify = function(el, no, code) {
	no = no ? no : '';
	code = code ? code : '';
	$.post("../regist.php", "mode=click_board_write_open&bo_table=<?php echo $bo_table;?>&code="+code+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 게시물관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 각 게시판들의 제목을 클릭하시면 해당 게시판의 게시물을 확인 할수 있습니다. </li>
					<li>- 각 게시판들의 제목을 클릭하시면 해당 게시판에 글을 등록할수 있으며 게시물 수정, 삭제가 가능합니다.  </li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<input type="hidden" name="bo_table" value="<?php echo $nf_util->get_html($bo_table);?>" />
				<div class="search ass_list">
					<table class="">
						<colgroup>
							<col width="8%">
						</colgroup>
						<tbody>
							<tr>
								<th>커뮤니티</th>
								<td>
									<ul>
										<?php
										if(is_array($nf_board->board_table_arr)) { foreach($nf_board->board_table_arr as $k=>$v) {
											$on = $bo_table==$v['bo_table'] ? 'on' : '';
											$cnt = $db->query_fetch("select count(distinct `pno`) as c from nf_board_report where `bo_table`=?", array($v['bo_table']));
										?>
										<li class="<?php echo $on;?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?bo_table=<?php echo $v['bo_table'];?>"><?php echo stripslashes($v['bo_subject']);?> (<?php echo number_format(intval($v['bo_write_count']));?>)</a></li>
										<?php
										} }?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>제목</option>
							<option value="wr_content" <?php echo $_GET['search_field']=='wr_content' ? 'selected' : '';?>>내용</option>
							<option value="sub+con" <?php echo $_GET['search_field']=='sub+con' ? 'selected' : '';?>>제목+내용</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>작성자</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>


			<form name="flist" action="">
			<?php if($bo_row['bo_category_list']) {?>
			<ul class="sub_category">
				<li class="<?php echo !$_GET['bunru'] ? 'on' : '';?>"><a href="./list.php?bo_table=<?php echo $bo_table;?>">전체</a></li>	
				<?php
				if(is_Array($board_info['bo_category_list_arr'])) { foreach($board_info['bo_category_list_arr'] as $k=>$v) {
					$on = $v==$_GET['bunru'] ? 'on' : '';
				?>
				<li class="<?php echo $on;?>"><a href="./list.php?bo_table=<?php echo $bo_table;?>&bunru=<?php echo $v;?>"><?php echo $nf_util->get_text($v);?></a></li>
				<?php
				} }?>
			</ul>
			<?php }?>
			<h6>게시물관리 > 취업노하우
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
					</select>
				</p>	
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/board/regist.php" para="bo_table=<?php echo $bo_table;?>" mode="board_select_delete" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onClick="click_modify(this)" class="blue"><strong>+</strong> 게시물등록</button>
			</div>

			<?php
			// : 게시판 리스트
			$skin = $bo_row['bo_type'];
			$_GET['bo_table'] = $bo_table;
			$skin = 'admin';
			include NFE_PATH.'/board/list.inc.php';
			?>
			</form>
		</div>
		<!--//conbox-->

	</section>
</div>
<!--//wrap-->

<?php
$is_write_name = true;
$_GET['code'] = 'insert';
include NFE_PATH.'/admini/include/board_add.php';
include '../include/footer.php';
?> <!--관리자 footer-->