<?php
if (!defined('NFE_PATH')) {
	define('NFE_PATH', dirname(__DIR__));
	include_once NFE_PATH . '/engine/_core.php';
}

// : 팝업 쿼리문 [ 팝업이 있으면 마우스드레그때문에 jqueryui를 사용합니다. ]
$popup_query = $db->is_connected() ? $db->_query("select * from nf_popup order by rank desc") : false;
$popup_length = $popup_query ? $db->num_rows($popup_query) : 0;
if(!@in_array('jqueryui', $_SERVER['__USE_API__']) && $popup_length>0) array_push($_SERVER['__USE_API__'], 'jqueryui');

$original_site_title = $_site_title_;
$_site_title_ = (($_site_title_) ? $_site_title_.' - ' : '').($env['site_title'] ?: 'BAMtube');
if(!$_site_content_) $_site_content_ = $env['meta_description'] ?: 'BAMtube - 업체 정보';
if(!$_site_keyword_) $_site_keyword_ = $env['meta_keywords'] ?: 'bamtube,업체,정보';
if(!$_site_title_not_description_ && $original_site_title) $_site_content_ = $original_site_title.' - '.$_site_content_;
if(!$_site_image_) $_site_image_ = '/data/logo/'.$env['logo_top'];

if($_ch_site_title_) $_site_title_ = $_ch_site_title_;
if($_ch_site_content_) $_site_content_ = $_ch_site_content_;

// : 검색어정보
$top_search_arr = array();
$to_search = $db->is_connected() ? $db->_query("select *, sum(`wr_hit`) as sum_hit from nf_search where 1 group by `wr_content` order by sum_hit desc limit 5") : false;
if ($to_search && ($to_search instanceof mysqli_result)) {
	while($top_row=$db->afetch($to_search)) {
		$top_search_arr[] = $top_row['wr_content'];
	}
}

// : 직종별 개수
$job_part_cnt = array();
$job_tema_cnt = array();
if ($db->is_connected() && isset($cate_p_array['job_part'][0]) && is_array($cate_p_array['job_part'][0])) {
	foreach($cate_p_array['job_part'][0] as $k=>$v) {
		$row = $db->query_fetch("select count(*) as c from nf_shop as ns where `wr_category` like ?".$nf_shop->shop_where.$nf_shop->service_where2, array("%,".$k.",%"));
		$job_part_cnt[$k] = $row ? $row : array('c' => 0);
	}
}
// : 테마별 개수
if ($db->is_connected() && isset($cate_p_array['job_tema'][0]) && is_array($cate_p_array['job_tema'][0])) {
	foreach($cate_p_array['job_tema'][0] as $k=>$v) {
		$row = $db->query_fetch("select count(*) as c from nf_shop as ns where find_in_set(?, `wr_tema`)".$nf_shop->shop_where.$nf_shop->service_where2, array($k));
		$job_tema_cnt[$k] = $row ? $row : array('c' => 0);
	}
}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=0, maximum-scale=5, user-scalable=yes">
<?php if(in_array('google', $env['sns_login_feed_arr'])) {?>
<meta name="google-signin-client_id" content="구글값">
<?php }?>
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?php echo $nf_util->get_html($_site_title_);?></title>
<meta name="description" content="<?php echo $nf_util->get_html($_site_content_);?>">
<meta name="keywords" content="<?php echo $nf_util->get_html($_site_keyword_);?>">
<meta name="robots" content="<?php echo $nf_util->get_html($_site_robots_ ?: 'index, follow');?>">
<?php
// 캐노니컬 URL: UTM 등 트래킹 파라미터 제거
$_canonical_uri_ = preg_replace('/[?&](utm_source|utm_medium|utm_campaign|utm_term|utm_content|fbclid|gclid)=[^&]*/i', '', domain.$_SERVER['REQUEST_URI']);
$_canonical_uri_ = preg_replace('/[?&]+$/', '', $_canonical_uri_);
?>
<link rel="canonical" href="<?php echo $nf_util->get_html($_canonical_uri_);?>" />
<link rel="icon" href="<?php echo domain;?>/data/favicon/<?php echo $nf_util->get_html($env['favicon']);?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo $nf_util->get_html($env['site_title'] ?: 'BAMtube');?>">
<meta property="og:locale" content="ko_KR">
<meta property="og:title" content="<?php echo $nf_util->get_html($_site_title_);?>">
<meta property="og:description" content="<?php echo $nf_util->get_html($_site_content_);?>">
<?php if($_site_image_) {?>
<meta property="og:image" content="<?php echo domain.$nf_util->get_html($_site_image_);?>">
<meta property="og:image:alt" content="<?php echo $nf_util->get_html($_site_title_);?>">
<?php }?>
<meta property="og:url" content="<?php echo $nf_util->get_html(domain.this_page);?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo $nf_util->get_html($_site_title_);?>">
<meta name="twitter:description" content="<?php echo $nf_util->get_html($_site_content_);?>">
<?php if($_site_image_) {?>
<meta name="twitter:image" content="<?php echo domain.$nf_util->get_html($_site_image_);?>">
<?php }?>

