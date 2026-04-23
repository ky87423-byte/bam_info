<?php
$PATH = $_SERVER['DOCUMENT_ROOT'];
include_once $PATH."/engine/_core.php";
$get_sadmin = $nf_admin->get_sadmin(admin_id);

// : 권한이 맞지 않으면 각 권한별 첫 페이지로 이동합니다.
if($admin_info['wr_level']<10 && !@in_Array($top_menu_code, $get_sadmin['admin_menu_array'])) {
	$get_top_menu = $nf_admin->get_top_menu($get_sadmin['first_link']);
	@header("Location: ".domain.$get_top_menu['sub_menu_url']);
	exit;
}

include NFE_PATH.'/admini/include/html_top.php';

$sms_count = $nf_sms->netfu_sms_Ord();

$day_2 = date("Y-m-d", strtotime("-1 day"));
$is_new['100']['100101'] = $db->query_fetch("select no from nf_shop as ns where ns.`is_wait`=1 and ns.`is_delete`=0 and `wr_rdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['100']['100102'] = $db->query_fetch("select no from nf_shop as ns where ns.`is_wait`=1 and ns.`is_delete`=0 and `wr_rdate`>='".$day_2." 00:00:00' ".$nf_shop->service_where2." limit 1") ? 1 : 0;
$is_new['100']['100103'] = $db->query_fetch("select no from nf_shop as ns where ns.`is_wait`=1 and ns.`is_delete`=0 and `wr_rdate`>='".$day_2." 00:00:00' ".$nf_shop->service_ing_where." limit 1") ? 1 : 0;
$is_new['100']['100104'] = $db->query_fetch("select no from nf_shop as ns where ns.`is_wait`=1 and ns.`is_delete`=1 and `wr_rdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['100']['100105'] = $db->query_fetch("select nr.no from nf_shop as ns right join nf_report as nr on nr.`pno`=ns.`no` where `code`='shop' and nr.`sdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['100']['100201'] = $db->query_fetch("select ns.no from nf_shop as ns right join nf_scrap as ns1 on ns.`no`=ns1.`pno` where `rdate`>='".$day_2." 00:00:00' limit 1");
$is_new['100']['100202'] = $db->query_fetch("select ns.no from nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where `rdate`>='".$day_2." 00:00:00' limit 1");
$is_new['100']['100203'] = $db->query_fetch("select ns.no from nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where `rdate`>='".$day_2." 00:00:00' limit 1");
$is_new['100']['100204'] = $db->query_fetch("select ns.no from nf_shop as ns right join nf_coupon_use as ncu on ns.`no`=ncu.`pno` where ncu.`code`='shop' and `rdate`>='".$day_2." 00:00:00' limit 1");

$is_new['300']['300101'] = $db->query_fetch("select no from nf_member as nm where mb_left=0 and mb_left_request=0 and mb_left=0 and mb_left_request=0 and `is_delete`=0 and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['300']['300102'] = $db->query_fetch("select no from nf_member as nm where 1 and nm.`mb_type`='company' and `is_delete`=0 and mb_left=0 and mb_left_request=0 and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['300']['300103'] = $db->query_fetch("select no from nf_member as nm where mb_left=0 and mb_left_request=0 and is_delete=0 and nm.`mb_type`='individual' and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['300']['300104'] = $db->query_fetch("select no from nf_member as nm where 1 and nm.`mb_left_request`=1 and `is_delete`=0 and `mb_left`=0 and `mb_left_request`=1 and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;

$is_new['300']['300201'] = $db->query_fetch("select no from nf_member where `mb_type`='company' and `is_delete`=0 and mb_left=0 and mb_left_request=0 and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['300']['300301'] = $db->query_fetch("select no from nf_member where `mb_type`='individual' and `is_delete`=0 and mb_left=0 and mb_left_request=0 and `mb_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;

$is_new['500']['500101'] = $db->query_fetch("select no from nf_payment as np where ((pay_method!='bank' && pay_status=1) or pay_method='bank') and `pay_wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['500']['500104'] = $db->query_fetch("select no from nf_tax as nt where `is_delete`=0 and `wr_type`='company' and `wdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;

$is_new['600']['600201'] = $db->query_fetch("select wr_no from nf_write_service_advert as nwb where 1 and `wr_is_comment`=0 and `wr_del`=0 and `wr_blind`=0 and `wr_datetime`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['600']['600203'] = $db->query_fetch("select no from nf_cs where `wr_type`=0 and `wr_date`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;
$is_new['600']['600205'] = $db->query_fetch("select no from nf_message as nm where (pmb_id='_admin_' or mb_id='_admin_') and `sdate`>='".$day_2." 00:00:00' limit 1") ? 1 : 0;


// : 공통 div폼
if(in_Array('send_email', $_SERVER['__USE_ETC__'])) include NFE_PATH.'/admini/include/email.inc.php'; // : 이메일
?>
<div class="layer_pop conbox popup_box- member-detail-" style="display:none;z-index:2;">
	<div class="h6wrap">
		<h6>회원정보</h6>
		<button type="button" onClick="$(this).closest('.member-detail-').css({'display':'none'})" class="close">X 창닫기</button>
	</div>
	<div class="table-">
	</div>
</div>



<script type="text/javascript">
var member_mno_click = function(el) {
	var mno = $(el).attr("mno");
	var code = $(el).attr("code");
	var obj = $(".conbox."+code);
	$.post(root+"/admini/regist.php", "mode=member_mno_click&code="+code+"&mno="+mno, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		$(".conbox.popup_box-").css({"display":"none"});
		if(data.js) eval(data.js);
	});
}
</script>

<header>
	<nav class="main_nav">
		<ul class="navbar">
			<li><a href="<?php echo $_menu_array_['300']['menus'][0]['sub_menu']['300101']['url'];?>">admin</a></li>
			<?php
			if(is_Array($_menu_array_)) { foreach($_menu_array_ as $k=>$v) {
				if($admin_info['wr_level']<10 && !in_array($k, $_get_sadmin_['admin_menu_array'])) continue;
				$menu_txt = $_top_menus_[$k.'000'];

				$on = substr($top_menu_code,0,3)==$k ? 'on' : '';

				$sub_menu_k = array_keys($v['menus'][0]['sub_menu']);

				$new_icon = '';
				if($v['new'] && array_sum($is_new[$k])>0) $new_icon = '<img src="../../images/admini/new.png" alt="new">';
			?>
			<li class="<?php echo $on;?>">
				<a href="<?php echo $v['link'];?>"><?php echo $menu_txt.$new_icon;?></a>

				<ul class="nav_sub">
					<?php
					if(is_array($v['menus'])) { foreach($v['menus'] as $k2=>$v2) {
						if($admin_info['wr_level']<10 && !in_array($v2['code'], $_get_sadmin_['admin_menu_array'])) continue;
					?>
					<li>
						<span><?php echo $v2['name'];?></span>
						<ul class="nav_list">
							<?php
							if(is_array($v2['sub_menu'])) { foreach($v2['sub_menu'] as $k3=>$v3) {
								if($admin_info['wr_level']<10 && !in_array($k3, $_get_sadmin_['admin_menu_array'])) continue;
								$new_icon = '';
								if($v3['new'] && $is_new[$k][$k3]>0) $new_icon = '<img src="../../images/admini/new.png" alt="new">';
							?>
							<li><a href="<?php echo $v3['url'];?>"><?php echo $v3['name'];?><?php echo $new_icon;?></a></li>
							<?php
							} }?>
						</ul>
					</li>
					<?php } }?>
				</ul>
			</li>
			<?php
			} }
			?>

			<?php if($nf_sms->config['wr_use'] || $nf_sms->config['wr_lms_use']) {?>
			<li><a href="<?php echo NFE_URL."/admini/config/sms.php";?>">문자건수 : <?php echo intval($sms_count)>0 ? number_format(intval($sms_count)) : 0;?>건 남음</a></li>
			<?php }?>
		</ul>
	</nav>
</header>