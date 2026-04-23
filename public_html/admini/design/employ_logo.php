<?php
$top_menu_code = "400103";
include '../include/header.php';

$db->_query("alter table nf_config change employ_logo_img shop_logo_img varchar(50) comment '업체정보 기본이미지 로고'");
$db->_query("alter table nf_config drop employ_logo_bg");
?>
<script type="text/javascript">
var upload_img = function(el, code) {
	var form = document.forms['fwrite'];
	form.code.value = code;
	nf_util.ajax_submit(form);
}
</script>
<!-- 채용공고기본로고 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="basic_logo">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 업체정보 등록시, 로고 이미지가 등록이 되어있지 않으면 기본 이미지가 채용공고에 노출됩니다.</li>
				</ul>
			</div>
			
			<form  name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="shop_logo_write" />
			<input type="hidden" name="code" value="" />
			<h6>업체정보 기본 이미지 로고 설정</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tbody>
					<tr>
						<th>기본 이미지 로고 등록</th>
						<td><input type="file" name="img" class="MAR5 input20"><button type="button" class="s_basebtn2 gray" onClick="upload_img(this, 'img')">등록</button><span> [ 권장사이즈 : 넓이 400px 이하, 높이 140px ]</span></td>
					</tr>
					<tr>
						<th>등록된 이미지 로고</th>
						<td>
							<ul class="logo1">
								<li class="MAB5">
									<p><img src="<?php echo NFE_URL.'/data/logo/'.$env['shop_logo_img'];?>?t=<?php echo time();?>" alt="업체정보 기본이미지 로고"></p>	
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
			 </table>
			 </form>



		</div>
		<!--//conbox-->

	

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->