<?php
header("Content-Type:text/html; charset=euc-kr;"); 
/*
****************************************************************************************
* <인증 결과 파라미터>
****************************************************************************************
*/
$authResultCode = $_POST['AuthResultCode'];		// 인증결과 : 0000(성공)
$authResultMsg = $_POST['AuthResultMsg'];		// 인증결과 메시지
$nextAppURL = $_POST['NextAppURL'];				// 승인 요청 URL
$txTid = $_POST['TxTid'];						// 거래 ID
$authToken = $_POST['AuthToken'];				// 인증 TOKEN
$payMethod = $_POST['PayMethod'];				// 결제수단
$mid = $_POST['MID'];							// 상점 아이디
$moid = $_POST['Moid'];							// 상점 주문번호
$amt = $_POST['Amt'];							// 결제 금액
$reqReserved = $_POST['ReqReserved'];			// 상점 예약필드
$netCancelURL = $_POST['NetCancelURL'];			// 망취소 요청 URL
	
/*
****************************************************************************************
* <승인 결과 파라미터 정의>
* 샘플페이지에서는 승인 결과 파라미터 중 일부만 예시되어 있으며, 
* 추가적으로 사용하실 파라미터는 연동메뉴얼을 참고하세요.
****************************************************************************************
*/

$response = "";

if($authResultCode === "0000"){
	/*
	****************************************************************************************
	* <해쉬암호화> (수정하지 마세요)
	* SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
	****************************************************************************************
	*/	
	$ediDate = date("YmdHis");
	$merchantKey = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // 상점키
	$signData = bin2hex(hash('sha256', $authToken . $mid . $amt . $ediDate . $merchantKey, true));

	try{
		$data = Array(
			'TID' => $txTid,
			'AuthToken' => $authToken,
			'MID' => $mid,
			'Amt' => $amt,
			'EdiDate' => $ediDate,
			'SignData' => $signData
		);		
		$response = reqPost($data, $nextAppURL); //승인 호출
		jsonRespDump($response); //response json dump example
		
	}catch(Exception $e){
		$e->getMessage();
		$data = Array(
			'TID' => $txTid,
			'AuthToken' => $authToken,
			'MID' => $mid,
			'Amt' => $amt,
			'EdiDate' => $ediDate,
			'SignData' => $signData,
			'NetCancel' => '1'
		);
		$response = reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
		jsonRespDump($response); //response json dump example
	}	
	
}else{
	//인증 실패 하는 경우 결과코드, 메시지
	$ResultCode = $authResultCode; 	
	$ResultMsg = $authResultMsg;
}

// API CALL foreach 예시
function jsonRespDump($resp){
	$resp_utf = iconv("EUC-KR", "UTF-8", $resp); 
	$respArr = json_decode($resp_utf);
	foreach ( $respArr as $key => $value ){
		echo "$key=". iconv("UTF-8", "EUC-KR", $value)."<br />";
	}
}

//Post api call
function reqPost(Array $data, $url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));	//POST data
	curl_setopt($ch, CURLOPT_POST, true);
	$response = curl_exec($ch);
	curl_close($ch);	 
	return $response;
}
?>