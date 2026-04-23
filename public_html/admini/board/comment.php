<?php
$top_menu_code = "600104";
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
$_table = $nf_board->get_table($bo_table);
$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
$board_info = $nf_board->board_info($bo_row);

$date_arr = array();
$_where = "";
if($_GET['date1']) $date_arr[] = "wr_datetime>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $date_arr[] = "wr_datetime<='".addslashes($_GET['date2'])." 23:59:59'";
if($date_arr[0]) $_where .= " and (".implode(" and ", $date_arr).")";
if($_GET['search_keyword']) {
	$_keyword['content'] = "`wr_content` like '".addslashes($_GET['search_keyword'])."'";
	if($_GET['search_field']) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = $_table." where wr_is_comment=1 ".$_where;
$order = " order by `wr_datetime` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = $_GET['page_row']>0 ? $_GET['page_row'] : 10;
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 각 게시판들의 제목을 클릭하시면 해당 게시판의 댓글을 확인 할수 있으며 댓글 삭제를 하실수 있습니다. </li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
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
											$_table = $nf_board->get_table($v['bo_table']);
											$cnt = $db->query_fetch("select count(*) as c from ".$_table." where wr_is_comment=1");
										?>
										<li class="<?php echo $on;?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?bo_table=<?php echo $v['bo_table'];?>"><?php echo stripslashes($v['bo_subject']);?> (<?php echo number_format(intval($cnt['c']));?>)</a></li>
										<?php
										} }?>
									</ul>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="content" <?php echo $_GET['search_field']=='content' ? 'selected' : '';?>>댓글내용</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>


			<form name="flist">
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>" />
			<h6>댓글관리 <span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건의 댓글이 검색 되었습니다.</span></h6>
			<div class="table_top_btn">
				<button type="button" onClick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board_comment" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="10%">
					<col width="8%">
					<col width="6%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>댓글내용</th>
						<th>작성자</th>
						<th>해당게시물보기</th>
						<th>등록일</th>
						<th>삭제</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
						?>
						<td colspan="6" class="no_list"></td>
						<?php
						break;


						default:
							while($row=$db->afetch($query)) {
								$b_info = $nf_board->info($row, $board_info);
								$parent_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($row['wr_parent']));

								// : 대댓글인경우
								if($parent_row['wr_is_comment']) {
									$parent_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($parent_row['wr_parent']));
								}

								$wr_parent_ori_arr = explode(",", $row['wr_parent_ori']);
								$b_no = $wr_parent_ori_arr[0] ? $wr_parent_ori_arr[0] : $row['wr_parent'];
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['wr_no'];?>"></td>
						<td class="tal">
							<?php echo $row['wr_del'] ? '<span style="color:red;">삭제됨</span>' : '';?>
							<a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $bo_table;?>&no=<?php echo $parent_row['wr_no'];?>#reply_comment_body-" class="blue" target="_blank">[<?php echo $nf_board->board_table_arr[$bo_table]['bo_subject'];?>] <?php echo stripslashes($parent_row['wr_subject']);?></a><br>
							<?php echo stripslashes($row['wr_content']);?>
						</td>
						<td><?php echo $nf_util->get_text($b_info['get_name']);?></td>
						<td><a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $bo_table;?>&no=<?php echo $parent_row['wr_no'];?>#reply_comment_body-" class="blue" target="_blank">해당게시물보기</a></td>
						<td><?php echo substr($row['wr_datetime'],0,10);?></td>
						<td>
							<button class="gray common" type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['wr_no'];?>" para="bo_table=<?php echo $bo_table;?>" mode="delete_board_comment" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onClick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board_comment" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			</form>

			<div><?php echo $paging['paging'];?></div>

		</div>
		<!--//conbox-->


		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->