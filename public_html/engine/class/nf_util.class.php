<?php
class nf_util extends DBConnection {

	var $cs_type_arr = array(0=>"고객문의관리", 1=>"광고·제휴 문의", 3=>"FAQ관리");
	var $content_config = array('site_introduce'=>'회사소개', 'membership'=>'이용약관', 'privacy'=>'개인정보취급방침', 'email_not_collect'=>'이메일무단수집거부');

	var $latlng_arr = array("lat"=>"37.5666805", "lng"=>"126.9784147");
	var $_mobile_array  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");
	var $mobile_is = false;
	var $sns_arr = array('kakao_talk'=>'카카오톡', 'twitter'=>'트위터', 'facebook'=>'페이스북', 'naver_band'=>'네이버밴드', 'kakao_story'=>'카카오스토리', 'naver_blog'=>'네이버블로그');
	var $sns_login_arr = array('kakao_talk'=>'카카오톡', 'naver'=>'네이버'); // , 'facebook'=>'페이스북', 'twitter'=>'트위터'
	var $ch_basic = array("\r\n"=>" ");
	var $content_arr = array("site_introduce"=>"사이트소개", "membership"=>"회원약관", "privacy"=>"개인정보취급방침", "board_manage"=>"개시판관리기준", "email_not_collect"=>"이메일무단수집거부", "bottom_site"=>"사이트하단", "bottom_email"=>"이메일하단");
	var $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	var $sns_array = array('facebook'=>'페이스북', 'kakao'=>'카카오톡', 'telegram'=>'텔레그램', 'wechat'=>'위챗', 'line'=>'라인', 'twitter'=>'X(구트위터)', 'instagram'=>'인스타그램', 'naver'=>'네이버', 'naver_band'=>'네이버밴드', 'blog'=>'블로그', 'cafe_home'=>'홈페이지', );
 
	var $page_code = 'pc';

	var $menu_arr = array();
	var $member_level = array();
	var $scrap_row_arr = array(); // : 스크랩쿼리문 읽은경우 여기에 넣습니다. [ 주키 ] 이런식

	var $use_arr = array(0=>'미사용', 1=>'사용');
	var $week_arr = array("일", "월", "화", "수", "목", "금", "토");
	var $date_arr = array('day'=>'일', 'week'=>'주', 'month'=>'개월', 'year'=>'년');
	var $date_arr2 = array('day'=>'일', 'week'=>'주일', 'month'=>'개월', 'year'=>'년');
	var $date_count_arr = array('day'=>'일', 'week'=>'주', 'month'=>'개월', 'year'=>'년', 'count'=>'건');
	var $gender_arr = array(1=>'남자', 2=>'여자');
	var $gender_short_arr = array(1=>'남', 2=>'여');
	var $phone_arr = array("02"=>"서울", "031"=>"경기", "032"=>"인천", "033"=>"강원", "041"=>"충남", "042"=>"대전", "043"=>"충북", "044"=>"세종", "051"=>"부산", "052"=>"울산", "053"=>"대구", "054"=>"경북", "055"=>"경남", "061"=>"전남", "062"=>"광주", "063"=>"전북", "064"=>"제주", "060"=>"인터넷", "070"=>"인터넷", "080"=>"인터넷", "0502"=>"평생번호", "0503"=>"평생번호");
	var $area_arr = array('서울', '인천', '경기', '부산', '세종', '광주', '울산', '대구', '대전', '경남', '경북', '충남', '충북', '전남', '전북', '강원', '제주');
	var $area_long_arr = array('서울'=>'서울특별시', '인천'=>'인천광역시', '경기'=>'경기도', '부산'=>'부산광역시', '세종'=>'세종특별자치시', '광주'=>'광주광역시', '울산'=>'울산광역시', '대구'=>'대구광역시', '대전'=>'대전광역시', '경남'=>'경상남도', '경북'=>'경상북도', '충남'=>'충청남도', '충북'=>'충청북도', '전남'=>'전라남도', '전북'=>'전라북도', '강원'=>'강원특별자치도', '제주'=>'제주특별자치도');
	var $hphone_arr = array("010", "011", "016", "017", "018", "019");

	var $target_arr = array(0=>'비대상', 1=>'대상');
	var $open_arr = array(0=>'비공개', 1=>'공개');
	var $level_arr = array(0=>'상', 1=>'중', 2=>'하');

	// : 거리 80px당 m거리
	var $map_distance_px = array(
		'daum'=>array(1=>80, 2=>60, 3=>50, 4=>50, 5=>62, 6=>62, 7=>62, 8=>62, 9=>62, 10=>62, 11=>62, 12=>62, 13=>62, 14=>62),
		'naver'=>array(1=>80, 2=>60, 3=>67, 4=>67, 5=>54, 6=>105, 7=>105, 8=>105, 9=>85, 10=>85, 11=>85, 12=>68, 13=>68, 14=>68, 15=>55, 16=>108, 17=>108, 18=>108),
		'google'=>array(3=>63, 4=>63, 5=>52, 6=>102, 7=>102, 8=>102, 9=>82, 10=>82, 11=>82, 12=>66, 13=>66, 14=>66, 15=>53, 16=>105, 17=>105, 18=>105, 19=>84, 20=>84, 21=>84),
	);
	// : 엔진 레벨별 거리값.
	var $map_distance = array(
		'daum'=>array(1=>20, 2=>30, 3=>50, 4=>100, 5=>250, 6=>500, 7=>1000, 8=>2000, 9=>4000, 10=>8000, 11=>16000, 12=>32000, 13=>64000, 14=>128000),
		'naver'=>array(1=>80, 2=>60, 3=>1000000, 4=>500000, 5=>200000, 6=>200000, 7=>100000, 8=>50000, 9=>20000, 10=>10000, 11=>5000, 12=>2000, 13=>1000, 14=>500, 15=>200, 16=>200, 17=>100, 18=>50),
		'google'=>array(3=>1000000, 4=>500000, 5=>200000, 6=>200000, 7=>100000, 8=>50000, 9=>20000, 10=>10000, 11=>5000, 12=>2000, 13=>1000, 14=>500, 15=>200, 16=>200, 17=>100, 18=>50, 19=>20, 20=>10, 21=>5),
	);