<?php
$_site_color_ = $nf_shop->site_color_arr[$env['site_color']];
?>
<style type="text/css">
:root {
--txt-color:<?php echo $_site_color_['color'];?>;
--y-txt-color:<?php echo $_site_color_['color2'];?>; /*yellow색상전용. 짙은갈색*/
--y-txt-color2:<?php echo $_site_color_['color3'];?>; /*yellow색상전용. 즐겨찾기 별색상*/
--y-bg-color:<?php echo $_site_color_['background-color2'];?>; /*yellow색상전용*/
--line-color:<?php echo $_site_color_['border-color'];?>;
--bg-color:<?php echo $_site_color_['background-color'];?>;
--border-top-color:<?php echo $_site_color_['border-top-color'];?>;
--accent-color:<?php echo $_site_color_['accent-color'];?>;
--border-left-color:<?php echo $_site_color_['border-left-color'];?>;
}
</style>

<!-- 외부 폰트/리소스 DNS 연결 선점 (렌더링 차단 최소화) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="//developers.kakao.com">
<link rel="dns-prefetch" href="//malsup.github.io">
<!-- 핵심 CSS 프리로드 -->
<link rel="preload" href="<?php echo NFE_URL;?>/css/default.css?v=<?php echo filemtime(NFE_PATH.'/css/default.css');?>" as="style">
<link rel="preload" href="<?php echo NFE_URL;?>/css/style.css?v=<?php echo filemtime(NFE_PATH.'/css/style.css');?>" as="style">
<link href="<?php echo NFE_URL;?>/css/default.css?v=<?php echo filemtime(NFE_PATH.'/css/default.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo NFE_URL;?>/css/style.css?v=<?php echo filemtime(NFE_PATH.'/css/style.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo NFE_URL;?>/css/bamtube.css?v=<?php echo filemtime(NFE_PATH.'/css/bamtube.css');?>" rel="stylesheet" type="text/css">
<!--
<?php if($env['site_color']) {?>
<link href="<?php echo NFE_URL;?>/css/<?php echo $env['site_color'];?>.css" rel="stylesheet" type="text/css">
<?php }?>
-->
<link href="<?php echo NFE_URL;?>/css/color_css.css" rel="stylesheet" type="text/css">
<link href="<?php echo NFE_URL;?>/css/axicon/axicon.min.css" rel="stylesheet" type="text/css" >
<link href="<?php echo NFE_URL;?>/css/swiper.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/earlyaccess/notosanskr.css" rel="stylesheet">
<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery-3.5.1.js"></script>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/jquery.form.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/plugin/jquery/jqueryui/jquery-ui.min.css" />
<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/jquery/jqueryui/jquery-ui.min.js"></script>


<script src="<?php echo NFE_URL;?>/_helpers/_js/swiper.js"></script>
<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.min.js"></script>
<script src="https://malsup.github.io/jquery.cycle2.swipe.js"></script>
<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.carousel.js"></script>
<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.scrollVert.js"></script>
<script src="<?php echo NFE_URL;?>/_helpers/_js/script.js"></script>
<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.scrollfollow.js"></script>

<?php if(@in_array('editor', $_SERVER['__USE_API__'])) {?>
<script type="text/javascript" src='<?php echo NFE_URL;?>/plugin/editor/<?php echo is_mobile ? 'mobile_' : '';?>cheditor/cheditor.js?time=<?php echo time();?>'></script>
<?php }?>

<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_util.class.js"></script>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/form.js"></script>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_category.class.js"></script>
<?php /*<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_job.class.js"></script>*/?>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_shop.class.js"></script>
<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_board.class.js"></script>

