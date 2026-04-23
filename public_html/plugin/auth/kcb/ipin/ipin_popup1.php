<?php
	header('Content-Type: text/html; charset=euc-kr');
	phpinfo();
	//**************************************************************************
	// 파일명 : ipin_popup1.php
	// - 바닥페이지
	// 아이핀 서비스 요청 정보 입력 화면
	//**************************************************************************
?>
<html>
<head>
<title>KCB 아이핀 서비스 샘플 1</title>
<script>
<!--
	function jsSubmit(){
		var popupWindow = window.open("ipin_popup2.php", "kcbPop", "left=200, top=100, status=0, width=450, height=550");
		popupWindow.focus()	;
	}
//-->
</script>
</head>
<body>
	<div><strong> - 아이핀 인증</strong></div>
	<input type="button" value="아이핀" onClick="jsSubmit();">
	
	<!-- 아이핀 팝업 처리결과 정보 = ipin_popup3 에서 값 입력 -->
	<form name="kcbResultForm" method="post">
		<input type="hidden" name="CP_CD" />
		<input type="hidden" name="TX_SEQ_NO" />
		<input type="hidden" name="RSLT_CD" />
		<input type="hidden" name="RSLT_MSG" />
		
		<input type="hidden" name="RSLT_NAME" />
		<input type="hidden" name="RSLT_BIRTHDAY" />
		<input type="hidden" name="RSLT_SEX_CD" />
		<input type="hidden" name="RSLT_NTV_FRNR_CD" />
		
		<input type="hidden" name="DI" />
		<input type="hidden" name="CI" />
		<input type="hidden" name="CI2" />
		<input type="hidden" name="CI_UPDATE" />
		<input type="hidden" name="VSSN" />
		
		<input type="hidden" name="RETURN_MSG" />
	</form>
</body>
</html>
