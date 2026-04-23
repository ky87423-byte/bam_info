<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->

<!-- 사이트디자인설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="design_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 구인구직 사이트의 전반적인 색상, 레이아웃, 문구등을 수정 관리하는 페이지 입니다.</li>
				</ul>
			</div>
			
			
			<h6>사이트기본설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tbody>
					<tr>
						<th>사이트기본색</th>
						<td class="color_choice">
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_gr"></label> <!-- black.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_yel"></label> <!-- yellow.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_org"></label> <!-- orange.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_dho"></label> <!-- red1.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_red"></label> <!-- red2.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_pk"></label> <!-- pink.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_pp"></label> <!-- purple.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_lgr"></label> <!-- green1.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_grn"></label> <!-- green2.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_bgr"></label> <!-- greenblue.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_wbl"></label> <!-- white_blue.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_dbl"></label> <!-- blue.css -->
							<label for=""><input type="radio"><img src="../../images/comn/b.gif" alt="" class="grf_indi"></label> <!-- deep_blue.css -->
						</td>
					</tr>
					<tr>
						<th>채용공고 로고 설정</th>	
						<td>
							<table class="table3" style="width:auto;">
								<tr>
									<th><label for=""><input type="radio">텍스트로고</label></th>
									<th><label for=""><input type="radio">이미지로고</label></th>
									<th><label for=""><input type="radio">배경로고</label></th>
									<th><label for=""><input type="radio">텍스트로고 + 이미지로고 + 배경로고</label></th>
								</tr>
								<tr>
									<td>
										<dl>
											<dt class="MAB5"><span>* 텍스트로고 예시 _ 텍스트로고 형식만 사용합니다.</span></dt>
											<dd><img src="../../images/text_ex1.jpg" alt=""></dd>
										</dl>
									</td>
									<td>
										<dl>
											<dt class="MAB5"><span>* 이미지로고 예시 _ 이미지로고 형식만 사용합니다.</span></dt>
											<dd><img src="../../images/logo_ex1.jpg" alt=""></dd>
										</dl>
									</td>
									<td>
										<dl>
											<dt class="MAB5"><span>* 배경로고 예시 _ 배경로고 형식만 사용합니다.</span></dt>
											<dd><img src="../../images/bg_ex1.jpg" alt=""></dd>
										</dl>
									</td>
									<td>
										<dl>
											<dt class="MAB5"><span>* 텍스트로고, 이미지로고, 배경로고를 함께 사용합니다.</span></dt>
											<dt class="MAB5"><span>* 텍스트로고를 등록한 기업은 텍스트로고가 출력되고,  <br>&nbsp;&nbsp;&nbsp;이미지로고를 등록한 기업은 이미지로고가 출력되고, <br>&nbsp;&nbsp;&nbsp;배경로고를 등록한 기업은 배경로고가 출력되는 방식입니다.	</span></dt>
										</dl>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th>브랜드채용 사용 유무</th>
						<td>
							<label for=""><input type="radio">사용</label>
							<label for=""><input type="radio">미사용</label>
						</td>
					</tr>
					<tr>
						<th>지도검색 사용 유무</th>
						<td>
							<label for=""><input type="radio">사용</label>
							<label for=""><input type="radio">미사용</label>
						</td>
					</tr>
				</tbody>
			 </table>

			<h6>상단메뉴 설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">메뉴 스타일</th>
					<td>
						<label for=""><input type="radio"><b class="MAR10">Design 01</b><img src="../../images/admini/t_01.jpg" alt="스킨1"></label>
					</td>
				</tr>
				<tr>
					<td>
						<label for=""><input type="radio"><b class="MAR10">Design 02</b><img src="../../images/admini/t_02.jpg" alt="스킨2"></label>
					</td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


			<h6>메인 채용공고 서비스 출력설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">플래티넘 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력 </label><span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
										<option value="">6</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>플래티넘 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">프라임 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label> <span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
										<option value="">6</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>프라임 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">그랜드 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력 </label><span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
										<option value="">6</option>									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>그랜드 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">배너형 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력 </label><span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>배너형 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>


			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">리스트형 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력 </label><span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">2</option>
										<option value="">3</option>
										<option value="">4</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>리스트형 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>메인 최근 공고 리스트</th>
					<td><label for=""><input type="checkbox">출력</label> /   &nbsp;&nbsp;출력건수 <input type="text" class="input10">건 <span>출력 설정하시면 메인 페이지 하단에 최근 채용공고 리스트가 출력됩니다</span></td>
				</tr>
				<tr>
					<th>일반 채용 리스트 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


			<h6>메인 인재공고 서비스 출력설정</h6>
			<table >
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">포커스 인재정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">총출력수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td><input type="text" class="input10"> 건</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>포커스 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">플러스 인재정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">총출력수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td><input type="text" class="input10"> 건</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>플러스 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>메인 최근 인재 리스트</th>
					<td><label for=""><input type="checkbox">출력</label> /  &nbsp;&nbsp;출력건수 <input type="text" class="input10">건 <span>출력 설정하시면 메인 페이지 하단에 최근 이력서 리스트가 출력됩니다</span></td>
				</tr>
				<tr>
					<th>일반 인재 리스트 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>

			<h6>서브 채용정보 페이지 출력설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">플래티넘 채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label> <span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
										<option value="">6</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>플래티넘  서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">배너형  채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label> <span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">3</option>
										<option value="">4</option>
										<option value="">5</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>배너형   서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">리스트형  채용정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label> <span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">가로 출력건수 X 세로 출력건수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td>
									가로 
									<select name="" id="" >
										<option value="">2</option>
										<option value="">3</option>
										<option value="">4</option>
									</select>
									<b>X</b> 세로 
									<input type="text" class="input10" placeholder="숫자입력">
								</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>리스트형   서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>일반 채용 리스트 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


			<h6>서브 인재정보 페이지 출력설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">포커스 인재정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">총출력수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td><input type="text" class="input10"> 건</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>포커스 인재정보 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="2">플러스  인재정보</th>
					<td>
						<label for=""><input type="checkbox">출력</label>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">총출력수</th>
								<th class="tac" colspan="2">테두리칼라설정</th>
							</tr>
							<tr>
								<td><input type="text" class="input10"> 건</td>
								<td>일반 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button></td>
								<td>테두리강조상품 : <input type="text" class="input10"><button class="basebtn gray MAL5"><img src="../../images/admini/color_choice.png" alt="">색상표</button>	</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>플러스 인재정보  서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>


			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>일반 인재 리스트 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>




			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


			<h6>기타 서비스 안내 설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>급구채용 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
				<tr>
					<th>채용공고 점프 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
				<tr>
					<th>채용공고 열람 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>급구인재 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
				<tr>
					<th>이력서 점프 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
				<tr>
					<th>이력서 열람 서비스안내</th>
					<td><textarea name="" id="" cols="30" rows="10">에디터</textarea></td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>

		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->