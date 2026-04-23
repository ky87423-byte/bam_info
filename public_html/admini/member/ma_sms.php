<?php
$top_menu_code = '300404';
include '../include/header.php';

?>

<!-- 회원mail발송 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-5','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<h6>메세지 출력 안내</h6>
			<table class="table2">
				<colgroup>
					<col style="width:10%">
					<col style="width:auto">
				</colgroup>	
				<tr>
					<th colspan="4">
						<ul>
							<li>※ SMS 최대 글자수는 90Byte이며, LMS 최대 글자수는 2,000Byte 입니다.</li>
							<li>※ <strong>LMS 사용시</strong> : 문자내용이 90byte를 초과하면 <span style="text-decoration:underline">LMS(장문)</span>로 발송 됩니다.</li>
							<li>※ <strong>LMS 미사용시</strong> : 문자내용이 90byte를 초과하면 <span style="text-decoration:underline">SMS(단문)로 90byte 까지</span>만 잘려서 발송 됩니다.</li>
						</ul>
					</th>
				</tr>
				<tr>
					<th class="gray">{사이트명}</th>
					<td>고객님의 사이트명이 출력됩니다 <br>예)정규직 구인구직 홈페이지 - 넷퓨</td>
					<th class="gray">{도메인}</th>
					<td>사이트의 도메인명이 출력됩니다 <br>예)njob.netfu.co.kr</td>
				</tr>
				<tr>
					<th class="gray">{회원명}</th>
					<td>회원 이름이 출력됩니다 <br>예)홍길동</td>
					<th class="gray">{아이디}</th>
					<td>회원 아이디가 출력됩니다 <br>예)netfu</td>
				</tr>
				<tr>
					<th class="gray">{희망직종}</th>
					<td>구인,구직 희망직종이 출력됩니다 <br>예)서비스</td>
					<th class="gray">{희망근무지역}</th>
					<td>희망근무지역이 출력됩니다 <br>예)서울</td>
				</tr>
				<tr>
					<th class="gray">{근무형태}</th>
					<td>설정한 근무형태가 출력됩니다 <br>예)아르바이트,정규직</td>
					<th class="gray">{등록건수}</th>
					<td>인재정보, 채용정보 등록건수가 출력됩니다 <br>예)10</td>
				</tr>
			</table>
		

			
			<h6>맞춤문자발송</h6>
			<div class="lms_con">
				<div class="lms">
					<h1><span>기업회원</span>(맞춤 인재정보) 정기 LMS</h1>
					<div>
						<textarea id="" style="text-align:left; " align="left"  name="" onkeyup="" onfocus="" hname="" needed ></textarea>
					</div>
					<p class="flex_btn">
						<button type="" class="save_btn">발송하기</button>
					</p>
				</div>

				<div class="lms">
					<h1><span>개인회원</span>(맞춤 채용정보) 정기 LMS</h1>
					<div>
						<textarea id="" style="text-align:left; " align="left"  name="" onkeyup="" onfocus="" hname="" needed ></textarea>
					</div>
					<p class="flex_btn">
						<button type="" class="save_btn">발송하기</button>
					</p>
				</div>
			</div>


		</div>
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->