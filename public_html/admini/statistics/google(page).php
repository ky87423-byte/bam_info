<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 구글로그분석(페이지통계) -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 구글(Google) API 연동을 통하여 Google analytics 에서 데이터를 추출하여 보여줍니다.</li>
					<li>- 구글 API 는 <a class="blue" href="http://www.google.com/analytics/" target="_blank">http://www.google.com/analytics/</a> 에서 발급 받으시면 됩니다.</li>
					<li>- 발급 받으신 정보를 <a href="../config/index.php" class="blue"></a>기본정보 하단에 입력해 주시면 연동 설정이 완료 됩니다.</li>
					<li>- 아래에 출력되는 통계 자료들은 <a href="https://marketingplatform.google.com/about/analytics/" target="_blank" class="blue">Google analytics</a> 에서도 확인 가능합니다.</li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="7%">
					</colgroup>
					<tr>
						<th>통계분류</th>
						<td>
							<ul>
								<li class="on"><a href="./google.php">접속통계</a></li>
								<li><a href="./google(page).php">페이지 통계</a></li>
								<li><a href="./google(system).php">시스템 통계</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
			<!--// ass_list -->

			<div class="search">
				 <table>
					<colgroup>
					</colgroup>
					<tbody>
						<tr>
							<td colspan="3" class="tac">
								<input type="text" class="input10"> ~ <input type="text" class="input10"> <button class="black day">오늘</button><button class="black day">이번주</button><button class="black day">이번달</button><button class="black day">1주일</button><button class="black day">15일</button><button class="black day">1개월</button><button class="black day">3개월</button><button class="black day">6개월</button>
								<input class="MAL5 MAR5" type="submit" class="blue"  value="검색"><button class="black" >초기화</button>
							</td> 
						</tr>
					</tbody>
				 </table>
			</div>
			
			<h6>랜딩(도착) 페이지 현황<span>사이트 첫 방문 페이지 URL Page 통계 입니다.</span></h6>
			<table class="table4">
				<colgroup>
				</colgroup>
				<tr>
					<th></th>
					<th></th>
				</tr>
			</table>

			<h6>많이 열린 페이지 현황</h6>
			<table class="table4">
				<colgroup>
				</colgroup>
				<tr>
					<th></th>
					<th></th>
				</tr>
			</table>

			<h6>많이 이탈한 페이지 현황</h6>
			<table class="table4">
				<colgroup>
				</colgroup>
				<tr>
					<th></th>
					<th></th>
				</tr>
			</table>

			

			
		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->