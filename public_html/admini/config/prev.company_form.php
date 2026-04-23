<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 기업회원 가입폼 설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 기업회원 가입폼 설정 페이지는 기업회원 회원가입시 필요한 필드를 선택하는 공간입니다. 사용유무를 선택하시면 됩니다.</li>
				</ul>
			</div>
			
			<h6>기업회원 가입폼 설정</h6>
			<table class="table4">
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="7%">
					<col width="">
				</colgroup>
				<tbody>
					<tr>
						<th>사용유무</th>
						<th>필수유무</th>
						<th>순서</th>
						<th>항목명</th>
					</tr>
					<tr class="tac">
						<td><input type="checkbox"></td>
						<td><input type="checkbox"></td>
						<td class=""><input type="text"></td>
						<td class="tal">사업자등록번호</td>
					</tr>	
				</tbody>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>

		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->