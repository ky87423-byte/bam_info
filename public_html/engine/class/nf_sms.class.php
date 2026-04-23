<?php
class nf_sms extends nf_util {

	var $config = array();
	var $sms_msg_array = array();

	// : sms문구 종류
	var $sms_msg = array(
		'member_write'=>'회원 가입시 발송',
		'member_modify'=>'회원 수정시 발송',
		'member_secession'=>'회원 탈퇴시 발송',
		'passwd_find'=>'비밀번호찾기 발송',
		'shop_regist'=>'상품 등록시 발송',
		'payment_complete'=>'상품 결제완료시 발송',
		'online_pay_process'=>'온라인 결제신청시 발송',
		'store_write'=>'입점문의 등록시 발송',
		'store_answer'=>'입점문의 답변시 발송',
		'customer_write'=>'고객문의 등록시 발송',
		'customet_answer'=>'고객문의 답변시 발송',
	);

	var $success_code = array(
			'0000' => 'SMS 정보 설정이 완료 되었습니다.',
			'0001' => 'SMS 메세지 설정이 완료 되었습니다.',
			'0002' => '성공적으로 SMS 문자를 발송하였습니다.',
			'0003' => '성공적으로 예약되었습니다.',
			'0004' => '테스트가 성공적입니다!',
			'0005' => 'SMS 발송이 완료 되었습니다.',
			'0006' => '성공적으로 SMS 문자를 발송하였습니다.\\n\\n데모 버전이기 때문에 실제 발송되진 않습니다.',
	);
	var $fail_code = array(
			'0000' => 'SMS 정보 설정중 오류가 발생하였습니다.\\n\\n솔루션 개발사 :: `넷퓨(Netfu)` 1:1 게시판에 문의하세요.',
			'0001' => 'SMS 메시지 설정중 오류가 발생하였습니다.\\n\\n솔루션 개발사 :: `넷퓨(Netfu)` 1:1 게시판에 문의하세요.',
			'0002' => '잘못된 번호형식 입니다.',
			'0003' => '스팸문자는 발송되지 않습니다.',
			'0004' => 'SMS 서버 접속중 오류가 발생하였습니다.',
	);

	function __construct() {
		global $db;

		$this->config = $db->query_fetch("select * from nf_sms_config");
		$this->config['wr_admin_num'] = "1544-9638";

		// : sms문구 디비 자동추가 - 이게 실행되면 SMS(문자)설정 리스트에 자동으로 뜸.
		if(is_array($this->sms_msg)) { foreach($this->sms_msg as $k=>$v) {
			$row = $db->query_fetch("select * from nf_sms_msg where `wr_type`='".$k."'");
			if(!$row) {
				$insert = $db->_query("insert into nf_sms_msg set `wr_type`='".$k."', `wr_title`='".$v."'");
				$row = $db->query_fetch("select * from nf_sms_msg where `wr_type`='".$k."'");
			}
			$this->sms_msg_array[$k] = $row;
		} }
	}

	function msg_replce($msg, $data=array()) {
		global $db, $env, $cate_p_array;

		// : 계좌정보 불러오기
		if($data['pay_bank']===true) {
			$bank_arr = explode("/", $_POST['bank']);
			$bank_name = $bank_arr[0];
			$bank_account = $bank_arr[1];
			$bank_account_name = $bank_arr[2];
			$bank_depositor = $_POST['depositor'];
		}

		// 치환 문구
		$trans = array(
			"{도메인}" => $this->get_http($_SERVER['HTTP_HOST']),
			"{날짜}" => date('m월 d일'),
			"{사이트명}" => stripslashes($env['site_name']),
			"{업체명}"=>$data['company_name'],

			"{이름}" => $data['name'],
			"{아이디}" => $data['mb_id'],
			"{비밀번호}" => $data['mb_password'],
			"{상품명}" => $data['cate2'],
			"{고객휴대폰}" => $data['hphone'],

			"{회사}" => $data['company_name'],

			"{은행명}" => $bank_name,
			"{계좌번호}" => $bank_account,
			"{예금주}" => $bank_account_name,
			"{입금자}" => $bank_depositor,
			"{결제금액}" => number_format(intval(strtr($data['price'], array(","=>"")))),
		);

		$arr['msg'] = strtr($msg, $trans);

		return $arr;
	}

	// : $data['sms_rphone'] = 수신번호
	// : $data['sms_sphone'] = 발신번호
	// : $data['sms_destination'] = 연락처|이름, 연락처|이름, 연락처|이름, 연락처|이름, 연락처|이름; // 이런형식이어야함.
	// : $data['sms_rname'] = 수신번호 이름
	// : $data그외 작업에 필요한 변수들
	function send_sms($code, $mid, $data=array()) {
		global $env;
		if(!$this->config['wr_use']) return false; // : sms미사용이면 실행하지 않습니다.

		$msg_config = $this->sms_msg_array[$code];
		if($data['fixed_content']) $msg_config['wr_content'] = $data['fixed_content'];

		if(!$msg_config['wr_use']) return false; // : 사용여부

		// : 회원SMS
		if($msg_config['wr_is_user']) {
			$msg = $this->msg_replce($msg_config['wr_content'], $data);
			$result[0] = $this->netfu_sms_Send($msg['msg'], $data['sms_rphone'], $data['sms_sphone'], $data['sms_destination'], array( 'mb_name' => $data['sms_rname'] ) );
		}

		// : 관리자SMS
		if($msg_config['wr_is_admin']) {
			$msg = $this->msg_replce($msg_config['wr_admin_content'], $data);
			$destination = $env['call_center']."|관리자";
			$datas['sms_rname'] = "관리자";
			$result[1] = $this->netfu_sms_Send($msg['msg'], $env['call_center'], $env['call_center'], $destination, array('mb_name'=>$data['sms_rname']));
		}

		return $result;
	}

