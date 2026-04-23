<?php
$merchantKey = $this->pg_config[$this->pg]['key']; // 상점키
$MID         = $this->pg_config[$this->pg]['id']; // 상점아이디
$goodsName   = $this->get_html($arr['gname']); // 결제상품명
$price       = $this->get_html($arr['price']); // 결제상품금액
$buyerName   = $this->get_html($arr['mb_name']); // 구매자명 
$buyerTel	 = $this->get_html($arr['mb_phone']); // 구매자연락처
$buyerEmail  = $this->get_html($arr['mb_email']); // 구매자메일주소        
$moid        = $this->get_html($arr['pay_oid']); // 상품주문번호                     
$returnURL	 = domain.NFE_URL."/include/regist.php"; // 결과페이지(절대경로) - 모바일 결제창 전용

$ediDate = date("YmdHis");
$hashString = bin2hex(hash('sha256', $ediDate.$MID.$price.$merchantKey, true));
?>
<script type="text/javascript">
//결제창 최초 요청시 실행됩니다.
function nicepayStart(){
	
	if(checkPlatform(window.navigator.userAgent) == "mobile"){//모바일 결제창 진입
		document.payForm.action = "https://web.nicepay.co.kr/v3/v3Payment.jsp";
		document.payForm.acceptCharset="euc-kr";
		document.payForm.submit();
	}else{//PC 결제창 진입
		goPay(document.payForm);
	}
}

//[PC 결제창 전용]결제 최종 요청시 실행됩니다. <<'nicepaySubmit()' 이름 수정 불가능>>
function nicepaySubmit(){
	document.payForm.submit();
}

//[PC 결제창 전용]결제창 종료 함수 <<'nicepayClose()' 이름 수정 불가능>>
function nicepayClose(){
	alert("결제가 취소 되었습니다");
}

//pc, mobile 구분(가이드를 위한 샘플 함수입니다.)
function checkPlatform(ua) {
	if(ua === undefined) {
		ua = window.navigator.userAgent;
	}
	
	ua = ua.toLowerCase();
	var platform = {};
	var matched = {};
	var userPlatform = "pc";
	var platform_match = /(ipad)/.exec(ua) || /(ipod)/.exec(ua) 
		|| /(windows phone)/.exec(ua) || /(iphone)/.exec(ua) 
		|| /(kindle)/.exec(ua) || /(silk)/.exec(ua) || /(android)/.exec(ua) 
		|| /(win)/.exec(ua) || /(mac)/.exec(ua) || /(linux)/.exec(ua)
		|| /(cros)/.exec(ua) || /(playbook)/.exec(ua)
		|| /(bb)/.exec(ua) || /(blackberry)/.exec(ua)
		|| [];
	
	matched.platform = platform_match[0] || "";
	
	if(matched.platform) {
		platform[matched.platform] = true;
	}
	
	if(platform.android || platform.bb || platform.blackberry
			|| platform.ipad || platform.iphone 
			|| platform.ipod || platform.kindle 
			|| platform.playbook || platform.silk
			|| platform["windows phone"]) {
		userPlatform = "mobile";
	}
	
	if(platform.cros || platform.mac || platform.linux || platform.win) {
		userPlatform = "pc";
	}
	
	return userPlatform;
}
</script>

<form name="payForm" method="post" action="<?php echo NFE_URL;?>/include/regist.php" accept-charset="utf-8">
<input type="hidden" name="param_opt_1"     value="payment_process" />
<input type="hidden" name="param_opt_2"     value="<?php echo $this->get_html($arr['pno']);?>" />
<input type="hidden" name="param_opt_3"     value="<?php echo $_SERVER['REQUEST_URI'];?>" />
<input type="hidden" name="PayMethod" value="<?php echo $this->nicepay_api[$_POST['pay_method']];?>">
<input type="hidden" name="GoodsName" value="<?php echo($goodsName)?>">
<input type="hidden" name="Amt" value="<?php echo($price)?>">
<input type="hidden" name="MID" value="<?php echo($MID)?>">
<input type="hidden" name="Moid" value="<?php echo($moid)?>">
<input type="hidden" name="BuyerName" value="<?php echo($buyerName)?>">
<input type="hidden" name="BuyerEmail" value="<?php echo($buyerEmail)?>">
<input type="hidden" name="BuyerTel" value="<?php echo($buyerTel)?>">
<input type="hidden" name="ReturnURL" value="<?php echo($returnURL)?>">
<input type="hidden" name="VbankExpDate" value="">
<!-- 옵션 -->	 
<input type="hidden" name="GoodsCl" value="0"/>						<!-- 상품구분(실물(1),컨텐츠(0)) -->
<input type="hidden" name="TransType" value="0"/>					<!-- 일반(0)/에스크로(1) --> 
<input type="hidden" name="CharSet" value="utf-8"/>				<!-- 응답 파라미터 인코딩 방식 -->
<input type="hidden" name="ReqReserved" value=""/>					<!-- 상점 예약필드 -->
			
<!-- 변경 불가능 -->
<input type="hidden" name="EdiDate" value="<?php echo($ediDate)?>"/>			<!-- 전문 생성일시 -->
<input type="hidden" name="SignData" value="<?php echo($hashString)?>"/>	<!-- 해쉬값 -->
</form>