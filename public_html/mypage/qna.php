<?php
include "../engine/_core.php";
if(!$env['use_shop_qna']) {
	die($nf_util->move_url($nf_util->page_back(), "사용하지 않는 기능입니다."));
}

$_site_title_ = '마이페이지 > Q&A';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = 'Q&A';
include NFE_PATH.'/include/m_title.inc.php';

$nf_member->check_login();

$nf_util->sess_page_save("shop_view");

$with_qna = true;
$search_where = $nf_search->shop();

if($member['mb_type']=='company')
	$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where nq.`pmno`=".intval($member['no']);
else
	$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where nq.`mno`=".intval($member['no']);
$q .= $search_where['where'];

$order = " order by nq.`no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$qna_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.shop_qa_q { display:none; }
.shop_review_a { display:none; }
</style>
<script type="text/javascript">
var a_content_view = function(el) {
	var obj = $(el).closest("li.item-").find(".shop_review_a");
	var obj2 = $(el).closest("li.item-").find(".shop_qa_q");
	if(obj[0]) {
		var display = obj.css("display")=='none' ? 'block' : 'none';
		obj.css({"display":display});
	}
	if(obj2[0]) {
		var display = obj2.css("display")=='none' ? 'block' : 'none';
		obj2.css({"display":display});
	}
}
</script>
<div class="my_sub wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_my['qna'] = 'on';
	include '../include/my_leftmenu.php';
	?>
	<div class="my_con">
		<form name="flist">
		<?php
		include '../include/mypage/shop_qa.php'; //qna
		?>
		</form>
		<div><?php echo $paging['paging'];?></div>
	</div>
</div>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>