<?php
// 에러 정보
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set("display_errors", 0); // 프로덕션: 에러를 화면에 출력하지 않음 (로그로만 기록)

$PATH = $_SERVER['DOCUMENT_ROOT'];
$http = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
$this_page = $_SERVER['REQUEST_URI'];//$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
if(!is_array($_SERVER['__USE_API__'])) $_SERVER['__USE_API__'] = array();
if(!is_array($_SERVER['__USE_ETC__'])) $_SERVER['__USE_ETC__'] = array();

if(PHP_INT_MAX == 2147483647) $bit_int = 32;
else $bit_int = 64;

define("NFE_URL", "");
define("NFE_PATH", $_SERVER['DOCUMENT_ROOT'].NFE_URL);
define("http", $http);
define("domain", $http.$_SERVER['HTTP_HOST']);
define("this_page", $this_page);
define("today", date("Y-m-d"));
define("today2", date("YmdHis"));
define("today_time", date("Y-m-d H:i:s"));
define("today_his", date("H:i:s"));
define("encode", "utf-8");
define("bit_int", $bit_int);
define("is_demo", 0);
define("main_page", NFE_URL.'/');

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
// iframe 때문에 간혹 세션이 깨지는 경우를 방지함
@header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

// 강제 header 지정
@header("Content-Type: text/html; charset=".encode);

if (!$time_limit) $time_limit = 0;
@set_time_limit($time_limit);

// : 현재위치 쿠키 ####################################
if($_GET['lat'] && $_GET['lng']) {
	$_COOKIE['this_area_pos'] = $_COOKIE['this_area_pos_here'];
} else if($_COOKIE['this_area_pos_here']) {
	$cookie_value = $_GET['lat']."@".$_GET['lng'];
	$time = strtotime($cookie_time." minute");
	setCookie('this_area_pos', $cookie_value, $time, "/");
	$_COOKIE['this_area_pos'] = $cookie_value;
}
if(!$_COOKIE['this_area_pos']) $_COOKIE['this_area_pos'] = "37.5666805@126.9784147";
##############################################

## 디비접속 ##
// DB 접속정보는 data/db_config.php 에서 관리합니다 (engine/class/db.class.php 참고)
include_once NFE_PATH."/engine/core.php";
############

// : 이전페이지가 관리자페이지인지 체크
$_back_admin_page_ = strpos($nf_util->page_back(), "/admini/")!==false ? true : false;
$admin_page = $_back_admin_page_;

// : 등록폼 모음
$icon_need = $admin_page ? '<em class="ess">*</em> ' : '<i class="axi axi-ion-android-checkmark"></i>';
$register_form_query = $db->_query("select * from nf_category where `wr_type` in ('register_form_company','register_form_employ','register_form_resume') and `wr_view`=1 order by wr_rank asc");
while($row=$db->afetch($register_form_query)) {
	$register_form_arr[$row['wr_type']][$row['wr_name']] = $row;
}

// : 이력서 선택사항 체크박스 출력여부
if(!$register_form_arr['register_form_resume']['자격증']) unset($nf_job->resume_select_type['license']);
if(!$register_form_arr['register_form_resume']['외국어능력']) unset($nf_job->resume_select_type['language']);
if(!$register_form_arr['register_form_resume']['보유기술및능력']) unset($nf_job->resume_select_type['skill']);
if(!$register_form_arr['register_form_resume']['수상.수료활동']) unset($nf_job->resume_select_type['prime']);
if(!$register_form_arr['register_form_resume']['채용우대사항']) unset($nf_job->resume_select_type['preferential']);

// : 환경변수
$env = $db->query_fetch("select * from `nf_config`");

// : $env['use_auth'] 변수는 환경설정엔 없고 아이핀,휴대폰인증,비바톤을 사용하면 true입니다.
if($env['use_ipin'] || $env['use_hphone'] || $env['use_bbaton']) $env['use_auth'] = true;
$env['bbaton_redirect_uri'] = domain.'/include/regist.php?mode=login_bbaton';
$env['naver_redirect_uri'] = domain.'/include/regist.php?mode=sns_login_process&engine=naver';
$env['naver_login_click'] = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".$env['naver_id']."&redirect_uri=".urlencode($env['naver_redirect_uri']);

