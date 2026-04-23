<?php
$top_menu_code = "200303";
include_once "../include/header.php";  // 관리자 공통 헤더

$admin_row = $db->query_fetch("select * from nf_admin where `no`=".intval($_GET['no']));
$get_sadmin = $nf_admin->get_sadmin($admin_row['wr_id']);
?>
<script type="text/javascript">
$(function(){
	var form = document.forms['fwrite'];
	$(form).find("[type=checkbox]").click(function(){
		var get_k = $(this).attr("k");
		if(get_k) {
			switch(get_k) {
				case "top":
					$(this).closest("tbody").find("[type=checkbox]").prop("checked", $(this)[0].checked);
				break;

				default:
					var first_checked = $(this).closest("tbody").find("[type=checkbox]").eq(0)[0].checked;
					$(this).closest("tbody").find(".menu_"+get_k+"_").find("[type=checkbox]").prop("checked", $(this)[0].checked);
					$(this).closest("tbody").find("[type=checkbox]").eq(0).prop("checked", first_checked);
				break;
			}
		}
	});
});
</script>
<!-- 부관리자등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="sadmin_write" />
			<input type="hidden" name="no" value="<?php echo $admin_row['no'];?>" />
			<h6>부관리자 설정<span>부관리자로 등록하실 정보를 입력해주세요</span></h6>
			<table>
				<colgroup>
					<col width="">
				</colgroup>
				<table>
					<tr>
						<th>관리자아이디</th>
						<td>
							<?php if($_GET['no']) {?>
							<?php echo $admin_row['wr_id'];?>
							<?php } else {?>
							<input type="text" name="wr_id" value="<?php echo $nf_util->get_html($admin_row['wr_id']);?>" maxbyte="20" needed hname="관리자아이디">
							<?php }?>
						</td>
						<th>관리자명</th>
						<td><input type="text" name="wr_name" value="<?php echo $nf_util->get_html($admin_row['wr_name']);?>" maxbyte="100" needed hname="관리자명"></td>
						<th>관리자닉네임</th>
						<td><input type="text" name="wr_nick" value="<?php echo $nf_util->get_html($admin_row['wr_nick']);?>" maxbyte="100" needed hname="관리자닉네임"></td>
					</tr>
					<tr>
						<th>비밀번호</th>
						<td><input type="text" name="wr_password" value="" minbyte="6" maxbyte="20" <?php echo $admin_row ? '' : 'needed';?> hname="비밀번호"></td>
						<th>비밀번호확인</th>
						<td><input type="text" name="wr_password2" value="" minbyte="6" matching="wr_password" maxbyte="20" <?php echo $admin_row ? '' : 'needed';?> hname="비밀번호"></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</table>

			<h6>부관리자 권한 지정<span>부관리자가 사용가능한 메뉴를 선택해주세요</span></h6>
			<table class="table3">
				<colgroup>
					<col width="12%">
					<col width="12%">
				</colgroup>
				<?php
				$count = 0;
				if(is_array($_menu_array_)) { foreach($_menu_array_ as $k=>$v) {
				?>
				<tbody>
					<?php
					$count2 = 0;
					if(is_array($v['menus'])) { foreach($v['menus'] as $k2=>$v2) {
						$count_int = $_menu_array_count_[$k];
						$count_int2 = $_menu_array_count_[$v2['code']];
						$count3 = 0;

						if(is_array($v2['sub_menu'])) { foreach($v2['sub_menu'] as $k3=>$v3) {
					?>
					<tr class="menu_<?php echo $k2;?>_">
						<?php
						if($count2===0) {
							$checked = @in_array($k, $get_sadmin['admin_menu_array']) ? 'checked' : '';
						?>
							<th rowspan="<?php echo $count_int;?>" class="tac"><label><input type="checkbox" name="admin_menu[]" k="top" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $_top_menus_[$k.'000'];?></label></th>
						<?php
						}?>
						<?php
						if($count3===0) {
							$checked = @in_array($v2['code'], $get_sadmin['admin_menu_array']) ? 'checked' : '';
						?>
							<td rowspan="<?php echo $count_int2;?>" class="tac"><b><label><input type="checkbox" name="admin_menu[]" k="<?php echo $k2;?>" value="<?php echo $v2['code'];?>" <?php echo $checked;?>><?php echo $v2['name'];?></label></b></td>
						<?php
						}
							$checked = $k3 && @in_array($k3, $get_sadmin['admin_menu_array']) ? 'checked' : '';
						?>
						<td><label><input type="checkbox" name="admin_menu[]" value="<?php echo $k3;?>" <?php echo $checked;?>><?php echo $v3['name'];?></label> <span>[ <?php echo $v3['url'];?> ]</span></td>
					</tr>
					<?php
							$count++;
							$count2++;
							$count3++;
						} }
					} }?>
				</tbody>
				<?php
				} }
				?>

				<!--tr>
					<th rowspan="14"><input type="checkbox">구인구직관리</th>	
					<td rowspan="8"><input type="checkbox">채용공고 관리</td>
					<td><input type="checkbox">전체 채용공고 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">진행중인 채용공고 </td>
				</tr>
				<tr>
					<td><input type="checkbox">마감된 채용공고</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용공고 등록</td>
				</tr>
				<tr>
					<td><input type="checkbox">신고 공고 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">서비스기간 만료 채용공고</td>
				</tr>
				<tr>
					<td><input type="checkbox">입사지원 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용공고 스크랩 관리</td>
				</tr>
				<tr>
					<td rowspan="6"><input type="checkbox">이력서 관리</td>
					<td><input type="checkbox">이력서 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">이력서 등록 </td>
				</tr>
				<tr>
					<td><input type="checkbox">신고 이력서 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">서비스기간 만료 이력서 </td>
				</tr>
				<tr>
					<td><input type="checkbox">이력서 스크랩 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">첨부파일 관리 </td>
				</tr>
				<tr>
					<th rowspan="23"><input type="checkbox">환경설정</th>	
					<td rowspan="8"><input type="checkbox">사이트관리</td>
					<td><input type="checkbox">기본정보설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">사이트소개 </td>
				</tr>
				<tr>
					<td><input type="checkbox">회원약관</td>
				</tr>
				<tr>
					<td><input type="checkbox">개인정보취급방침</td>
				</tr>
				<tr>
					<td><input type="checkbox">게시판관리기준</td>
				</tr>
				<tr>
					<td><input type="checkbox">이메일무단수집거부</td>
				</tr>
				<tr>
					<td><input type="checkbox">사이트하단</td>
				</tr>
				<tr>
					<td><input type="checkbox">메일하단</td>
				</tr>
				<tr>
					<td rowspan="4"><input type="checkbox">분류관리</td>
					<td><input type="checkbox">공통적용 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원가입 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용정보 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">인재정보 분류</td>
				</tr>
				<tr>
					<td rowspan="2"><input type="checkbox">서비스/출력수 설정</td>
					<td><input type="checkbox">SMS 환경설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">지도설정</td>
				</tr>
				<tr>
					<td rowspan="6"><input type="checkbox">등록폼 관리</td>
					<td><input type="checkbox">기업회원 가입폼 설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용정보 항목 설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">이력서 항목 설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원 탈퇴요청 사유</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용공고 신고 사유</td>
				</tr>
				<tr>
					<td><input type="checkbox">이력서 신고 사유</td>
				</tr>
				<tr>
					<td rowspan="3"><input type="checkbox">운영자관리</td>
					<td><input type="checkbox">관리자정보설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">부관리자관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">부관리자등록</td>
				</tr>
				<tr>
					<th rowspan="18"><input type="checkbox">회원관리</th>	
					<td rowspan="7"><input type="checkbox">회원종합관리</td>
					<td><input type="checkbox">전체회원관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">불량회원관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">탈퇴요청관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">탈퇴회원관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원등급/포인트설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원포인트관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원간쪽지발송내역</td>
				</tr>
				<tr>
					<td rowspan="4"><input type="checkbox">회원별맞춤메일링</td>
					<td><input type="checkbox">정기메일링설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">정기메일링발송내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">맞춤인재정보관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">맞춤채용정보관리</td>
				</tr>
				<tr>
					<td rowspan="3"><input type="checkbox">기업회원관리</td>
					<td><input type="checkbox">기업회원관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">기업회원등록</td>
				</tr>
				<tr>
					<td><input type="checkbox">기업정보관리</td>
				</tr>
				<tr>
					<td rowspan="2"><input type="checkbox">개인회원관리</td>
					<td><input type="checkbox">개인회원관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">개인회원등록</td>
				</tr>
				<tr>
					<td rowspan="2"><input type="checkbox">회원CRM관리</td>
					<td><input type="checkbox">회원MAIL발송</td>
				</tr>
				<tr>
					<td><input type="checkbox">회원SMS발송</td>
				</tr>
				<tr>
					<th rowspan="8"><input type="checkbox">디자인관리</th>
					<td rowspan="3"><input type="checkbox">기본디자인관리</td>
					<td><input type="checkbox">사이트디자인설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">사이트로고설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">채용공고기본로고</td>
				</tr>
				<tr>
					<td rowspan="5"><input type="checkbox">개별디자인관리</td>
					<td><input type="checkbox">배너관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">팝업관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">팝업등록</td>
				</tr>
				<tr>
					<td><input type="checkbox">팝업스킨관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">MAIL스킨관리</td>
				</tr>
				<tr>
					<th rowspan="13"><input type="checkbox">결제관리</th>
					<td rowspan="4"><input type="checkbox">결제환경관리</td>
					<td><input type="checkbox">결제환경설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">무통장입금계좌설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">결제페이지설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">서비스별금액설정</td>
				</tr>
				<tr>
					<td rowspan="2"><input type="checkbox">패키지 결제관리</td>
					<td><input type="checkbox">채용공고패키지설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">이력서패키지설정</td>
				</tr>
				<tr>
					<td rowspan="7"><input type="checkbox">결제관리</td>
					<td><input type="checkbox">결제통합관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">결제대기내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">결제완료내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">취소요청내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">취소완료내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">세금계산서신청내역</td>
				</tr>
				<tr>
					<td><input type="checkbox">현금영수증신청내역</td>
				</tr>
				<tr>
					<th rowspan="15"><input type="checkbox">커뮤니티</td>
					<td rowspan="4"><input type="checkbox">게시판관리</td>
					<td><input type="checkbox">게시판관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">게시물관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">게시판메인설정</td>
				</tr>
				<tr>
					<td><input type="checkbox">메인게시판출력설정</td>
				</tr>
				<tr>
					<td rowspan="2"><input type="checkbox">설문조사관리</td>
					<td><input type="checkbox">설문조사관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">설문조사등록</td>
				</tr>
				<tr>
					<td rowspan="5"><input type="checkbox">운영자관리</td>
					<td><input type="checkbox">공지사항관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">공지사항등록</td>
				</tr>
				<tr>
					<td><input type="checkbox">고객문의 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">광고문의 관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">제휴문의 관리</td>
				</tr>
				<tr>
					<td rowspan="4"><input type="checkbox">분류 관리</td>
					<td><input type="checkbox">공지사항 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">고객문의 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">광고문의 분류</td>
				</tr>
				<tr>
					<td><input type="checkbox">제휴문의 분류</td>
				</tr>
				<tr>
					<th rowspan="5"><input type="checkbox">통계관리</th>
					<td rowspan="2"><input type="checkbox">통계현황</td>
					<td><input type="checkbox">접속통계</td>
				</tr>
				<tr>
					<td><input type="checkbox">구글로그분석</td>
				</tr>
				<tr>
					<td rowspan="3"><input type="checkbox">검색어현황</td>
					<td><input type="checkbox">검색어통계</td>
				</tr>
				<tr>
					<td><input type="checkbox">인기검색어관리</td>
				</tr>
				<tr>
					<td><input type="checkbox">실시간검색어관리</td>
				</tr>
				<tr>
					<th><input type="checkbox">모바일웹</th>
					<td><input type="checkbox">모바일웹</td>
					<td><input type="checkbox">모바일웹 설정</td>
				</tr-->
			</table>
			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $admin_row['no'] ? '수정하기' : '저장하기';?></button>
				<button type="button" class="cancel_btn">돌아가기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->