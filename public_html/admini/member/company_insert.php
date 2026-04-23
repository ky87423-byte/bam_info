<?php
$_SERVER['__USE_API__'] = array('editor');
$add_cate_arr = array('job_company', 'job_listed', 'job_company_type', 'email');
$top_menu_code = '300107';
include '../include/header.php';

$mem_row = $db->query_fetch("select * from nf_member as nm where nm.`mb_id`=?", array($_GET['mb_id']));
$mb_hphone_arr = explode("-", $mem_row['mb_hphone']);
$mb_email_arr = explode("@", $mem_row['mb_email']);
$mb_receive_arr = explode(",", $mem_row['mb_receive']);
?>
<!-- 기업회원등록 -->
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_member.class.js"></script>
<script type="text/javascript">
var click_write = function(code) {
	var form = document.forms['fwrite'];
	form.process.value = code;
}
</script>
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<form name="fwrite" action="../../include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="member_write" />
			<input type="hidden" name="mb_type" value="company" />
			<input type="hidden" name="mno" value="<?php echo intval($mem_row['no']);?>" />
			<input type="hidden" name="back_page" value="<?php echo $nf_util->page_back();?>" />
			<input type="hidden" name="process" value="" />
			<h6>기업회원 정보입력<span><b>*</b> 표시는 필수 입력사항</span></h6>

			<?php
			$my_member = $mem_row;
			$admin_page = true;
			include NFE_PATH.'/member/company_part.inc.php';
			?>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<!-- <button type="submit" onClick="click_write('save_next_write')" class="save_btn2">저장후 등록</button> -->
				<button type="button" class="cancel_btn">취소하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 f
ooter-->