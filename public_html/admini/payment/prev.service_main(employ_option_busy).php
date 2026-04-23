<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->


<!-- 서비스별금액설정(급구) -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 유료 상품 사용여부 및 금액/기간을 설정하는 페이지입니다.</li>
					<li>- 각각의 유료상품 서비스별 유무료설정 및 기간, 할인율등을 설정하실수 있습니다.</li>
					<li>- 채용, 이력서 옵션서비스에서 아이콘은 직접 등록/수정/삭제 가능하며, 형광펜은 색상 설정이 가능합니다.</li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="10%">
					</colgroup>
					<tr>
						<th>메인 페이지 채용정보</th>
						<td>
							<ul>
								<li class="on"><a href="./service_main.php">플래티넘</a></li>
								<li><a href="#">프라임</a></li>
								<li><a href="#">그랜드</a></li>
								<li><a href="#">배너형</a></li>
								<li><a href="#">리스트형</a></li>
								<li><a href="#">일반리스트</a></li>
								<li><a href="#">테두리강조</a></li>
						</td>
					</tr>
					<tr>
						<th>메인 페이지 인재정보</th>
						<td>
							<ul>
								<li><a href="#">포커스인재정보</a></li>
								<li><a href="#">플러스인재정보</a></li>
								<li><a href="#">일반리스트</a></li>
								<li><a href="#">테두리강조</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>서브 페이지 채용정보</th>
						<td>
							<ul>
								<li><a href="#">플래티넘</a></li>
								<li><a href="#">배너형</a></li>
								<li><a href="#">리스트형</a></li>
								<li><a href="#">일반리스트</a></li>
								<li><a href="#">테두리강조</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>서브 페이지 인재정보</th>
						<td>
							<ul>
								<li><a href="#">포커스인재정보</a></li>
								<li><a href="#">플러스인재정보</a></li>
								<li><a href="#">일반리스트</a></li>
								<li><a href="#">테두리강조</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>채용정보 옵션서비스</th>
						<td>
							<ul>
								<li><a href="./service_main(employ_option_busy).php">급구</a></li>
								<li><a href="./service_main(employ_option_neon).php">형광펜</a></li>
								<li><a href="#">굵은글자</a></li>
								<li><a href="./service_main(employ_option_icon).php">아이콘</a></li>
								<li><a href="./service_main(employ_option_color).php">글자색</a></li>
								<li><a href="#">반짝칼라</a></li>
								<li><a href="#">점프</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>인재정보 옵션서비스</th>
						<td>
							<ul>
								<li><a href="#">급구</a></li>
								<li><a href="#">형광펜</a></li>
								<li><a href="#">굵은글자</a></li>
								<li><a href="#">아이콘</a></li>
								<li><a href="#">글자색</a></li>
								<li><a href="#">반짝칼라</a></li>
								<li><a href="#">점프</a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>기타 서비스</th>
						<td>
							<ul>
								<li><a href="#">이력서열람권</a></li>
								<li><a href="#">채용공고열람권</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>

			
			<h6>채용정보 옵션서비스 > 급구</h6>
			<table class="">
				<colgroup>
					<col width="10%">
					<col width="%">
				</colgroup>
				<tr>
					<th>사용/미사용 설정</th>
					<td>
						<label for=""><input type="radio">사용</label>
						<label for=""><input type="radio">미사용</label>
					</td>
				</tr>
				<tr>
					<th>서비스 설정</th>
					<td>
						<input type="text" class="input5" placeholder="0">
						<select name="" id="" class="select5">
							<option value="">일</option>
						</select>
						<input type="text" class="input5 MAL10" placeholder="0">원,
						할인율 <input type="text" class="input5" placeholder="0"> %
						<button class="blue common"><strong>+</strong> 등록 or 수정</button> <!--리스트 수정버튼 눌렀을때, '수정'텍스트로 변환-->
						<span>* 금액을 '0'으로 등록시 무료로 설정 됩니다</span>
					</td>
				</tr>
				<tr>
					<th>서비스금액</th>
					<td>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="40">
								<col width="40">
								<col width="5%">
								<col width="10%">
							</colgroup>
							<tr>
								<th class="tac">순서</th>
								<th class="tac">설정값</th>
								<th class="tac">금액</th>
								<th class="tac">할인율</th>
								<th class="tac">편집</th>
							</tr>
							<tr>
								<td><input type="text"></td>
								<td>1일</td>
								<td><b class="red">무료</b>, 30,000원 , <span class="line-through">120,000</span> => 108,000원</td>
								<td>0%</td>
								<td>
									<button class="common gray"><i class="axi axi-plus2"></i>수정하기</button>
									<button class="common gray"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<h6>채용정보 옵션서비스 > 급구 > 아이콘설정</h6>
			<table>
				<tr>
					<th>현재 등록된 아이콘</th>
					<td><img src="../../images/icon_hurry.gif" alt=""></td>
				</tr><tr>
					<th>아이콘 업로드</th>
					<td><input type="file" class="input20"><button class="s_basebtn2 gray MAL5">등록</button> <span>* 권장 사이즈 : 17px * 16px , 업로드 확장자 : *.jpg, *.gif, *.png</span></td>
				</tr>
			</table>
		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->