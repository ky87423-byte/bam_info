<?php
$_site_title_ = "마이페이지 > 홈";
include '../include/header_meta.php';
include '../include/header.php';
$nf_member->check_login();

$m_title = "마이페이지 홈";
include NFE_PATH.'/include/m_title.inc.php';

$mno_field = $member['mb_type']=='individual' ? 'mno' : 'pmno';

$cnt_shop = $db->query_fetch("select count(*) as c from nf_shop where is_delete=0 and `mno`=".intval($member['no']));
$cnt_guide = $db->query_fetch("select count(*) as c from nf_guide where `".$mno_field."`=".intval($member['no']));
$cnt_qna = $db->query_fetch("select count(*) as c from nf_qna where `".$mno_field."`=".intval($member['no']));
$cnt_scrap = $db->query_fetch("select count(*) as c from nf_scrap where `mno`=".intval($member['no']));
?>
<script type="text/javascript">
var a_content_view = function(el) {
	var obj = $(el).closest("li.item-").find(".shop_review_a");
	var obj2 = $(el).closest("li.item-").find(".shop_review_q");
	var obj3 = $(el).closest("li.item-").find(".shop_qa_q");
	if(obj[0]) {
		var display = obj.css("display")=='none' ? 'block' : 'none';
		obj.css({"display":display});
	}
	if(obj2[0]) {
		var display = obj2.css("display")=='none' ? 'block' : 'none';
		obj2.css({"display":display});
	}
	if(obj3[0]) {
		var display = obj3.css("display")=='none' ? 'block' : 'none';
		obj3.css({"display":display});
	}
}
</script>
<div class="my_sub wrap1400">
	<!--마이페이지 왼쪽 메뉴-->
	<?php
	$left_my['my_home'] = 'on';
	include '../include/my_leftmenu.php';
	?>
	<div class="my_con my_main">
		<div class="my_info_wrap">
			<div class="my_info">
				<div class="my">
					<em><img src="<?php echo NFE_URL;?>/data/member_level/<?php echo $env['member_level_arr'][$member['mb_level']]['icon'];?>" alt=""><?php echo $env['member_level_arr'][$member['mb_level']]['name'];?></em>
					<p><span><?php echo $member['mb_nick'];?></span>님 안녕하세요.</p>
				</div>
				<div class="simple_info">
					<?php
					if($member['mb_type']=='company') {
					?>
					<a href="<?php echo NFE_URL;?>/mypage/list.php">
						<dl class="s_shop">
							<dt>업체등록</dt>
							<dd><?php echo number_format($cnt_shop['c']);?></dd>
						</dl>
					</a>
					<?php
					}
					if($env['use_shop_guide']) {?>
					<a href="<?php echo NFE_URL;?>/mypage/review.php">
						<dl class="s_review">
							<dt>이용후기</dt>
							<dd><?php echo number_format($cnt_guide['c']);?></dd>
						</dl>
					</a>
					<?php }?>
					<?php if($env['use_shop_qna']) {?>
					<a href="<?php echo NFE_URL;?>/mypage/qna.php">
						<dl class="s_qa">
							<dt>Q&A</dt>
							<dd><?php echo number_format($cnt_qna['c']);?></dd>
						</dl>
					</a>
					<?php }?>
					<a href="<?php echo NFE_URL;?>/mypage/scrap.php">
						<dl class="s_scrap">
							<dt>스크랩</dt>
							<dd><?php echo number_format($cnt_scrap['c']);?></dd>
						</dl>
					</a>
				</div>
			</div>
		</div>
		
		<?php
		if($member['mb_type']=='company') {
			$q = "nf_shop as ns where `is_delete`=0 and `mno`=".intval($member['no']);
			$order = " order by `no` desc";
			$shop_query = $db->_query("select * from ".$q.$order." limit 0, 5");
			$total['c'] = $db->num_rows($shop_query);
			include '../include/mypage/shop_current.php'; //업체등록 현황
		}
		?>

		<?php
		if($env['use_shop_guide']) {
			if($member['mb_type']=='company')
				$q = "nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`pmno`=".intval($member['no']);
			else
				$q = "nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`mno`=".intval($member['no']);
			$order = " order by ng.`no` desc";
			$guide_query = $db->_query("select * from ".$q.$order." limit 0, 5");
			$_arr['total'] = $db->num_rows($guide_query);
			include '../include/mypage/shop_review.php'; //이용후기
		}
		?>
		
		<?php
		if($env['use_shop_qna']) {
			if($member['mb_type']=='company')
				$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where nq.`pmno`=".intval($member['no']);
			else
				$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where nq.`mno`=".intval($member['no']);
			$q .= $search_where['where'];

			$order = " order by nq.`no` desc";
			$qna_query = $db->_query("select * from ".$q.$order." limit 0, 5");
			$_arr['total'] = $db->num_rows($qna_query);
			include '../include/mypage/shop_qa.php'; //Q&A
		}
		?>

		<?php
		$q = "nf_shop as ns right join nf_scrap as ns1 on ns.`no`=ns1.`pno` where ns.`is_delete`=0 and ns1.`mno`=".intval($member['no']);
		$order = " order by ns1.`no` desc";
		$scrap_query = $db->_query("select * from ".$q.$order." limit 0, 5");
		$_arr['total'] = $db->num_rows($scrap_query);
		include '../include/mypage/shop_scrap.php'; //스크랩
		?>
	</div>
</div>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>