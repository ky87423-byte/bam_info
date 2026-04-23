<?php
include "./engine/_core.php";

$basic_date = '2023-11-02 00:00:00';

$xml_arr = array();
//※메인페이지
$xml_arr[] = array('', '');

//※지역
$xml_arr[] = array('/include/category_view.php', '');
if(is_array($cate_p_array['area'][0])) { foreach($cate_p_array['area'][0] as $k=>$v) {
	$xml_arr[] = array('/include/category_view.php?code=area&wr_area[]='.$v['wr_name']);
	if(is_array($cate_p_array['area'][$k])) { foreach($cate_p_array['area'][$k] as $k2=>$v2) {
		$xml_arr[] = array('/include/category_view.php?code=area&wr_area[]='.$v['wr_name'].'&wr_area[]='.$v2['wr_name']);
		if(is_array($cate_p_array['area'][$k2])) { foreach($cate_p_array['area'][$k2] as $k3=>$v3) {
			$xml_arr[] = array('/include/category_view.php?code=area&wr_area[]='.$v['wr_name'].'&wr_area[]='.$v2['wr_name'].'&wr_area[]='.$v3['wr_name']);
		} }
	} }
} }

//※업종
$xml_arr[] = array('/include/category_view.php', '');
if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
	$xml_arr[] = array('/include/category_view.php?code=category&category[]='.$k);
	if(is_array($cate_p_array['job_part'][$k])) { foreach($cate_p_array['job_part'][$k] as $k2=>$v2) {
		$xml_arr[] = array('/include/category_view.php?code=category&category[]='.$k.'&category[]='.$k2);
		if(is_array($cate_p_array['job_part'][$k2])) { foreach($cate_p_array['job_part'][$k2] as $k3=>$v3) {
			$xml_arr[] = array('/include/category_view.php?code=category&category[]='.$k.'&category[]='.$k2.'&category[]='.$k3);
		} }
	} }
} }
//※테마
if(is_array($cate_p_array['job_tema'][0])) { foreach($cate_p_array['job_tema'][0] as $k=>$v) {
	$xml_arr[] = array('/include/category_view.php?code=tema&tema[]='.$k);
} }

//※각 솔루션별 상세페이지 (차후 솔루션 추가시 자동 생성 되게)
if(is_array($nf_solution->kind)) { foreach($nf_solution->kind as $k=>$v) {
	if($v['not_use']) continue;
	$xml_arr[] = array('/homepage/index.php?code='.$k, $v['odate']);
} }

//※부가서비스페이지
$xml_arr[] = array('/include/location_view.php?code=location', '');
$xml_arr[] = array('/map/index.php', '');
$xml_arr[] = array('/include/review.php', '');
$xml_arr[] = array('/service/event_list.php', '');
$xml_arr[] = array('/board/notice_list.php', '');
$xml_arr[] = array('/service/index.php', '');
$xml_arr[] = array('/service/advert.php', '');
$xml_arr[] = array('/service/company.php', '');
$xml_arr[] = array('/service/terms.php', '');
$xml_arr[] = array('/service/privacy_policy.php', '');
$xml_arr[] = array('/service/board_criterion.php', '');
$xml_arr[] = array('/service/cs_center.php', '');

//※공지사항 리스트 및 상세페이지 (공지글 자동 갱신)
$notice_xml = '';
$notice_query = $db->_query("select * from nf_notice order by `no` desc limit 0, 1000");
$cnt = 0;
while($row=$db->afetch($notice_query)) {
	$xml_url = '/board/notice_view.php?no='.$row['no'];
	$notice_xml .= '<url>
<loc><![CDATA['.domain.$xml_url.']]></loc>
<lastmod>'.date(DATE_ATOM, strtotime($row['wr_date'])).'</lastmod>
<changefreq>always</changefreq>
<priority>0.0</priority>
</url>';
	$cnt++;
}

