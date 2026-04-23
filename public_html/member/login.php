<?php
include_once dirname(__DIR__)."/engine/_core.php";
$nf_member->check_not_login();

$_site_title_ = '로그인';
include dirname(__DIR__) . '/include/header_meta.php';
include dirname(__DIR__) . '/include/header.php';

$move_page = trim($_GET['page_url']) ? $_GET['page_url'] : $nf_util->page_back();
if(!$move_page) $move_page = NFE_URL.'/';

$m_title = '로그인';
include NFE_PATH.'/include/m_title.inc.php';
?>
<script type="text/javascript">
$(function(){
	$(".loginbox .logintab li").click(function(){
		$(".loginbox .logintab li").css({background:'#222',borderRight:'1px solid #2a2a2a'}).find("a").css({color:'#888'});
		$(this).css({background:'#ff385c',borderRight:'none'}).find("a").css({color:'#fff'});
		var form = document.forms['flogin'];
		var kind = $(this).attr("kind");
		form.kind.value = kind;
	});
	// Set initial active state
	$(".loginbox .logintab li.on").css({background:'#ff385c'}).find("a").css({color:'#fff'});
	$(".loginbox .logintab li:not(.on)").css({background:'#1a1a1a'}).find("a").css({color:'#888'});
	<?php if(defined('is_demo') && is_demo) {?>click_tab_login('company');<?php }?>
});
</script>
<section class="login_sub sub" style="min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;background:#0a0a0a;padding:4rem 1rem;">
	<form name="flogin" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return validate(this)" style="width:100%;max-width:460px;">
	<input type="hidden" name="mode" value="login_process" />
	<input type="hidden" name="kind" value="company" />
	<input type="hidden" name="url" value="<?php echo urlencode($move_page);?>" />

	<div class="loginborder" style="background:#1a1a1a;border:1px solid #2a2a2a;border-radius:10px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.6);">
		<div style="background:linear-gradient(135deg,#ff385c,#c0284a);padding:2.5rem;text-align:center;">
			<div style="font-family:'Arial Black',Arial,sans-serif;font-size:3rem;font-weight:900;letter-spacing:-1px;line-height:1;color:#fff;">
				BAM<span style="display:inline-block;width:8px;height:8px;background:rgba(255,255,255,0.6);border-radius:50%;margin:0 2px 6px 2px;vertical-align:bottom;"></span>tube
			</div>
			<p style="color:rgba(255,255,255,0.75);font-size:1.3rem;margin-top:8px;">계정에 로그인하세요</p>
		</div>

		<div class="centerwrap" style="padding:2.5rem;">
			<div class="loginbox">
				<ul class="logintab" style="display:flex;border:1px solid #2a2a2a;border-radius:6px;overflow:hidden;margin-bottom:2rem;">
					<li class="on" kind="company" style="flex:1;text-align:center;"><a href="#none" style="display:block;padding:1rem;font-size:1.4rem;font-weight:600;">업소회원</a></li>
					<li kind="individual" style="flex:1;text-align:center;border-left:1px solid #2a2a2a;"><a href="#none" style="display:block;padding:1rem;font-size:1.4rem;font-weight:600;">개인회원</a></li>
				</ul>

				<div class="logininput">
					<p style="display:flex;flex-direction:column;gap:1rem;">
						<input type="text" name="mid" value="<?php echo $nf_util->get_html($save_id);?>" hname="아이디" needed placeholder="아이디를 입력하세요"
							style="width:100%;background:#111;border:1.5px solid #333;border-radius:6px;color:#f0f0f0;padding:0 1.4rem;height:50px;font-size:1.5rem;">
						<input type="password" name="passwd" hname="비밀번호" needed value="" placeholder="비밀번호를 입력하세요"
							style="width:100%;background:#111;border:1.5px solid #333;border-radius:6px;color:#f0f0f0;padding:0 1.4rem;height:50px;font-size:1.5rem;">
					</p>
					<button style="width:100%;background:#ff385c;color:#fff;height:52px;border-radius:6px;font-size:1.6rem;font-weight:700;margin-top:1.5rem;transition:background 0.2s;" onmouseenter="this.style.background='#e02040'" onmouseleave="this.style.background='#ff385c'">로그인</button>
				</div>

				<ul class="loginlink" style="display:flex;justify-content:space-between;align-items:center;margin-top:1.5rem;flex-wrap:wrap;gap:8px;">
					<li><label style="display:flex;align-items:center;gap:5px;cursor:pointer;font-size:1.3rem;color:#888;">
						<input type="checkbox" name="save_id" value="1" <?php echo $save_id ? 'checked' : '';?>>아이디 저장
					</label></li>
					<li><a href="<?php echo NFE_URL;?>/member/register.php" style="font-size:1.3rem;color:#ff385c;font-weight:600;">회원가입</a></li>
					<li><a href="<?php echo NFE_URL;?>/member/find_idpw.php" style="font-size:1.3rem;color:#888;">아이디/비밀번호 찾기</a></li>
				</ul>
			</div>

			<div id="status" style="margin-top:1rem;"></div>
		</div>
	</div>
	</form>
</section>

<?php
if(is_file(NFE_PATH.'/plugin/login/login_api.php')) include NFE_PATH.'/plugin/login/login_api.php';
?>