<?php
$add_cate_arr = array('job_employ_report_reason');
$top_menu_code = '100107';
include '../include/header.php';

$_where = "";
$_date_arr = array();
if($_GET['date1']) $_date_arr[] = "nr.`sdate`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "nr.`sdate`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

$_keyword_arr = array();
if(trim($_GET['search_keyword'])) {
	if($_GET['search_field']=='wr_subject||wr_content') {
		$_where .= " and (`wr_subject` like '%".addslashes($_GET['search_keyword'])."%' or `wr_content` like '%".addslashes($_GET['search_keyword'])."%')";
	} else {
		$_keyword_arr['mb_id'] = "nr.`mb_id` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['pmb_id'] = "ne.`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_company_name'] = "ne.`wr_company_name` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_name'] = "ne.`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_subject'] = "ne.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
		$_where .= " and ".$_keyword_arr[$_GET['search_field']];
	}
}

$q = "nf_report as nr right join nf_employ as ne on nr.`pno`=ne.`no` where `code`='employ' ".$_where;
$order = " order by nr.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$report_query = $db->_query("select *, nr.`no` as nr_no from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var ch_status = function(el, no) {
	if(confirm("상태를 변경하시겠습니까?")) {
		$.post("../regist.php", "mode=ch_report_status&no="+no+"&val="+el.value, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}
}
</script>
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 개인회원이 채용공고를 신고한 리스트입니다.</li>
					<li>- 리스트 우측에 편집을 통해 신고된 공고를 중지 및 복구 시킬수 있습니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="page_row" value="<?php echo intval($_GET['page_row']);?>" />
			<div class="search">
				<table>
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
						<tr>
							<th>신고일</th>
							<td colspan="3">
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
						<option value="mb_id" <?php echo $_GET['search_field']=='mb_id' ? 'selected' : '';?>>신고자 아이디</option>
						<option value="pmb_id" <?php echo $_GET['search_field']=='pmb_id' ? 'selected' : '';?>>정보주인 아이디</option>
						<option value="wr_company_name" <?php echo $_GET['search_field']=='wr_company_name' ? 'selected' : '';?>>회사명</option>
						<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>담당자명</option>
						<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>채용공고제목</option>
					</select>
					<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
					<input type="submit" class="blue" value="검색"></input>
					<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
				</div>
			</div>
			</form>
			<!--//search-->

			<h6>신고 정규직 공고<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_report" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '복구하시겠습니까?')" url="../regist.php" mode="repair_select_report" tag="chk[]" check_code="checkbox" class="gray"><strong>+</strong> 선택복구</button>
			</div>

			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="15%">
					<col width="%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="18%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>회사명/아이디/담당자명</th>
						<th colspan="2">채용정보</th>
						<th>신고자(아이디)</th>
						<th>신고일</th>
						<th>신고사유</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($total['c']<=0) {
						case true:
					?>
					<td colspan="8" class="no_list"></td>
					<?php
						break;


						default:
							while($row=$db->afetch($report_query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['nr_no'];?>"></td>
						<td class="job_info tal">
							<a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['pmno'];?>">
								<ul>
									<li><span>회사명</span> : <?php echo $nf_util->get_text($row['wr_company_name']);?></li>
									<li><span>아이디</span> : <?php echo $nf_util->get_text($row['wr_id']);?></li>
									<li><span>담당자</span> : <?php echo $nf_util->get_text($row['wr_name']);?></li>
								</ul>
							</a>
						</td>
						<td><a href="<?php echo NFE_URL;?>/employ/employ_detail.php?no=<?php echo $row['pno'];?>" target="_blank" class="blue"><?php echo $nf_util->get_text($row['wr_subject']);?></a></td>
						<td>
							<ul>
								<li>등록일 : <?php echo date("Y/m/d", strtotime($row['wr_wdate']));?></li>
								<li>수정일 : <?php echo date("Y/m/d", strtotime($row['wr_udate']));?></li>
								<li>조회 : <?php echo number_format(intval($row['wr_hit']));?>건</li>
							</ul>
						</td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['mno'];?>"><?php echo $row['mb_name'];?>(<?php echo $row['mb_id'];?>)</a></td>
						<td><?php echo $row['sdate'];?></td>
						<td><?php echo $nf_util->get_text($cate_p_array['job_employ_report_reason'][0][$row['sel_no']]['wr_name']);?></td>
						<td style="text-align:center">
							<select onChange="ch_status(this, '<?php echo $row['nr_no'];?>')">
								<option value="0">신청</option>
								<option value="-1" <?php echo $row['status']==='-1' ? 'selected' : '';?>>중지</option>
								<option value="1" <?php echo $row['status']==='1' ? 'selected' : '';?>>복구</option>
							</select>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div><?php echo $paging['paging'];?></div>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_report" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '복구하시겠습니까?')" url="../regist.php" mode="repair_select_report" tag="chk[]" check_code="checkbox" class="gray"><strong>+</strong> 선택복구</button>
			</div>
			</form>
		</div>
		<!--//consadmin conbox-->

	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->