<?php
$top_menu_code = '100105';
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
		$_keyword_arr['pmb_id'] = "ns.`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_company'] = "ns.`wr_company` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_name'] = "ns.`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_subject'] = "ns.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
		$_where .= " and ".$_keyword_arr[$_GET['search_field']];
	}
}

$q = "nf_shop as ns right join nf_report as nr on nr.`pno`=ns.`no` where `code`='shop' ".$_where;
$order = " order by nr.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$report_query = $db->_query("select *, nr.`no` as nr_no, ns.`no` as ns_no from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
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
					<li>
						- 사이트 회원이 해당 업체 상품를 신고한 리스트입니다.
					</li>
					<li>
						- 리스트 우측에 편집을 통해 신고된 공고를 중지 및 복구 시킬수 있습니다.
					</li>
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
						<option value="wr_company" <?php echo $_GET['search_field']=='wr_company' ? 'selected' : '';?>>업체명</option>
						<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>상품 제목</option>
					</select>
					<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
					<input type="submit" class="blue" value="검색"></input>
					<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
				</div>
			</div>
			</form>
			<!--//search-->

			<h6>신고 상품<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
					<col width="7%">
					<col>
					<col width="12%">
					<col width="12%">
					<col width="8%">
					<col width="8%">
					<col width="15%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>이미지</th>
						<th>상품정보</th>
						<th colspan="2">등록정보</th>
						<th>신고자(아이디)</th>
						<th>신고일</th>
						<th>신고사유</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr>
						<td colspan="8" class="no_list"></td>
					</tr>
					<?php
						break;


						default:
							while($row=$db->afetch($report_query)) {
								$shop_info = $nf_shop->shop_info($row);
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['nr_no'];?>"></td>
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" style="width:80px;height:80px;"></td>
						<td class="product tal"><a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $row['pno'];?>" target="_blank">
							<ul>
								<li><b class="blue"><span>제목</span> : <?php echo $nf_util->get_text($row['wr_subject']);?></b></li>
								<li><span>지역</span> : <?php echo strtr($shop_info['area_txt'], $cate_array['area']);?></li>
								<?php/*<li><span>테마</span> : <?php echo strtr(strtr($row['wr_tema'], array(","=>", ")), $cate_array['job_tema']);?></li>*/?>
								<li><span>업종</span> : <?php echo $shop_info['category_txt'];?></li>
							</ul>
						</a></td>
						<td class="product tal">
							<ul>
								<li><b class="blue"><span>업체명</span> : <?php echo $nf_util->get_text($row['wr_company']);?></b></li>
								<li onclick="member_mno_click(this)" mno="<?php echo $row['pmno'];?>"><span>아이디</span> : <?php echo $nf_util->get_text($row['wr_id']);?></li>
								<li onclick="member_mno_click(this)" mno="<?php echo $row['pmno'];?>"><span>이&nbsp;&nbsp;&nbsp;름</span> : <?php echo $nf_util->get_text($row['wr_name']);?></li>
								<li><span>연락처</span> : <?php echo $nf_util->get_text($row['wr_phone']);?> / <?php echo $nf_util->get_text($row['wr_hphone']);?></li>
							</ul>
						</td>
						<td>
							<ul>
								<li><b>등록일</b> : <?php echo $row['wr_rdate'];?></li>
								<li><b>수정일</b> : <?php echo $row['wr_udate'];?></li>
							</ul>
						</td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['mno'];?>"><?php echo $row['mb_name'];?>(<?php echo $row['mb_id'];?>)</a></td>
						<td><?php echo $row['sdate'];?></td>
						<td><?php echo $row['sel_reason'];?></td>
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