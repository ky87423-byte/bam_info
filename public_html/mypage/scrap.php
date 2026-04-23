<?php
$_site_title_ = '마이페이지 > 스크랩';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '스크랩';
include NFE_PATH.'/include/m_title.inc.php';
$nf_member->check_login();

$search_where = $nf_search->shop();

$q = "nf_shop as ns right join nf_scrap as ns1 on ns.`no`=ns1.`pno` where ns.`is_delete`=0 and ns1.`mno`=".intval($member['no']).$search_where['where'];
$order = " order by ns1.`no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$scrap_query = $db->_query("select *, ns.`no` as ns_no from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>

<div class="my_sub wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_my['scrap'] = 'on';
	include '../include/my_leftmenu.php';
	?>
	<div class="my_con">
		<form name="flist">
		<?php
		include '../include/mypage/shop_scrap.php'; //스크랩
		?>
		<div><?php echo $paging['paging'];?></div>
		</form>
	</div>
</div>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>
