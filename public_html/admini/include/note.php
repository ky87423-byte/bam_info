<div class="note_box">
	<form name="fsms_send" action="../regist.php" method="post" onSubmit="return validate(this)">
	<input type="hidden" name="mode" value="sms_select_send" />
	<textarea name="sms_member_json" style="display:none;"></textarea>
	<div class="note_box_bg">
		<p class="date"><i class="axi axi-ion-chatbox-working"></i><?php echo date("Y년 m월 d일");?></p>
		<dl class="recipient">
			<dt><i class="axi-ion-person"></i></dt>
			<dd>
				<textarea name="sms_member" cols="30" rows="10" placeholder="받는사람 아이디값" hname="받는사람 아이디를 선택해주세요" needed></textarea>
			</dd>
		</dl>
		<dl class="sender">
			<dd><textarea name="sms_content" placeholder="쪽지 내용을 입력해주세요" hname="쪽지 내용" needed></textarea></dd>
			<dt><i class="axi-ion-person"></i></dt>
		</dl>
	</div>
	<button type="submit">쪽지 보내기</button>
	</form>
</div>