$env['sns_feed_arr'] = explode(",", $env['sns_feed']);
$env['sns_login_feed_arr'] = explode(",", $env['sns_login_feed']);
$env['service_config_arr'] = unserialize(stripslashes($env['service_config']));
$env['service_intro_arr'] = unserialize($env['service_intro']);
$env['service_name_arr'] = unserialize(stripslashes($env['service_name']));
$env['member_level_arr'] = $nf_util->get_unse($env['member_level']);
$env['member_point_arr'] = $nf_util->get_unse($env['member_point']);
$env['service_end_email_arr'] = explode(",", $env['service_end_email']);
if(!$env['map_engine']) $env['map_engine'] = "daum";
$nf_shop->service_name_setting(); // : 서비스명 재정리
$nf_payment->pg_config();

// : 차단아이피
$intercept_ip = explode("\r\n", stripslashes($env['intercept_ip']));
if(is_array($intercept_ip)) { foreach($intercept_ip as $k=>$v) {
	if($v && strpos($_SERVER['REMOTE_ADDR'], strtr($v, array(".*"=>"")))!==false) exit;
} }

// : 점프함수 - footer.php 맨아래에서 실행합니다.
include_once NFE_PATH.'/engine/function/jump.function.php';


// : 카테고리 모음
$cate_area_array = array('SI'=>array(), 'GU'=>array()); // nf_category->get_area()에서 사용
$cate_array = array();
$cate_p_array = array();
$cate_first_array = array(); // : 각 카테고리 첫번째 값
$cate_p_depth_arr = array(); // : 최대 카테고리 깊이값
$cate_read_arr = array('board_menu', 'online', 'groups', 'job_part', 'area', 'job_pay', 'job_tema', 'job_date', 'job_target', 'job_type', 'notice', 'shop_shop_report_reason', 'email');
if($add_cate_arr) $cate_read_arr = array_merge($cate_read_arr, $add_cate_arr);
$nf_category->get_cate($cate_read_arr);

// : 상품아이콘
$icon_query = $db->_query("select * from nf_icon where `wr_use`=1 and `wr_code`=? order by `wr_rank` asc", array('shop_icon'));
while($icon_row=$db->afetch($icon_query)) {
	$shop_icon_arr[$icon_row['wr_id']] = $env['icon_type']=='text' ? $icon_row['wr_name'] : '<img src="'.NFE_URL.$nf_shop->attach_dir['icon'].$icon_row['wr_image'].'" />';
	$shop_icon_row_arr[$icon_row['wr_id']] = $icon_row;
}

$area_depth = $nf_category->get_depth("area");
$job_part_depth = $nf_category->get_depth("job_part");

/*
* SESSION 설정 (GNUBOARD 참고)
*/
//@ini_set('memory_limit','1024M');	// mysql 메모리 사이즈를 늘림
ini_set("session.use_trans_sid", 0);	// PHPSESSID를 자동으로 넘기지 않음
ini_set("url_rewriter.tags","");			// 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)
ini_set("max_input_vars", 5000);	// post 값 설정

// 세션 저장 디렉터리: 없으면 자동 생성, 실패 시 서버 기본값 사용
$sess_dir = NFE_PATH.'/engine/session';
if (!is_dir($sess_dir)) @mkdir($sess_dir, 0755, true);
if (is_dir($sess_dir) && is_writable($sess_dir)) {
	ini_set('session.save_path', $sess_dir);
	session_save_path($sess_dir);
}

if (isset($SESSION_CACHE_LIMITER))
	@session_cache_limiter($SESSION_CACHE_LIMITER);
else
	@session_cache_limiter("no-cache, must-revalidate");


/*
* 기본환경설정
*/
ini_set("session.gc_maxlifetime", ($env['session_time']*60));
// GC 확률 1/100 (1%) — 기본값. 기존 1/1(100%)은 매 요청마다 세션 삭제를 시도해 성능·안정성 문제 발생
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);

ini_set("session.cookie_domain", "");
ini_set("session.cookie_httponly", 1);   // JS에서 쿠키 접근 차단 (XSS 방어)
ini_set("session.cookie_samesite", "Lax"); // CSRF 방어

// 세션 스타트~
session_start();

// : 로그인한 회원정보
if(trim($_SESSION['sess_user_uid'])) {
	$mem_row = $db->query_fetch("select * from nf_member where `mb_id`=?", array($_SESSION['sess_user_uid']));
	if($mem_row) {
		$nf_member->get_member($mem_row['no']);
		$member = $nf_member->member_arr[$mem_row['no']];

		if($mem_row && $member['mb_type']) {
			$_where = "";
			$member_info_array = $nf_member->get_member_ex($member['no']);
			$member_ex = $member_info_array['member_ex'];
			$member_service = $member_info_array['member_se'];
		}

		$member_tax = $db->query_fetch("select * from nf_tax where `mno`=".intval($member['no']));

		// : 미확인 쪽지 수
		$my_message_cnt = $db->query_fetch("select * from nf_message as nm where 1 and `pdel`=0 and `rdate`='1000-01-01 00:00:00' and `pmno`=".intval($member['no'])." and `pmno`>0 limit 1");
	}
}

