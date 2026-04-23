<?php
$top_menu_code = "400301";
include '../include/header.php';
?>
<script type="text/javascript">
var upload_logo = function(el, code) {
	var form = document.forms['fwrite'];
	form.code.value = code;
	nf_util.ajax_submit(form);
}
</script>
<!-- 사이트로고설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="logo_set">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 로고등록시 등록된 권장사이즈를 기준으로 크게 벗어나지 않게 등록해주시고 현재 권장사이즈가 가장 적정사이즈라고 보시면 됩니다. </li>
					<li>- 메일로고는 메일 발송시 나타나는 로고 입니다. 홈페이지에 등록된 로고보다는 조금 작게 만들어주시는게 좋습니다.</li>
				</ul>
			</div>
			
			<form  name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="logo_write" />
			<input type="hidden" name="code" value="" />
			<div class="logo_wrap_box">
				<div class="logo_regist">
					<h6>상단로고</h6>
					<table>
						<colgroup>
						</colgroup>
						<tbody class="tac">
							<tr>
								<th class="tac">[ 권장사이즈 : 245px * 60px ]</th>
							</tr>
							<tr>
								<th><p><img src="<?php echo NFE_URL.'/data/logo/'.$env['logo_top'];?>?t=<?php echo time();?>" alt=""></p></th>
							</tr>
							<tr>
								<th class="tar"><input type="file" name="top" class="MAR5"><button type="button" class="s_basebtn2 gray" onClick="upload_logo(this, 'top')">등록</button></th>
							</tr>
						</tbody>
					 </table>
				 </div>

				<div class="logo_regist">
					<h6>메일상단로고</h6>
					<table>
						<colgroup>
						</colgroup>
						<tbody class="tac">
							<tr>
								<th class="tac">[ 권장사이즈 : 245px * 60px ]</th>
							</tr>
							<tr>
								<th><p><img src="<?php echo NFE_URL.'/data/logo/'.$env['logo_mail_top'];?>?t=<?php echo time();?>" alt=""></p></th>
							</tr>
							<tr>
								<th class="tar"><input type="file" name="mail_top" class="MAR5"><button type="button" class="s_basebtn2 gray" onClick="upload_logo(this, 'mail_top')">등록</button></th>
							</tr>
						</tbody>
					 </table>
				 </div>
			</div>

			<div class="logo_wrap_box">
				<div class="logo_regist">
					<h6>하단로고</h6>
					<table>
						<colgroup>
						</colgroup>
						<tbody class="tac">
							<tr>
								<th class="tac">[ 권장사이즈 : 245px * 60px ]</th>
							</tr>
							<tr>
								<th><p><img src="<?php echo NFE_URL.'/data/logo/'.$env['logo_bottom'];?>?t=<?php echo time();?>" alt=""></p></th>
							</tr>
							<tr>
								<th class="tar"><input type="file" name="bottom" class="MAR5"><button type="button" class="s_basebtn2 gray" onClick="upload_logo(this, 'bottom')">등록</button></th>
							</tr>
						</tbody>
					 </table>
				 </div>

				<div class="logo_regist">
					<h6>메일하단로고</h6>
					<table>
						<colgroup>
						</colgroup>
						<tbody class="tac">
							<tr>
								<th class="tac">[ 권장사이즈 : 245px * 60px ]</th>
							</tr>
							<tr>
								<th><p><img src="<?php echo NFE_URL.'/data/logo/'.$env['logo_mail_bottom'];?>?t=<?php echo time();?>" alt=""></p></th>
							</tr>
							<tr>
								<th class="tar"><input type="file" name="mail_bottom" class="MAR5"><button type="button" class="s_basebtn2 gray" onClick="upload_logo(this, 'mail_bottom')">등록</button></th>
							</tr>
						</tbody>
					 </table>
				 </div>
			</div>
			</form>



		</div>
		<!--//conbox-->

	

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->