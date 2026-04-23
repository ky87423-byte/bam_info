<?php
$add_cate_arr = array('');
$_SERVER['__USE_API__'] = array('jqueryui');
include_once "../engine/_core.php";
$nf_member->check_login();

$_site_title_ = '마이페이지 > 할인쿠폰';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '할인쿠폰';
include NFE_PATH.'/include/m_title.inc.php';

$_where = "";

$coupon_end = ($_GET['code']=='date_end') ? '!' : '';
$StartDate = today;
$EndDate = today;
$_where .= " and ".$coupon_end."(('$StartDate' <= ns.coupon_date1 AND '{$EndDate}' >= ns.coupon_date1) OR ('{$StartDate}' <= ns.coupon_date2 AND '{$EndDate}' >= ns.coupon_date2) OR (ns.coupon_date1 <= '{$EndDate}' AND ns.coupon_date2 >= '{$EndDate}'))";

if($_GET['number']) $_where .= " and ncu.`number`='".addslashes($_GET['number'])."'";

if($member['mb_type']=='individual') {
	$q = "nf_shop as ns right join nf_coupon_use as ncu on ns.`no`=ncu.`pno` where ncu.`mno`=".intval($member['no']).$nf_shop->shop_where.$_where;
} else {
	$q = "nf_shop as ns right join nf_coupon_use as ncu on ns.`no`=ncu.`pno` where ncu.`pmno`=".intval($member['no']).$nf_shop->shop_where.$_where;
}
$order = " order by ncu.`no` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$coupon_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>

	<section class="my_sub wrap1400">
		<?php
		$left_on['couopn'] = 'on';
		include '../include/my_leftmenu.php';
		?>
		<div class="my_con">
			<section class="s_common my_coupon tab_style3">
				<p class="s_title">할인쿠폰</p>
				<ul class="top_tab">
					<li class="<?php echo $_GET['code'] ? '' : 'on';?>"><a href="<?php echo NFE_URL;?>/mypage/mycoupon.php"><button type="button">발행쿠폰</button></a></li>
					<li class="<?php echo $_GET['code']=='date_end' ? 'on' : '';?>"><a href="<?php echo NFE_URL;?>/mypage/mycoupon.php?code=date_end"><button type="button">기간만료</button></a></li>
				</ul>

				<form name="flist">
				<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
				<div class="date_search">
					<ul class="fl">
						<li class=""><button type="button" class="white" onClick="nf_util.all_check('#check_all', '.chk_')">전체선택</button><input type="checkbox" id="check_all" style="display:none;" /></li>
						<li class=""><button type="button" class="white" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../include/regist.php" mode="delete_select_coupon" tag="chk[]" check_code="checkbox">선택삭제</button></li>
						<?php if($_GET['code']!='date_end' && $member['mb_type']=='company') {?>
						<li class=""><button type="button" class="white" onclick="nf_util.ajax_select_confirm(this, 'flist', '사용으로 선택하시겠습니까?')" url="../include/regist.php" mode="select_coupon_use" tag="chk[]" check_code="checkbox">사용</button></li>
						<li class=""><button type="button" class="white" onclick="nf_util.ajax_select_confirm(this, 'flist', '미사용으로 선택하시겠습니까?')" url="../include/regist.php" mode="select_coupon_use_not" tag="chk[]" check_code="checkbox">미사용</button></li>
						<?php }?>
					</ul>
					<ul class="fr">
						<li><input type="text" name="number" value="<?php echo $nf_util->get_html($_GET['number']);?>"> <button type="submit" class="bbcolor">쿠폰번호조회</button></li>
					</ul>
				</div>
				
				<ul class="my_coupon_list">
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<li class="no_info">쿠폰 내역이 없습니다.</li>
					<?php
						break;

						default:
							while($coupon_row=$db->afetch($coupon_query)) {
								$shop_info = $nf_shop->shop_info($coupon_row);
								$pmem_row = $db->query_fetch("select * from nf_member where `no`=".intval($coupon_row['mno']));
					?>
					<li>
						<input type="checkbox" name="chk[]" class="chk_" value="<?php echo $coupon_row['no'];?>">
						<div class="shop_info <?php echo $_GET['code']=='date_end' ? 'date-end-' : '';?>">
							<div class="box01">
								<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $coupon_row['pno'];?>"><p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p></a>
								<div>
									<dl>
										<dt class="line1"><a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $coupon_row['pno'];?>"><b>[<?php echo $shop_info['area_end'];?>]</b><?php echo $nf_util->get_text($coupon_row['wr_company']);?></a></dt>
										<dd class="line1"><?php echo $nf_util->get_text($coupon_row['coupon_subject']);?></dd>
									</dl>
									<?php if($coupon_row['use']) {?>
										<span class="use use_y">사용</span>
									<?php } else {?>
									<span class="use use_n">미사용</span>
									<?php }?>
								</div>
							</div>
							<ul class="box02">
								<li>쿠폰번호 : <span class="orange"><?php echo $coupon_row['number'];?></span></li>
								<li>유효기간 : <span class="blue"><?php echo date("Y.m.d", strtotime($coupon_row['coupon_date1']));?>~<?php echo date("Y.m.d", strtotime($coupon_row['coupon_date2']));?></span></li>
								<?php if($member['mb_type']=='company') {?>
								<li>사용건수 : <em class="red"><?php echo number_format($shop_info['coupon_use_int']);?></em>건 사용 / 총 <?php echo number_format($shop_info['coupon_allow_int']);?>건</li><!--업체회원에게만 보이게-->
								<?php }?>
							</ul>
						</div>
						<?php if($member['mb_type']=='company') {?>
						<ul class="use_member">
							<li><span>이름</span><?php echo $nf_util->get_text($pmem_row['mb_name']);?></li>
							<li><span>연락처</span><?php echo $nf_util->get_text($pmem_row['mb_hphone']);?></li>
							<li><span>다운일시</span><?php echo $nf_util->get_text($coupon_row['rdate']);?></li>
						</ul>
						<?php }?>
					</li>
					<?php
							}
						break;
					}
					?>
				</ul>
				</form>
			</section>
			<!--페이징-->
			<div><?php echo $paging['paging'];?></div>
		</div>
	</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>
