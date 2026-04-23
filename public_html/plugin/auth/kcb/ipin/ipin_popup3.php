<?php
include "../../../../engine/_core.php";

    //**************************************************************************
	// 파일명 : ipin_popup3.php
	// - 팝업페이지
	// 아이핀 서비스 인증 결과 화면(return url)
	// 암호화된 인증결과정보를 복호화한다.
	//**************************************************************************
	
	/**************************************************************************
	 * okcert3 아이핀 서비스 파라미터
	 **************************************************************************/
	/* 팝업창 리턴 항목 */
	$MDL_TKN  =	$_REQUEST["MDL_TKN"];			// 모듈토큰

	// ########################################################################
	// # KCB로부터 부여받은 회원사코드(아이디) 설정 (12자리)
	// ########################################################################
	$CP_CD = $env['ipin_id'];				// 회원사코드(아이디)
	
	//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
    //' 타겟 : 운영/테스트 전환시 변경 필요
    //'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	$target = "PROD"; // 테스트="TEST", 운영="PROD"
	
	//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
    //' 라이센스 파일
    //'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	//$license = "C:\\okcert3_license\\".$CP_CD."_TIS_01_".$target."_AES_license.dat";
	$license = $_SERVER['DOCUMENT_ROOT'].'/plugin/auth/kcb/license/'.$CP_CD.'_TIS_01_PROD_AES_license.dat';
	
	
	/**************************************************************************
	okcert3 request param JSON String
	**************************************************************************/
	$params = '{ "MDL_TKN":"'.$MDL_TKN.'" }';
    
	
	$svcName = "TIS_IPIN_POPUP_RESULT";
	$out = NULL;
	
	// okcert3 실행
	$ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);	// UTF-8
	//$ret = okcert3($target, $CP_CD, $svcName, $params, $license, $out);		// EUC-KR
	
	/**************************************************************************
	okcert3 응답 정보
	**************************************************************************/
	$RSLT_CD = "";						// 결과코드
	$RSLT_MSG = "";						// 결과메시지
	$TX_SEQ_NO = ""; 					// 거래일련번호
	
	$RSLT_NAME		= "";
	$RSLT_BIRTHDAY	="";
	$RSLT_SEX_CD	= "";
	$RSLT_NTV_FRNR_CD="";
	
	$DI			= "";
	$CI 		= "";
	$CI2 		= "";
	$CI_UPDATE	= "";
	$VSSN		= "";
	
	$RETURN_MSG = "";					// 리턴메시지
	if($ret == 0) {		// 함수 실행 성공일 경우 변수를 결과에서 얻음
		//$out = iconv("euckr","utf-8",$out);		// 인코딩 icnov 처리. okcert3 호출(EUC-KR)일 경우에만 사용 (json_decode가 UTF-8만 가능)
		$output = json_decode($out,true);		// $output = UTF-8
		
		$RSLT_CD = $output['RSLT_CD'];
		//$RSLT_MSG  = iconv("utf-8","euckr", $output["RSLT_MSG"]);	// 다시 EUC-KR 로 변환
		
		if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리
		if(isset($output["RETURN_MSG"]))  $RETURN_MSG  = $output['RETURN_MSG'];
		
		if( $RSLT_CD == "T000" ) { // T000 : 정상건
			//$RSLT_NAME  = iconv("utf-8","euckr",$output['RSLT_NAME']); // 다시 EUC-KR 로 변환
			$RSLT_BIRTHDAY	= $output['RSLT_BIRTHDAY'];
			$RSLT_SEX_CD	= $output['RSLT_SEX_CD'];
			$RSLT_NTV_FRNR_CD=$output['RSLT_NTV_FRNR_CD'];
			
			$DI			= $output['DI'];
			$CI 		= $output['CI'];
			$CI2 		= $output['CI2'];
			$CI_UPDATE	= $output['CI_UPDATE'];
			$VSSN		= $output['VSSN'];

			/*
			$field_name_IPIN_DEC = array(
				"dupInfo        ",	// 0
				"coinfo1        ",	// 1
				"coinfo2        ",	// 2
				"ciupdate       ",	// 3
				"virtualNo      ",	// 4
				"cpCode         ",	// 5
				"realName       ",	// 6
				"cpRequestNumber",	// 7
				"age            ",	// 8
				"sex            ",	// 9
				"nationalInfo   ",	// 10
				"birthDate      ",	// 11
				"authInfo       ",	// 12
			);
			*/

			$field = $output;
			$add_field = array(
				'0'=>$output['DI'],
				'1'=>$output['CI'],	// 1
				'2'=>$output['CI2'],	// 2
				'3'=>$output['CI_UPDATE'],	// 3
				'4'=>$output['VSSN'],	// 4
				'5'=>$output['CP_CD'],	// 5
				'6'=>iconv("UTF-8", "EUC-KR", $output['RSLT_NAME']),	// 6
				'7'=>$output[''],	// 7
				'8'=>$output['RSLT_BIRTHDAY'],	// 8
				'9'=>$output['RSLT_SEX_CD']=='M' ? 1 : 2,	// 9
				'10'=>"",	// 10
				'11'=>$output['RSLT_BIRTHDAY'],	// 11
				'12'=>"",	// 12
			);

			$field = array_merge($field, $add_field);
			$is_adult = date("Ymd", strtotime("-19 year", time()))>=$field[11] ? true : false;

			if($env['use_adult'] && !$is_adult) {
				die("<script>alert('성인이 아닙니다.\n\n성인만 접근 가능합니다.');opener.location.href='".NFE_PATH."/';self.close();</script>");
			}

			$_SESSION['adult_view'] = 1;
			$_SESSION['certify_info'] = $field;

			// : 성공
			$_SESSION['certify_type'] = "ipin";
			if($member['mb_id']){	 // 회원이라면
				if($is_adult) { // 성인 맞다
					$db->_query("update nf_member set is_adult=1 where `no`=?", array($member['no']));
				}
			}

			$move_url = 'window.opener.location.href = "'.$_SESSION['page_code_auth'].'";';
			if(!$_SESSION['page_code_auth']) {
				$move_url = 'window.opener.location.reload();';
			}
			$_SESSION['page_code_auth'] = "";
		}
	}?>
	<script type="text/javascript">
	<?php echo $move_url;?>
	window.close();
	</script>
	<?php
	exit;
