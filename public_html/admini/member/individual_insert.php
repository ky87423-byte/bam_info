<?php
$add_cate_arr = array('email');
$top_menu_code = '300108';
include '../include/header.php';

$mem_row = $db->query_fetch("select * from nf_member as nm where nm.`mb_id`=?", array($_GET['mb_id']));
$mb_phone_arr = explode("-", $mem_row['mb_phone']);
$mb_hphone_arr = explode("-", $mem_row['mb_hphone']);
$mb_email_arr = explode("@", $mem_row['mb_email']);
$mb_receive_arr = explode(",", $mem_row['mb_receive']);
?>
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_member.class.js"></script>
<script type="text/javascript">
var click_write = function(code) {
	var form = document.forms['fmember'];
	form.process.value = code;
}
</script>
<!-- 개인회원등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">

			<form name="fmember" action="../../include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="member_write" />
			<input type="hidden" name="mb_type" value="individual" />
			<input type="hidden" name="mno" value="<?php echo intval($mem_row['no']);?>" />
			<input type="hidden" name="back_page" value="<?php echo $nf_util->page_back();?>" />
			<input type="hidden" name="process" value="" />
			<h6>개인회원 정보입력<span><b>*</b> 표시는 필수 입력사항</span></h6>

			<?php
			$company_row = $member_ex;
			$my_member = $mem_row;
			$nf_member->get_member($mem_row['no']);
			$admin_page = true;
			include NFE_PATH.'/member/individual_part.inc.php';
			?>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="submit" class="save_btn2" onClick="click_write('save_next_write')">저장후 등록</button>
				<button type="submit" class="cancel_btn">취소하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 f
ooter-->