<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '300203';
include '../include/header.php';

$nf_util->sess_page_save("admin_point_list");

$_where = "";
$_date_arr = array();
if($_GET['date1']) $_date_arr[] = "`point_datetime`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "`point_datetime`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

if(trim($_GET['search_keyword'])) {
	$_keyword_arr['mb_id'] = "np.`mb_id` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword_arr['point_content'] = "np.`point_content` like '%".addslashes($_GET['search_keyword'])."%'";
	if($_GET['search_field']) $_where .= " and ".$_keyword_arr[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword_arr).")";
}

$q = "nf_point as np where 1 ".$_where;
$order = " order by np.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$point_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<!-- 회원포인트관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 회원별 포인트 적립 내용을 출력합니다.</li>
					<li>- 관리자가 임의로 회원별로 포인트를 지급/수정/삭제 할수 있습니다.</li>
					<li>- 차감시 (-) 를 입력하시면 됩니다. ex) -100</li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="10%">
							<col width="%">
						</colgroup>
						<tbody>
							<tr>
								<th>일시</th>
								<td>
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="mb_id" <?php echo $_GET['search_field']=='mb_id' ? 'selected' : '';?>>아이디</option>
							<option value="point_content" <?php echo $_GET['search_field']=='point_content' ? 'selected' : '';?>>적립/사용내역</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			
			<h6>포인트관리
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_point" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="">
					<col width="5%">
					<col width="5%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>회원ID</th>
						<th>이름/대표자</th>
						<th>별명</th>
						<th>적립/사용내역</th>
						<th>포인트</th>
						<th>포인트합</th>
						<th>일시</th>
					</tr>
				</thead>
				<tbody class="tac">
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="8" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($row=$db->afetch($point_query)) {
								$get_member = $nf_member->get_member($row['mno']);
								switch($row['point_rel_table']) {
									case "nf_board_file":
										$file_row = $db->query_fetch("select * from nf_board_file where no=".intval($row['point_rel_id']));
										$_table = $nf_board->get_table($file_row['bo_table']);
										$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($file_row['wr_no']));
										$row['point_content'] .= '<div><a href="'.NFE_URL.'/board/view.php?bo_table='.$file_row['bo_table'].'&no='.$file_row['wr_no'].'" target="_blank">제목 : '.$b_row['wr_subject'].'</a></div><div>다운로드파일 : ('.$file_row['file_source'].')</div>';
									break;
								}
					?>
					<tr>
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $row['mno'];?>"><?php echo $nf_util->get_text($row['mb_id']);?></a></td>
						<td><?php echo $nf_util->get_text($row['mb_name']);?></td>
						<td><?php echo $nf_util->get_text($row['mb_nick']);?></td>
						<td><?php echo $row['no'];?>@<?php echo $row['point_content'];?></td>
						<td><?php echo number_format(intval($row['point_point']));?></td>
						<td><?php echo number_format(intval($row['point_mb_point']));?></td>
						<td><?php echo $row['point_datetime'];?></td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div><?php echo $paging['paging'];?></div>
			</form>

			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?\n삭제후 포인트를 다시 지급하거나 차감할려면 하단의 포인트지급으로 부여해주시기 바랍니다.')" url="../regist.php" mode="delete_select_point" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<h6>포인트지급</h6>
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="point_write" />
			<table class="table4">
				<colgroup>
					<col width="10%">
					<col width="%">
					<col width="%">
					<col width="7%">
				</colgroup>
				<tr>
					<th>회원아이디</th>
					<th>적립/사용내역</th>
					<th>포인트</th>
					<th>입력</th>
				</tr>
				<tr>
					<td>
						<input type="text" name="mid" hname="회원아이디" needed />
					</td>
					<td><input type="text" name="code" hname="적립/사용내역" needed></td>
					<td><input type="text" name="point" hname="포인트" needed onkeyup="this.value=this.value.number_format()" style="width:200px;">p <span class="blue" style="font-size:12px;">* 차감할 경우에는 - 를 붙여주세요. <b>ex) -100</b></span></td>
					<td class="tac"><button class="gray common"><i class="axi axi-pencil"></i> 등록하기</button></td>
				</tr>
			</table>
			</form>
		</div>
		
		
		<!--//payconfig conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->