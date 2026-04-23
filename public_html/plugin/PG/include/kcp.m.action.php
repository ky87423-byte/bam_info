<?php
$not_move_page_check = true;
include "../../../engine/_core.php";

// 거래등록 응답 값
$approvalKey    = $_POST[ "approvalKey"    ]; // 거래등록키
$traceNo        = $_POST[ "traceNo"        ]; // 추적번호
$PayUrl         = $_POST[ "PayUrl"         ]; // 거래등록 PAY URL
// 인증시 필요한 결제수단 세팅 값
$pay_method     = $_POST[ "pay_method"   ]; // 결제수단
$actionResult   = $_POST[ "actionResult" ];
$van_code       = $_POST[ "van_code" ];
// 가맹점 리턴 URL
$Ret_URL        = $_POST[ "Ret_URL"     ]; 

/* kcp와 통신후 kcp 서버에서 전송되는 결제 요청 정보 */
$req_tx          = $_POST[ "req_tx"         ]; // 요청 종류
$res_cd          = $_POST[ "res_cd"         ]; // 응답 코드
$site_cd         = $_POST[ "site_cd"        ]; // 사이트 코드
$tran_cd         = $_POST[ "tran_cd"        ]; // 트랜잭션 코드
$ordr_idxx       = $_POST[ "ordr_idxx"      ]; // 쇼핑몰 주문번호
$good_name       = $_POST[ "good_name"      ]; // 상품명
$good_mny        = $_POST[ "good_mny"       ]; // 결제 총금액
$buyr_name       = $_POST[ "buyr_name"      ]; // 주문자명
$buyr_tel1       = $_POST[ "buyr_tel1"      ]; // 주문자 전화번호
$buyr_tel2       = $_POST[ "buyr_tel2"      ]; // 주문자 핸드폰 번호
$buyr_mail       = $_POST[ "buyr_mail"      ]; // 주문자 E-mail 주소
$use_pay_method  = $_POST[ "use_pay_method" ]; // 결제 방법
$enc_info        = $_POST[ "enc_info"       ]; // 암호화 정보
$enc_data        = $_POST[ "enc_data"       ]; // 암호화 데이터
$cash_yn         = $_POST[ "cash_yn"        ];
$cash_tr_code    = $_POST[ "cash_tr_code"   ];
/* 기타 파라메터 추가 부분 - Start - */
$param_opt_1    = $_POST[ "param_opt_1"     ]; // 기타 파라메터 추가 부분
$param_opt_2    = $_POST[ "param_opt_2"     ]; // 기타 파라메터 추가 부분
$param_opt_3    = $_POST[ "param_opt_3"     ]; // 기타 파라메터 추가 부분
/* 기타 파라메터 추가 부분 - End -   */
?>
<!DOCTYPE>
<html>
<head>
	<title>*** NHN KCP API SAMPLE ***</title>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<meta http-equiv="x-ua-compatible" content="ie=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">  
	<script type="text/javascript">
	function chk_pay() {
		var pay_form = document.pay_form;
		
		if (pay_form.res_cd.value != "" )
		{   
			if (pay_form.res_cd.value != "0000" )
			{
				if (pay_form.res_cd.value == "3001")
				{
					alert("사용자가 취소하였습니다.");
				}
				pay_form.res_cd.value = "";
				location.href = "<?php echo $param_opt_3;?>"; // 샘플에서는 거래등록 페이지로 이동
			}
		}
		if (pay_form.enc_info.value)
			pay_form.submit();
	}
	</script>
</head>
<body onload="chk_pay();">
	<form name="pay_form" method="post" action="<?php echo NFE_URL;?>/include/regist.php">
	<input type="hidden" name="req_tx"         value="<?php echo $req_tx?>" />               <!-- 요청 구분          -->
	<input type="hidden" name="res_cd"         value="<?php echo $res_cd?>" />               <!-- 결과 코드          -->
	<input type="hidden" name="site_cd"        value="<?php echo $site_cd?>" />              <!-- 사이트 코드      -->
	<input type="hidden" name="tran_cd"        value="<?php echo $tran_cd?>" />              <!-- 트랜잭션 코드      -->
	<input type="hidden" name="ordr_idxx"      value="<?php echo $ordr_idxx?>" />            <!-- 주문번호           -->
	<input type="hidden" name="good_mny"       value="<?php echo $good_mny?>" />             <!-- 휴대폰 결제금액    -->
	<input type="hidden" name="good_name"      value="<?php echo $good_name?>" />            <!-- 상품명             -->
	<input type="hidden" name="buyr_name"      value="<?php echo $buyr_name?>" />            <!-- 주문자명           -->
	<input type="hidden" name="buyr_tel1"      value="<?php echo $buyr_tel1?>" />            <!-- 주문자 전화번호    -->
	<input type="hidden" name="buyr_tel2"      value="<?php echo $buyr_tel2?>" />            <!-- 주문자 휴대폰번호  -->
	<input type="hidden" name="buyr_mail"      value="<?php echo $buyr_mail?>" />            <!-- 주문자 E-mail      -->
	<input type="hidden" name="enc_info"       value="<?php echo $enc_info?>" />
	<input type="hidden" name="enc_data"       value="<?php echo $enc_data?>" />
	<input type="hidden" name="use_pay_method" value="<?php echo $use_pay_method?>" />
	<input type="hidden" name="cash_yn"        value="<?php echo $cash_yn?>" />              <!-- 현금영수증 등록여부-->
	<input type="hidden" name="cash_tr_code"   value="<?php echo $cash_tr_code?>" />
	<!-- 추가 파라미터 -->
	<input type="hidden" name="param_opt_1"    value="<?php echo $param_opt_1?>" />
	<input type="hidden" name="param_opt_2"    value="<?php echo $param_opt_2?>" />
	<input type="hidden" name="param_opt_3"    value="<?php echo $param_opt_3?>" />
	</form>
</body>
</html>