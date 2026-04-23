<?php
include_once "../engine/_core.php";
if($member['no']) die($nf_util->move_url("/", "이미 로그인되 있습니다."));

$_site_title_ = "아이디·비밀번호 찾기";
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '아이디·비밀번호 찾기';
include NFE_PATH.'/include/m_title.inc.php';
?>
<section class="find_idpw sub">
	<div class="wrap1400 MAT20">
		<div class="loginborder">
			<div class="centerwrap">
				<h3>아이디·비밀번호 찾기</h3>
				<div>
					<div class="loginbox">
						<ul class="logintab">
							<li class="on input_wrap-"><a href="#none">아이디</a></li>
							<li class="input_wrap-"><a href="#none">비밀번호</a></li>
						</ul>
						
						<div class="input_wrap input_wrap-child-">
						<form action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
						<input type="hidden" name="mode" value="find_id" />
							<em>* 회원가입 시 등록한 정보를 입력해주세요. 입력하신 이메일로 아이디·비밀번호 정보를 발송해 드립니다.</em>
							<dl>
								<dt>이름</dt>
								<dd><input type="text" name="name" placeholder="이름을 입력해주세요."></dd>
							</dl>
							<dl>
								<dt>이메일</dt>
								<dd><input type="text" name="email" placeholder="이메일을 입력해주세요."></dd>
							</dl>
							<button>확인</button>
						</form>
						</div>

						<div class="input_wrap input_wrap-child-" style="display:none;">
						<form action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
						<input type="hidden" name="mode" value="find_pw" />
							<em>* 회원가입 시 등록한 정보를 입력해주세요. 입력하신 이메일로 아이디·비밀번호 정보를 발송해 드립니다.</em>
							<dl>
								<dt>이름</dt>
								<dd><input type="text" name="name" placeholder="이름을 입력해주세요."></dd>
							</dl>
							<dl>
								<dt>아이디</dt>
								<dd><input type="text" name="mid" placeholder="아이디를 입력해주세요."></dd>
							</dl>
							<dl>
								<dt>이메일</dt>
								<dd><input type="text" name="email" placeholder="이메일을 입력해주세요."></dd>
							</dl>
							<button>확인</button>
						</form>
						</div>
					</div>
					<script type="text/javascript">
					nf_util.click_tab('.input_wrap-');
					</script>
					<!--loginbox-->
				</div>
			</div>
		</div>
	</div>
</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>