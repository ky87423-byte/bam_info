<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 구글로그분석(시스템 통계) -->
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
			
			<h6>브라우저 / 비전</h6>
			<table class="table4">
				<colgroup>
					<col width="55%">
					<col width="55%">
				</colgroup>
				<tr>
					<th>브라우저</th>
					<th>브라우저 버전</th>
				</tr>	
				<tr class="tac">
					<td>d</td>
					<td>d</td>
				</tr>
			</table>

			<h6>운영체제 / 비전</h6>
			<table class="table4">
				<colgroup>
					<col width="55%">
					<col width="55%">
				</colgroup>
				<tr>
					<th>운영체제</th>
					<th>운영체제 버전</th>
				</tr>	
				<tr class="tac">
					<td>d</td>
					<td>d</td>
				</tr>
				<tr>
					<th>ISP 제공업체 정보</th>
					<th>접속자 모니터 해상도</th>
				</tr>	
				<tr class="tac">
					<td>d</td>
					<td rowspan="2">d</td>
				</tr>
				<tr class="tac" >
					<td style="border-right:1px solid #d9d9d9">d</td>
				</tr>
			</table>

			

			
		</div>
		<!--//conbox-->


	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->