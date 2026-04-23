<?php
include "../../engine/_core.php";
$top_menu_code = "500101";
if($_GET['pay_status']==='0') $top_menu_code = "500102";
if($_GET['pay_status']==='1') $top_menu_code = "500103";
include '../include/header.php'; // : 관리자 탑메뉴

$nf_util->sess_page_save("payment_list");
if(!is_array($_GET['service'])) $_GET['service'] = array();
if(!is_array($_GET['tax_status'])) $_GET['tax_status'] = array();

//$_where = " and (pay_status>0 or (pay_status=0 and `pay_method`='bank'))";
if(strlen($_GET['pay_status'])>0) $_where = " and `pay_status`=".intval($_GET['pay_status']);
// : 날짜
$field = 'pay_wdate';
if($_GET['date1']) $_date_arr[] = "np.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "np.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

// : 서비스 검색
if(count($_GET['service'])>0) $_where .= " and find_in_set('".implode("', `pay_service`) or find_in_set('", $_GET['service'])."', `pay_service`)";
if(count($_GET['tax_status'])>0) $_where .= " and tax_status in ('".implode("','", $_GET['tax_status'])."')";
// : 결제수단
if($_GET['pay_method']) $_where .= " and `pay_method`='".addslashes($_GET['pay_method'])."'";

if($_GET['search_keyword']) {
	$_keyword['pay_uid'] = "`pay_uid` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['pay_name'] = "`pay_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['pay_bank_name'] = "`pay_bank_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_keyword['pay_email'] = "`pay_email` like '%".addslashes($_GET['search_keyword'])."%'";
	if($_GET['search_field']) $_where .= " and ".$_keyword[$_GET['search_field']];
	else $_where .= " and (".implode(" or ", $_keyword).")";
}

$q = "nf_payment as np where ((pay_method!='bank' && pay_status=1) or pay_method='bank') ".$_where;

