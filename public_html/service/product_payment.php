<?php
$add_cate_arr = array('online');
include_once "../engine/_core.php";
$nf_member->check_login();
$_site_title_ = '업체등록 결제페이지';

// : 결제할 정보
$_code = $_GET['code'] ? $_GET['code'] : 'employ';
if($db->is_table('nf_'.$_code)) $info_row = $db->query_fetch("select * from ".'nf_'.$_code." where `no`=? and `mno`=?", array($_GET['no'], $member['no']));
else $info_row = $db->query_fetch("select * from nf_shop where `no`=? and `mno`=?", array($_GET['no'], $member['no']));
if(in_array($_code, array('shop')) && !$info_row) {
	die($nf_util->move_url("/", "삭제된 정보입니다."));
}

include '../include/header_meta.php';
include '../include/header.php';
include '../include/scroll_banner.php'; // : 사이드배너

$m_title = '상품선택 및 결제';
include NFE_PATH.'/include/m_title.inc.php';
?>
<style type="text/css">
.bank-tr- { display:none; }
.tax-c- { display:none; }
.tax-tr- { display:none; }
</style>
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_payment.class.js"></script>
<script type="text/javascript">
$(function(){
	nf_payment.click_service();
});
</script>
<!-- 기업 상품서비스-->
<section class="service_sub">
	<div class="sub service wrap1400">

		<form name="fpayment" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_payment.submit(this)">
		<input type="hidden" name="mode" value="payment_start" />
		<input type="hidden" name="code" value="<?php echo $_code;?>" />
		<input type="hidden" name="no" value="<?php echo $info_row['no'];?>" />
		<?php
		if($_GET['code']=='shop') include NFE_PATH.'/include/service.shop.inc.php';

		if(in_array($_GET['code'], array('read', 'jump'))) {
			$code = 'shop';
			include NFE_PATH.'/include/service/'.$_GET['code'].'.service.inc.php';
		}
		?>
		

		<h4 class="orange">결제안내</h4>
		<div class="payment register ">
			<div class="payment_box">
				<h5>적용할 업체정보 제목</h5>
				<div class="apply_product">
					<?php echo $nf_util->get_text($info_row['wr_subject']);?>
				</div>
				<h5>신청상품</h5>
				<table class="style2 tac">
					<colgroup>
						<col width="25%">
						<col width="50%">
						<col width="25%">
					</colgroup>
					<thead>
					<tr>
						<th>상품명</th>
						<th>상품내용</th>
						<th>금액</th>
					</tr>
					</thead>
					<tbody class="click_service_list-">
					<tr>
						<td colspan="3">서비스를 선택해주시기 바랍니다.</td>
					</tr>
					</tbody>
				</table>
				<div class="all_pay">
					<p>신청상품 합계금액</p>
					<p><span class="orange price-hap-">0</span>원</p>
				</div>

				<?php
				include NFE_PATH.'/include/etc/payment_price.inc.php';
				?>
			</div>
		</div>
		<!--//payment-->
		</form>
	</div>
</section>

<!--푸터영역-->
<?php
include NFE_PATH.'/include/pg_start.php';

include NFE_PATH.'/include/service_intro.box.php';
//include NFE_PATH.'/include/footer.php';
?>

