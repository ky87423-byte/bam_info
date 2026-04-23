<script type="text/javascript">
/****************************************************************/
/* m_Completepayment  설명                                      */
/****************************************************************/
/* 인증완료시 재귀 함수                                         */
/* 해당 함수명은 절대 변경하면 안됩니다.                        */
/* 해당 함수의 위치는 payplus.js 보다먼저 선언되어여 합니다.    */
/* Web 방식의 경우 리턴 값이 form 으로 넘어옴                   */
/****************************************************************/
	function m_Completepayment( FormOrJson, closeEvent ) {
	var frm = document.order_info;

		/********************************************************************/
		/* FormOrJson은 가맹점 임의 활용 금지                               */
		/* frm 값에 FormOrJson 값이 설정 됨 frm 값으로 활용 하셔야 됩니다.  */
		/* FormOrJson 값을 활용 하시려면 기술지원팀으로 문의바랍니다.       */
		/********************************************************************/
		GetField( frm, FormOrJson );

		if( frm.res_cd.value == "0000" ) {
			frm.submit();
		} else {
			alert( "[" + frm.res_cd.value + "] " + frm.res_msg.value );
			closeEvent();
		}
	}
</script>
<script type="text/javascript" src="https://<?php echo $nf_payment->pg_demo ? 'test' : '';?>pay.kcp.co.kr/plugin/payplus_web.jsp"></script>
<?php
/*
결제창 호출 JS
개발 : https://testpay.kcp.co.kr/plugin/payplus_web.jsp
운영 : https://pay.kcp.co.kr/plugin/payplus_web.jsp
*/
?>
<script type="text/javascript">
	/* 결제창 실행 함수 */
	function jsf__pay( form ) {
		try {
			KCP_Pay_Execute( form );
		} catch (e) {
			/* IE 에서 결제 정상종료시 throw로 스크립트 종료 */
		}
	}
</script>


<form name="order_info" method="post" action="<?php echo NFE_URL;?>/include/regist.php">
</form>