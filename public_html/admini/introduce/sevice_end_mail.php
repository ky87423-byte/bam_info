<?php
$top_menu_code = '100205';
include '../include/header.php';
?>


<!--기본정보설정-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="config_index conbox">
		<div class="guide">
			<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
			<ul>
				<li>
					- 해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide1-6','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button>
				</li>
				<li>
					- 상품의 서비스기간이 만료되기전 회원에게 체크된 일자에 자동으로 마감 안내 메일을 발송 할수있습니다. 
				</li>
			</ul>
		</div>	

		<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="service_end_email_update" />

		<div class="conbox">
			<h6>상품 서비스 마감 안내 메일 설정</h6>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>서비스마감 안내메일 사용여부</th>
					<td>
						<label><input type="radio" name="use_service_end_email" value="1" checked>사용함</label>
						<label><input type="radio" name="use_service_end_email" value="0" <?php echo !$env['use_service_end_email'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
				<tr>
					<th>안내메일 발송일</th>
					<td>
						<?php
						$end_date_arr = array('1 day'=>'1일전', '2 day'=>'2일전', '3 day'=>'3일전', '4 day'=>'4일전', '5 day'=>'5일전', '6 day'=>'6일전', '7 day'=>'7일전', '15 day'=>'15일전', '30 day'=>'30일전');
						if(is_Array($end_date_arr)) { foreach($end_date_arr as $k=>$v) {
							$checked = in_array($k, $env['service_end_email_arr']) ? 'checked' : '';
						?>
						<label><input type="checkbox" name="service_end_email[]" <?php echo $checked;?> value="<?php echo $k;?>"><?php echo $v;?></label>
						<?php
						} }
						?>
					</td>
				</tr>
				<tr>
					<th>안내메일 메일링 스킨관리</th>
					<td><button class="gray s_basebtn2" type="button" onClick="location.href='../design/mail_skin.php?code=shop_end'">스킨관리 페이지로 이동</button></td>
				</tr>
			</table>


			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


		</div>
		</form>
		<!--//conbox-->
	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->