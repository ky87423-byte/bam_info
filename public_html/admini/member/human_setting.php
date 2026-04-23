<?php
$top_menu_code = '300202';
include '../include/header.php';
?>


<!--기본정보설정-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="config_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>휴면회원 조건을 설정 하는 페이지 입니다. </li>
				</ul>
			</div>

			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="dormancy_config_update" />

			<div class="conbox">
				<h6>휴면회원 설정</h6>

				<table class="MAT10 bt">
					<colgroup>
						<col width="12%">
					</colgroup>
					<tr>
						<th>휴면회원 기능 사용여부</th>
						<td>
							<label><input type="radio" name="use_dormancy" value="1" checked>사용함</label>
							<label><input type="radio" name="use_dormancy" value="0" <?php echo !$env['use_dormancy'] ? 'checked' : '';?>>사용안함</label>
						</td>
					</tr>
					<tr>
						<th>휴면회원 기준일</th>
						<td>마지막 로그인 후 <input type="text" class="input10" name="dormancy_last_login" value="<?php echo intval($env['dormancy_last_login']);?>" hname="휴면회원 기준일" needed placeholder="숫자 입력"> 일 지날 경우 <b class="blue">휴면회원으로 분류됨</b></td>
					</tr>
					<tr>
						<th>휴면회원 예정안내메일<br>발송 기준일</th>
						<td>마지막 로그인 후 <input type="text" class="input10" name="dormancy_schedule_last_login" value="<?php echo intval($env['dormancy_schedule_last_login']);?>" hname="휴면회원 예정안내메일 발송 기준일" needed placeholder="숫자 입력"> 일 지날 경우 <b class="blue">휴면회원으로 분류됨을 알리는 메일 발송</b></td>
					</tr>
					<tr>
						<th>휴면회원 메일링 스킨관리</th>
						<td><button class="gray s_basebtn2" type="button" onClick="location.href='../design/mail_skin.php?code=dormancy_ing'">스킨관리 페이지로 이동</button></td>
					</tr>
				</table>


				<div class="flex_btn">
					<button type="submit" class="save_btn">저장하기</button>
				</div>


			</div>
			</form>
		<!--//conbox-->
		</div>
	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->