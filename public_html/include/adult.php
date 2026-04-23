<?php
if($member['no'] && $member['is_adult']) return false;
if($member['no'] && $_SESSION['adult_view']) $db->_query("update nf_member set is_adult=1 where `no`=".intval($member['no']));
if($_SESSION['adult_view']) return false;
if($nf_util->is_adult($member['mb_birth'])) return false;
if($member['mb_type'] == 'company' || $member['mb_type'] == 'individual') return false;
?>
<script type="text/javascript" src="<?php echo NFE_URL;?>/plugin/auth/nf_auth.class.js"></script>
<style>
.adult {
  position: fixed; left: 0; top: 0; width: 100%; height: 100%;
  background: #060606;
  z-index: 99999;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Noto Sans KR', 'Malgun Gothic', sans-serif;
}
.adult_box {
  width: 100%; max-width: 520px;
  background: #111;
  border: 1px solid #2a2a2a;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 16px 48px rgba(0,0,0,0.7);
  margin: 1rem;
}
.adult_header {
  background: linear-gradient(135deg, #ff385c, #c0284a);
  padding: 2.5rem 2rem 2rem;
  text-align: center;
}
.adult_logo {
  font-family: 'Arial Black', Arial, sans-serif;
  font-size: 3rem; font-weight: 900;
  color: #fff; letter-spacing: -1px;
  line-height: 1; margin-bottom: 0.8rem;
}
.adult_logo .dot {
  display: inline-block; width: 8px; height: 8px;
  background: rgba(255,255,255,0.6); border-radius: 50%;
  margin: 0 2px 6px 2px; vertical-align: bottom;
}
.adult_warning {
  background: rgba(0,0,0,0.3); border-radius: 6px;
  padding: 0.8rem 1rem; font-size: 1.25rem; color: rgba(255,255,255,0.85);
  line-height: 1.6; margin-top: 0.8rem;
}
.adult_warning strong { color: #fff; font-weight: 700; }
.adult_body { padding: 2rem; }
.adult_notice {
  background: rgba(255,56,92,0.07); border: 1px solid rgba(255,56,92,0.2);
  border-radius: 6px; padding: 1rem 1.2rem; margin-bottom: 1.5rem;
  font-size: 1.3rem; color: #ccc; line-height: 1.6;
}
.adult_notice b { color: #ff385c; }
.adult_login_form { margin-bottom: 1.5rem; }
.adult_tabs {
  display: flex; border: 1px solid #2a2a2a; border-radius: 6px;
  overflow: hidden; margin-bottom: 1rem;
}
.adult_tabs label {
  flex: 1; text-align: center; padding: 0.9rem;
  font-size: 1.4rem; font-weight: 600; cursor: pointer;
  color: #777; background: #1a1a1a; transition: all 0.2s;
}
.adult_tabs input[type="radio"] { display: none; }
.adult_tabs input[type="radio"]:checked + label { background: #ff385c; color: #fff; }
.adult_input {
  width: 100%; background: #0d0d0d !important;
  border: 1.5px solid #333 !important; border-radius: 6px !important;
  color: #f0f0f0 !important; padding: 0 1.2rem !important;
  height: 46px !important; font-size: 1.4rem !important; margin-bottom: 0.8rem;
  box-sizing: border-box;
}
.adult_input::placeholder { color: #555 !important; }
.adult_submit {
  width: 100%; background: #ff385c; color: #fff;
  height: 48px; border-radius: 6px; font-size: 1.5rem;
  font-weight: 700; cursor: pointer; border: none;
  transition: background 0.2s; margin-top: 0.4rem;
}
.adult_submit:hover { background: #e02040; }
.adult_footer {
  display: flex; justify-content: space-between; align-items: center;
  padding: 1.2rem 2rem;
  border-top: 1px solid #1e1e1e;
  background: #0d0d0d;
}
.adult_footer a {
  font-size: 1.3rem; font-weight: 600;
  padding: 0.6rem 1.4rem; border-radius: 5px;
}
.adult_footer .btn_out { color: #888; border: 1px solid #333; }
.adult_footer .btn_out:hover { border-color: #666; color: #ccc; }
.adult_footer .btn_join {
  background: #ff385c; color: #fff !important;
}
.adult_footer .btn_join:hover { background: #e02040; }
</style>

<section class="adult">
<div class="adult_box">
  <div class="adult_header">
    <div class="adult_logo">BAM<span class="dot"></span>tube</div>
    <div class="adult_warning">
      이 사이트는 <strong>19세 이상</strong>만 이용 가능한<br>
      <strong>청소년 유해 매체물</strong>입니다.
    </div>
  </div>

  <div class="adult_body">
    <?php if(!$member['no']): ?>
    <div class="adult_notice">
      <b>비회원</b>은 이용이 불가합니다. 로그인 후 이용해 주세요.
    </div>

    <div class="adult_login_form">
      <form name="flogin_adult" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return validate(this)">
        <input type="hidden" name="mode" value="login_process" />
        <input type="hidden" name="url" value="<?php echo urlencode($_SERVER['REQUEST_URI']);?>" />
        <div class="adult_tabs">
          <input type="radio" name="kind" value="individual" id="at_indi" checked>
          <label for="at_indi">개인회원</label>
          <input type="radio" name="kind" value="company" id="at_comp">
          <label for="at_comp">업소회원</label>
        </div>
        <input type="text" name="mid" class="adult_input" placeholder="아이디" hname="아이디" needed value="<?php echo $nf_util->get_html($save_id);?>">
        <input type="password" name="passwd" class="adult_input" placeholder="비밀번호" hname="비밀번호" needed>
        <button type="submit" class="adult_submit">로그인</button>
      </form>
    </div>
    <?php else: ?>
    <div class="adult_notice" style="text-align:center;padding:2rem;">
      <?php if($env['use_ipin'] || $env['use_hphone'] || $env['use_bbaton']): ?>
      <p style="font-size:1.5rem;margin-bottom:1.5rem;">성인인증 후 이용하실 수 있습니다.</p>
      <?php if($env['use_hphone']): ?>
      <button onclick="nf_auth.auth_func('sms')" style="background:#ff385c;color:#fff;padding:1rem 3rem;border-radius:6px;font-size:1.5rem;font-weight:700;">휴대폰 인증</button>
      <?php endif; ?>
      <?php if($env['use_bbaton']): ?>
      <button onclick="nf_auth.auth_func('bbaton')" style="background:#333;color:#fff;padding:1rem 3rem;border-radius:6px;font-size:1.5rem;margin-top:0.8rem;">비바톤 인증</button>
      <?php endif; ?>
      <?php else: ?>
      <p style="font-size:1.5rem;color:#ccc;">인증 중...</p>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="adult_footer">
    <a href="<?php echo domain;?>" class="btn_out">19세 미만 나가기</a>
    <?php if(!$member['no']): ?>
    <a href="<?php echo domain;?>/member/register.php" class="btn_join">회원가입</a>
    <?php endif; ?>
  </div>
</div>
</section>

<?php
if(is_file(NFE_PATH.'/plugin/auth/kcb/auth.inc.php')) include NFE_PATH.'/plugin/auth/kcb/auth.inc.php';
