<?php
$_site_title_ = '마이페이지 > 업체등록 현황';
include '../include/header_meta.php';
include '../include/header.php';
$nf_member->check_login('company');

$search_where = $nf_search->shop();

$m_title = '업체등록 현황';
include NFE_PATH.'/include/m_title.inc.php';

$q = "nf_shop as ns where `is_delete`=0 and `mno`=".intval($member['no']).$search_where['where'];
$order = " order by `no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$shop_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>

<div class="my_sub wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_my['shop_list'] = 'on';
	include '../include/my_leftmenu.php';
	?>
	<div class="my_con">
		<form name="flist">
		<input type="hidden" name="mode" />
		<?php
		include '../include/mypage/shop_current.php'; //업체등록 현황
		?>
		</form>
		<div><?php echo $paging['paging'];?></div>
	</div>
</div>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>