// : SNS가입했는데 회원종류선택해서 가입안하면 강제로 회원종류선택 페이지로 이동
if(!$not_mb_type_check && !$mem_row['mb_type'] && $mem_row && strpos($_SERVER['PHP_SELF'], '/admini/')===false) {
	die(header('Location: '.NFE_URL.'/member/login_select.php'));
}

define("sess_user_uid", $member['mb_id']);
if($mem_row['is_dormancy'] && strpos($_SERVER['PHP_SELF'], '/member/login_sleep.php')===false && strpos($_SERVER['PHP_SELF'], '/regist.php')===false) {
	die(header('Location: '.NFE_URL.'/member/login_sleep.php'));
}


$save_id = $nf_util->Decrypt($_COOKIE['save_id']);
## 관리자정보 ##
$is_admin = false;
$is_super_admin = false;	// 최고관리자 체크
$admin_info = $db->query_fetch("select * from nf_admin where `wr_id`=?", array($_SESSION[$nf_admin->sess_adm_uid]));

define("admin_id", $admin_info['wr_id']); // : 지우면 안됨
define("admin_no", $admin_info['no']); // : 지우면 안됨

if($admin_info) {
	if($admin_info['wr_level']<10) $_get_sadmin_ = $nf_admin->get_sadmin(admin_id);
	if($admin_info) {
		$is_admin = true;
		if($admin_info['wr_level']==10) {
			$is_super_admin = true;
		}
		$admin_name = $admin_info['wr_name'];
		$admin_nick = $admin_info['wr_nick'];
	}
}
##########

// : 모바일 체크
$mAgent = array("iPhone","iPod","Android","Blackberry", "Opera Mini", "Windows ce", "Nokia", "sony");
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
	if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
		$chkMobile = true;
		break;
	}
}
define("is_mobile", $chkMobile);

if(!$is_admin && $env['under_construction'] && strpos($_SERVER['PHP_SELF'], '/admini/')===false && strpos($_SERVER['PHP_SELF'], '/include/construction.html')===false) {
	header('Location: '.NFE_URL.'/include/construction.html');
	exit;
}


// : 각 정보별 검색
if(count($nf_category->job_part_adult_arr)>0) {
	$nf_shop->adult_where = " and (`wr_job_type` like '%,".implode(",%' or `wr_job_type` like '%,", $nf_category->job_part_adult_arr).",%')"; // : 성인직종 검색
	$nf_shop->not_adult_where = " and !(`wr_job_type` like '%,".implode(",%' or `wr_job_type` like '%,", $nf_category->job_part_adult_arr).",%')"; // : 성인직종이 아닌거 검색
}


