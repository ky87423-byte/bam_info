<?php
if(!$env['article_denied']) return false;
if($member['mb_id']) return false;

include_once NFE_PATH."/plugin/kcaptcha/captcha.lib.php";
$captcha_html = '';
$captcha_js   = '';
//if ($is_guest) {
	$captcha_html = captcha_html2();
	$captcha_js = chk_captcha_js();
//}

switch(is_mobile) {

## : 모바일
	case true:
?>
<style type="text/css">
/*
td.captcha .capcha_btn { display:none; }
td.captcha .captcha_num { border:none; }
*/
.captcha{position:relative}
.captcha .capcha_img{height:50px;padding:0 2px;float:left;border:1px solid #ccc}
.captcha .capcha_img img{width:100px;margin-top:10px}
.captcha .capcha_btn_gp{float:left}
.captcha .capcha_btn_gp img{height:20px;vertical-align:middle;margin-right:5px}
.captcha .capcha_btn_gp button{font-size:.7em;height:19px;line-height:19px;margin:3px;}
.captcha .capcha_btn_gp .capcha_btn{height:19px;line-height:19px}
.captcha .confirm_btn0504{position:relative;top:5px}
.captcha audio { display:none; }
</style>
<tr>
	<th scope="row" style="letter-spacing:-0.1em">자동등록방지</th>
	<td class="captcha"><span class="captcha_txt">자동등록방지 문자 입력</span><span class="captcha_num"><?php echo $captcha_html;?></span><input type="text" name="captcha_key" value="" maxlength="6" required hname="자동등록방지숫자" style="ime-mode:disabled;"></td>
</tr>
<?php
		break;





## : PC
	default:
?>
<tr>
	<th scope="row">
		<i class="axi axi-ion-android-checkmark"></i> 자동등록방지
	</th>
	<td class="capcha">
		<div class="capcha_hd">
			<?php echo $captcha_html;?>
		</div>
		<div class="capcha_txt">
			<input type="text" name="captcha_key" id="captcha_key" class="mail_confirm03 captcha_box" size="6" maxlength="6" needed hname="자동등록방지숫자" style="ime-mode:disabled;"><span>왼쪽에 보이는 문자대로 입력해주세요.</span>
		</div>
	</td>
</tr>
<?php
		break;
}
?>