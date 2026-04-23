<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 광고문의 관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 문의글에 답변을 하면 자동으로 메일발송되며 '답변여부'가 'X'에서 '<b class="blue">O</b>'로 바뀝니다.</li>
					<li>- 문의글의 제목을 클릭하시면 문의내용과 답변글을 확인할 수 있습니다.</li>
					<li>- [광고 &middot; 제휴 문의] 작성시 분류 변경하는 방법 좌측메뉴의 분류관리에서 하실수 있습니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<div class="search ass_list">
					 <table class="">
						<colgroup>
							<col width="5%">
						</colgroup>
						<tbody>
							<tr>
								<th>기간</th>
								<td colspan="3">
									<label for=""><input type="checkbox">전체</label>
									<input type="text" class="input10"> ~ <input type="text" class="input10"> <button class="black day">오늘</button><button class="black day">이번주</button><button class="black day">이번달</button><button class="black day">1주일</button><button class="black day">15일</button><button class="black day">1개월</button><button class="black day">3개월</button><button class="black day">6개월</button>
								</td>
							</tr>
							<tr>
								<th>분류</th>
								<td>
									<select name="" id="">
										<option value="">전체</option>
									</select>
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

			<ul class="sub_category">
				<li class="on"><a href="">전체</a></li>	
				<li><a href="">광고문의</a></li>	
				<li><a href="">제휴문의</a></li>	
			</ul>
			<h6>광고 &middot; 제휴문의 관리
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
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="7%">
					<col width="6%">
					<col width="7%">
					<col width="7%">
					<col width="5%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" onClick=""></th>
						<th>제목</th>
						<th><a href="">작성자▼</a></th>
						<th><a href="">조회▼</a></th>
						<th><a href="">문의일▼</a></th>
						<th><a href="">답변여부▼</a></th>
						<th>답변</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value=""></td>
						<td class="tal"><b class="MAR5">[광고문의]</b><a href="" class="blue">일반문의합니다.</a><img src="../../images/ic/file.gif" alt="파일다운"><img src="../../images/ic/pic.gif" alt="사진"></td>
						<td>김이름</td>
						<td>2</td>
						<td>2013-10-31</td>
						<td><b class="blue">O</b> <br>X</td>
						<td><button class="gray common">Re 답변</button></td>
						<td><button class="gray common"><i class="axi axi-minus2"></i> 삭제</button></td>
					</tr>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
		</div>
		<!--//conbox-->


		<!-- 답글/게시물보기 팝업-->
		<div class="layer_pop conbox">
			<div class="h6wrap">
				<h6>광고 &middot; 제휴 문의 글</h6>
				<button class="close">X 창닫기</button>
			</div>
			<table>
				<colgroup>
					<col width="16%">
					<col width="%">
					<col width="16%">
					<col width="%">
				</colgroup>
				<tr>
					<th>제목</th>
					<td colspan="3">일반문의 합니다.</td>
				</tr>
				<tr>
					<th>담당자</th>
					<td>김이름</td>
					<th>작성일</th>
					<td>2013-11-01 09:56:07</td>
				</tr>
				<tr>
					<th>회사명</th>
					<td colspan="3">넷퓨</td>
				</tr>
				<tr>
					<th>고객ID</th>
					<td>gest</td>
					<th>이메일</th>
					<td>test@test.test <button class="common gray">이메일보내기</button></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td>010-000-00</td>
					<th>홈페이지</th>
					<td><a href="" class="blue">http</a></td>
				</tr>
				<tr>
					<th>내용</th>
					<td colspan="3">문의내용</td>
				</tr>
			</table>

			<div class="h6wrap">
				<h6>광고 &middot; 제휴문의 답변 글</h6>
			</div>
			<table>
				<colgroup>
					<col width="16%">
					<col width="%">
					<col width="16%">
					<col width="%">
				</colgroup>
				<tr>
					<th>작성자</th>
					<td><input type="text" class="input10"></td>
				</tr>
				<tr>
					<th>내용</th>
					<td>에디터</td>
				</tr>
			</table>
			<div class="pop_btn">
				<button class=" blue">저장하기</button>
				<button class="gray">창닫기</button>
			</div>
		</div>
		<!--//답글/게시물보기 팝업-->

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->