// : 서비스 있는것들 자동체크해서 wr_is_service값에 1 넣기
$auto_service_row = $db->query_fetch("select * from nf_auto_read where `code`=? and `rdate`=?", array('service', today));
if(!$auto_service_row) {
	//$db->_query("update nf_shop set `wr_is_service`=1 where !(`wr_service0_0`<? and wr_service0_1<? and wr_service0_2<? and wr_service0_3<?)", array(today, today, today, today));
	$query = $db->_query("select * from nf_member as nm right join nf_shop as ns on nm.`no`=ns.`mno` where !(`wr_service0_0`<? and wr_service0_1<? and wr_service0_2<? and wr_service0_3<?)", array(today, today, today, today));
	while($row=$db->afetch($query)) {

		// : 모든서비스의 마지막 마감일값 저장
		$wr_service_val = $row['wr_service0_0'];
		if($wr_service_val<$row['wr_service0_1']) $wr_service_val = $row['wr_service0_1'];
		if($wr_service_val<$row['wr_service0_2']) $wr_service_val = $row['wr_service0_2'];
		if($wr_service_val<$row['wr_service0_3']) $wr_service_val = $row['wr_service0_3'];
		$update = $db->_query("update nf_shop set wr_service_last=? where `no`=?", array($wr_service_val, intval($row['no'])));

		// : 마감일 메일 보내기
		if($env['use_service_end_email']) {
			if(is_Array($env['service_end_email_arr'])) { foreach($env['service_end_email_arr'] as $k=>$v) {
				if(is_array($nf_shop->service_charge_arr['shop'])) { foreach($nf_shop->service_charge_arr['shop'] as $k2=>$v2) {
					$_field ='wr_service'.$k2;
					$end_date = date("Y-m-d", strtotime($v));
					if($row[$_field]<today) continue;
					if($row[$_field]==$end_date) {
						$email_arr = array();
						$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='shop_end'");
						$ch_arr = $nf_email->ch_content($_val);
						$ch_arr['{업체명}'] = $nf_util->get_text($row['wr_company']);
						$ch_arr['{서비스}'] = $v2.' 서비스';
						$ch_arr['{서비스마감일}'] = $row[$_field];
						$email_arr['subject'] = strtr("안녕하세요. {사이트명}입니다. 귀사의 상품 서비스기간 만료 안내 메일입니다.", $ch_arr);
						$email_arr['email'] = $row['mb_email'];
						$email_arr['content'] = strtr(stripslashes($mail_skin['content']), $ch_arr);
						$nf_email->send_mail($email_arr);
					}
				} }
			} }
		}
	}

	// : 휴면 관련
	$last_login1 = date("Y-m-d", strtotime("-".$env['dormancy_last_login'].' day')); // : 휴면회원으로 변경
	$last_login2 = date("Y-m-d", strtotime("-".$env['dormancy_schedule_last_login'].' day')); // : 휴면회원 예정일
	$query = $db->_query("select * from nf_member where is_dormancy=0 and (date(mb_last_login)<=? or date(mb_last_login)<=?)", array($last_login1, $last_login2));
	if($env['use_dormancy']) {
		while($row=$db->afetch($query)) {
			// : 휴면회원 기준일
			if(substr($row['mb_last_login'],0,10)<=$last_login1) {
				$email_arr = array();
				$update = $db->_query("update nf_member set is_dormancy=1, mb_dodate=? where `no`=".intval($row['no']), array(today_time));
				$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='dormancy_end'");
				$ch_arr = $nf_email->ch_content($row);
				$ch_arr['{휴면처리일}'] = today;
				$ch_arr['{휴면처리설정일}'] = intval($env['dormancy_last_login']);
				$email_arr['subject'] = strtr("안녕하세요. {사이트명}입니다. 회원님의 아이디가 휴면 상태로 변경되었습니다.", $ch_arr);
				$email_arr['email'] = $row['mb_email'];
				$email_arr['content'] = strtr(stripslashes($mail_skin['content']), $ch_arr);
				$nf_email->send_mail($email_arr);
			}
			// : 휴면회원 예정안내메일 발송 기준일
			if(substr($row['mb_last_login'],0,10)<=$last_login2) {
				$email_arr = array();
				$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='dormancy_ing'");
				$ch_arr = $nf_email->ch_content($row);
				$ch_arr['{휴면예정일}'] = date("Y-m-d", strtotime($env['dormancy_last_login'].' day '.$row['mb_last_login']));
				$email_arr['subject'] = strtr("안녕하세요. {사이트명}입니다. 회원님의 아이디가 휴면 상태로 전환될 예정입니다.", $ch_arr);
				$email_arr['email'] = $row['mb_email'];
				$email_arr['content'] = strtr(stripslashes($mail_skin['content']), $ch_arr);
				$nf_email->send_mail($email_arr);
			}
		}
	}
	$db->_query("insert into nf_auto_read set `code`=?, `rdate`=?", array('service', today));
}

// : new마크
$shop_new_time = date("Y-m-d H:i:s", strtotime("-".$env['use_shop_new_time']." hour"));

// : 업체 사진 tmp디렉토리 삭제
$delete_dir = $_SERVER['DOCUMENT_ROOT'].'/data/shop/tmp/';
$delete_fime = $nf_util->recursive_file_delete($delete_dir, 3);

// : LiteSpeed 캐시 제어
// 세션 쿠키가 존재하거나 로그인 상태이면 캐시 절대 금지
// (쿠키 여부로 판단해야 LiteSpeed가 PHP 실행 전에도 올바르게 캐시 룩업을 건너뜀)
$_has_session_cookie = !empty($_COOKIE[session_name()]);
if ($member['no'] || $is_admin || $_has_session_cookie) {
    @header('X-LiteSpeed-Cache-Control: no-cache, no-store, private');
} else {
    @header('X-LiteSpeed-Cache-Control: public, max-age=3600');
    @header('Vary: Cookie');
}
?>