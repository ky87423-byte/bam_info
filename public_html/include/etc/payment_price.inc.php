<script type="text/javascript">
$(function(){
	var form = document.forms['fpayment'];
	if($(form).find("[name='pay_methods']:checked").val()=='bank') {
		nf_payment.click_pay_methods($(form).find("[name='pay_methods']:checked")[0]);
	}
});
</script>
<h5>결제정보</h5>
<table class="style1">
	<?php
	if($env['member_point_arr']['use_point']) {
	?>
	<tr class="pay-method-p">
		<th>신청상품 합계</th>
		<td><span class="orange price-hap-">0</span>원</td>
	</tr>
	<tr class="pay-method-p">
		<th>할인내역</th>
		<td><input type="text" name="use_point" onkeyup="this.value=this.value.number_format()"> 포인트 <button type="button" onClick="nf_payment.click_point()" class="base2">사용하기</button><br>보유 포인트 : <b><?php echo number_format(intval($member['mb_point']));?></b>포인트</td>
	</tr>
	<?php
	}?>
	<tr>
		<th>최종 결제금액</th>
		<td><span class="red price-result-">0</span>원</td>
	</tr>
</table>

<h5 class="pay-method-p">결제수단 선택</h5>
<table class="style1 pay-method-p">
	<?php
	include NFE_PATH.'/include/etc/payment_method.inc.php';
	?>
</table>
<div class="next_btn">
	<a href="<?php echo NFE_URL;?>/<?php echo $member['mb_type'];?>/index.php"><button type="button" class="base graybtn">My홈으로</button></a> <!--마이페이지 홈으로 이동-->
	<button type="button" onClick="nf_payment.submit()" class="base">결제하기</button>
</div>