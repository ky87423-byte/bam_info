<?php
$add_cate_arr = array('');
$_SERVER['__USE_API__'] = array('jqueryui');
include_once "../engine/_core.php";
$nf_member->check_login();

$_site_title_ = '마이페이지 > 유료결제내역';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '유료결제내역';
include NFE_PATH.'/include/m_title.inc.php';

$nf_util->sess_page_save("mypage_payment_list");

$_where = " and (pay_status>0 or (pay_status=0 and `pay_method`='bank'))";
// : 날짜
$field = 'pay_wdate';
if($_GET['date1']) $_date_arr[] = "np.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "np.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

$q = "nf_payment as np where `pay_mno`=".intval($member['no'])." ".$_where;

$order = " order by np.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 10;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$_arr['tema'] = 'B';
$paging = $nf_util->_paging_($_arr);

$payment_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
$(function(){
	$(".date_li-").click(function(){
		$(".date_li-").removeClass("on");
		$(this).addClass("on");
	});
});
</script>
	<section class="my_sub wrap1400">
		<?php
		$left_on['pay'] = 'on';
		include '../include/my_leftmenu.php';
		?>
		<div class="my_con">
			<section class="pay_list tab_style3">
				<p class="s_title">유료결제내역</p>
				<ul class="help_text">
					<li>회원님께서 구매하신 모든 유료서비스 이용내역을 확인할 수 있습니다.</li>
					<li>최근 3개월 이내 조회만 확인할 수 있으며, 과거 이용내역은 고객센터로 문의해 주세요.</li>	
				</ul>
				<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<div class="date_search">
					<ul class="fl">
						<li>조회기간&nbsp;&nbsp;</li>
						<li class="date_li-"><?php echo $nf_util->btn_date_tag('', '');?></li>
						<li class="date_li-"><?php echo $nf_util->btn_date_tag(1, 'week');?></li>
						<li class="date_li-"><?php echo $nf_util->btn_date_tag(1, 'month');?></li>
						<li class="date_li-"><?php echo $nf_util->btn_date_tag(3, 'month');?></li>
					</ul>
					<ul class="fr">
						<li><input type="text" name="date1" value="<?php echo $nf_util->get_html($_GET['date1']);?>" class="datepicker_inp"> ~ <input type="text" name="date2" value="<?php echo $nf_util->get_html($_GET['date2']);?>" class="datepicker_inp"> <button type="submit" class="bbcolor">검색</button></li>
						<li>
							<select name="page_row" onChange="nf_util.ch_page_row(this)">
								<option value="10" <?php echo $_GET['page_row']=='10' ? 'selected' : '';?>>10개씩 보기</option>
								<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개씩 보기</option>
								<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개씩 보기</option>
								<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개씩 보기</option>
							</select>
						</li>
					</ul>
				</div>
				</form>
				<table class="style3">
					<colgroup>
						<col width="55%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
					</colgroup>
					<tr>
						<th>상품명</th>
						<th>결제방법</th>
						<th>포인트사용</th>
						<th>결제금액</th>
					</tr>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr>
						<td colspan="4" style="padding:1.5rem .5rem">유료 결제내역이 없습니다.</td>
					</tr>
					<?php
						break;


						default:
							while($pay_row=$db->afetch($payment_query)) {
								$pay_info = $nf_payment->pay_info($pay_row);
					?>
					<tr>
						<td colspan="4">
							<div class="wrap">
								<div class="pr_name tal">
									<ul>
										<?php
										$post_service = $pay_info['post_unse']['service'];
										$post_arr = $pay_info['post_unse'];
										$price_arr = $pay_info['price_unse'];
										$tag_skin = 'skin1';
										include NFE_PATH.'/include/payment/service.inc.php';
										?>
									</ul>
								</div>
								<div class="pay_way">
									<em>결제방법 : </em>	<?php echo $pay_info['pay_method_txt'];?>
								</div>
								<div class="point_us">
									<em>포인트사용 : </em> <span><?php echo number_format(intval($pay_row['pay_dc']));?></span> 원
								</div>
								<div class="payment">
									<em>총 결제금액 : </em> <span><?php echo number_format($pay_row['pay_price']-$pay_row['pay_dc']);?></span> 원
								</div>
								<div class="assi_line">
									<ul class="fl">
										<li>신청일 : <?php echo $pay_row['pay_wdate'];?></li>
									</ul>
									<ul class="fr">
										<li>
											<?php if($pay_row['pay_status']>0) {?>
												<span class="blue"><?php echo $nf_payment->pay_status[$pay_row['pay_status']];?> : <?php echo date("Y.m.d (H.i)", strtotime($pay_row['pay_sdate']));?></span>
											<?php } else {?>
												<span class="red"><?php echo $nf_payment->pay_status[$pay_row['pay_status']];?></span>
											<?php }?>
										</li>
									</ul>
								</div>
							</div>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</table>
			</section>
			<!--페이징-->
			<div><?php echo $paging['paging'];?></div>
		</div>
	</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>
