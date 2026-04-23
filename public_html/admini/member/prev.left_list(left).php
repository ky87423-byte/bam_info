<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 탈티회원관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 회원탈퇴를 승인, 수정, 복귀 등을 관리 하실 수 있습니다.</li>
					<li>- 회원탈퇴와 동시에 해당회원이 등록한 정보는 비공개로 설정됩니다.</li>
					<li>- 회원이 회원탈퇴를 하면 관리자에서 탈퇴이유와 함께 탈퇴회원을 확인 할 수 있습니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<div class="search">
					 <table>
						<colgroup>
							<col width="5%">
							<col width="45%">
							<col width="5%">
							<col width="45%">
						</colgroup>
						<tbody>
							<tr>
								<th><select name="" id=""><option value="">가입일</option></select></th>
								<td>
									<label for=""><input type="checkbox">전체</label>
									<input type="text" class="input10"> ~ <input type="text" class="input10"> <button class="black day">오늘</button><button class="black day">이번주</button><button class="black day">이번달</button><button class="black day">1주일</button><button class="black day">15일</button><button class="black day">1개월</button><button class="black day">3개월</button><button class="black day">6개월</button>
								</td>
								<th>방문수</th>
								<td><label for=""><input type="checkbox">전체</label> <input type="text" class="input10"> ~ <input type="text" class="input10"></td>
							</tr>
							<tr>
								<th>불량회원구분</th>
								<td><label for=""><input type="checkbox">불량회원</label></td>
								<th>탈퇴구분</th>
								<td colspan="3">
									<label for=""><input type="checkbox">탈퇴요청</label>
									<label for=""><input type="checkbox">탈퇴완료</label>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field" id="">
							<option value="">통합검색</option>
						</select>
						<input type="text" name="search_keyword">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			
			<h6>회원관리<span>총 <b>0</b>명의 회원이 검색되었습니다.</span>
				<p class="h6_right">
					<label for=""><input type="checkbox">항상 상세검색</label>
					<select name="" id="">
						<option value="">15개출력</option>
						<option value="">30개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray"><strong><i class="axi axi-replay"></i></strong> 선택복귀</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" onClick=""></th>
						<th><a href="">회원구분▼</a></th>
						<th><a href="">회원ID▼</a></th>
						<th><a href="">이름/대표자 (성별/나이)▼</a></th>
						<th><a href="">포인트▼</a></th>
						<th><a href="">회사명▼</a></th>
						<th>탈퇴사유</th>
						<th><a href="">가입일▼</a></th>
						<th><a href="">탈퇴요청일▼</a></th>
						<th>이메일</th>
						<th>메모</th>
						<th>수정</th>
						<th>복귀</th>
					</tr>
				</thead>
				<tbody>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value=""></td>
						<td >개인회원</td>
						<td>test_company</td>
						<td>강진우 (남/34세)</td>
						<td>50</td>
						<td><span class="gray">개인</span><br>회사이름</td>
						<td>탈퇴사유란</td>
						<td>2015-05-55</td>
						<td>2012-15-15 10:00:15</td>
						<td><button class="gray common">이메일</button></td>
						<td><button class="gray common">메모</td>
						<td><button class="gray common"><i class="axi axi-plus2"></i> 수정</button></td>
						<td><button class="gray common"><i class="axi axi-replay"></i> 복귀</button></td>
					</tr>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray"><strong><i class="axi axi-replay"></i></strong> 선택복귀</button>
			</div>
		</div>
		<!--//payconfig conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->