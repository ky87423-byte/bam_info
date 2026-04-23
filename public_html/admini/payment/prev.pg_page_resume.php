<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->


<!-- 결제페이지설정(인재) -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 채용공고, 이력서 등록후 유료상품 안내 페이지로 이동 여부를 설정하는 페이지입니다.</li>
					<li>- 사용으로 체크시 채용공고, 이력서 등록후 바로 결제 페이지로 이동되어 결제를 유도합니다.</li>
					<li>- 결제페이지를 사용하지 않는 경우에는 채용공고, 이력서 등록후 바로 이곳에서 설정한 무료기간 만큼 자동으로 등록됩니다.</li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="8%">
					</colgroup>
					<tr>
						<th>페이지 설정</th>
						<td>
							<ul>
								<li class="on"><a href="./pg_page_audit.php">채용공고 심사 여부</a></li>
								<li><a href="./pg_page_employ.php">채용공고 결제 페이지</a></li>
								<li><a href="./pg_page_resume.php">이력서 결제 페이지</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>

			
			<h6>이력서 결제 페이지 설정</h6>
			<table class="table3">
				<colgroup>
					<col width="13%">
					<col width="%">
				</colgroup>
				<tr>
					<th>페이지 사용여부</th>
					<td>
						<label for=""><input type="radio">사용</label>
						<label for=""><input type="radio">미사용</label> <span>* '0' 입력시엔 서비스 기간을 부여하지 않습니다.</span>
					</td>
				</tr>
				<tr>
					<th>(서비스명) 서비스 기간</th>
					<td>
						<input type="text" class="input10">
						<select name="" id="" class="select10">
							<option value="">일</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>메인 일반 서비스 기간</th>
					<td>
						<input type="text" class="input10">
						<select name="" id="" class="select10">
							<option value="">일</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>서브 인재정보 일반 서비스 기간</th>
					<td>
						<input type="text" class="input10">
						<select name="" id="" class="select10">
							<option value="">일</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->