	var $point_minus_read_cookie_time = '1 day'; // : 읽을때 포인트 차감 쿠키 시간
	var $point_minus_download_cookie_time = '1 second'; // : 다운받을때 포인트 차감 쿠키 시간

	var $image_attach_ext = array("jpg", "gif", "png", "zip");
	var $attach_ext = array("jpg", "gif", "png", "webp", "zip", "pdf", "ppt", "xls", "xlxs", "doc", "hwp");
	var $photo_ext = array("jpeg", "jpg", "gif", "png", "webp");
	var $favicon_ext = array("ico", "jpg", "png", "gif");
	var $ext_not_upload = array("php", "jsp", "asp", "exe", "html", "htm", "js", "css", "php3", "phtml", "shtml", "shtm", "inc", "pl", "cgi");

	var $ch_bad_txt = "♪♬♩"; // : 금지어는 이 단어로 치환됩니다.

	var $attach_dir = array(
	);

	var $secret_key = "secret key"; // : 암호화 키값 입력
	var $secret_iv = "secret iv"; // : 암호화 비밀번호 입력

	function __construct(){
	}

	// : 구글 리캡챠 v3
	function recaptcha_check() {
		global $env;
		if(!$env['google_recaptcha_secret'] || !$env['google_recaptcha'] || strpos($this->page_back(), "/admini/")!==false) return array('success'=>1);

		$captcha = $_POST['g-recaptcha'];
		$secretKey = $env['google_recaptcha_secret']; // 위에서 발급 받은 "비밀 키"를 넣어줍니다.
		$ip = $_SERVER['REMOTE_ADDR']; // 옵션값으로 안 넣어도 됩니다.

		$data = array(
			'secret' => $secretKey,
			'response' => $captcha,
			'remoteip' => $ip  // ip를 안 넣을거면 여기서도 빼줘야죠
		);

		$url = "https://www.google.com/recaptcha/api/siteverify";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);
		curl_close($ch);

		$responseKeys = json_decode($response, true);
		if(!$responseKeys['success']) {
			$responseKeys['msg'] = "정상적인 방식으로 접근해주시기 바랍니다.";
			$responseKeys['move'] = $this->page_back();
		}

