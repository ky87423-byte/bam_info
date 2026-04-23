<?php include '../include/html_top.php'; ?> <!--html_top-->
<?php include '../include/header.php'; ?> <!--관리자 탑메뉴-->


<!-- 인재정보 아이콘 등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 인재정보 상위상품을 구매한 고객이 선택할 수있는 아이콘을 등록하는 페이지입니다. </li>
				</ul>
			</div>

			
			<h6>인재정보 아이콘 등록</h6>
			<div class="table_top_btn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" url="" mode="" tag="" check_code="" class="blue"><strong>+</strong> 아이콘등록</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="5%">
					<col width="">
					<col width="12%">
				</colgroup>
				<tr>
					<th><input type="checkbox" name="check_all" onClick=""></th>
					<th>출력순서</th>
					<th>사용여부</th>
					<th>아이콘이미지</th>
					<th>편집</th>
				</tr>
				<tr class="tac">
					<td><input type="checkbox"></td>
					<td><input type="text"></td>
					<td><input type="checkbox"></td>
					<td><img src="../../images/injae_icon2.jpg" alt=""></td>
					<td>
						<button class="gray common"><i class="axi axi-plus2"></i> 수정하기</button>
						<button class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
					</td>
				</tr>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" url="" mode="" tag="" check_code="" class="blue"><strong>+</strong> 아이콘등록</button>
			</div>
		</div>
		<!--//conbox-->

		<!-- 아이콘 등록 팝업-->
		<div class="layer_pop conbox">
			<div class="h6wrap">
				<h6>아이콘 등록</h6>
				<button class="close">X 창닫기</button>
			</div>
			<div class="text_info">
				<b>GIF, JPEG, JPG, PNG</b> 파일형식으로 <b>100KB 용량 이내</b>의 파일을 권장합니다.
			</div>
			<input type="file">
			<div class="pop_btn">
				<button class="blue">적용</button>
				<button class="gray">닫기</button>
			</div>
		</div>
		<!--// 아이콘 등록 팝업-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->