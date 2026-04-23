<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 이력서 패키지 설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 개인회원 이력서 상품별 패키지 설정을 하는 페이지입니다.</li>
				</ul>
			</div>
			
			<h6>이력서 패키지 설정</h6>
			<div class="table_top_btn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" url="" mode="" tag="" check_code="" class="blue"><strong>+</strong> 패키지 등록</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="4%">
					<col width="18%">
					<col width="8%">
					<col width="%">
					<col width="5%">
					<col width="7%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" onClick=""></th>
						<th>순서</th>
						<th>패키지제목</th>
						<th>결제금액</th>
						<th>패키지내용</th>
						<th>상태</th>
						<th>등록일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value=""></td>
						<td><input type="text"></td>
						<td>프리미엄 패키지 상품</td>
						<td>300,000 원</td>
						<td>
							<ul>
								<li>메인 플래티넘 1 개월</li>
								<li>메인 플래티넘 1 개월</li>
								<li>메인 플래티넘 1 개월</li>
							</ul>
						</td>
						<td>사용중</td>
						<td>2015-04-13</td>
						<td>
							<button class="gray common"><i class="axi axi-plus2"></i> 수정하기</button>
							<button class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" url="" mode="" tag="" check_code="" class="blue"><strong>+</strong> 패키지 등록</button>
			</div>
		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->