<?php
$top_menu_code = "600105";
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
if($bo_table) $_where .= " and bo_table='".addslashes($bo_table)."'";
if($_GET['date1']) $date_arr[] = "wdate>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $date_arr[] = "wdate<='".addslashes($_GET['date2'])." 23:59:59'";
if($date_arr[0]) $_where .= " and (".implode(" and ", $date_arr).")";

$q = "nf_board_report where 1 ".$_where;
$order = " order by `wdate` desc";
$group = " group by pno";
$total = $db->query_fetch("select count(distinct `pno`) as c from ".$q);

$_arr = array();
$_arr['num'] = $_GET['page_row']>0 ? $_GET['page_row'] : 10;
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$query = $db->_query("select *, count(*) as c from ".$q.$group.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.blind- { background-color:#f5f5f5; }
</style>
<script type="text/javascript">
var click_blind = function(el, bo_table, no) {
	$.post("../regist.php", "mode=click_board_blind&bo_table="+bo_table+"&no="+no, function(data){
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
					<li>- 신고된 게시물을 관리하는 페이지 입니다.</li>
					<li>- 신고된 게시물이 문제가 있을시 처리현황에 블라인드 처리를 하시면 해당 게시물은 노출되지 않습니다.</li>
					<li>- 블라인드 처리된 게시물을 다시 노출 시킬때는 처리현황에서 블라인드 처리를 누르시면 블라인드가 해제 됩니다.</li>
				</ul>
			</div>
			
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
										<li class="<?php echo $on;?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?bo_table=<?php echo $v['bo_table'];?>"><?php echo stripslashes($v['bo_subject']);?> (<?php echo number_format(intval($cnt['c']));?>)</a></li>
										<?php
										} }?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
					
				</div>
				<!--//search-->

			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="page_row" value="<?php echo $_GET['page_row'];?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="8%">
						</colgroup>
						<tbody>
							<tr>
								<th>등록일</th>
								<td colspan="3">
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
									<input type="submit" class="blue" value="검색"></input>
									<button class="black">초기화</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!--//search-->
			</form>


			<h6>신고 게시물관리 <span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건의 신고 게시물이 검색 되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
					</select>
				</p>
			</h6>


			<form name="flist">
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>" />
			<div class="table_top_btn">
				<button type="button" onClick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board_report" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="3%">
					<col width="10%">
					<col width="10%">
					<col width="">
					<col width="10%">
					<col width="7%">
					<col width="7%">
					<col width="6%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>번호</th>
						<th>타입</th>
						<th>메뉴</th>
						<th>게시물내용/댓글내용</th>
						<th>작성자</th>
						<th>신고건수</th>
						<th>처리현황</th>
						<th>삭제</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
						?>
						<td colspan="10" class="no_list"></td>
						<?php
						break;

						default:
							while($row=$db->afetch($query)) {
								$bo_table = trim($row['bo_table']);
								$_table = $nf_board->get_table($bo_table);
								$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
								$board_info = $nf_board->board_info($bo_row);
								$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($row['pno']));
								$b_info = $nf_board->info($b_row, $board_info);

								$wr_content = $b_row['wr_is_comment'] ? $b_row['wr_content'] : $b_row['wr_subject'];
						?>
						<tr class="tac <?php echo $row['wr_blind'] ? 'blind-' : '';?>">
							<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
							<td><?php echo $paging['bunho']--;?></td>
							<td><?php echo $b_row['wr_is_comment'] ? '댓글' : '게시물';?></td>
							<td><?php echo $bo_row['bo_subject'];?></td>
							<td class="tal"><a href="<?php echo NFE_URL;?>/board/view.php?bo_table=<?php echo $bo_table;?>&no=<?php echo $b_row['wr_no'];?>" target="_blank" class="blue"><?php echo $wr_content;?></a></td>
							<td><?php echo $nf_util->get_text($b_info['get_name']);?></td>
							<td><?php echo $row['c'];?></td>
							<td><button type="button" class="gray common" onClick="click_blind(this, '<?php echo $bo_table;?>', '<?php echo $b_row['wr_no'];?>')"><?php echo $b_row['wr_blind'] ? '블라인드 해지' : '블라인드 처리';?></button></td>
							<td>
								<button class="gray common" type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" para="bo_table=<?php echo $bo_table;?>" mode="delete_board_report" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board_report" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<div><?php echo $paging['paging'];?></div>
		</div>
		</form>
		<!--//conbox-->


		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->