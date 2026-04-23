<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 회원 탈퇴요청 사유 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 각 항목별 설정을 통해 항목을 추가/수정/삭제 하실 수 있습니다.</li>
					<li>- 분류항목을 수정하고 추가할 수 있으며 사용자화면에서 보이는 순서도 바꿀 수 있습니다.</li>
				</ul>
			</div>
			
			<h6>회원 탈퇴요청 사유</h6>
			<div class="table_top_btn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray"><strong class="blue">O</strong> 일괄적용</button></a>
				<button type="button" class="blue"><strong>+</strong> 항목추가</button></a>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="">
					<col width="7%">
					<col width="7%">
				</colgroup>
				<tbody>
					<tr>
						<th><input type="checkbox"></th>
						<th>순서</th>
						<th>분류명</th>
						<th>수정</th>
						<th>삭제</th>
					</tr>
					<tr class="tac">
						<td><input type="checkbox"></td>
						<td class=""><input type="text"></td>
						<td><input type="text"></td>
						<td><button class="gray common"><i class="axi axi-ion-android-checkmark"></i> 수정하기</button></td>
						<td><button class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button></td>
					</tr>	
					<tr class="add_list tac">
						<td colspan="2">분류명 입력</td>
						<td><input type="text"></td>
						<td colspan="2"><button class="gray common"><i class="axi axi-ion-arrow-up-a"></i> 등록</button></td>
					</tr>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray"><strong class="blue">O</strong> 일괄적용</button></a>
				<button type="button" class="blue"><strong>+</strong> 항목추가</button></a>
			</div>

		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->