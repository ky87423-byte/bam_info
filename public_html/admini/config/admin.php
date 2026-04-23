<?php
$top_menu_code = '200301';
include '../include/header.php';

$admin_row = $db->query_fetch("select * from nf_admin where `wr_id`='".admin_id."'");
?>


<!-- 관리자정보설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 관리자 사이트에 로그인 할 수 있는 관리자의 아이디, 비밀번호 등을 입력합니다.</li>
					<li>- 관리자의 비밀번호는 주기적으로 자주 변경하시는 것이 좋습니다. (정보유출 주의)</li>
				</ul>
			</div>
			<h6>관리자정보설정</h6>
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="admin_modify" />
			<table>
				<colgroup>
					<col width="10%">
					<col width="">
					<col width="10%">
					<col width="">
				</colgroup>
				<tbody>
					<tr>
						<th>아이디</th>
						<td><input type="text" name="admin_id" hname="아이디" needed value="<?php echo $nf_util->get_html($admin_row['wr_id']);?>"></td>
						<th>닉네임</th>
						<td><input type="text" name="admin_nick" hname="닉네임" needed value="<?php echo $nf_util->get_html($admin_row['wr_nick']);?>"></td>
					</tr>
					<tr>
						<th>새비밀번호</th>
						<td><input type="text" name="admin_password" hname="새비밀번호" needed></td>
						<th>새비밀번호확인</th>
						<td><input type="text" name="admin_password2" matching="admin_password" hname="새비밀번호확인" needed></td>
					</tr>
				</tbody>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="button" class="cancel_btn" onClick="nf_util.reset_form(this.form)">초기화</button>
			</div>
			</form>
		</div>
		<!--//conbox-->



	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->