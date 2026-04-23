<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 이력서 패키지 등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<h6>이력서 패키지 등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>패키지 사용여부</th>
					<td>
						<label for=""><input type="radio">사용</label>
						<label for=""><input type="radio">미사용</label>
					</td>
				</tr>
				<tr>
					<th>패키지 제목</th>
					<td><input type="text"></td>
				</tr>
				<tr>
					<th>패키지 설명</th>
					<td>에디터</td>
				</tr>
				<tr>
					<th>패키지 금액</th>
					<td><input type="text" class="input10"> 원</td>
				</tr>
				<tr>
					<th>패키지 내용</th>
					<td>
						<table class="table3" style="width:auto;">
							<tr class="tac">
								<th class="tac">서비스 명</th>
								<th class="tac">기간</th>
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">메인 포커스 인재정보</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">메인 플러스 인재정보</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">메인 인재정보 테두리강조</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">서브 포커스 인재정보</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">서브 플러스 인재정보</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">서브 인재정보 테두리강조</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">인재정보 일반리스트</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">굵은글자</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">반짝칼라</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">점프 서비스</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">급구 옵션</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
							<tr>
								<td><label for=""><input type="checkbox">채용정보 열람권</label></td>	
								<td>
									<input type="text" class="input5">
									<select name="" id="">
										<option value="">일</option>
									</select>
								</td>	
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="submit" class="cancel_btn">취소하기</button>
			</div>
		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->