		return $responseKeys;
	}

	function get_brow($agent) {
		$agent = strtolower($agent);

		if (preg_match("/msie 5.0[0-9]*/", $agent))				{ $result = "MSIE 5.0"; }
		else if(preg_match("/msie 5.5[0-9]*/", $agent))		{ $result = "MSIE 5.5"; }
		else if(preg_match("/msie 6.0[0-9]*/", $agent))		{ $result = "MSIE 6.0"; }
		else if(preg_match("/msie 7.0[0-9]*/", $agent))		{ $result = "MSIE 7.0"; }
		else if(preg_match("/msie 8.0[0-9]*/", $agent))		{ $result = "MSIE 8.0"; }
		else if(preg_match("/msie 9.0[0-9]*/", $agent))		{ $result = "MSIE 9.0"; }
		else if(preg_match("/msie 10.0[0-9]*/", $agent))		{ $result = "MSIE 10"; }
		else if(preg_match("/rv:11.0/", $agent))					{ $result = "MSIE 11"; }
		else if(preg_match("/msie 4.[0-9]*/", $agent))			{ $result = "MSIE 4.x"; }
		//else if(preg_match("/Edge*/", $agent))						{ $result = "Edge"; }
		else if(stristr($agent,'Edge'))									{ $result = "Edge"; }
		else if(preg_match("/firefox/", $agent))					{ $result = "FireFox"; }
		else if(preg_match("/chrome/", $agent))					{ $result = "Chrome"; }
		else if(preg_match("/x11/", $agent))						{ $result = "Netscape"; }
		else if(preg_match("/opera/", $agent))						{ $result = "Opera"; }
		else if(preg_match("/safari/", $agent))						{ $result = "Safari"; }
		else if(preg_match("/gec/", $agent))						{ $result = "Gecko"; }
		else if(preg_match("/bot|slurp/", $agent))				{ $result = "Robot"; }
		else if(preg_match("/internet explorer/", $agent))		{ $result = "IE"; }
		else if(preg_match("/mozilla/", $agent))					{ $result = "Mozilla"; }
		else { $result = "기타"; }

		return $result;
	}

	// OS 를 알아내자
	// 그누보드 참고
	function get_os($agent) { 

		$agent = strtolower($agent); 

		//echo $agent; echo "<br/>"; 

		if (preg_match("/windows 98/", $agent))                { $result = "98"; } 
		else if(preg_match("/iphone/", $agent))                { $result = "iPhone"; } //iPhone 
		else if(preg_match("/ipad/", $agent))                  { $result = "iPad"; } //iPad 
		else if(preg_match("/ipod/", $agent))                  { $result = "iPod"; } //iPod 
		else if(preg_match("/android/", $agent))                { $result = "Android"; } //Android 
		else if(preg_match("/psp/", $agent))                    { $result = "PSP"; } //PSP 
		else if(preg_match("/playstation/", $agent))            { $result = "PLAYSTATION"; } //PLAYSTATION 
		else if(preg_match("/berry/", $agent))                  { $result = "BlackBerry"; } //BlackBerry 
		else if(preg_match("/symbian/", $agent))                { $result = "Symbian"; } //Symbian 
		else if(preg_match("/ericsson/", $agent))              { $result = "SonyEricsson"; } //SonyEricsson 
		else if(preg_match("/nokia/", $agent))                  { $result = "Nokia"; } //Nokia 
		else if(preg_match("/benq/", $agent))                  { $result = "BenQ"; } //BenQ 
		else if(preg_match("/mot/", $agent))                    { $result = "Motorola"; } //Motorola 
		else if(preg_match("/nintendo/", $agent))              { $result = "Nintendo"; } //Nintendo 
		else if(preg_match("/palm/", $agent))                  { $result = "Palm"; } //Palm 
		else if(preg_match("/sch/", $agent))                    { $result = "T*옴니아"; } //T*옴니아 
		else if(preg_match("/sph/", $agent))                    { $result = "애니콜"; } //삼성폰 
		else if(preg_match("/sgh/", $agent))                    { $result = "옴니아"; } //옴니아 
		else if(preg_match("/sch/", $agent))                    { $result = "T*옴니아"; } //T*옴니아 
		else if(preg_match("/im-s/", $agent))                  { $result = "스카이폰"; } //스카이폰 
		else if(preg_match("/lg/", $agent))                    { $result = "LG 사이언"; } //LG 사이언 
		else if(preg_match("/googleproducer|google web preview/", $agent))            { $result = "Google Web Preview"; } //Google Web Preview and Feedfetcher 
		else if(preg_match("/google-site-verification/", $agent))  { $result = "Google Webmaster tools"; } //Google Webmaster tools 
		else if(preg_match("/feedfetcher-google/", $agent))        { $result = "Feedfetcher-Google"; } //Feedfetcher-Google 
		else if(preg_match("/desktop google reader/", $agent))      { $result = "Desktop Google Reader"; } //Desktop Google Reader 
		else if(preg_match("/appengine-google/", $agent))          { $result = "AppEngine-Google"; } //AppEngine-Google 
		else if(preg_match("/google wireless transcoder/", $agent)) { $result = "Google Wireless Transcoder"; } //Google Wireless Transcoder 
		else if(preg_match("/google/", $agent))            { $result = "Google Robot"; } //구글로봇 
		else if(preg_match("/mediapartners/", $agent))      { $result = "Google AdSense"; } //구글애드센스 
		else if(preg_match("/-mobile/", $agent))            { $result = "Google-Mobile Robot"; } //구글모바일로봇 
		else if(preg_match("/naver blog/", $agent))        { $result = "NAVER Blog Rssbot"; } //네이버블로그로봇 
		else if(preg_match("/naver|yeti/", $agent))        { $result = "NAVER Robot"; } //네이버로봇 
		else if(preg_match("/daumsearch/", $agent))        { $result = "DaumSearch validator"; } //다음검색 검사기 
		else if(preg_match("/daum/", $agent))              { $result = "DAUM Robot"; } //다음로봇 
		else if(preg_match("/yahooysmcm/", $agent))        { $result = "YahooYSMcm"; } //야후!문맥광고 
		else if(preg_match("/yahoocachesystem/", $agent))  { $result = "YahooCacheSystem"; } //야후!캐싱시스템 
		else if(preg_match("/yahoo/", $agent))              { $result = "Yahoo! Robot"; } //야후!로봇 
		else if(preg_match("/natefeedfetcher/", $agent))    { $result = "NATEFeed Fetcher"; } //네이트FeedFetcher 
		else if(preg_match("/egloosfeedfetcher/", $agent))  { $result = "Egloos FeedFetcher"; } //이글루스FeedFetcher 
		else if(preg_match("/empas|nate/", $agent))        { $result = "NATE Robot"; } //네이트로봇 
		else if(preg_match("/bingpreview/", $agent))        { $result = "BingPreview"; } //BingPreview로봇 
		else if(preg_match("/bing/", $agent))              { $result = "Bing Robot"; } //Bing로봇 
		else if(preg_match("/msn/", $agent))                { $result = "MSN Robot"; } //MSN로봇 
		else if(preg_match("/zum/", $agent))                { $result = "Zum Robot"; } //Zum로봇 
		else if(preg_match("/qrobot/", $agent))            { $result = "Qrobot"; } //Qrobot로봇 
		else if(preg_match("/archive|ia_archiver/", $agent)){ $result = "Archive Robot"; } //아카이브로봇 
		else if(preg_match("/twitter/", $agent))            { $result = "Twitter Robot"; } //Twitter로봇 
		else if(preg_match("/facebook/", $agent))          { $result = "Facebook Robot"; } //Facebook로봇 
		else if(preg_match("/whois/", $agent))              { $result = "Whois Search Robot"; } //Whois Search로봇 
		else if(preg_match("/checkprivacy/", $agent))      { $result = "KISA"; } //한국인터넷진흥원 
		else if(preg_match("/mj12/", $agent))              { $result = "MJ12bot"; } //MJ12bot 
		else if(preg_match("/baidu/", $agent))              { $result = "Baiduspider"; } //Baiduspider 
		else if(preg_match("/yandex/", $agent))            { $result = "YandexBot"; } //YandexBot로봇 
		else if(preg_match("/Sogou/", $agent))              { $result = "Sogou web spider"; } //Sogou로봇 
		else if(preg_match("/tweetedtimes/", $agent))      { $result = "TweetedTimes Bot"; } //TweetedTimes Bot 
		else if(preg_match("/discobot/", $agent))          { $result = "Discoveryengine Robot"; } //Discoveryengine로봇 
		else if(preg_match("/twiceler/", $agent))          { $result = "Twiceler Robot"; } //Twiceler로봇 
		else if(preg_match("/ezooms/", $agent))            { $result = "Ezooms Robot"; } //Ezooms로봇 
		else if(preg_match("/wbsearch/", $agent))          { $result = "WBSearchBot"; } //WBSearchBot 
		else if(preg_match("/proximic/", $agent))          { $result = "proximic"; } //proximic로봇 
		else if(preg_match("/GTWek/", $agent))              { $result = "GTWek"; } //GTWek로봇 
		else if(preg_match("/java|python|axel|dalvik|greatnews|hmschnl|huawei|jakarta|netcraft|parrotsite|readability|unwind|pagepeeker|shunix|crystalsemantics|turnitin|komodia|siteIntel|apercite/", $agent))          { $result = "Unknown Robot"; } //Unknown로봇 
		else if(preg_match("/cron/", $agent))              { $result = "WebCron"; } //WebCron 
		else if(preg_match("/capture/", $agent))            { $result = "WebCapture"; } //WebCapture로봇 
		else if(preg_match("/w3c/", $agent))                { $result = "W3C Validator"; } //W3C Validator 
		else if(preg_match("/wget/", $agent))              { $result = "Wget Validator"; } //Wget 
		else if(preg_match("/fetcher/", $agent))            { $result = "Feed Fetcher"; } //Feed Fetcher 
		else if(preg_match("/feed|reader|rss/", $agent))    { $result = "Feed Reader"; } //Feed Reader 
		else if(preg_match("/bot|slurp|scrap|spider|crawl|curl/", $agent))          { $result = "Robot"; } 
		else if(preg_match("/docomo/", $agent))                { $result = "DoCoMo"; } //DoCoMo 
		else if(preg_match("/windows 95/", $agent))            { $result = "Windows 95"; } 
		else if(preg_match("/windows nt 4\.[0-9]*/", $agent))  { $result = "Windows NT"; } 
		else if(preg_match("/windows nt 5\.0/", $agent))        { $result = "Windows 2000"; } 
		else if(preg_match("/windows nt 5\.1/", $agent))        { $result = "Windows XP"; } 
		else if(preg_match("/windows nt 5\.2/", $agent))        { $result = "Windows 2003"; } 
		else if(preg_match("/windows nt 6\.0/", $agent))        { $result = "Windows Vista"; } 
		else if(preg_match("/windows nt 6\.1/", $agent))        { $result = "Windows 7"; } 
		else if(preg_match("/windows nt 6\.2/", $agent))        { $result = "Windows 8"; } 
		else if(preg_match("/windows 9x/", $agent))            { $result = "Windows ME"; } 
		else if(preg_match("/windows ce/", $agent))            { $result = "Windows CE"; } 
		else if(preg_match("/windows nt 10/", $agent))            { $result = "Windows 10"; } 
		else if(preg_match("/linux/", $agent))                  { $result = "Linux"; } 
		else if(preg_match("/sunos/", $agent))                  { $result = "sunOS"; } 
		else if(preg_match("/irix/", $agent))                  { $result = "IRIX"; } 
		else if(preg_match("/phone|skt|lge|0450/", $agent))    { $result = "Mobile"; } 
		else if(preg_match("/internet explorer/", $agent))      { $result = "IE"; } 
		else if(preg_match("/mozilla/", $agent))                { $result = "Mozilla"; } 
		else if(preg_match("/macintosh/", $agent))              { $result = "Mac"; } 
		else { $result = "기타"; }

		return $result; 

	}

	function get_var($k) {
		return $_GET[$k] ? $_GET[$k] : $_POST[$k];
	}

	// : 테이블이 있는지 체크
	function is_table($table) {
		global $db;
		return $row = $db->query_fetch("show tables like '".$table."'");
	}

	function get_engine($page) {
		return strpos($page, '/m/')!==false ? 'mobile' : 'pc';
	}

	function get_http($url) {
		if(substr($url, 0, 4)!='http') return 'http://'.$url;
		else return $url;
	}

	function page_back() {
		$page_back = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : NFE_URL.'/';
		return $page_back;
	}

	function is_scrap($no, $code) {
		global $member, $db;

		$is_scrap = false;
		if(!$this->scrap_row_arr[$code]) $this->scrap_row_arr[$code] = array();
		if(!$this->scrap_row_arr[$code][$no]) {
			$is_scrap = $db->query_fetch("select * from nf_scrap where `mno`=? and `pno`=? and `code`=?", array($member['no'], $no, $code));
			if($is_scrap) {
				$this->scrap_row_arr[$code][$no] = $no;
				$is_scrap = true;
			}
		} else {
			$is_scrap = true;
		}
		return $is_scrap;
	}

	function is_good($no, $code) {
		global $member, $db;

		$is_good = false;
		if(!$this->good_row_arr[$code]) $this->good_row_arr[$code] = array();
		if(!$this->good_row_arr[$code][$no]) {
			$is_good = $db->query_fetch("select * from nf_click_good where `mno`=? and `pno`=? and `code`=?", array($member['no'], $no, $code));
			if($is_good) {
				$this->good_row_arr[$code][$no] = $no;
				$is_good = true;
			}
		} else {
			$is_good = true;
		}
		return $is_good;
	}

	function get_dir_date($code, $date="") {
		if($date && strlen($date)!=6) $date = date("Ym", strtotime($date));
		if(!$date) $date = date("Ym");
		$arr['date'] = $date;
		$arr['dir'] = '/data/'.$code.'/';
		$oldumask = umask(0);
		if(!is_dir(NFE_PATH.$arr['dir'].$arr['date'])) mkdir(NFE_PATH.$arr['dir'].$arr['date'], 0707);
		umask($oldumask);
		return $arr;
	}

	// : 영문, 숫자, 특수문자중 몇가지이상 맞으면 통과
	static function check_word($word, $num=2) {
		$int = preg_match("/[0-9]/", $word);
		$eng = preg_match("/[a-zA-Z]/", $word);
		$spe = preg_match("/[`~!@#$%^&*\(\)\-\=_\+\[\]\{\}\\\|;':\",.\/<>?]/", $word);  //특수기호 정규표현식
		$length = strlen($word)>=5 && strlen($word)<=20 ? true : false;

		$hap = $int+$eng+$spe;
		if($hap>=$num && $length===true) return true;
		else return false;
	}

	public function btn_date_tag($num, $txt="") {
		$date1 = date("Y-m-d", strtotime("-".$num.' '.$txt));
		$date2 = date("Y-m-d");
		$date_txt = $num.strtr($txt, $this->date_arr2);
		if(strlen($num)<=0) {
			$date1 = "";
			$date2 = "";
			$date_txt = "전체";
		}
		return '<button type="button" class="white" onClick="nf_util.date_search_click(this, \''.$date1.'\', \''.$date2.'\')">'.$date_txt.'</button>';
	}

	// : 저장한 세션 페이지
	public function sess_page_save($code) {
		$page_memory = $this->get_unse($_SESSION['page_memory']);
		$page_memory[$code] = $_SERVER['REQUEST_URI'];
		$_SESSION['page_memory'] = addslashes(serialize($page_memory));
	}