//※업체상세
$shop_xml = '';
$notice_query = $db->_query("select * from nf_shop as ns where 1 ".$nf_shop->service_where2.$nf_shop->shop_where." order by `no` desc limit 0, 1000");
$cnt = 0;
while($row=$db->afetch($notice_query)) {
	$xml_url = '/shop/index.php?no='.$row['no'];
	$shop_xml .= '<url>
<loc><![CDATA['.domain.$xml_url.']]></loc>
<lastmod>'.date(DATE_ATOM, strtotime($row['wr_rdate'])).'</lastmod>
<changefreq>always</changefreq>
<priority>0.0</priority>
</url>';
	$cnt++;
}

//※쿠폰상세
$coupon_xml = '';
$_where = "";
$StartDate = date("Y-m-d");
$EndDate = date("Y-m-d");
$_where .= " and (('$StartDate' <= coupon_date1 AND '{$EndDate}' >= coupon_date1) OR ('{$StartDate}' <= coupon_date2 AND '{$EndDate}' >= coupon_date2) OR (coupon_date1 <= '{$EndDate}' AND coupon_date2 >= '{$EndDate}'))";
$coupon_query = $db->_query("select * from nf_shop as ns where ns.`coupon_use`=1 ".$nf_shop->service_where2.$nf_shop->shop_where.$_where." order by ns.`no` desc");
$cnt = 0;
while($row=$db->afetch($coupon_query)) {
	$xml_url = '/include/event.php?no='.$row['no'];
	$coupon_xml .= '<url>
<loc><![CDATA['.domain.$xml_url.']]></loc>
<lastmod>'.date(DATE_ATOM, strtotime($row['wr_rdate'])).'</lastmod>
<changefreq>always</changefreq>
<priority>0.0</priority>
</url>';
	$cnt++;
}

//※커뮤니티
$board_xml = "";
if(is_array($nf_board->board_menu_view[0])) { foreach($nf_board->board_menu_view[0] as $k=>$v) {
	$xml_arr[] = array('/board/index.php?cno='.$k);
	if(is_array($nf_board->board_botable_arr[$k])) { foreach($nf_board->board_botable_arr[$k] as $k2=>$v2) {
		$xml_arr[] = array('/board/list.php?bo_table='.$k2);
		if($v2['bo_read_level']) continue; // : 게시물읽기 손님만 출력
		$_table = 'nf_write_'.$k2;
		$b_q = $db->_query("select * from ".$_table." as nwb where 1 and `wr_is_comment`=0 and `wr_del`=0 and `wr_blind`=0");
		$board_info = $nf_board->board_info($v2);
		while($b_row=$db->afetch($b_q)) {
			$b_info = $nf_board->info($b_row, $board_info);
			if($b_info['is_secret']) continue;

			$xml_url = '/board/view.php?bo_table='.$k2.'&no='.$b_row['wr_no'];
			$coupon_xml .= '<url>
		<loc><![CDATA['.domain.$xml_url.']]></loc>
		<lastmod>'.date(DATE_ATOM, strtotime($b_row['wr_last'])).'</lastmod>
		<changefreq>always</changefreq>
		<priority>0.0</priority>
		</url>';
		}
	} }
} }


// : xml태그 만들기
Header("Content-type: text/xml charset=UTF-8");
ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
if(is_array($xml_arr)) { foreach($xml_arr as $k=>$v) {
	if(!$v[1]) $v[1] = $basic_date;
	$time = date(DATE_ATOM, strtotime($v[1]));
?>
<url>
<loc><![CDATA[<?php echo domain.$v[0];?>]]></loc>
<lastmod><?php echo $time;?></lastmod>
<changefreq>always</changefreq>
<priority>0.0</priority>
</url>
<?php
} }
echo $notice_xml;
echo $shop_xml;
echo $coupon_xml;
echo $board_xml;
?>
</urlset>
<?php
$xml = ob_get_clean();
echo $xml;
?>