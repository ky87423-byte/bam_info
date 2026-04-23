<?php
$pg_m = is_mobile ? '.m' : '';
$pg_start = "";

switch($nf_payment->pg) {
	case "kcp":
		switch(is_mobile) {
			case true:
			break;

			default:
				ob_start();
				include NFE_PATH.'/plugin/PG/include/kcp.start.php';
				$pg_start = ob_get_clean();
			break;
		}
?>
<?php
		break;



// : 해쉬값 위변조때문에 form태그는 자바스크립트로 불러옵니다.
	case "nicepay":
?>
<script src="https://web.nicepay.co.kr/v3/webstd/js/nicepay-3.0.js" type="text/javascript"></script>
<?php
		break;



	case "toss":
?>
<script src="https://js.tosspayments.com/v1/payment"></script>
<?php
	break;
}
?>
<div class="pg_tag_put_"><?php echo $pg_start;?></div>