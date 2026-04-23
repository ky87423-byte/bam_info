<?php
include "../../engine/_core.php";
$top_menu_code = "500104";
if($_GET['code']=='individual') $top_menu_code = "500105";
include '../include/header.php'; // : 관리자 탑메뉴

$nf_util->sess_page_save("tax_list_admin");

$_GET['code'] = $_GET['code'] ? $_GET['code'] : 'company';
$_where = "";
$_date_arr = array();
if($_GET['date1']) $_date_arr[] = "`wdate`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "`wdate`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";
if(strlen($_GET['status'][0])>0) $_where .= " and `wr_status` in ('".implode("','", $_GET['status'])."')";

if($_GET['search_keyword']) {
	$_keyword = array();
	$_keyword['wr_name'] = "`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_id'] = "`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_company_name'] = "`wr_company_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['wr_ceo_name'] = "`wr_ceo_name` like '%".addslashes($_GET['search_keyword'])."%'";
	if($_GET['search_field']) $_where .= " and (".$_keyword[$_GET['search_field']].")";
	else $_where .= " and (".implode(" or ", $_keyword).")";
}
if($_GET['code']) $_where .= " and `wr_type`='".addslashes($_GET['code'])."'";

$q = "nf_tax as nt where `is_delete`=0 ".$_where;
$order = " order by nt.`udate` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$tax_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
</style>
<script type="text/javascript">
var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	if(display=='none') {
		obj.css({"display":display});
		return;
	}

	var mb_id = $(el).closest("tr").attr("mb_id");
	$.post("../regist.php", "mode=open_box&type=member&mb_id="+mb_id+"&code="+code, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
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
					<li>- 세금계산서(현금영수증) 신청 내역을 토대로 전문 세금계산서 발행업체 또는 국세청 홈페이지에서 세금계산서(현금영수증)를 발행해주시면 됩니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="code" value="<?php echo $_GET['code'];?>" />
			<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="5%">
						</colgroup>
						<tbody>
							<tr>
								<th>신청일</th>
								<td colspan="3">
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
							</tr>
							<tr>
								<th>진행상태</th>
								<td>
									<?php
									if(is_array($nf_payment->tax_status)) { foreach($nf_payment->tax_status as $k=>$v) {
										$checked = strlen($_GET['status'][0])>0 && in_array($k, $_GET['status']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="status[]" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
									} }?>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>신청자명</option>
							<option value="wr_id" <?php echo $_GET['search_field']=='wr_id' ? 'selected' : '';?>>신청회원ID</option>
							<?php
							if($_GET['code']!='individual') {
							?>
							<option value="wr_company_name" <?php echo $_GET['search_field']=='wr_company_name' ? 'selected' : '';?>>회사명</option>
							<option value="wr_ceo_name" <?php echo $_GET['search_field']=='wr_ceo_name' ? 'selected' : '';?>>대표자명</option>
							<?php
							}?>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			<h6>세금계산서신청내역
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_tax" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<?php
					if($_GET['code']!='individual') {
					?>
					<col width="8%">
					<col width="5%">
					<col width="%">
					<?php
					}?>
					<col width="10%">
					<col width="15%">
					<col width="6%">
					<col width="6%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>신청회원ID</th>
						<?php
						if($_GET['code']!='individual') {
						?>
						<th>기업명</th>
						<th>대표자명</th>
						<th>업태/종목</th>
						<?php
						}?>
						<th>이메일</th>
						<th><?php if($_GET['code']!='individual') {?>담당자명/<?php }?>연락처</th>
						<th>신청일</th>
						<th>수정일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
							$colspan = 10;
							if($_GET['code']=='company') $colspan = 13;
					?>
					<tr><td colspan="<?php echo $colspan;?>" class="no_list"></td></tr>
					<?php
						break;

						default:
							while($row=$db->afetch($tax_query)) {
								$wr_name = $row['wr_type']=='individual' ? $row['wr_name'] : $row['wr_manager'];
					?>
					<tr class="tac" mb_id="<?php echo $row['wr_id'];?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
						<td><a href="<?php echo NFE_URL;?>/admini/payment/tax_modify.php?no=<?php echo $row['no'];?>" class="blue"><?php echo $nf_util->get_text($row['wr_id']);?></a></td>
						<?php
						if($_GET['code']!='individual') {
						?>
						<td><a href="<?php echo NFE_URL;?>/admini/payment/tax_modify.php?no=<?php echo $row['no'];?>" class="blue"><?php echo $nf_util->get_text($row['wr_company_name']);?></a></td>
						<td><?php echo $nf_util->get_text($row['wr_ceo_name']);?></td>
						<td><?php echo $nf_util->get_text($row['wr_condition']);?> / <?php echo $nf_util->get_text($row['wr_item']);?></td>
						<?php
						}?>
						<td><?php echo $nf_util->get_text($row['wr_email']);?></td>
						<td><?php echo $wr_name;?> / <?php echo $nf_util->get_text($row['wr_phone']);?></td>
						<td><?php echo substr($nf_util->get_date2($row['wdate']),0,10);?></td>
						<td><?php echo substr($nf_util->get_date2($row['udate']),0,10);?></td>
						<td>
							<a href="<?php echo NFE_URL;?>/admini/payment/tax_modify.php?no=<?php echo $row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_tax" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button>
							<button type="button" onClick="open_box(this, 'memo-')" class="gray common">메모</button>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			</form>
			<div><?php echo $paging['paging'];?></div>

			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_tax" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
		</div>
		<!--//conbox-->

		


		
	</section>
</div>
<!--//wrap-->

<?php
include NFE_PATH.'/admini/include/tax.inc.php';
include NFE_PATH.'/admini/include/memo.inc.php'; // : 메모
include '../include/footer.php';
?> <!--관리자 footer-->