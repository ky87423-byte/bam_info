<?php
$top_menu_code = "100204";
include '../include/header.php'; // : 관리자 탑메뉴

$_where = "";

// : 날짜
$field = 'rdate';
if($_GET['rdate']=='use_date') $field = 'use_date';
if(in_array($_GET['rdate'], array('rdate', 'use_date'))) {
	if($_GET['date1']) $_date_arr[] = "ncu.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
	if($_GET['date2']) $_date_arr[] = "ncu.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
	if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";
} else if($_GET['rdate']=='coupon_end') {
	$StartDate = $_GET['date1'];
	$EndDate = $_GET['date2'];
	$_where .= " and (('$StartDate' <= ns.coupon_date1 AND '{$EndDate}' >= ns.coupon_date1) OR ('{$StartDate}' <= ns.coupon_date2 AND '{$EndDate}' >= ns.coupon_date2) OR (ns.coupon_date1 <= '{$EndDate}' AND ns.coupon_date2 >= '{$EndDate}'))";
}

if($_GET['code']=='date_end') $_where .= " and ns.`coupon_date2`<'".today."'";
if($_GET['number']) $_where .= " and ncu.`number`='".addslashes($_GET['number'])."'";
if($_GET['shop_no']) $_where .= " and ncu.`pno`=".intval($_GET['shop_no']);
if(strlen($_GET['use'])>0) $_where .= " and ncu.`use`=".intval($_GET['use']);

// : 통합검색
$_keyword['wr_company'] = "ns.`wr_company` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_subject'] = "ns.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['name'] = "ncu.`name` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['wr_id'] = "ncu.`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['c_id'] = "ncu.`c_id` like '%".addslashes($_GET['search_keyword'])."%'";
$_keyword['number'] = "ncu.`number` like '%".addslashes($_GET['search_keyword'])."%'";
if($_GET['search_keyword']) {
	if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = "nf_shop as ns right join nf_coupon_use as ncu on ns.`no`=ncu.`pno` where ncu.`code`='shop' ".$_where;
$order = " order by ncu.`no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$coupon_query = $db->_query("select *, ns.`wr_id` as ns_id from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var ch_use = function(el, no) {
	var val = el.value;
	$.post("../regist.php", "mode=click_coupon_use&no="+no+"&val="+val, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>
<!-- 쿠폰 사용여부 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="coupon product_common">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>
						- 해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide1-5','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button>
					</li>
					<li>
						- 발행된 쿠폰의 사용 현황을 확인 할수 있으며 사용여부를 설정 해줄수 있습니다. 
					</li>
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
								<th>
									<select name="rdate">
										<option value="use_date" <?php echo $_GET['rdate']=='use_date' ? 'selected' : '';?>>쿠폰 사용일</option>
										<option value="rdate" <?php echo $_GET['rdate']=='rdate' ? 'selected' : '';?>>쿠폰 다운일</option>
										<option value="coupon_end" <?php echo $_GET['rdate']=='coupon_end' ? 'selected' : '';?>>쿠폰 유효기간</option>
									</select>
								</th>
								<td colspan="3">
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td colspan="3">
									<label><input type="checkbox" name="use" onClick="nf_util.one_check(this)" value="1" <?php echo $_GET['use'] ? 'checked' : '';?>> 사용</label>
									<label><input type="checkbox" name="use" onClick="nf_util.one_check(this)" value="0" <?php echo $_GET['use']==='0' ? 'checked' : '';?>> 미사용</label>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="wr_company" <?php echo $_GET['search_field']=='wr_company' ? 'selected' : '';?>>업체명</option>
							<option value="c_id" <?php echo $_GET['search_field']=='c_id' ? 'selected' : '';?>>업체아이디</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>상품제목</option>
							<option value="name" <?php echo $_GET['search_field']=='name' ? 'selected' : '';?>>개인이름</option>
							<option value="wr_id" <?php echo $_GET['search_field']=='wr_id' ? 'selected' : '';?>>개인아이디</option>
							<option value="number" <?php echo $_GET['search_field']=='number' ? 'selected' : '';?>>쿠폰번호</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>쿠폰 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_coupon_use" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col >
					<col width="17%">
					<col width="17%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>이미지</th>
						<th>상품정보</th>
						<th>쿠폰정보</th>
						<th>회원정보</th>						
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
							while($coupon_row=$db->afetch($coupon_query)) {
								$shop_info = $nf_shop->shop_info($coupon_row);
								$m_row = $db->query_fetch("select * from nf_member where `no`=".intval($coupon_row['pmno']));
								$m_row2 = $db->query_fetch("select * from nf_member where `no`=".intval($coupon_row['mno']));
					?>
					<tr class="tac">
						<td><input type="checkbox" class="chk_" name="chk[]" value="<?php echo $coupon_row['no'];?>"></td>
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" style="width:80px;height:80px;"></td>
						<td class="shop_info tal">
							<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $coupon_row['pno'];?>" target="_blank"><h4><strong><?php echo $nf_util->get_text($coupon_row['wr_subject']);?></strong></h4></a>
							<ul class="text item-txt">
							    <li><em>업체명</em><span><b><?php echo $nf_util->get_text($coupon_row['wr_company']);?></b></span></li>
								<li onClick="member_mno_click(this)" class="blue pointer" mno="<?php echo $coupon_row['pmno'];?>"><em>아이디</em><span><?php echo $nf_util->get_text($coupon_row['ns_id']);?></span></li>
								<li class="blue pointer" onClick="member_mno_click(this)" mno="<?php echo $coupon_row['pmno'];?>"><em>닉네임</em><span><?php echo $nf_util->get_text($m_row['mb_nick']);?></span></li>
							</ul>
						</td>
						<td class="shop_info_member">
							<ul class="tal">
							    <li><span>쿠폰번호</span><b><?php echo $coupon_row['number'];?></b></li>
								<li><span>유효기간</span><?php echo $coupon_row['coupon_date1'];?> ~ <?php echo $coupon_row['coupon_date1'];?></li>
								<li><span>사용일자</span><?php echo $coupon_row['use_date'];?></li>
								<li><span>사용여부</span><select onChange="ch_use(this, '<?php echo intval($coupon_row['no']);?>')"><option value="1">사용</option><option value="0" <?php echo $coupon_row['use'] ? '' : 'selected';?>>미사용</option></select></li>
							</ul>
						</td>
						<td class="shop_info_member">							
							<ul class="tal">
							    <li onClick="member_mno_click(this)" class="blue pointer" mno="<?php echo $coupon_row['mno'];?>"><span>아이디</span><b><?php echo $coupon_row['wr_id'];?></b></li>
								<li class="blue pointer" onClick="member_mno_click(this)" mno="<?php echo $coupon_row['mno'];?>"><span>이&nbsp;&nbsp;&nbsp;름</span><?php echo $nf_util->get_text($coupon_row['name']);?></li>
								<li class="blue pointer" onClick="member_mno_click(this)" mno="<?php echo $coupon_row['mno'];?>"><span>닉네임</span><?php echo $nf_util->get_text($m_row2['mb_nick']);?></li>
								<li><span>다운일자</span><?php echo $coupon_row['rdate'];?></li>
							</ul>
						</td>						
						<td style="text-align:center">			
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $coupon_row['no'];?>" mode="delete_coupon_use" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_coupon_use" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->