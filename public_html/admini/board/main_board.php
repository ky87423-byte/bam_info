<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 메인게시판출력설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="board_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 메인 INDEX 페이지 최하단에 출력되는 게시판의 순서와 출력유무, 출력갯수 등을 설정하실수 있습니다.</li>
				</ul>
			</div>
	

			<div class="category_wrap">
				<div class="category_1 category">
					<div class="category_area">
						<h3>게시판 소속 상위 메뉴 설정</h3>
						<table class="table4">
							<colgroup>
								<col width="3%">
								<col width="">
								<col width="13%">
							</colgroup>
							<thead>
								<tr>
									<th>V</th>
									<th>항목</th>
									<th>편집</th>
								</tr>
							</thead>
							<tbody>
								<tr class="tac on">
									<td><input type="checkbox"></td>
									<td><input type="text"></td>
									<td>
										<button class="s_basebtn gray">수정</button>
										<button class="s_basebtn gray">삭제</button>
									</td>
								</tr>
								<tr class="tac">
									<td><input type="checkbox"></td>
									<td><input type="text"></td>
									<td>
										<button class="s_basebtn gray">수정</button>
										<button class="s_basebtn gray">삭제</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="button_area">
						<ul>
							<li><button class="orange">▲위로</button></li>
							<li><button class="orange">▼아래로</button></li>
							<li><button class="blue_base">▲맨처음</button></li>
							<li><button class="blue_base">▼끝으로</button></li>
						</ul>
						<button class="blue">+추가</button>
					</div>
				</div>
			</div>
			
			<h6>게시판 설정/출력수</h6>
			<table class="table4">
				<colgroup>
					<col width="5%">
					<col width="">
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="10%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th>출력순서</th>
						<th>게시판명</th>
						<th>출력유무</th>
						<th>제목길이</th>
						<th>출력갯수</th>
						<th>이미지사이즈</th>
						<th><a href="">출력형태<img src="../../images/ic/q.gif" alt="" class="MAL5" style="vertical-align:baseline;"></a></th>
					</tr>
				</thead>
				<tbody>
					<tr class="tac">
						<td><input type="text"></td>
						<td class="tal">사진이야기</td>
						<td><input type="checkbox"></td>
						<td><input type="text"></td>
						<td><input type="text"></td>
						<td><input type="text" class="input5"> X <input type="text" class="input5"></td>
						<td><select name="" id=""><option value="">텍스트형</option></select></td>
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