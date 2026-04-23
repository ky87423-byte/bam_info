<script type="text/javascript">
var clientKey = "<?php echo $this->pg_config['toss']['key'];?>";
var tossPayments = TossPayments(clientKey);
var toss_var = function() {
	tossPayments.requestPayment("<?php echo $this->toss_api[$_POST['pay_methods']];?>", {
		amount: <?php echo intval($arr['price']);?>,
		orderId: "<?php echo $pay_oid;?>",
		orderName: "<?php echo $this->get_html($arr['gname']);?>",
		customerName: "<?php echo $member['mb_name'];?>",
		customerEmail:"<?php echo $member['mb_email'];?>",
		successUrl: "<?php echo domain;?>/include/regist.php",
		failUrl: "<?php echo domain;?>/include/regist.php"
	});
}
</script>