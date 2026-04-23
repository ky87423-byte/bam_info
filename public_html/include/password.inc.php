<form name="fpassword" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="password_write" />
<input type="hidden" name="code" value="" />
<input type="hidden" name="kind" value="" />
<input type="hidden" name="bo_table" value="" />
<input type="hidden" name="no" value="" />
<div class="lock_pw password-box-">
	<p>비밀번호를 입력해주세요.</p>
	<div>
		<input type="password" name="pw" value=""><button type="submit">확인</button>
	</div>
	<button type="button" onClick="$('.password-box-').removeClass('on')">닫기</button>
</div>
</form>