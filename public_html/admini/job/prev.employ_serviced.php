<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->


<!--서비스기간 만료 채용공고-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 해당 페이지는 마감된 채용공고 리스트입니다.</li>
					<li>- 해당 공고의 서비스/기간등을 수정하시고 싶으실때는 편집에 서비스승인 버튼을 클릭해주시면 됩니다.</li>
					<li>- 등록하기 버튼은 관리자가 직접 해당회원의 새로운 채용공고를 등록하는 기능입니다.</li>
					<li>- 복사하기 버튼은 해당 채용공고를 복사하여 수정후 새로운 채용공고로 등록하는 기능입니다.</li>
					<li>- 유료로 현재 노출되고 있는 공고는 서비스/기간 부분에 서비스보기 버튼이 생성이 됩니다. 서비스보기를 누르시면 현재 진행중인 유료상품기간이 노출됩니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<div class="search">
					 <table>
						<colgroup>
							<col width="8%">
							<col width="42%">
							<col width="8%">
							<col width="42%">
						</colgroup>
						<tbody>
							<tr>
								<th>등록일</th>
								<td colspan="3">
									<label for=""><input type="checkbox">전체</label>
									<input type="text" class="input10"> ~ <input type="text" class="input10"> <button class="black day">오늘</button><button class="black day">이번주</button><button class="black day">이번달</button><button class="black day">1주일</button><button class="black day">15일</button><button class="black day">1개월</button><button class="black day">3개월</button><button class="black day">6개월</button>
								</td>
							</tr>
							<tr>
								<th>서비스</th>
								<td colspan="3">
									<label for=""><input type="checkbox">메인플래티넘</label>
									<label for=""><input type="checkbox">서브플래티넘</label>
									<label for=""><input type="checkbox">메인프라임</label>
									<label for=""><input type="checkbox">메인그랜드</label>
								</td>
							</tr>
							<tr>
								<th>직종</th>
								<td>
									<select name="" id="">
										<option value="">= 1차 직종선택 =</option>
									</select>
									<select name="" id="">
										<option value="">= 2차 직종선택 =</option>
									</select>
									<select name="" id="">
										<option value="">= 3차 직종선택 =</option>
									</select>
								</td>
								<th>지역</th>
								<td>
									<select name="" id="">
										<option value="">-- 시·도 --</option>
									</select>
									<select name="" id="">
										<option value="">-- 시·군·구 --</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>경력</th>
								<td>
									<label for=""><input type="radio">신입</label>
									<label for=""><input type="radio">경력</label>
									<label for=""><input type="checkbox">무관포함</label>
								</td>
								<th>성별</th>
								<td>
									<label for=""><input type="radio">무관</label>
									<label for=""><input type="radio">신입</label>
									<label for=""><input type="radio">경력</label>
								</td>
							</tr>
							<tr>
								<th>학력</th>
								<td>
									<label for=""><input type="checkbox">학력부관</label>
									<label for=""><input type="checkbox">중학교졸업</label>
								</td>
								<th>채용마감</th>
								<td>
									<input type="text">
									<label for=""><input type="checkbox">상시모집</label>
									<label for=""><input type="checkbox">채용시까지</label>
								</td>
							</tr>
							<tr>
								<th>접수방법</th>
								<td colspan="3">
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
							<option value="wr_name">이름</option>
							<option value="wr_id" >아이디</option>
						</select>
						<input type="text" name="search_keyword">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			
			<h6>정규직 정보 관리<span>총 <b>0</b>건이 검색되었습니다.</span>
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
				<button type="button" class="red">선택 서비스 승인</button>
				<button type="button" class="blue"><strong>+</strong> 공고등록</button></a>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="8%">
					<col width="15%">
					<col width="%">
					<col width="10%">
					<col width="18%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" onClick=""></th>
						<th><a href="">등급구분▼</a></th>
						<th>회원등급</th>
						<th>회사명/아이디/담당자명</th>
						<th colspan="2">채용정보</th>
						<th>서비스/기간</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value=""></td>
						<td >사용자<br><span class="gray">관리자</span></td>
						<td>일반회원<br><span class="gray">관리자</span>	</td>
						<td class="job_info tal">
							<a href="">
								<ul>
									<li><span>회사명</span> : 코리아엔</li>
									<li><span>아이디</span> : koreaen</li>
									<li><span>담당자</span> : 조현아대리</li>
								</ul>
							</a>
						</td>
						<td><a href="" class="blue">고객관리 상담 및 영업 모집</a></td>
						<td>
							<ul>
								<li>등록일 : 2020/11/11</li>
								<li>수정일 : 2020/11/11</li>
								<li>마감일 : 채용시까지</li>
								<li>조회 : 1건</li>
							</ul>
						</td>
						<td class="job_service_list">
							<button class="gray" style="color:#158fe7; padding:2px 3px; font-weight:700;"><i class="axi axi-plus2"></i> 서비스보기</button>
							<ul class="tal">
								<li><span>메인 플래티넘</span> : ~ 2020/01/01</li>
								<li><span>메인 프라임</span> : ~ 2020/01/01</li>
								<li><span>메인 프라임 로고강조</span> : ~ 2020/01/01</li>
								<li><span>메인 그랜드</span> : ~ 2020/01/01</li>
								<li><span>메인 배너형</span> : ~ 2020/01/01</li>
								<li><span>메인 리스트형</span> : ~ 2020/01/01</li>
								<li><span>메인 일반</span> : ~ 2020/01/01</li>
								<li><span>정규직 프래티넘</span> : ~ 2020/01/01</li>
								<li><span>정규직 배너형</span> : ~ 2020/01/01</li>
								<li><span>정규직 리스트형</span> : ~ 2020/01/01</li>
								<li><span>정규직 일반</span> : ~ 2020/01/01</li>
								<li><span>굵은글자</span> : ~ 2020/01/01</li>
								<li><span>점프</span> : ~ 2020/01/01</li>
							</ul>
							<span>서비스없음</span>
						</td>
						<td style="text-align:center">
							<button class="gray common"><i class="axi axi-plus2"></i> 수정하기</button>
							<button class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
							<button class="gray common"><i class="axi axi-pencil"></i> 등록하기</button>
							<button class="gray common"><i class="axi axi-content-copy"></i> 복사하기</button>
							<button class="red common">서비스 승인</button>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="red">선택 서비스 승인</button>
				<button type="button" class="blue"><strong>+</strong> 공고등록</button></a>
			</div>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->