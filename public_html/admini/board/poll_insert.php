<?php
$top_menu_code = '600302';
include '../include/header.php';

$poll_row = $db->query_fetch("select * from nf_poll where `no`=".intval($_GET['no']));
$poll_content = $nf_util->get_unse($poll_row['poll_content']);
?>

<!-- 설문조사 등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<form name="fwrite" action="../regist.php" method="post">
			<input type="hidden" name="mode" value="poll_write" />
			<input type="hidden" name="no" value="<?php echo intval($poll_row['no']);?>" />
			<h6>설문조사 등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>투표기간</th>
					<td>
						<input type="text" name="poll_wdate" value="<?php echo $poll_row['poll_wdate'];?>" class="input10 datepicker_inp_enddate"> ~
						<input type="text" name="poll_edate" value="<?php echo $poll_row['poll_edate'];?>" class="input10 datepicker_inp_enddate">
					</td>
				</tr>
				<tr>
					<th>투표자</th>
					<td>
						<label><input type="radio" name="poll_member" value="1" checked>회원(로그인후 투표)</label>
						<label><input type="radio" name="poll_member" value="0" <?php echo $poll_row && !$poll_row['poll_member'] ? 'checked' : '';?>>회원 + 비회원</label>
					</td>
				</tr>
				<tr>
					<th>중복투표여부</th>
					<td>
						<label><input type="radio" name="poll_overlap" value="0" checked>중복투표 불가</label>
						<label><input type="radio" name="poll_overlap" value="1" <?php echo $poll_row['poll_overlap'] ? 'checked' : '';?>>중복투표 가능</label>
					</td>
				</tr>
				<tr>
					<th>주제</th>
					<td>
						<input type="text" name="poll_subject" value="<?php echo $nf_util->get_html($poll_row['poll_subject']);?>">
					</td>
				</tr>
				<tr>
					<th>응답항목</th>
					<td>
						<ul class="paste-body-">
							<?php
							$length = @count($poll_content);
							if($length<=0) $length = 1;
							for($i=0; $i<$length; $i++) {
							?>
							<li class="MAB5"><em class="count-"><?php echo $i+1;?></em>. <input type="text" name="content[]" value="<?php echo $nf_util->get_html($poll_content[$i]);?>"><button type="button" class="basebtn gray MAL5" onClick="nf_util.clone_paste(this, 'li')"><b>+</b> <?php echo $i===0 ? '추가' : '제거';?></button></li>
							<?php
							}?>
						</ul>
					</td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $poll_row ? '수정' : '등록';?>하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->