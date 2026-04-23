<?php
$add_cate_arr = array('subway', 'job_date', 'job_week', 'job_pay', 'job_pay_support', 'job_type', 'job_welfare', 'job_grade', 'job_position', 'job_age', 'job_target', 'job_document', 'job_tema', 'job_conditions');
$top_menu_code = '100108';
include '../include/header.php';

$_where = " and na.`code`='employ'";
if($_GET['eno']) $_where .= " and `pno`=".intval($_GET['eno']);

$_date_arr = array();
if($_GET['date1']) $_date_arr[] = "na.`sdate`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "na.`sdate`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

if($_GET['mno']) $_where .= " and na.`mno`=".intval($_GET['mno']);

if(trim($_GET['search_keyword'])) {
	$_keyword['mb_name'] = "`mb_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['mb_id'] = "`mb_id` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_company_name'] = "`wr_company_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_name'] = "`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_subject'] = "`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
	if($_GET['search_field']) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = "nf_accept as na right join nf_employ as ne on na.`pno`=ne.`no` where 1 ".$_where;
$order = " order by na.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$accept_query = $db->_query("select *, na.`no` as na_no, na.mno as na_mno, na.pmno as na_pmno from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<!--입사지원 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 개인회원들의 온라인 입사지원 현황을 관리하시는 페이지 입니다.</li>
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
								<th>지원일</th>
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
							<option value="mb_name" <?php echo $_GET['search_field']=='mb_name' ? 'selected' : '';?>>지원자명</option>
							<option value="mb_id" <?php echo $_GET['search_field']=='mb_id' ? 'selected' : '';?>>지원자ID</option>
							<option value="wr_company_name" <?php echo $_GET['search_field']=='wr_company_name' ? 'selected' : '';?>>회사명</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>담당자명</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>채용공고 제목</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>입사지원 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_accept" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="12%">
					<col width="%">
					<col width="12%">
					<col width="10%">
					<col width="7%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>지원자</th>
						<th>기업명</th>
						<th colspan="2">지원한 채용공고</th>
						<th>지원일</th>
						<th>상대방삭제</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<td colspan="7" class="no_list"></td>
					<?php
						break;


						default:
							while($row=$db->afetch($accept_query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['na_no'];?>"></td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['na_mno'];?>"><?php echo $nf_util->get_text($row['mb_name']);?><br><span>(<?php echo $nf_util->get_text($row['mb_id']);?>)</span></a></td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['na_pmno'];?>"><?php echo $nf_util->get_text($row['wr_company_name']);?></a></td>
						<td><a href="<?php echo NFE_URL;?>/employ/employ_detail.php?no=<?php echo $row['pno'];?>" target="_blank" class="blue"><?php echo $nf_util->get_text($row['wr_subject']);?></a>
						</td>
						<td>
							<ul>
								<li>등록일 : <?php echo date("Y/m/d", strtotime($row['wr_wdate']));?></li>
								<li>수정일 : <?php echo date("Y/m/d", strtotime($row['wr_udate']));?></li>
								<li>마감일 : 채용시까지</li>
								<li>조회 : <?php echo number_format(intval($row['wr_hit']));?>건</li>
							</ul>
						</td>
						<td><?php echo date("Y-m-d", strtotime($row['sdate']));?></td>
						<td><?php echo $row['pdel'] ? 'O' : '';?></td>
						<td style="text-align:center">
							<button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?\n기업회원, 개인회원 둘다 삭제됩니다.')" no="<?php echo $row['na_no'];?>" mode="delete_accept" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
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
			</form>

			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_accept" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->