<style type="text/css">
.drag-skin- { cursor:pointer; }
.password-box- { display:none !important; }
.password-box-.on { display:flex !important;} 
</style>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript">
var root = "<?php echo NFE_URL;?>";
var site_ip = "<?php echo $_SERVER['REMOTE_ADDR'];?>";
var map_engine = "<?php echo $env['map_engine'];?>";
var editor_max_size = <?php echo intval($env['editor_max_size'] * 1048576);?>;
var _link = location.href;
var _subject = document.title;
var _img = $("meta[property='og\\:image']").attr("content");
var _description = $("meta[property='og\\:description']").attr("content");

var this_location = {};
var basic_location = {'lat':37.5666805, 'lng':126.9784147, 'zoom':3};
var todayDate = new Date();



var add_favorite = function(){
	var top_menu_code = "<?php echo $top_menu_code;?>";
	var top_menu = "<?php echo $top_menu_txt;?>";
	var middle_menu = "<?php echo $middle_menu_txt;?>";
	var sub_menu = "<?php echo $sub_menu_txt;?>";
	var url = "<?php echo $sub_menu_url;?>";

	confirm_msgs = "[ " + top_menu + " > ";
	confirm_msgs += (middle_menu) ? middle_menu + " > " : "";
	confirm_msgs += (sub_menu) ? sub_menu : "";

	var confirm_msg = confirm_msgs + " ] 를 퀵메뉴에 추가 하시겠습니까?";

	if(confirm(confirm_msg)){

		$.post(root+"/admini/regist.php", "mode=quick_insert&top_menu_code="+top_menu_code, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.js) eval(data.js);
		});
	}
}

var del_favorite = function(el) {
	var code = $(el).attr("code");
	if(confirm("삭제하시겠습니까?")) {
		$.post(root+"/admini/regist.php", "mode=quick_delete&top_menu_code="+code, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.js) eval(data.js);
		});
	}
}

Kakao.init("<?php echo $env['kakao'];?>");

<?php
// : 데모체크
if(is_demo) {?>
	var click_tab_login = function(mb_type) {
		$.post(root+"/include/regist.php", "mode=click_tab_login&mb_type="+mb_type, function(data){
			data = $.parseJSON(data);
			if(data.js) eval(data.js);
		});
	}
<?php
}?>
</script>

<?php
echo stripslashes($env['head_scripts']);
?>
<style>
/* BAMtube Toast Notification */
#bt-toast-container {
  position: fixed;
  bottom: 2.4rem;
  left: 50%;
  transform: translateX(-50%);
  z-index: 99999;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
  pointer-events: none;
}
.bt-toast {
  background: #1e1e1e;
  color: #f0f0f0;
  font-size: 1.4rem;
  font-family: 'Noto Sans KR', 'Malgun Gothic', sans-serif;
  padding: 1rem 2rem;
  border-radius: 6px;
  border: 1px solid #333;
  box-shadow: 0 4px 20px rgba(0,0,0,0.7);
  min-width: 180px;
  max-width: 360px;
  text-align: center;
  pointer-events: auto;
  opacity: 0;
  transform: translateY(12px);
  transition: opacity 0.22s ease, transform 0.22s ease;
  line-height: 1.5;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}