// : 이동할 세션페이지
	public function sess_page($code) {
		$page_memory = $this->get_unse($_SESSION['page_memory']);
		if($page_memory[$code]) return $page_memory[$code];
		else return $this->page_back();
	}

	static function get_text($txt) {
		return strip_tags(stripslashes($txt));
	}

	static function get_is_editor($txt) { // : 에디터글자가 있는지 체크 - 글을 다 지우고 적더라도 띄어쓰기나 태그가 남을경우가 있기때문
		return strip_tags(stripslashes(strtr($txt, array("\r\n"=>"", " "=>""))));
	}

	static function get_html($txt) {
		return htmlspecialchars(stripslashes($txt));
	}

	static function get_age($birth) {
		return date("Y")-date("Y", strtotime($birth))+1;
	}

	static function is_adult($birth) {
		if(!$birth) return false;
		$age_int = 20;
		return date("Y")-date("Y", strtotime($birth))+1>=$age_int ? true : false;
	}

	function check_adult($birth) {
		$age_adult = $this->is_adult($birth);
		return ($_SESSION['_auth_process_']['adult'] || $age_adult) ? true : false;
	}

	static function get_birth($age) {
		return date("Y")-$age+1;
	}

	static function get_intval($intval) {
		return intval(strtr($intval, array(','=>'')));
	}

	static function in_array($v, $arr) {
		return is_array($arr) && in_array($v, $arr) ? true : false;
	}

	// : 개월값으로 년, 월 값 출력
	static function get_month_year($month=0) {
		$arr = array();
		$arr['txt'] = array();

		$year = intval(floor($month/12));
		$month = intval($month%12);

		if($year>0) $arr['txt'][] = $year.'년';
		if($month>0) $arr['txt'][] = $month.'개월';

		if(!$arr['txt'][0]) $arr['txt'][] = '신입';

		return implode(' ', $arr['txt']);
	}

	static function get_month_calc($date1, $date2) {
		$date1_year = substr($date1, 0, 4);
		$date1_month = substr($date1, 5, 2);
		$date2_year = substr($date2, 0, 4);
		$date2_month = substr($date2, 5, 2);

		$month_int = ($date2_year-$date1_year)*12;
		$month_int += $date2_month-$date1_month;

		return $month_int;
	}

	static function get_ext($fname) {
		return strtolower(array_pop(explode(".", $fname)));
	}

	public function get_week($date) {
		return $this->week_arr[date("w", strtotime($date))];
	}

	function han ($s) { return reset(json_decode('{"s":"'.$s.'"}')); }
	function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','$this->han("$0")',$str); }

	public function json_encode($txt) {
		return $this->to_han(json_encode($txt));
	}

	static function get_domain($txt) {
		$txt = stripslashes($txt);
		return substr($txt,0,4)==='http' ? $txt : 'http://'.$txt;
	}

	static function target_blank($url) {
		return $_target = strpos($url, 'http://')!==false || strpos($url, 'https://')!==false ? ' target="_blank"' : ''; 
	}

	static function move_url($url, $msg='') {
		if($msg) $alert = 'alert("'.$msg.'");';
		die('<script type="text/javascript">'.$alert.'location.replace("'.$url.'");</script>');
	}

	static function opener_move_url($url, $msg='') {
		if($msg) $alert = 'alert("'.$msg.'");';
		die('<script type="text/javascript">'.$alert.'opener.location.replace("'.$url.'");window.close();</script>');
	}

	static function close_url($msg) {
		if($msg) $alert = 'alert("'.$msg.'");';
		die('<script type="text/javascript">'.$alert.'window.close();</script>');
	}

	// : 쿠키 저장 [ 기본 1개월간 저장 ]
	static function cookie_save($name, $value, $checked="yes", $time="1 month") {
		$time = ($checked=='yes') ? strtotime($time) : strtotime("-$time");
		setCookie($name, $value, $time, "/");
	}

	// : 쿠키삭제
	static function cookie_unset($name) {
		setCookie($name, "", time()-100, "/");
	}

	static function get_date($date, $part="-") {
		if($date>date("Y-m-d 00:00:00")) return substr($date, 10);
		else return substr(strtr($date,array("-"=>$part)), 0, 10);
	}

	static function get_date2($date) {
		if(substr($date,0,4)!='1000') return $date;
		else return '';
	}

	static function get_remain($num, $banum) {
		/*
		if($num>$banum && $num%$banum>0)
			return $banum-($num%$banum);
		else
			return ($num%$banum);
		*/

		if($num%$banum===0) return 0;

		if($num>$banum)
			return $banum-($num%$banum);
		else
			return $banum-($num%$banum);
	}

	static function hphone_hyphen($hphone) {
		$_len = strlen($hphone);
		if($_len==11) {
			$_arr[] = substr($hphone, 0, 3);
			$_arr[] = substr($hphone, 3, 4);
			$_arr[] = substr($hphone, 7, 4);
		} else {
			$_arr[] = substr($hphone, 0, 3);
			$_arr[] = substr($hphone, 3, 3);
			$_arr[] = substr($hphone, 6, 4);
		}
		return @implode("-", $_arr);
	}

	function get_para_page($url) {
		$get_url = explode("?", $url);
		$para = explode("?", $this->page_back());

		if(strpos($url, "/m/")===false && strpos($_SERVER['PHP_SELF'], "/m/")!==false) $get_url_head = '/m';

		return NFE_URL.$get_url_head.$get_url[0]."?".$para[1]."&".$get_url[1];
	}

	static function get_filesize($size, $code='B') {
		$result = $size.'B';
		if($code=='B') return $result;

		$size1 = $size/1024;
		$result = sprintf("%0.2f", $size1).'KB';
		if($code=='KB') return $result;

		$size2 = $size1/1024;
		$result = sprintf("%0.2f", $size2).'M';
		if($code=='M') return $result;

		$size3 = $size2/1024;
		$result = sprintf("%0.2f", $size3).'G';
		if($code=='G') return $result;
	}

	// : 랜덤 문자 생성
	static function rand_word($max=6, $type='') {
		switch($type) {
			case "number":
				$ipwd="0123456789";
				break;
			default:
				$ipwd="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
		}
		$pwd="";
		for($i=0;$i<$max;$i++) {
			$pwd.=$ipwd[rand(0, strlen($ipwd)-1)]; 
		}
		return $pwd;
	}

	function percent_int($poll_arr) {
		$all_int = array_sum($poll_arr);

		$count = 0;
		$length = count($poll_arr);
		if(is_array($poll_arr)) { foreach($poll_arr as $k=>$v) {
			if($v<=0) $arr[$k] = 0;
			else if($length-1>$count) $arr[$k] = sprintf("%0.2f", $v/$all_int)*100;
			$count++;
		} }

		// : 마지막
		if($all_int<=0) $arr[$length-1] = 0;
		else $arr[$length-1] = 100-array_sum($arr);

		return $arr;
	}

	function get_unse($txt) {
		$arr = unserialize($txt);
		if(!is_array($arr)) $arr = unserialize(stripslashes($txt));
		return $arr;
	}

	function captcha_key_check() {
		global $env, $member;

		if(!$env['article_denied']) return false;
		if($member['no']) return false;

		// 회원이 아닐 경우 자동등록방지 확인
		if( !$member['no'] ){
			$captcha_key = $_POST['captcha_key'];
			if( $captcha_key != $_SESSION['ss_captcha_key'] ){
				$arr['msg'] = '자동등록방지 숫자가 일치하지 않습니다.';
				$arr['move'] = $this->page_back();
			}
		}
		return $arr;
	}

	// : 페이징 태그
	public function paging_tag($arr) {
		$arr['tema'] = $arr['tema'] ? $arr['tema'] : 'A';
		switch($arr['tema']) {
			// : 기본 페이징
			case "A":
				$arr['start'] = '<div class="paging">';
				$arr['pprev'] = '<a href="{링크}"><button type="button" class="MAR5"><img src="'.NFE_URL.'/images/admini/first.gif" alt=""></button></a>';
				$arr['prev'] = '<a href="{링크}"><button type="button" class="MAR10"><img src="'.NFE_URL.'/images/admini/pre.gif" alt="이전" /></button></a>';
				$arr['int'] = '<span><a href="{링크}" class="{on}">{숫자}</a></span>';
				$arr['next'] = '<a href="{링크}"><button type="button" class="MAL10"><img src="'.NFE_URL.'/images/admini/next.gif" alt=""></button></a>';
				$arr['nnext'] = '<a href="{링크}"><button type="button" class="MAL5"><img src="'.NFE_URL.'/images/admini/last.gif" alt=""></button></a>';
				$arr['end'] = '</div>';
				break;

			case "B":
				$arr['start'] = '<div class="paging"><ul>';
				$arr['pprev'] = '<li class="prev_all"><a href="{링크}"><img src="../images/ic/paging_prev_all.png" alt=""></a></li>';
				$arr['prev'] = '<li class="prev"><a href="{링크}"><img src="../images/ic/paging_prev.png" alt=""></a></li>';
				$arr['int'] = '<li class="{on}"><a href="{링크}">{숫자}</a></li>';
				$arr['next'] = '<li class="next"><a href="{링크}"><img src="../images/ic/paging_next_all.png" alt=""></a></li>';
				$arr['nnext'] = '<li class="next_all"><a href="{링크}"><img src="../images/ic/paging_next.png" alt=""></a></li>';
				$arr['end'] = '</ul></div>';
				break;

			// : 쇼핑몰용 페이징
			case "B":
				$arr['start'] = '<div class="paging2 fade-in-bottom paging_body_" url="'.$arr['href'].'" para="'.$arr['para'].'" form_para="'.$arr['form_para'].'" style="animation-delay:1.2s">';
				$arr['pprev'] = '<a href="{링크}">«</a>';
				$arr['prev'] = '<a href="{링크}">＜</a>';
				$arr['int'] = '<a href="{링크}" class="{on}">{숫자}</a>';
				$arr['next'] = '<a href="{링크}">＞</a>';
				$arr['nnext'] = '<a href="{링크}">»</a>';
				$arr['end'] = '</div>';
				break;

			case "C":
				$arr['start'] = '<div class="clearfix paging_body_" url="'.$arr['href'].'" para="'.$arr['para'].'" form_para="'.$arr['form_para'].'"><div class="paging2" style="margin-top:0;margin-bottom:20px">';
				$arr['pprev'] = '<a href="{링크}">«</a>';
				$arr['prev'] = '<a href="{링크}">＜</a>';
				$arr['int'] = '<a href="{링크}" class="{on}">{숫자}</a>';
				$arr['next'] = '<a href="{링크}">＞</a>';
				$arr['nnext'] = '<a href="{링크}">≫</a>';
				$arr['end'] = '</div></div>';
				break;
		}
		return $arr;
	}


	// 시작값
	function start_page($page_int, $arr) {
		$start = $page_int>1 ? (($page_int-1)*intval($arr['limit'])) : 0;
		return intval($start);
	}

	public function _paging_link_($arr, $url, $no) {
		switch($arr['click']) {
			case "js":
				return '#none" onClick="nf_util.ajax_paging(this, \''.$arr['var'].'\', \''.$no.'\', \''.$arr['num'].'\', \''.$arr['code'].'\')';
				break;
			default:
				return $url.$no.$arr['anchor'];
				break;
		}
	}

	// : 페이징 함수
	public function _paging_($arr) {
		if(!$arr['var']) $arr['var'] = 'page';
		if(!$arr['group']) $arr['group'] = 10;
		if(!$arr['num']) $arr['num'] = 20;

		parse_str($_SERVER['QUERY_STRING'], $_output);
		unset($_output[$arr['var']]);
		$_para = $arr['parameter'] ? $arr['parameter'] : http_build_query($_output, '', '&amp;');

		$paging_tag = $this->paging_tag($arr);
		$url_page = $arr['url_page'] ? $arr['url_page'] : $_SERVER['PHP_SELF'];

		$page_int = $_POST[$arr['var']] ? $_POST[$arr['var']] : $_GET[$arr['var']];
		$this_page = $page_int>0 ? $page_int : 1;
		$start = $this_page>$arr['group'] ? (intval(($this_page-1)/$arr['group'])*$arr['group']) : 0;
		$url = $url_page.'?'.$_para.'&'.$arr['var'].'=';
		$prev_page = (ceil($this_page/$arr['group'])-1)*$arr['group'];
		$end_page = ceil($arr['total']/$arr['num']);

		if(($start+1)>$arr['group']) $_page[] = strtr($paging_tag['pprev'], array('{링크}'=>$this->_paging_link_($arr, $url, 1))).strtr($paging_tag['prev'], array('{링크}'=>$this->_paging_link_($arr, $url, $prev_page)));
		for($i=1; $i<=$arr['group']; $i++) {
			$start += 1;
			if($start>$end_page) break;
			$_on = $start==$this_page ? 'on blue' : '';
			$_page[] = strtr($paging_tag['int'], array('{링크}'=>$this->_paging_link_($arr, $url, $start), '{on}'=>$_on, '{숫자}'=>$start));
		}
		if($start<$end_page) $_page[] = strtr($paging_tag['next'], array('{링크}'=>$this->_paging_link_($arr, $url, ($start+1)))).strtr($paging_tag['nnext'], array('{링크}'=>$this->_paging_link_($arr, $url, ($end_page))));

		//if($end_page>1)
			$arr['paging'] = $paging_tag['start'].' '.@implode(" ", $_page).' '.$paging_tag['end'];
		//start_page
		$page_int>1 ? (($page_int-1)*$arr['limit']) : 0;

		$arr['start'] = $this->start_page($page_int, array('limit'=>$arr['num']));
		$arr['bunho'] = $arr['total']-$arr['start'];
		$arr['limit'] = " limit ".$arr['start'].", ".$arr['num'];
		$arr['parameter'] = 'page='.$page_int;

		return $arr;
	}

	// : 특수문자 제거
	function special_field($field) {
		return "replace(".$field.", ' ', '')";
	}


	// : 파일 다운로드  -- http://php.net/manual/en/function.readfile.php의 Examples참조
	function file_download( $filepath, $original ){

		if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
			header("content-type: doesn/matter");
			header("content-length: ".filesize("$filepath"));
			header("content-disposition: attachment; filename=\"$original\"");
			header("content-transfer-encoding: binary");
		} else {
			header("content-type: file/unknown");
			header("content-length: ".filesize("$filepath"));
			header("content-disposition: attachment; filename=\"$original\"");
			header("content-description: php generated data");
		}
		header("pragma: no-cache");
		header("expires: 0");
		flush();

		$fp = fopen("$filepath", "rb");

		$download_rate = 10;

		while(!feof($fp)) {
			print fread($fp, round($download_rate * 1024));
			flush();
			usleep(1000);
		}
		fclose ($fp);
		flush();
	}

	// : 암호화
	// : openssl_encrypt 함수는 php 5.3이상부터 가능하다고 하네요 지금 5.2.17이라 안됨.
	// : 못해도 5.6은 되야 좋겠음..
	/*
	사용법 - 
	$send_arr = array(0=>'영', 1=>'일', 2'=>'이');
	$send_seri = serialize($send_arr);
	$encrypted = $util->Encrypt($send_seri);
	*/
	function Encrypt($str) {
		$key = hash('sha256', $this->secret_key);
		$iv = substr(hash('sha256', $this->secret_iv), 0, 16);
		if(function_exists("openssl_encrypt")) {
			return str_replace("=", "", base64_encode(openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)));
		} else {
			return base64_encode($str);
		}
	}
	// : 복호화
	/*
	$decrypted = $util->Decrypt($_GET['ekey']);
	*/
	function Decrypt($str) {
		$key = hash('sha256', $this->secret_key);
		$iv = substr(hash('sha256', $this->secret_iv), 0, 16);
		if(function_exists("openssl_decrypt")) {
			return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv);
		} else {
			return base64_decode($str);
		}
	}


	function filesize_check($size) {
		global $env;
		$max = $env['editor_max_size']*1024*1024;
		if($max<$size) {
			return $env['editor_max_size'].'M까지 업로드가 가능합니다.';
		}
		return false;
	}


	// make_thumbnail(tmp, "파일경로/파일명", "100", "100", "100");
	// $fileName 확장자가 .webp 이면 자동으로 WebP 로 변환 저장 (GD imagewebp 필요)
	function make_thumbnail($filePath, $fileName, $w='500', $h='500', $q=100){
		global $env;

		$save_as_webp = (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'webp')
		                && function_exists('imagewebp');

		//썸네일 생성
		$size = @getimagesize($filePath);
		if (!$size || $size[2] === 0) {
			// 이미지 정보를 읽을 수 없으면 원본 그대로 이동
			if (move_uploaded_file($filePath, $fileName)) return $fileName;
			return false;
		}

		// 원본이 이미 목표 크기 이하이고 WebP 변환도 불필요하면 그대로 이동
		if (!$save_as_webp && $size[0] <= $w && $size[1] <= $h) {
			if (move_uploaded_file($filePath, $fileName)) return $fileName;
			return false;
		}

		// 비율 유지 리사이즈 계산
		if ($size[0] == 0) $size[0] = 1;
		if ($size[1] == 0) $size[1] = 1;
		if ($size[0] > $size[1]) { $per = $w / $size[0]; }
		else                     { $per = $h / $size[1]; }
		// 원본이 목표보다 작으면 확대하지 않음
		if ($per > 1) $per = 1;
		$width  = (int)($size[0] * $per);
		$height = (int)($size[1] * $per);

		$dst = imagecreatetruecolor($width, $height);

		// 소스 이미지 로드
		switch ($size[2]) {
			case 1: // GIF
				$src = @imagecreatefromgif($filePath);
				break;
			case 2: // JPEG
				$src = @imagecreatefromjpeg($filePath);
				break;
			case 3: // PNG — 투명도 유지
				imageAlphaBlending($dst, false);
				imageSaveAlpha($dst, true);
				$src = @imagecreatefrompng($filePath);
				break;
			case 18: // WebP
				$src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : false;
				break;
			default:
				if (move_uploaded_file($filePath, $fileName)) return $fileName;
				return false;
		}

		if (!$src) {
			imagedestroy($dst);
			if (move_uploaded_file($filePath, $fileName)) return $fileName;
			return false;
		}

		// WebP 출력 시 흰 배경 합성 (투명 → 흰색)
		if ($save_as_webp && in_array($size[2], array(3, 1))) {
			$bg = imagecreatetruecolor($width, $height);
			$white = imagecolorallocate($bg, 255, 255, 255);
			imagefill($bg, 0, 0, $white);
			imagecopyresampled($bg, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			imagedestroy($src);
			imagedestroy($dst);
			$dst = $bg;
		} else {
			imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			imagedestroy($src);
		}

		// 저장
		if ($save_as_webp) {
			imagewebp($dst, $fileName, $q);
		} else {
			switch ($size[2]) {
				case 1: imagegif($dst, $fileName);              break;
				case 2: imagejpeg($dst, $fileName, $q);        break;
				case 3: imagepng($dst, $fileName);             break;
				case 18:
					if (function_exists('imagewebp')) imagewebp($dst, $fileName, $q);
					break;
			}
		}

		imagedestroy($dst);
		return $fileName;
	}

	function where_date($start_field, $end_field, $start='', $end='') {
		if(!$start) $start = date("Y-m-d");
		if(!$end) $end = date("Y-m-d");
		return " and (('".$start."' <= ".$start_field." AND '".$end."' >= ".$start_field.") OR ('".$start."' <= ".$end_field." AND '".$end."' >= ".$end_field.") OR (".$start_field." <= '".$end."' AND ".$end_field." >= '".$end."'))";
	}

	function coupon_generator() {
		$len = 16;
		$chars = '0123456789';
		//$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
		srand((double)microtime()*1000000);

		$i = 0;
		$str = "";
		while ($i < $len) {
			$num = rand() % strlen($chars);
			$tmp = substr($chars, $num, 1);
			$str .= $tmp;
			$i++;
		}

		$str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "$1-$2-$3-$4", $str);

		return $str;
	}

	// : 할인율 계산
	function get_sale($sale, $int) {
		$sale = $sale>0 ? $sale : 0;
		return intval($int)-(intval($int)*$sale/100);
	}
	function get_sale_reverse($sale, $int) {
		$sale = $sale>0 ? $sale : 0;
		return (intval($int)*$sale/100);
	}

	function get_cutting($unit, $price) {
		return floor($price/$unit)*$unit;
	}

	function get_price_int($price, $sale=1) {
		return $price>0 ? number_format(intval($this->get_sale($sale, $price))) : '무료';
	}

	// : 거리계산함수 - 위도경도로 계산 // : 마일계산은 6371대신 3959로 대체
	function get_distance_int($int) {
		return sprintf("%0.2f", $int).'km';
	}
	function get_distance($datas) {
	//	return 6371 * acos(cos(deg2rad($datas['this_lat'])) * cos(deg2rad($datas['lat'])) * cos(deg2rad($datas['lng']) - deg2rad($datas['this_lng'])) + sin(deg2rad($datas['this_lat'])) * sin(deg2rad($datas['lat'])));
	}
