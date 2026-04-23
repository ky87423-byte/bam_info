<?php
include_once "../../engine/_core.php";

switch($_POST['mode']) {

	case "sns_login_process":

		function get_curl($url, $header='') {
			global $env;
			$url_array = explode("?", $url);
			$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)';
			$cu = curl_init ();
			curl_setopt ($cu, CURLOPT_URL, $url_array[0]);
			curl_setopt ($cu, CURLOPT_POST, 1);
			curl_setopt($cu, CURLOPT_POSTFIELDS, $url_array[1]); 
			curl_setopt ($cu, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($cu, CURLOPT_TIMEOUT, 3);
			//curl_setopt ($cu, CURLOPT_USERAGENT, $agent);

			$headers = array();
			$headers[] = "X-Naver-Client-Id: ".$env['naver_id'];
			$headers[] = "X-Naver-Client-Secret: ".$env['naver_secret'];
			$headers[] = $header;

			curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);

			$buffer = curl_exec ($cu);
			$cinfo = curl_getinfo($cu);
			curl_close($cu);
			if ($cinfo['http_code'] != 200) {
				return "";
			}
			return $buffer;
		}

		switch($_POST['engine']) {
			case "kakao":
				$json = json_decode(stripslashes($_POST['json']), true);
				$mb_id = 'k_'.substr($json['id'],0,10);
				$mb_name = $json['properties']['nickname'];
			break;

			case "naver":
				// 네이버 로그인 콜백 예제
				$client_id = $env['naver_id'];
				$client_secret = $env['naver_secret'];
				$code = $_POST["code"];
				$state = $_POST["state"];
				$redirectURI = urlencode($env['naver_redirect_uri']);
				$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
				$is_post = false;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, $is_post);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$headers = array();
				$response = curl_exec ($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close ($ch);

				// : 네이버 회원정보 불러오기
				$get_token = json_decode($response, true);
				$headers = "Authorization: ".$get_token['token_type']." ".$get_token['access_token'];
				$get_result2 = get_curl("https://openapi.naver.com/v1/nid/me", $headers);
				$get_array = json_decode($get_result2, true);

				$mb_id = 'n_'.substr($get_array['response']['id'],0,10);
				$mb_name = $get_array['response']['name'];
				$mb_email = explode("@", $get_array['response']['email']);
				$mb_hphone = explode("-", $get_array['response']['mobile']);
			break;
		}

		$mem_row = $db->query_fetch("select * from nf_member where `mb_id`=?", array($mb_id));
		if($mem_row['mb_left'] || $mem_row['is_delete']) {
			$arr['msg'] = "이미 탈퇴한 회원이므로 재가입할 수 없습니다.\n관리자에게 문의해주시기 바랍니다.";
			$arr['move'] = NFE_URL.'/';
		} else {
			$_val = array();
			if(!$mem_row || !$mem_row['mb_type']) {
				$mno = $mem_row['no'];
				$_POST['mb_id'] = $mb_id;
				$_POST['mb_level'] = $env['member_point_arr']['register_level'];
				$_POST['mb_name'] = $mb_name;
				$_POST['mb_hphone'] = $mb_hphone;
				$_POST['mb_email'] = $mb_email;

				$prev_mode = $_POST['mode'];
				$_POST['mode'] = "member_write";
				$_include = true;
				include NFE_PATH.'/include/regist.php';
				$arr['move'] = NFE_URL.'/member/login_select.php';
			} else {
				$mno = $mem_row['no'];
				$arr['move'] = NFE_URL."/mypage/index.php";
			}

			$nf_member->login($mno);

			$arr['msg'] = "";
		}

		switch($_POST['engine']) {
			case "naver":
				die($nf_util->opener_move_url($arr['move']));
			break;

			default:
				die(json_encode($arr));
			break;
		}
	break;
}
?>