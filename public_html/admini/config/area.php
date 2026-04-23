<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 공통적용 분류(지역) -->
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
			
			<div class="ass_list">
				<table>
					<colgroup>
						<col width="10%">
					</colgroup>
					<tr>
						<th>공통적용 분류</th>
						<td>
							<ul>
								<li class="on"><a href="../config/job_type.php">업·직종</a></li>
								<li><a href="../config/area.php">지역</a></li>
								<li><a href="">지하철</a></li>
								<li><a href="../config/work_type.php">근무형태</a></li>
								<li><a href="../config/job_date.php">근무기간</a></li>
								<li><a href="">근무요일</a></li>
								<li><a href="">급여조건</a></li>
								<li><a href="">이메일</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>채용정보 분류</th>
						<td>
							<ul>
								<li><a href="">복리후생</a></li>
								<li><a href="">직급/직책</a></li>
								<li><a href="">우대조건</a></li>
								<li><a href="">모집대상</a></li>
								<li><a href="">제출서류</a></li>
								<li><a href="">연령특이사항</a></li>
								<li><a href="">급여지원조건</a></li>
								<li><a href="">테마</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>인재정보 분류</th>
						<td>
							<ul>
								<li><a href="">근무시간</a></li>
								<li><a href="">외국어종류</a></li>
								<li><a href="">외국어공인시험</a></li>
								<li><a href="">컴퓨터능력</a></li>
								<li><a href="">고용지원금대상자</a></li>
								<li><a href="">장애등급</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>회원가입 분류</th>
						<td>
							<ul>
								<li><a href="">회사분류</a></li>
								<li><a href="">상장여부</a></li>
								<li><a href="">기업형태</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>브랜드채용 분류</th> <!--브랜드채용 1차 까지만-->
						<td>
							<ul>
								<li><a href="">브랜드채용</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>

			<h6>공통적용 분류 :: 지역</h6>
			<div class="category_wrap">
				<div class="category_1 category">
					<div class="category_area">
						<h3>1차 지역 분류</h3>
						<table class="table4">
							<colgroup>
								<col width="3%">
								<col width="">
								<col width="20%">
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
				<!--//category_1-->
				<em>▶</em>

				<div class="category_2 category">
					<div class="category_area">
						<h3>2차 지역 분류</h3>
						<table class="table4">
							<colgroup>
								<col width="3%">
								<col width="">
								<col width="20%">
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
				<!--//category_2-->
				<em>▶</em>

				<div class="category_3 category">
					<div class="category_area">
						<h3>3차 지역 분류</h3>
						<table class="table4">
							<colgroup>
								<col width="3%">
								<col width="">
								<col width="20%">
							</colgroup>
							<thead>
								<tr>
									<th>V</th>
									<th>항목</th>
									<th>편집</th>
								</tr>
							</thead>
							<tbody>
								<tr class="tac">
									<td colspan="3">2차 직종을(를) 먼저 선택해주세요.</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--//category_3-->
			</div>
			<!--//category_wrap-->
		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->