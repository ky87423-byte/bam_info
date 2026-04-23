<?php
$not_check_login = true;
$PATH = $_SERVER['DOCUMENT_ROOT'];
include_once $PATH."/engine/_core.php";

if($_SESSION[$nf_admin->sess_adm_uid]) {
	$nf_util->move_url(NFE_URL.'/admini/introduce/index.php');
}

include './include/html_top.php';
?>
<style>
body{background-color: #e9f0f4; width:100%;}
* {margin:0; padding:0;}
.loginWrap {position:absolute; top:50%; left:50%; margin-top:-150px; margin-left:-210px; width:400px; height:270px; background:#fff;}
.loginWrap h2 {color:#fff; background:#2e3641; height:55px; line-height:51px; font-size:17px; letter-spacing:-1px; margin:0; padding-left:20px; box-sizing:border-box;}
.loginWrap form {width:100%;}
.loginWrap .loginBox {padding:15px 35px; list-style:none;}
.loginWrap .loginBox li {margin:10px 0;}
.loginWrap .loginBox input {border:1px solid #dae4eb; height:40px; width:100%; outline:none; padding-left:10px; box-sizing:border-box;}
.loginWrap .loginBox button {display:block; background:#2e3641; color:#fff; font-weight:bold; width:100%; border:none; height:42px; line-height:42px; font-size:18px; cursor:pointer;}
</style>
 <body>
 	<form name="flogin" action="./regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
	<input type="hidden" name="mode" value="login_process" />
	<input type="hidden" name="prev_url" value="<?php echo urlencode($_GET['prev_url']);?>" />
	<div class="loginWrap">
		<h2>관리자 로그인</h2>
		<ul class="loginBox">
			<form action="">
				<li><input type="text" style="background:url(../images/admin/text_bg_01.gif) no-repeat 98% 50%"  hname="관리자 아이디" required name="uid" autofocus value="<?php echo is_demo ? 'netfu_admin' : '';?>"></li>
				<li><input type="password" style="background:url(../images/admin/text_bg_02.gif) no-repeat 98% 50%" hname="관리자 비밀번호" required name="passwd" value="<?php echo is_demo ? 'netfu_admin' : '';?>"></li>
				<li><button>Login</button></li>
			</form>
		</ul>
	</div>
	</form>
 </body>
</html>
