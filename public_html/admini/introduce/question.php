<?php
$top_menu_code = "100203";
include '../include/header.php'; // : 관리자 탑메뉴

$_where = "";

// : 날짜
$field = 'rdate';
if($_GET['date1']) $_date_arr[] = "nq.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "nq.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

// : 통합검색
$_keyword['wr_name'] = "nq.`name` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_id'] = "nq.`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_company_name'] = "ns.`wr_company` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_subject'] = "ns.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['subject'] = "nq.`subject` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['content'] = "nq.`content` like '%".addslashes($_GET['search_keyword'])."%'";
if($_GET['search_keyword']) {
	if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where 1 ".$_where;
$order = " order by nq.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$qna_query = $db->_query("select *, ns.`wr_id` as ns_id from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
table tr.answer { display:none; }
</style>
<script type="text/javascript">
var click_subject = function(el) {
	var obj = $(el).closest("tr").next();
	var display = obj.css("display")=='table-row' ? 'none' : 'table-row';
	obj.css({"display":display});
}
</script>
<!-- 상품문의 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="product_common">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 상품문의 현황을 관리하는 페이지 입니다.</li>
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
								<th>문의작성일</th>
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
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>이름</option>
							<option value="wr_id" <?php echo $_GET['search_field']=='wr_id' ? 'selected' : '';?>>아이디</option>
							<option value="wr_company_name" <?php echo $_GET['search_field']=='wr_company_name' ? 'selected' : '';?>>업체명</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>상품제목</option>
							<option value="subject" <?php echo $_GET['search_field']=='subject' ? 'selected' : '';?>>상품문의 제목</option>
							<option value="content" <?php echo $_GET['search_field']=='content' ? 'selected' : '';?>>상품문의 내용</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>상품문의 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_qna" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col >
					<col >
					<col width="10%">
					<col width="10%">
					<col width="5%">
				</colgroup>
				<thead>
					
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>이미지</th>
						<th>상품정보</th>
						<th>작성자정보</th>
						<th>문의일</th>	
						<th>답변상태</th>
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
							while($qna_row=$db->afetch($qna_query)) {
								$shop_info = $nf_shop->shop_info($qna_row);
								$m_row = $db->query_fetch("select * from nf_member where `no`=".intval($qna_row['pmno']));
								$m_row2 = $db->query_fetch("select * from nf_member where `no`=".intval($qna_row['mno']));
					?>
					<tr class="tac">
						<td><input type="checkbox" class="chk_" name="chk[]" value="<?php echo $qna_row['no'];?>"></td>
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" style="width:80px;height:80px;"></td>
						<td class="shop_info tal">
							<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $qna_row['pno'];?>" target="_blank"><h4><strong><?php echo $nf_util->get_text($qna_row['wr_subject']);?></strong></h4></a>
							<ul class="text item-txt">
							    <li><em>업체명</em><span><b><?php echo $nf_util->get_text($qna_row['wr_company']);?></b></span></li>
								<li onClick="member_mno_click(this)" class="blue pointer" mno="<?php echo $qna_row['pmno'];?>"><em>아이디</em><span><?php echo $nf_util->get_text($qna_row['ns_id']);?></span></li>
								<li class="" mno="<?php echo $m_row['no'];?>"><em>닉네임</em><span><?php echo $nf_util->get_text($m_row['mb_nick']);?></span></li>
							</ul>
						</td>
						<td class="shop_info tal">
							<a href="#none" onClick="click_subject(this)"><h4><strong><?php echo $nf_util->get_text($qna_row['subject']);?></strong></h4></a>
							<ul class="text item-txt">
							    <li onClick="member_mno_click(this)" class="blue pointer" mno="<?php echo $qna_row['mno'];?>"><em>아이디</em><span><b><?php echo $nf_util->get_text($qna_row['wr_id']);?></b></span></li>
								<li class="" mno="<?php echo $qna_row['mno'];?>"><em>이&nbsp;&nbsp;&nbsp;름</em><span><?php echo $nf_util->get_text($qna_row['name']);?></span></li>
								<li class="" mno="<?php echo $m_row2['no'];?>"><em>닉네임</em><span><?php echo $nf_util->get_text($m_row2['mb_nick']);?></span>  </li>
								<li class=""><em>이메일</em><?php echo $qna_row['email'];?></li>
								<li class=""><em>연락처</em><?php echo $qna_row['phone'];?></li>
							</ul>
						</td>
						<td><?php echo $qna_row['rdate'];?></td>
						<td>
							<?php if($qna_row['answer']) {?>
								<p class="blue MAB10">답변완료</p>
							<?php } else {?>
								<p class="red MAB10">미답변</p>
							<?php }?>
							<button type="button" class="gray common" onClick="open_qna_box(this, '<?php echo intval($qna_row['no']);?>')" code="comment"><i class="axi axi-minus2"></i> 답변하기</button>
						</td>
						<td style="text-align:center">			
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $qna_row['no'];?>" mode="delete_qna" url="<?php echo NFE_URL;?>/include/regist.php"><i class="axi axi-minus2"></i> 삭제</button>
						</td>
					</tr>
					<tr class="answer">
						<td colspan="8">
							<div>
							<?php echo stripslashes($qna_row['content']);?>
							</div>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_qna" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->
<?php include '../include/qa_answer.inc.php'; ?> <!--상품문의 레이아웃-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->