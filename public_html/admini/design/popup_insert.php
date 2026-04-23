<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = "400205";
include '../include/header.php';


$popup_row = $db->query_fetch("select * from nf_popup where `no`=".intval($_GET['no']));
if($popup_row) {
	$popup_begin_h = date("H", strtotime($popup_row['popup_begin']));
	$popup_end_h = date("H", strtotime($popup_row['popup_end']));
}
if(!$popup_row['popup_begin']) $popup_begin_h = "00";
if(!$popup_row['popup_end']) $popup_end_h = "00";
?>
<style type="text/css">
input[type=text]:disabled { background:#f5f5f5; }
select { min-width:50px !important; }
</style>
<!-- 팝업등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 사이트접속시 열리는 첫화면 즉, 메인페이지에서 설정하신 팝업이 뜨게 됩니다.</li>
					<li>- 팝업기능을 사용하지 않으려면 전체선택후 '미출력'버튼을 클릭하시면 됩니다.</li>
					<li>- 출력할 팝업이 여러개라면 팝업순서에 따라 팝업의 출력 순서가 바뀝니다.</li>
				</ul>
			</div>

			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="popup_write" />
			<input type="hidden" name="no" value="<?php echo $popup_row['no'];?>" />
			<h6>팝업등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>팝업제목</th>
					<td>
						<input type="text" name="popup_title" value="<?php echo $nf_util->get_html($popup_row['popup_title']);?>" >
						<label><input type="checkbox" name="popup_title_view" value="1" <?php echo $popup_row['popup_title_view'] ? 'checked' : '';?>>출력</label>
						출력시 팝업창 상단에 팝업제목 노출 (20자 이내)
					</td>
				</tr>
				<tr>
					<th>메인 출력</th>
					<td>
						<label><input type="radio" name="popup_view" value="1" checked>출력</label>
						<label><input type="radio" name="popup_view" value="0" <?php echo !$popup_row['popup_view'] ? 'checked' : '';?>>미출력</label>
					</td>
				</tr>
				<tr>
					<th>서브페이지 출력</th>
					<td>
						<label><input type="radio" name="popup_sub_view" value="1" checked>출력</label>
						<label><input type="radio" name="popup_sub_view" value="0" <?php echo !$popup_row['popup_sub_view'] ? 'checked' : '';?>>미출력</label>
					</td>
				</tr>
				<tr>
					<th>팝업크기</th>
					<td>가로 <input type="text" name="popup_width" value="<?php echo $nf_util->get_html($popup_row['popup_width']);?>" class="input10"> px, 세로 <input type="text" name="popup_height" value="<?php echo $nf_util->get_html($popup_row['popup_height']);?>" class="input10"> px <span class="MAL10">*운영체제에 따라 실제 출력크기와 다를 수 있음</span></td>
				</tr>
				<tr>
					<th>팝업위치</th>
					<td>상단 <input type="text" name="popup_top" value="<?php echo $nf_util->get_html($popup_row['popup_top']);?>" class="input10"> px, 왼쪽 <input type="text" name="popup_left" value="<?php echo $nf_util->get_html($popup_row['popup_left']);?>" class="input10"> px <span class="MAL10">*새창 사용시 브라우저의 좌측상단 끝이 0,0 이며, 레이어 사용시 웹페이지 내용부터 계산</span></td>
				</tr>
				<tr>
					<th>출력기간</th>
					<td>시작일
						<input type="text" name="popup_begin" value="<?php echo $nf_util->get_html(substr($popup_row['popup_begin'],0,10));?>" class="input10 datepicker_inp_enddate">
						<select name="popup_begin_time">
						<?php
						for($i=0; $i<=23; $i++) {
							$int = sprintf("%02d", $i);
							$selected = $popup_begin_h==$int ? 'selected' : '';
						?>
						<option value="<?php echo $int;?>" <?php echo $selected;?>><?php echo $int;?></option>
						<?php }?>
						</select>
						~ 종료일
						<input type="text" name="popup_end" value="<?php echo $nf_util->get_html(substr($popup_row['popup_end'],0,10));?>" class="input10 datepicker_inp_enddate">
						<select name="popup_end_time">
						<?php
						for($i=0; $i<=23; $i++) {
							$int = sprintf("%02d", $i);
							$selected = $popup_end_h==$int ? 'selected' : '';
						?>
						<option value="<?php echo $int;?>" <?php echo $selected;?>><?php echo $int;?></option>
						<?php }?>
						</select>
						<label><input type="checkbox" name="popup_unlimit" <?php echo $popup_row['popup_unlimit'] ? 'checked' : '';?> value="1">제한없음</label>
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td><textarea type="editor" name="popup_content" style="height:300px;"><?php echo stripslashes($popup_row['popup_content']);?></textarea></td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $popup_row ? '수정' : '등록';?>하기</button>
				<button type="button" class="cancel_btn">취소하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->