<?php
$top_menu_code = "100205";
include '../include/header.php'; // : 관리자 탑메뉴

$_where = "";

// : 날짜
$field = 'rdate';
if($_GET['date1']) $_date_arr[] = "ns.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "ns.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

// : 통합검색
$_keyword['wr_name'] = "ns.`mb_name` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_id'] = "ns.`mb_id` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_id2'] = "nr2.`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_name2'] = "ns.`pmb_name` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_subject'] = "nr2.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
if($_GET['search_keyword']) {
	if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = "nf_scrap as ns right join nf_resume as nr2 on ns.`pno`=nr2.`no` where ns.`code`='resume' ".$_where;
$order = " order by ns.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$scrap_query = $db->_query("select *, ns.`no` as ns_no, ns.`mno` as ns_mno from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<!--이력서 스크랩 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 기업회원들의 이력서 스크랩을 관리할수 있는 페이지 입니다.</li>
				</ul>
			</div>

			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
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
								<th>스크랩일</th>
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
						<select name="search_field" id="">
							<option value="">통합검색</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>회원명</option>
							<option value="wr_id" <?php echo $_GET['search_field']=='wr_id' ? 'selected' : '';?>>아이디</option>
							<option value="wr_id2" <?php echo $_GET['search_field']=='wr_id2' ? 'selected' : '';?>>개인회원 아이디</option>
							<option value="wr_name2" <?php echo $_GET['search_field']=='wr_name2' ? 'selected' : '';?>>개인회원 이름</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>이력서 제목</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>이력서 스크랩 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
					</select>
				</p>
			</h6>

			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="code" value="resume" />
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_scrap" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="8%">
					<col width="%">
					<col width="10%">
					<col width="10%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>회원구분</th>
						<th>회원정보</th>
						<th colspan="2">스크랩한 이력서</th>
						<th>스크랩일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
						?>
					<tr><td colspan="7" class="no_list"></td></tr>
						<?php
						break;

						default:
							while($row=$db->afetch($scrap_query)) {
								$resume_info = $nf_job->resume_info($row);
								$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($row['ns_mno']));
					?>
					<tr class="tac">
						<td><input type="checkbox" class="chk_" name="chk[]" value="<?php echo $row['ns_no'];?>"></td>
						<td><?php echo $nf_member->mb_type[$get_member['mb_type']];?>회원</td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['ns_mno'];?>"><?php echo $nf_util->get_text($get_member['mb_name']);?><br><span>(<?php echo $nf_util->get_text($get_member['mb_id']);?>)</span></a></td>
						<td><a href="<?php echo NFE_URL;?>/resume/resume_detail.php?no=<?php echo $row['pno'];?>" class="blue" target="_blank"><?php echo $nf_util->get_text($row['wr_subject']);?></a></td>
						<td>
							<ul>
								<li>등록일 : <?php echo date("Y/m/d", strtotime($row['wr_wdate']));?></li>
								<li>수정일 : <?php echo date("Y/m/d", strtotime($row['wr_udate']));?></li>
								<li>조회 : <?php echo number_format(intval($row['wr_hit']));?>건</li>
							</ul>
						</td>
						 <td><?php echo substr($row['rdate'],0,10);?></td>
						<td style="text-align:center">
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['ns_no'];?>" mode="delete_scrap" url="<?php echo NFE_URL;?>/include/regist.php" para="code=resume"><i class="axi axi-minus2"></i> 삭제</button>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_scrap" tag="chk[]" check_code="checkbox" class="gray" para="code=employ"><strong>-</strong> 선택삭제</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->