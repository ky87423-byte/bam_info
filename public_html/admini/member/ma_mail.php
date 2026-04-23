<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '300403';
include '../include/header.php';
?>
<script type="text/javascript">
var send_setting_mailing = function() {
	var form = document.forms['fsend'];
	form.mode.value = "send_setting_mailing";
	if(validate(form))
		form.submit();
}

var setting_write = function() {
	var form = document.forms['fsend'];
	form.mode.value = "send_setting_write";
	if(validate(form))
		form.submit();
}
</script>
<!-- 회원mail발송 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-4','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
			<h6>맞춤메일링 내용 설정</h6>
			<form name="fsend" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="" />
			<table>
				<colgroup>
					<col style="width:10%">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th>치환문자</th>
						<td>{메일상단로고} {메일하단로고} {메일하단} {사이트명} {도메인} {회원이름} {회원아이디} {맞춤인재정보} {맞춤채용정보} {맞춤인재 세팅정보} {맞춤채용 세팅정보}</td>
					</tr>
					<tr>	
						<th>기업회원 (맞춤 인재정보) <br>메일링 내용 편집</th>
						<td>
							<textarea type="editor" name="resume_setting_content" style="width:100%;height:200px;"><?php echo stripslashes($env['setting_resume_text']);?></textarea>
						</td>
					</tr>
					<tr>
						<th>개인회원 (맞춤 채용정보) <br>메일링 내용 편집</th>
						<td>
							<textarea type="editor" name="employ_setting_content" style="width:100%;height:200px;"><?php echo stripslashes($env['setting_employ_text']);?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="flex_btn">
				<button type="button" onClick="setting_write()" class="save_btn">저장하기</button>
				<button type="button" onClick="send_setting_mailing()" class="save_btn">발송하기</button>
			</div>
			</form>
		</div>
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->