.bt-toast.bt-toast--show {
  opacity: 1;
  transform: translateY(0);
}
.bt-toast .bt-toast-icon {
  font-size: 1.6rem;
  flex-shrink: 0;
}
.bt-toast--success { border-left: 3px solid #22c55e; }
.bt-toast--success .bt-toast-icon { color: #22c55e; }
.bt-toast--info    { border-left: 3px solid #3b82f6; }
.bt-toast--info    .bt-toast-icon { color: #3b82f6; }
.bt-toast--warn    { border-left: 3px solid #f59e0b; }
.bt-toast--warn    .bt-toast-icon { color: #f59e0b; }
.bt-toast--error   { border-left: 3px solid #ff385c; }
.bt-toast--error   .bt-toast-icon { color: #ff385c; }
</style>
<script>
(function() {
  // Create toast container once DOM is ready
  function ensureContainer() {
    var c = document.getElementById('bt-toast-container');
    if (!c) {
      c = document.createElement('div');
      c.id = 'bt-toast-container';
      document.body.appendChild(c);
    }
    return c;
  }

  function showToast(msg, type) {
    type = type || 'info';
    var icons = { success: '✓', info: 'ℹ', warn: '⚠', error: '✕' };
    var c = ensureContainer();
    var t = document.createElement('div');
    t.className = 'bt-toast bt-toast--' + type;
    t.innerHTML = '<span class="bt-toast-icon">' + icons[type] + '</span><span>' + msg + '</span>';
    c.appendChild(t);
    // Trigger transition
    requestAnimationFrame(function() {
      requestAnimationFrame(function() { t.classList.add('bt-toast--show'); });
    });
    setTimeout(function() {
      t.classList.remove('bt-toast--show');
      setTimeout(function() { if (t.parentNode) t.parentNode.removeChild(t); }, 260);
    }, 2800);
  }

  // Classify message to pick icon/color
  function classify(msg) {
    if (!msg) return 'info';
    var s = String(msg);
    if (/취소|삭제|실패|오류|에러|error|fail|없습니다|없어요/i.test(s)) return 'warn';
    if (/완료|복사|설정|추가|저장|성공|success/i.test(s)) return 'success';
    if (/로그인|권한|인증/i.test(s)) return 'error';
    return 'info';
  }

  // Override native alert
  window.alert = function(msg) {
    if (document.body) {
      showToast(String(msg || ''), classify(msg));
    } else {
      document.addEventListener('DOMContentLoaded', function() {
        showToast(String(msg || ''), classify(msg));
      });
    }
  };

  // Expose for manual use
  window.btToast = showToast;
})();
</script>

<!-- 구조화 데이터 (JSON-LD) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "<?php echo addslashes($env['site_title'] ?: 'BAMtube');?>",
  "url": "<?php echo domain;?>/",
  "description": "<?php echo addslashes($nf_util->get_text($env['meta_description'] ?: 'BAMtube - 업체 정보'));?>",
  "inLanguage": "ko-KR",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "<?php echo domain;?>/include/location_view.php?code=location&top_keyword={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>
</head>

<body>
<?php 
	if($_SERVER['PHP_SELF'] == '/index.php') {
		$class_val= "main_side";
	}else if($_SERVER['PHP_SELF'] == '/include/category_view.php' || $_SERVER['PHP_SELF'] == '/include/location_view.php') {
		$class_val= "shop_side";
	}else if($_SERVER['PHP_SELF'] == '/shop/index.php') {
		$class_val= "detail_side";
	}else{
		$class_val= "common_side";
	}
?>
<div class="sticky_wrap <?php echo $class_val;?>">

<?php
if(strpos($_SERVER['PHP_SELF'], '.inc.')===false) {

	
	if($env['use_message']) include_once NFE_PATH.'/include/etc/message.inc.php'; // : 쪽지박스
	include_once NFE_PATH.'/include/etc/report2.inc.php'; // : 업체신고하기
	
	if($env['use_adult'] && strpos($_SERVER['PHP_SELF'], '/regist.php')===false && strpos($_SERVER['PHP_SELF'], '/register.php')===false && strpos($_SERVER['PHP_SELF'], '/member_regist.php')===false && $_SERVER['SCRIPT_NAME'] !== NFE_URL . '/index.php') {
		// : 성인인증 사용시 사용
		include NFE_PATH.'/include/adult.php';
		if(!$member['no'])
		{
			exit;
		}
	}


	// : 팝업
	if($popup_length>0) {
		while($popup_row=$db->afetch($popup_query)) {
			$popup_allow = true;
			if($_COOKIE['popup_'.$popup_row['no']]) $popup_allow = false;
			if(!$popup_row['popup_view'] && $_SERVER['PHP_SELF']==NFE_URL.'/index.php') $popup_allow = false;
			if(!$popup_row['popup_sub_view'] && $_SERVER['PHP_SELF']!=NFE_URL.'/index.php') $popup_allow = false;
			if(!$popup_allow) continue;

			if(!$popup_row['popup_unlimit']) {
				if(substr($popup_row['popup_begin'],0,4)>'1000' && $popup_row['popup_begin']>today.' '.date("H").':00:00') continue;
				if(substr($popup_row['popup_end'],0,4)>'1000' && $popup_row['popup_end']<today.' '.date("H").':00:00') continue;
			}

			include NFE_PATH.'/include/etc/popup.inc.php';
		}
	}

	include_once NFE_PATH.'/include/password.inc.php';
}

// : 공지사항
$notice_array_query = $db->query_fetch_rows("select * from nf_notice as nn where 1 order by nn.`no` desc limit 0, 10");
?>