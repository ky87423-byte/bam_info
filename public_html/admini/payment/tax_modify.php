<?php
$add_cate_arr = array('job_company', 'job_listed', 'job_company_type', 'email');
include "../../engine/_core.php";

$member_tax = $db->query_fetch("select * from nf_tax where `no`=".intval($_GET['no']));
$wr_type = $member_tax['wr_type'] ? $member_tax['wr_type'] : 'company';
$top_menu_code = '500206';
if($wr_type=='individual') $top_menu_code = '500207';
include '../include/header.php';

$wr_pay_date = $member_tax ? $member_tax['wr_pay_date'] : today;

$mb_hphone_arr = explode("-", $member_tax['wr_hphone']);
$mb_phone_arr = explode("-", $member_tax['wr_phone']);
$mb_email_arr = explode("@", $member_tax['wr_email']);
$mb_biz_no_arr = explode("-", $member_tax['wr_biz_no']);
?>
<!-- 세금계산서수정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="tax_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<h6>세금계산서 신청 정보 수정<span><b>*</b> 표시는 필수 입력사항</span></h6>

			<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" method="post">
			<input type="hidden" name="mode" value="tax_write" />
			<input type="hidden" name="no" value="<?php echo $member_tax['no'];?>" />
			<?php
			$admin_page = true;
			include NFE_PATH.'/include/etc/tax.'.$wr_type.'.inc.php';
			?>
			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $member_tax ? '수정' : '저장';?>하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 f
ooter-->