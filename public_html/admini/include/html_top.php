<?php
$PATH = $_SERVER['DOCUMENT_ROOT'];
include_once $PATH."/engine/_core.php";
if(!$not_check_login) $nf_admin->check_admin();

$get_top_menu = $nf_admin->get_top_menu($top_menu_code);
$top_menu_code_head = $get_top_menu['top_menu_code_head'];
$top_menu_code_middle = $get_top_menu['top_menu_code_middle'];
$top_menu_txt = $get_top_menu['top_menu_txt'];
$middle_menu_txt = $get_top_menu['middle_menu_txt'];
$sub_menu_txt = $get_top_menu['sub_menu_txt'];
$sub_menu_url = $get_top_menu['sub_menu_url'];

if($ch_sub_menu_txt) $sub_menu_txt = $ch_sub_menu_txt;
if(!$_site_title_) $_site_title_ = '관리자 - '.$sub_menu_txt;
?>
<!DOCTYPE HTML>
<html lang="ko" id="no-fouc">
<head>
	<title><?php echo $_site_title_;?></title>
	<link rel="icon" href="/data/favicon/<?php echo $env['favicon'];?>">
	<meta charset="utf-8">
	<meta name="description" content="<?php echo $_site_content_;?>">
	<meta name="keywords" content="<?php echo $_site_keyword_;?>">

	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo $_site_title_;?>">
	<meta property="og:description" content="<?php echo $_site_content_;?>">
	<?php if($_site_image_) {?>
	<meta property="og:image" content="<?php echo $_site_image_;?>">
	<?php }?>
	<meta property="og:url" content="<?php echo domain.this_page;?>">

	<meta property="twitter:card" content="summary">
	<meta property="twitter:title" content="<?php echo $_site_title_;?>">
	<meta property="twitter:description" content="<?php echo $_site_content_;?>">
	<?php if($_site_image_) {?>
	<meta property="twitter:image" content="<?php echo $_site_image_;?>">
	<?php }?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Generator" content="">
	<meta name="Author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/plugin/jquery/colorpicker/spectrum/spectrum.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/css/admin_style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/css/admin_default.css">
	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/css/axicon/axicon.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/css/admini_color.css">
	

	<link rel="stylesheet" type="text/css" href="<?php echo NFE_URL;?>/plugin/jquery/jqueryui/jquery-ui.min.css" />
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/jquery.min.js"></script>
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/layermove2.js"></script>
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/jquery.form.min.js"></script>
	<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/jquery/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/jquery/colorpicker/spectrum/spectrum.js" ></script>

	<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.min.js"></script>
	<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.swipe.js"></script>
	<script src="<?php echo NFE_URL;?>/_helpers/_js/jquery.cycle2.carousel.js"></script>

	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_util.class.js"></script>
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/form.js"></script>
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_category.class.js"></script>
	<script type='text/javascript' src="<?php echo NFE_URL;?>/_helpers/_js/nf_shop.class.js"></script>

	<script type="text/javascript" src='<?php echo NFE_URL;?>/plugin/editor/<?php echo is_mobile ? 'mobile_' : '';?>cheditor/cheditor.js?time=<?php echo time();?>'></script>

	<script type="text/javascript">
	var root = "<?php echo NFE_URL;?>";
	var site_ip = "<?php echo $_SERVER['REMOTE_ADDR'];?>";
	var map_engine = "<?php echo $env['map_engine'];?>";
	var editor_max_size = <?php echo intval($env['editor_max_size'] * 1048576);?>;

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
	</script>
</head>
<body style="background-color:<?php echo $body_bg;?>;">
<?php
include_once NFE_PATH.'/include/etc/message.inc.php'; // : 쪽지박스
?>