// : 거리계산 쿼리문 // : 마일계산은 6371대신 3959로 대체
	function distance_q($datas) {
		if(!$datas['field_lat']) $datas['field_lat'] = 'wr_lat';
		if(!$datas['field_lng']) $datas['field_lng'] = 'wr_lng';
		switch($datas['type']) {
			case 'where':
				return $geo_q = " and ((6371 * acos(cos(radians('".$datas['lat']."')) * cos(radians(".$datas['alias']."`".$datas['field_lat']."`)) * cos(radians(".$datas['alias']."`".$datas['field_lng']."`) - radians('".$datas['lng']."')) + sin(radians('".$datas['lat']."')) * sin(radians(".$datas['alias']."`".$datas['field_lat']."`)))) <= ".$datas['distance'].")";
				break;
			default:
				return $geo_q = '(6371 * acos(cos(radians('.$datas['lat'].')) * cos(radians('.$datas['alias'].'`'.$datas['field_lat'].'`)) * cos(radians('.$datas['alias'].'`'.$datas['field_lng'].'`) - radians('.$datas['lng'].')) + sin(radians('.$datas['lat'].')) * sin(radians('.$datas['alias'].'`'.$datas['field_lat'].'`)))) as map_distance';
				break;
		}
	}

	// : 맵 범위
	function distance_int($width, $zoom) {
		global $env;

		$px = $this->map_distance_px[$env['map_engine']][$zoom];
		$distance = $this->map_distance[$env['map_engine']][$zoom];

		$px = $px ? $px : 1;
		$px_val = ceil($width/$px);
		
		return ($px_val*$distance)/1000;
	}

	// SQL 쿼리 문자열 생성
	function QueryString( $arr ){		
			foreach($arr as $field => $value) $field_vals[count($field_vals)] = $value!==NULL ? 
				is_array(unserialize($value)) ? "`$field` = '$value'" : "`$field` = '" . addslashes($value) . "'" : "`$field` = '' ";
		return @join(', ', $field_vals);
	}
	
	//recursive_file_delete('./data/file');
	function recursive_file_delete($dir, $day=5) {

		$time = 24*60*60*$day;

		$cnt = 0;
		if(is_dir($dir)) {
			if($dh = opendir($dir)) {
				while(($entry = readdir($dh)) !== false) {
					if($entry == '.' || $entry == '..') continue;
					$subdir = strtr($dir.'/'.$entry, array('//'=>'/'));
					if(is_dir($subdir)) {
						$this->recursive_file_delete($subdir, $day);
					} else {
						if($entry == 'index.php') continue;
						$sfile = strtr($dir.'/'.$entry, array('//'=>'/'));
						$mtime = @filemtime($sfile);

						// 최종수정일이 30일 이상인 파일만 삭제
						if(file_exists($sfile) && ((time()-$mtime) <= $time)) continue;

						// 파일삭제
						@unlink($sfile);
						$cnt++;
					}
				}

				closedir($dh);
			}
		}
	}	
}
?>