	function send_sms_member_write($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("member_write", "", $sms_arr);
	}

	function send_sms_member_modify($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("member_modify", "", $sms_arr);
	}

	function send_sms_member_secession($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("member_secession", "", $sms_arr);
	}

	function send_sms_passwd_find($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("passwd_find", "", $sms_arr);
	}

	// : 계좌번호 문자보내기
	function send_sms_online_pay_process($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];
		$sms_arr['pay_bank'] = true;

		return $this->send_sms("online_pay_process", "", $sms_arr);
	}

	function send_sms_online_pay_confirm($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("payment_complete", "", $sms_arr);
	}

	// : 계좌번호 문자보내기
	function send_sms_shop_write($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];
		$sms_arr['pay_bank'] = true;

		return $this->send_sms("shop_regist", "", $sms_arr);
	}

	function send_sms_shop_admin_send($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		// : 회원SMS
		$result[0] = $this->netfu_sms_Send($arr['msg'], $sms_arr['sms_rphone'], $sms_arr['sms_sphone'], $sms_arr['sms_destination'], array( 'mb_name' => $sms_arr['sms_rname'] ) );
	}

	function send_sms_service_advert_send($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("store_write", "", $sms_arr);
	}

	function send_sms_service_advert_answer_send($arr) {
		global $env;
		$sms_arr = $arr;

		$sms_arr['sms_rphone'] = $sms_arr['phone'];
		$sms_arr['sms_sphone'] = $env['call_center'];
		$sms_arr['sms_destination'] = $sms_arr['phone'].'|'.$sms_arr['name']; // 이런형식이어야함.
		$sms_arr['sms_rname'] = $sms_arr['name'];

		return $this->send_sms("store_answer", "", $sms_arr);
	}

	function netfu_sms_Send( $msg, $rphone, $sphone, $destination="", $datas="" ){
		global $is_demo;

		$sms_url = "http://sslsms.netfu.co.kr/sms_sender.php";

		$sms['user_id'] = base64_encode($this->config['wr_api_id']); //SMS 아이디.
		$sms['secure'] = base64_encode($this->config['wr_api_key']) ; //인증키
		$sms['msg'] = base64_encode(stripslashes($msg));

		$sms['rphone'] = base64_encode($rphone);

		$sms['name'] = base64_encode($datas['mb_name']);
		
		$sphones = explode('-',$sphone);
		$sms['sphone1'] = base64_encode($sphones[0]);
		$sms['sphone2'] = base64_encode($sphones[1]);
		$sms['sphone3'] = base64_encode($sphones[2]);

		$sms['rdate'] = ""; // base64_encode($_POST['rdate']); 예약 날짜
		$sms['rtime'] = ""; // base64_encode($_POST['rtime']); 예약 시간

		$sms['mode'] = $this->config['wr_lms_use']; // lms 사용유무

		$returnurl = $_POST['returnurl'];
		$sms['returnurl'] = ""; // base64_encode($returnurl); 전송 리턴 페이지

		$sms['testflag'] = ($is_demo) ? base64_encode('Y') : base64_encode('N'); // 데모일땐 테스트 / 실제 사용시엔 전송

		if($destination!='') $sms['detsination'] = urlencode(base64_encode($destination));

		$sms['repeatFlag'] = ""; // base64_encode($_POST['repeatFlag']); 반복설정
		$sms['repeatNum'] = ""; // base64_encode($_POST['repeatNum']); 반복횟수
		$sms['repeatTime'] = ""; // base64_encode($_POST['repeatTime']); 전송간격

		$nointeractive = 0; //$_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3]."/".$host_info[4];

		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) { 
			fputs($fp, $header.$data);
			$rsp = '';
			while(!feof($fp)) { 
				$rsp .= fgets($fp,8192); 
			}

			fclose($fp);
			
			$result['result'] = "success";
			$result['msg'] = $this->_success("0002");

		} else {
			$result['result'] = "errors";
			$result['msg'] = $this->_errors('0004');
		}

		return implode($result,'/');
	}


	function netfu_sms_Ord(){
	
		$sms_url = "http://sslsms.netfu.co.kr/sms_remain.php"; // SMS 잔여건수 요청 URL

		$sms['url'] = base64_encode($_SERVER['HTTP_HOST']);
		$sms['user_id'] = base64_encode($this->config['wr_api_id']); //SMS 아이디.
		$sms['secure'] = base64_encode($this->config['wr_api_key']) ; //인증키
		$sms['mode'] = base64_encode('count');	// SMS 발송 가능 건수

		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3]."/".$host_info[4];
		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) {
			fputs($fp, $header.$data);
			$rsp = '';
			while(!feof($fp)) {
				$rsp .= fgets($fp,8192);
			}

			fclose($fp);

			$msg = explode("\r\n\r\n",trim($rsp));

			$result = $msg[1]; //잔여건수

		} else {
			//$result = "Connection Failed";
			$result = 0;
		}


		return $result;
	}


	/**
	* 에러코드에 맞는 에러를 토해낸다.
	*/
	function _errors( $err_code ){

			$result = $this->fail_code[$err_code];

		return $result;

	}

	/**
	* 완료코드에 맞는 에러를 토해낸다.
	*/
	function _success( $success_code ){

			$result = $this->success_code[$success_code];

		return $result;

	}
}
?>