$order = " order by np.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 10;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['payment_list'] = $q.$order;
$payment_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
$payment_group = $db->_query("select count(pay_status) as c, pay_status from ".$q." group by pay_status");
while($row=$db->afetch($payment_group)) {
	$payment_status[$row['pay_status']] = $row['c'];
}
?>
<style type="text/css">
.tr_status1 td { background-color:#eee !important; }
</style>
<script type="text/javascript">
var payment_process = function(el, no) {
	var txt = $(el).find("option:selected").text();
	if(confirm(txt+"(으)로 변경하시겠습니까?")) {
		$.post("../regist.php", "mode=payment_process&val="+el.value+"&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}
}

var ch_tax_status = function(el, no) {
	$.post("../regist.php", "mode=ch_tax_status&val="+el.value+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
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
					<li>- 상품 결제 현황을 관리 하는 페이지 입니다. </li>
					<li>- 결제수단이 무통장입금의 경우 진행상태를 결제완료로 변경해주셔야 결제완료된 상품으로 노출이 됩니다. </li>
					<li>- 세금계산서 또는 현금영수증 신청시 해당 정보를 토대로 전문 세금계산서 발행업체 또는 국세청 홈페이지에서 세금계산서(현금영수증)를 발행해주시면 됩니다. </li>
				</ul>
			</div>

			<form name="fsearch" action="" method="get">
				<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
				<input type="hidden" name="sort_lo" value="<?php echo $nf_util->get_html($_GET['sort_lo']);?>" />
				<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="13%">
							<col width="%">
							<col width="10%">
							<col width="40%">
						</colgroup>
						<tbody>
							<tr>
								<th><select name="" id=""><option value="">결제일</option></select></th>
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
									<label><input type="radio" name="pay_status" value="" checked>전체</label>
									<label><input type="radio" name="pay_status" value="0" <?php echo $_GET['pay_status']==='0' ? 'checked' : '';?>>결제대기</label>
									<label><input type="radio" name="pay_status" value="1" <?php echo $_GET['pay_status']==='1' ? 'checked' : '';?>>결제완료</label>
								</td>
								<th>세금계산서상태</th>
								<td>
									<?php
									if(is_array($nf_payment->tax_status)) { foreach($nf_payment->tax_status as $k=>$v) {
										$checked = is_array($_GET['tax_status']) && in_array($k, $_GET['tax_status']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="tax_status[]" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
									} }?>
								</td>
							</tr>
							<tr>
								<th>결제방법</th>
								<td colspan="3">
									<?php
									if(is_array($nf_payment->pay_kind)) { foreach($nf_payment->pay_kind as $k=>$v) {
										if(in_array($k, array('admin'))) continue;
										$checked = is_array($_GET['pay_method']) && in_array($k, $_GET['pay_method']) ? 'checked' : '';
									?>
									<label><input type="radio" name="pay_method" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
									} }
									?>
								</td>
							</tr>
							<tr>
								<th>서비스 상품종류</th>
								<td colspan="3">
									<?php
									if(is_array($nf_job->service_name['employ']['main'])) { foreach($nf_job->service_name['employ']['main'] as $k=>$v) {
										$checked = in_array('employ_0_'.$k, $_GET['service']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="service[]" value="employ_0_<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
									} }?>
									<label><input type="checkbox" name="service[]" value="employ_0_board" <?php echo in_array('employ_0_board', $_GET['service']) ? 'checked' : '';?>>테두리강조</label>
								</td>
							</tr>	
							
							<tr>
								<th>기타 서비스</th>
								<td colspan="3">
									<label><input type="checkbox" name="service[]" value="resume_read" <?php echo in_array('resume_read', $_GET['service']) ? 'checked' : '';?>>점프서비스</label>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="pay_uid" <?php echo $_GET['search_field']=='pay_uid' ? 'selected' : '';?>>회원아이디</option>
							<option value="pay_name" <?php echo $_GET['search_field']=='pay_name' ? 'selected' : '';?>>이름</option>
							<option value="pay_bank_name" <?php echo $_GET['search_field']=='pay_bank_name' ? 'selected' : '';?>>입금자명</option>
							<option value="pay_email" <?php echo $_GET['search_field']=='pay_email' ? 'selected' : '';?>>이메일</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			<h6>결제통합관리
				<span> 총 <b><?php echo number_format(intval($_arr['total']));?></b>건</span>
				<span> 결제대기 <b class="red"><?php echo number_format(intval($payment_status[0]));?></b></span>
				<span> 결제완료 <b class="green"><?php echo number_format(intval($payment_status[1]));?></b></span>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?\n포인트를 돌려줘야한다면 포인트관리에서 수동으로 포인트를 부여해주시기 바랍니다.')" url="../regist.php" mode="delete_select_payment" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="../regist.php?mode=excel_payment_list_q"><button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button></a>
			</div>

			<form name="flist">
			<input type="hidden" name="mode" value="" />
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="5%">
					<col width="8%">
					<col width="">
					<col width="20%">
					<col width="6%">
					<col width="4%">
					<col width="14%">
					<col width="6%">
					<col width="6%">
					<col width="6%">
					<col width="4%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>회원구분</th>
						<th>이름</th>
						<th>아이디</th>
						<th>업체명/제목</th>
						<th>결제정보</th>
						<th>결제수단</a></th>
						<th>진행상태</th>
						<th>세금계산서 상태</th>
						<th>할인금액</th>
						<th>결제금액</th>
						<th>결제일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="12" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($pay_row=$db->afetch($payment_query)) {
								$pay_info = $nf_payment->pay_info($pay_row);
								$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($pay_row['pay_mno']));
								if(in_array($pay_row['pay_type'], array('shop')))
									$info_row = $db->query_fetch("select * from nf_".$pay_row['pay_type']." where `no`=".intval($pay_row['pay_no']));
								if(!$pay_row['pay_status'] && $pay_info['post_unse']['tax_use']) $pay_row['pay_status'] = 1;
					?>
					<tr class="tac tr_status<?php echo $pay_row['pay_status'];?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $pay_row['no'];?>"></td>
						<td><?php echo $nf_member->mb_type[$get_member['mb_type']];?>회원</td>
						<td><?php echo $pay_row['pay_name'];?></td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $pay_row['pay_mno'];?>"><?php echo $pay_row['pay_uid'];?></a></td>
						<td>
							<?php if($pay_info['info_link']) {?>
							<a href="<?php echo $pay_info['info_link'];?>" target="_blank" class="blue"><?php echo $nf_util->get_text($info_row['wr_company']);?> / <?php echo $nf_util->get_text($info_row['wr_subject']);?></a>
							<?php }?>
						</td>
						<td>
							<?php
							if(!array_key_exists($pay_row['pay_type'], $nf_payment->payment_basic_code)) {
							?>
							<ul  class="tal">
								<?php
								$post_service = $pay_info['post_unse']['service'];
								$post_arr = $pay_info['post_unse'];
								$price_arr = $pay_info['price_unse'];
								$tag_skin = 'skin1';
								include NFE_PATH.'/include/payment/service.inc.php';
								?>
							</ul>
							<?php } else {?>
							<?php echo $nf_payment->payment_basic_code[$pay_row['pay_type']];?>
							<?php
							}?>
						</td>
						<td><?php echo $pay_info['pay_method_txt'];?></td>
						<td>
							<select name="pay_status[]" onChange="payment_process(this, '<?php echo $pay_row['no'];?>')">
							<?php
							if(is_array($nf_payment->pay_status)) { foreach($nf_payment->pay_status as $k=>$v) {
								$selected = $pay_row['pay_status']===(string)$k ? 'selected' : '';
							?>
							<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
							<?php
							} }
							?>
							</select>
						</td>
						<td>
							<select onChange="ch_tax_status(this, '<?php echo $pay_row['no'];?>')">
							<?php
							if(is_array($nf_payment->tax_status)) { foreach($nf_payment->tax_status as $k=>$v) {
								$selected = $pay_row['tax_status']===(string)$k ? 'selected' : '';
							?>
							<option value="<?php echo intval($k);?>" <?php echo $selected;?>><?php echo $v;?></option>
							<?php
							} }?>
							</select>

							<?php
							if($pay_info['post_unse']['tax_use']) {
							?>
							<fieldset style="border:1px solid #ddd;border-radius:20px;margin-top:10px;">
								<legend>현금영수증 정보</legend>
							<div><?php echo $nf_payment->pay_tax_type[$pay_info['post_unse']['pay_tax_type']];?></div>
							<?php if($pay_info['post_unse']['pay_tax_type']==='1') {?>
								<div><?php echo $nf_payment->pay_tax_num_type[$pay_info['post_unse']['pay_tax_num_type']];?> : <?php echo $pay_info['post_unse']['pay_tax_num_person'];?></div>
							<?php } else {?>
								<div>사업자등록번호 : <?php echo implode("-", $pay_info['post_unse']['pay_tax_num_biz']);?></div>
							<?php }?>
							<fieldset>
							<?php
							}?>
						</td>
						<td class="tar"><?php echo number_format(intval($pay_row['pay_dc']));?></td>
						<td class="tar"><?php echo number_format(intval($pay_row['pay_price']));?></td>
						<td>
							<span class="gray"><?php echo strtr($pay_row['pay_wdate'], array(" "=>"<br/>"));?><br>↓</span><br>
							<?php echo $pay_row['pay_status']==='1' ? strtr($pay_row['pay_sdate'], array(" "=>"<br/>")) : "입금대기";?>
						</td>
						<td><button type="button" onClick="nf_util.ajax_post(this, '삭제하시겠습니까?\n포인트를 돌려줘야한다면 포인트관리에서 수동으로 포인트를 부여해주시기 바랍니다.')" no="<?php echo $pay_row['no'];?>" mode="delete_payment" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i>삭제</button></td>
					</tr>
					<tr>
						<th colspan="13" class="tal bg_blue">전체합계 : <span class="red"><?php echo number_format(intval($pay_row['pay_price']));?>원</span></th>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			</form>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?\n포인트를 돌려줘야한다면 포인트관리에서 수동으로 포인트를 부여해주시기 바랍니다.')" url="../regist.php" mode="delete_select_payment" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="../regist.php?mode=excel_payment_list_q"><button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button></a>
			</div>
		</div>
		<!--//conbox-->

		<div><?php echo $paging['paging'];?></div>


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->