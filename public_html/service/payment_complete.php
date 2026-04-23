<?php
$add_cate_arr = array('online');
include_once "../engine/_core.php";
$nf_member->check_login();

$pay_row = $db->query_fetch("select * from nf_payment where `no`=? and `pay_mno`=?", array($_GET['no'], $member['no']));
if(!$pay_row) {
	die($nf_util->move_url("/", "결제정보가 없습니다."));
}

$pay_info = $nf_payment->pay_info($pay_row);
$price_result = $pay_row['pay_price']-$pay_row['pay_dc'];

include '../include/header_meta.php';
include '../include/header.php';
include '../include/scroll_banner.php'; // : 사이드배너

$m_title = '결제완료';
include NFE_PATH.'/include/m_title.inc.php';
?>
<div class="wrap1260 MAT20">
	<section class="register service sub payment_complete">
		<p  style="display:none;">결제완료</p>
		<div class="box_wrap">
			<div class="join_message">
				<p><i class="axi axi-ion-android-checkmark"></i></p>
				<p>
					<?php if($pay_row['pay_status']==='1') {?>
					결제가 완료되었습니다.
					<?php } else if($pay_row['pay_method']=='bank') {?>
					무통장입금건은 입금 확인 후 서비스가 적용됩니다.
					<?php } else {?>
					결제가 취소되었습니다.
					<?php }?>
				</p>
			</div>

			<div class="payment_box">
				<?php
				$basic_pay_type = array('direct');
				if(!array_key_exists($pay_row['pay_type'], $nf_payment->payment_basic_code)) {
				?>
				<h5>신청상품</h5>
				<table class="style2 tac">
					<colgroup>
						<col width="25%">
						<col width="60%">
						<col width="15%">
					</colgroup>
					<tr>
						<th>상품명</th>
						<th>상품내용</th>
						<th>금액</th>
					</tr>
					<?php
					$post_service = $pay_info['post_unse']['service'];
					$post_arr = $pay_info['post_unse'];
					$price_arr = $pay_info['price_unse'];
					include NFE_PATH.'/include/payment/service.inc.php';
					?>
				</table>
				<?php
				}?>

				<h5>결제정보</h5>
				<table class="style2">
					<?php
					if(array_key_exists($pay_row['pay_type'], $nf_payment->payment_basic_code)) {
					?>
					<tr>
						<th>상품정보</th>
						<td><?php echo $nf_payment->payment_basic_code[$pay_row['pay_type']];?></td>
					</tr>
					<?php
					}?>
					<?php
					if(!array_key_exists($pay_row['pay_type'], $nf_payment->payment_basic_code)) {
						if($price_result>0) {
					?>
					<tr>
						<th>신청상품 합계</th>
						<td><span class="orange"><?php echo number_format(intval($pay_row['pay_price']));?></span>원</td>
					</tr>
					<?php
						}

						if($pay_row['pay_dc']>0) {
					?>
					<tr>
						<th>할인내역</th>
						<td><?php echo number_format(intval($pay_row['pay_dc']));?> 포인트</td>
					</tr>
					<?php
						}
					}?>
					<tr>
						<th>최종 결제금액</th>
						<td><span class="red"><?php echo number_format(intval($pay_row['pay_price'])-intval($pay_row['pay_dc']));?></span>원</td>
					</tr>
				</table>

				<?php
				if($price_result>0) {
				?>
				<h5>결제수단 선택</h5>
				<table class="style2">
					<tr>
						<th>결제방법</th>
						<td><?php echo $pay_info['pay_method_txt'];?></td>
					</tr>
					<?php
					if($pay_row['pay_method']=='bank') {
					?>
					<tr>
						<th>입금은행</th>
						<td><?php echo strtr($pay_row['pay_bank_name'], array('/'=>' '));?></td>
					<tr>
						<th>입금자명</th>
						<td><?php echo $nf_util->get_text($pay_row['pay_bank']);?></td>
					</tr>
					<?php
					}?>
				</table>
				<?php
				}?>

				<div class="next_btn">
					<a href="<?php echo NFE_URL;?>/"><button type="button" class="base graybtn">홈으로</button></a>
					<a href="<?php echo NFE_URL;?>/member/pay.php"><button type="button" class="base">유료 결제내역 보기</button></a>
				</div>

			</div>
			<!--//payment_box-->
		</div>
		<!--//box_wrap-->
	</section>
</div>

<!--푸터영역-->
<?php //include '../include/footer.php'; ?>