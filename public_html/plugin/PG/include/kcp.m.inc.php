<?php
// : /plugin/PG/include/regist.php에서 변수 가져옵니다. 아래는 그 외의 변수입니다.
$req_tx = "pay";
$site_cd = $this->pg_config[$this->pg]['id'];
$ordr_idxx = $this->get_html($arr['pay_oid']);
$good_mny = intval($arr['price']);
$good_name = $this->get_html($arr['gname']);
$buyr_name = $this->get_html($arr['mb_name']);
$buyr_tel2 = $this->get_html($arr['mb_phone']);
$buyr_mail = $this->get_html($arr['mb_email']);
$pay_method = $this->kcp_m_api1[$_POST['pay_methods']];
$Ret_URL = domain.NFE_URL.'/plugin/PG/include/kcp.m.action.php';
$actionResult = $this->kcp_m_api2[$_POST['pay_methods']];
$van_code = "";
$shop_name = $this->get_html($env['site_name_eng']);

/*
==========================================================================
	  거래등록 API URL
 --------------------------------------------------------------------------
 */
//$target_URL = "https://stg-spl.kcp.co.kr/std/tradeReg/register"; //개발환경
$target_URL = "https://spl.kcp.co.kr/std/tradeReg/register"; //운영환경
/* 
==========================================================================
요청 정보                                                          
--------------------------------------------------------------------------
*/
// 인증서정보(직렬화)
$kcp_cert_info      = stripslashes($this->pg_config['kcp']['cert']);




/* ============================================================================== */

$data = array(
	"site_cd"        => $site_cd,
	"kcp_cert_info"  => $kcp_cert_info,
	"ordr_idxx"      => $ordr_idxx,
	"good_mny"       => $good_mny,
	"good_name"      => $$good_name,
	"pay_method"     => $pay_method,
	"Ret_URL"        => $Ret_URL,
	"escw_used"      => "N",
	"user_agent"     => ""
);

$req_data = json_encode($data);

$header_data = array( "Content-Type: application/json", "charset=utf-8" );

// API REQ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $target_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// API RES
$res_data  = curl_exec($ch); 

/* 
==========================================================================
거래등록 응답정보                                                               
--------------------------------------------------------------------------
*/
$res_cd      = ""; // 응답코드
$res_msg     = ""; // 응답메세지
$approvalKey = ""; // 거래등록키
$traceNo     = ""; // 추적번호
$PayUrl      = ""; // 거래등록 PAY URL

// RES JSON DATA Parsing
$json_res = json_decode($res_data, true);

$res_cd      = $json_res["Code"];
$res_msg     = $json_res["Message"];
$approvalKey = $json_res["approvalKey"];
$traceNo     = $json_res["traceNo"];
$PayUrl      = $json_res["PayUrl"];
curl_close($ch);
?>
<script type="text/javascript">
/* kcp web 결제창 호츨 (변경불가) */
function call_pay_form() {
	var v_frm = document.order_info;
	var PayUrl = v_frm.PayUrl.value;
	// 인코딩 방식에 따른 변경 -- Start
	if(v_frm.encoding_trans == undefined) {
		v_frm.action = PayUrl;
	} else {
		// encoding_trans "UTF-8" 인 경우
		if(v_frm.encoding_trans.value == "UTF-8") {
			v_frm.action = PayUrl.substring(0,PayUrl.lastIndexOf("/"))  + "/jsp/encodingFilter/encodingFilter.jsp";
			v_frm.PayUrl.value = PayUrl;
		} else {
			v_frm.action = PayUrl;
		}
	}

	if(v_frm.Ret_URL.value == "") {
		/* Ret_URL값은 현 페이지의 URL 입니다. */
		alert("연동시 Ret_URL을 반드시 설정하셔야 됩니다.");
		return false;
	} else {
		v_frm.submit();
	}
}

/* kcp 통신을 통해 받은 암호화 정보 체크 후 결제 요청 (변경불가) */
function chk_pay() {
	self.name = "tar_opener";
	var pay_form = document.pay_form;

	if (pay_form.res_cd.value != "" ) {
		if (pay_form.res_cd.value != "0000" ) {
			if (pay_form.res_cd.value == "3001") {
				alert("사용자가 취소하였습니다.");
			}
			pay_form.res_cd.value = "";
			location.href = "<?php echo NFE_URL;?>/"; // 샘플에서는 거래등록 페이지로 이동
		}
	}
	if (pay_form.enc_info.value) {
		//alert(11);
		pay_form.submit();
	}
}
</script>

<?php
$param_opt_2 = $this->get_html($arr['pno']);
$param_opt_2 = !empty($param_opt_2) ? $param_opt_2 : 1;
?>

<!-- 주문정보 입력 form : order_info -->
<form name="order_info" method="post">
<!-- 추가 파라미터 ( 가맹점에서 별도의 값전달시 param_opt 를 사용하여 값 전달 ) -->
<input type="hidden" name="param_opt_1"     value="payment_process" />
<input type="hidden" name="param_opt_2"     value="<?php echo $param_opt_2;?>" />
<input type="hidden" name="param_opt_3"     value="<?php echo $this->page_back();?>" />
<input type="hidden" name="ordr_idxx" value="<?php echo $ordr_idxx;?>" maxlength="40" readonly />
<input type="hidden" name="good_name" value="<?php echo $good_name;?>" readonly />
<input type="hidden" name="good_mny" value="<?php echo $good_mny;?>" maxlength="9" readonly />
<input type="hidden" name="buyr_name" value="<?php echo $buyr_name;?>" />
<input type="hidden" name="buyr_tel2" value="<?php echo $buyr_tel2;?>" />
<input type="hidden" name="buyr_mail" value="<?php echo $buyr_mail;?>" />
<!-- 공통정보 -->
<input type="hidden" name="req_tx"          value="<?php echo $req_tx;?>" />              <!-- 요청 구분 -->
<input type="hidden" name="shop_name"       value="<?php echo $shop_name;?>" />        <!-- 사이트 이름 -->
<input type="hidden" name="site_cd"         value="<?php echo $site_cd;?>" />    <!-- 사이트 코드 -->
<input type="hidden" name="currency"        value="410"/>               <!-- 통화 코드 -->
<!-- 인증시 필요한 파라미터(변경불가)-->
<input type="hidden" name="escw_used"       value="N" />
<input type="hidden" name="pay_method"      value="<?php echo $pay_method;?>" />
<input type="hidden" name="ActionResult"    value="<?php echo $actionResult;?>" />
<input type="hidden" name="van_code"        value="" />
<!-- 신용카드 설정 -->
<input type="hidden" name="quotaopt"        value="12"/> <!-- 최대 할부개월수 -->
<!-- 가상계좌 설정 -->
<input type="hidden" name="ipgm_date"       value="" />
<!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
<input type="hidden" name="Ret_URL"         value="<?php echo $Ret_URL;?>" />
<!-- 화면 크기조정 -->
<input type="hidden" name="tablet_size"     value="1.0 " />
<!-- 거래등록 응답값 -->
<input type="hidden" name="approval_key" id="approval" value="<?php echo $approvalKey;?>"/>
<input type="hidden" name="traceNo"                    value="<?php echo $traceNo;?>" />
<input type="hidden" name="PayUrl"                     value="<?php echo $PayUrl;?>" />
<!-- 인증창 호출 시 한글깨질 경우 encoding 처리 추가 (**인코딩 네임은 대문자)  -->
<input type="hidden" name="encoding_trans" value="UTF-8" />
</form>