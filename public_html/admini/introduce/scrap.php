<?php
$top_menu_code = "100201";
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
$_keyword['wr_company_name'] = "ne.`wr_company_name` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_subject'] = "ne.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
if($_GET['search_keyword']) {
	if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$search_where = $nf_search->shop();

$q = "nf_shop as ns right join nf_scrap as ns1 on ns.`no`=ns1.`pno` where ns.`is_delete`=0".$search_where['where'];
$order = " order by ns1.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$scrap_query = $db->_query("select *, ns1.`no` as ns1_no, ns1.`mno` as ns1_mno from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<!--채용공고 스크랩 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="scrap product_common">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 업체정보 스크랩 현황을 관리하시는 페이지 입니다.</li>
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
							<option value="wr_company_name" <?php echo $_GET['search_field']=='wr_company_name' ? 'selected' : '';?>>회사명</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>채용공고제목</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>업체정보 스크랩 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
			<input type="hidden" name="code" value="employ" />
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_scrap" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="%">
					<col width="10%">
					<col width="10%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>이미지</th>
						<th>상품정보</th>
						<th>회원정보</th>
						<th>스크랩일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
						?>
					<tr><td colspan="6" class="no_list"></td></tr>
						<?php
						break;

						default:
							while($row=$db->afetch($scrap_query)) {
								$shop_info = $nf_shop->shop_info($row);
								$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($row['ns1_mno']));
					?>
					<tr class="tac">
						<td><input type="checkbox" class="chk_" name="chk[]" value="<?php echo $row['ns1_no'];?>"></td>
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지" style="width:80px;height:80px;"></td>
						<td class="shop_info tal">
							<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $row['pno'];?>" target="_blank"><h4><strong><?php echo $nf_util->get_text($row['wr_subject']);?></strong></h4></a>
							<ul class="text item-txt">
							    <li><em>업체명</em><span><b><?php echo $nf_util->get_text($row['wr_company']);?></b></span></li>
								<li><em>등록일</em><span><?php echo date("Y/m/d", strtotime($row['wr_rdate']));?></span></li>
								<li><em>수정일</em><span><?php echo date("Y/m/d", strtotime($row['wr_udate']));?></span>  </li>
							</ul>
						</td>
						<td class="shop_info_member">
							<ul class="tal">
								<li onClick="member_mno_click(this)" class="blue pointer" mno="<?php echo $row['ns1_mno'];?>"><span>아이디</span> <?php echo $nf_util->get_text($get_member['mb_id']);?></li>
								<li onClick="member_mno_click(this)" class="blue" mno="<?php echo $row['ns1_mno'];?>"><span>이&nbsp;&nbsp;&nbsp;름</span> <?php echo $nf_util->get_text($get_member['mb_name']);?></li>
							</ul>
						</td>
						<td><?php echo $row['rdate'];?></td>
						<td style="text-align:center">
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['ns1_no'];?>" mode="delete_scrap" url="<?php echo NFE_URL;?>/include/regist.php" para="code=shop"><i class="axi axi-minus2"></i> 삭제</button>
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
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_scrap" para="code=shop" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<div><?php echo $paging['paging'];?></div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->