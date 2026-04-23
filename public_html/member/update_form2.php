<!--header_meta-->
<?php include '../include/header_meta.php'; ?>
<!--header_wrap-->
<?php include '../include/header.php'; ?>
<!--사이드 배너-->
<?php include '../include/scroll_banner.php'; ?>

<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>개인정보 수정<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<div class="wrap1260 my_sub">
	<section class="sub">
		<!--개인서비스 왼쪽 메뉴-->
		<?php
		$left_on['update_form'] = 'on';
		include '../include/indi_leftmenu.php';
		?>
		<div class="subcon_area">
			<section class="register pass_check">
				<p class="s_title">비밀번호 확인</p>
				<ul class="help_text">
					<li>회원님의 정보를 안전하게 보호하기 위해 비밀번호를 다시 한 번 입력해 주세요.</li>
				</ul>
				<table class="style1">
					<tr>
						<th>회원아이디<i class="axi axi-ion-android-checkmark""></i></th>
						<td>testid</td>
					</tr>
					<tr>
						<th>비밀번호확인</th>
						<td><input type="text"><em>* 비밀번호를 입력해 주십시오.</em></td>
					</tr>
				</table>

				<h1 style="margin-top:4rem">개인정보 수정</h1>
				<table class="style1">
					<tbody>
						<tr>
							<th>회원아이디<i class="axi axi-ion-android-checkmark"></i></th>
							<td>testid</td>
						</tr>
						<tr>
							<th>이름<i class="axi axi-ion-android-checkmark"></i></th>
							<td><input type="text"><em>* 실명인증 사용을 위해 실명을 입력해주세요.</em></td>
						</tr>
						<tr>
							<th>생년월일/성별<i class="axi axi-ion-android-checkmark"></i></th>
							<td>
								<select title="년도선택" name="" id="" required hname="생년">
									<option value="">년도</option>
									<option value="">2000</option>
								</select>
								<select title="월선택" name="" id="" required hname="생월">
									<option value="">월</option>
									<option value="">01</option>
								</select>
								<select title="일선택" name="" id="" required hname="생일">
									<option value="">일</option>
									<option value="">01</option>
								</select>
								<label for="mb_gender_0"  name="mb_gender" class="" ><input type="radio" id="mb_gender_0" name="logo">남</label>
								<label for="mb_gender_1"  name="mb_gender" class="" ><input type="radio" id="mb_gender_1" name="logo">여</label>
							</td>
						</tr>
						<tr>
							<th>닉네임<i class="axi axi-ion-android-checkmark"></i></th>
							<td><input type="text"><button class="base2">중복확인</button><em> * 커뮤니티(게시판)등 익명성이 필요한 곳에서 사용됩니다.</em></td>
						</tr>
						<tr>
							<th>연락처<i class="axi axi-ion-android-checkmark"></i></th>
							<td class="size1"><input type="text"> - <input type="text"> - <input type="text"><em>* 채용정보에 사용됩니다. 실제 정보를 입력해주세요</em></td>
						</tr>
						<tr>
							<th>이메일<i class="axi axi-ion-android-checkmark"></i></th>
							<td><input type="text"> @ <input type="text">
								<select title="일선택" name="" id="" required hname="생일">
									<option value="">직접입력</option>
									<option value="">naver.com</option>
								</select>
								<em>* 채용정보에 사용됩니다. 실제 정보를 입력해주세요</em>
							</td>
						</tr>
						<tr>
							<th>휴대폰</th>
							<td class="size1"><input type="text"> - <input type="text"> - <input type="text"></td>
						</tr>
						<tr>
							<th>주소</th>
							<td><input type="text"><button class="base2">우편번호 검색</button><br>
								<input type="text"><input type="text">
							</td>
						</tr>
						<tr>
							<th>홈페이지</th>
							<td><input type="text"></td>
						</tr>
						<tr>
							<th>SMS수신동의</th>
							<td><label for="consent1" class="checkstyle1" ><input type="checkbox" id="consent1">취업/채용관련 소식 등의 SMS수신</label></td>
						</tr>
						<tr>
							<th>이메일수신동의</th>
							<td><label for="consent2" class="checkstyle1" ><input type="checkbox" id="consent2">채용정보 등의 이메일 수신</label></td>
						</tr>
						<tr>
							<th>쪽지수신동의</th>
							<td><label for="consent3" class="checkstyle1" ><input type="checkbox" id="consent3">회원간의 쪽지 수신</label></td>
						</tr>
					</tbody>
				</table>
				<div class="next_btn">
					<button class="base">수정하기</button>
				</div>
			</section>
		</div>
	</section>
</div>
<!--푸터영역-->
<?php include '../include/footer.php'; ?>
