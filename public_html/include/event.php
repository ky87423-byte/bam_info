<?php
$add_cate_arr = array('job_tema');

$m_title = "할인쿠폰 상세";
include '../include/header_meta.php';
include '../include/header.php';

include NFE_PATH.'/include/m_title.inc.php';

$_where = "";
$StartDate = date("Y-m-d");
$EndDate = date("Y-m-d");
$_where .= " and (('$StartDate' <= coupon_date1 AND '{$EndDate}' >= coupon_date1) OR ('{$StartDate}' <= coupon_date2 AND '{$EndDate}' >= coupon_date2) OR (coupon_date1 <= '{$EndDate}' AND coupon_date2 >= '{$EndDate}'))";

$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`=".intval($_GET['no'])." and ns.`coupon_use`=1 and ((ns.`coupon_allow_int`-ns.`coupon_use_int`)>0) ".$nf_shop->service_where2.$nf_shop->shop_where.$_where);
$shop_info = $nf_shop->shop_info($shop_row);

if(!$shop_row) {
	$arr['msg'] = "쿠폰정보가 없습니다.";
	$arr['move'] = $nf_util->page_back();
	die($nf_util->move_url($arr['move'], $arr['msg']));
}

$shop_info = $nf_shop->shop_info($shop_row);

include NFE_PATH.'/include/etc/kakao_send.inc.php';
?>
<section class="event wrap1400">
	<div class="coupon_info">
		<img src="../images/icon/coupon_img.png" alt="할이쿠폰 아이콘 이미지">	
		<ul>
			<li>* <b>[쿠폰 다운받기]</b>를 클릭해주세요.</li>
			<li>* 다운 받은 쿠폰은 <b>[내정보 > 마이쿠폰]</b>에서 확인할 수 있습니다.</li>
			<li>* 샵 이용시 쿠폰을 꼭 제시해주세요.</li>
		</ul>
	</div>

	<div class="event_wrap">
		<div class="coupon">
			<p class="shop_name line1"><?php echo $shop_info['area_end'];?> <?php echo $shop_row['wr_company'];?></p>
			<div>
				<dl>
					<dt><?php echo $nf_util->get_text($shop_row['coupon_subject']);?></dt>
					<dd><span><?php echo number_format($shop_info['coupon_price']);?></span>원 할인</dd>
				</dl>
				<div class="c_num">
					<span>쿠폰넘버</span><?php echo $coupon_number;?>
				</div>
			</div>
		</div>
		<div class="coupon_box">
			<dl class="dead">
				<dt>사용기한</dt>
				<dd><?php echo $shop_row['coupon_date1'];?> ~ <?php echo $shop_row['coupon_date2'];?></dd>
			</dl>
			<dl class="use">
				<dt>이용안내</dt>
				<dd><b><?php echo $nf_shop->coupon_limit_arr[$shop_row['coupon_limit']];?></b></dd>
				<dd><?php echo nl2br($nf_util->get_text($shop_row['coupon_content']));?></dd>
			</dl>
		</div>
	</div>

	<ul class="btn">
		<li><button type="button" class="kakao-link-btn-is" onclick="get_coupon_func()">쿠폰 다운받기</button></li>
		<li><a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>"><button type="button">업체정보보기</button></a></li>
	</ul>
</section>


<?php
echo $kakao_process;
//include NFE_PATH.'/include/footer.php';
?>