?>
<title>KCB 아이핀 서비스 샘플 3</title>
<script language="javascript" type="text/javascript" >
	function fncOpenerSubmit() {
		opener.document.kcbResultForm.CP_CD.value    	= "<?=$CP_CD?>";
		opener.document.kcbResultForm.RSLT_CD.value   	= "<?=$RSLT_CD?>";
		opener.document.kcbResultForm.RSLT_MSG.value  	= "<?=$RSLT_MSG?>";
		opener.document.kcbResultForm.TX_SEQ_NO.value 	= "<?=$TX_SEQ_NO?>";
		
		opener.document.kcbResultForm.RETURN_MSG.value  = "<?=$RETURN_MSG?>";
<?php
 	if ($ret == 0) {
?>
		opener.document.kcbResultForm.RSLT_NAME.value        = "<?=$RSLT_NAME?>";
		opener.document.kcbResultForm.RSLT_BIRTHDAY.value    = "<?=$RSLT_BIRTHDAY?>";
		opener.document.kcbResultForm.RSLT_SEX_CD.value      = "<?=$RSLT_SEX_CD?>";
		opener.document.kcbResultForm.RSLT_NTV_FRNR_CD.value = "<?=$RSLT_NTV_FRNR_CD?>";
		
		opener.document.kcbResultForm.DI.value          = "<?=$DI?>";
		opener.document.kcbResultForm.CI.value          = "<?=$CI?>";
		opener.document.kcbResultForm.CI2.value         = "<?=$CI2?>";
		opener.document.kcbResultForm.CI_UPDATE.value   = "<?=$CI_UPDATE?>";
		opener.document.kcbResultForm.VSSN.value      	= "<?=$VSSN?>";
<?php
	}
?>	
		opener.document.kcbResultForm.action = "ipin_popup4.php";

		opener.document.kcbResultForm.submit();
		self.close();
	}	
</script>
</head>
<body>
<?php
	if($ret == 0) {
		//인증결과 복호화 성공
		// 인증결과를 확인하여 페이지분기등의 처리를 수행해야한다.
	 	if ($RSLT_CD == "T000") {
			echo ("<script>alert('본인인증성공'); fncOpenerSubmit();</script>");
		}
		else {
			echo ("<script>alert('본인인증실패 : ".$RSLT_CD." : ".$RSLT_MSG."'); fncOpenerSubmit();</script>");
		}
	} else {
		//인증결과 복호화 실패
		echo ("<script>alert('인증결과복호화 실패 Fuction fail / ret: ".$ret."'); self.close(); </script>");
	}
?>
</body>
</html>
