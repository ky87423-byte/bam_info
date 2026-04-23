<?php
include_once "../engine/_core.php";
if(empty($_GET['sort'])) $_GET['sort'] = "";
$shop_row = $db->query_fetch("select * from nf_shop as ns where `no`=".intval($_GET['no']));
$check_shop_row = $db->query_fetch("select * from nf_shop as ns where `no`=".intval($_GET['no']).$nf_shop->shop_where.$nf_shop->service_where2);
if(!$shop_row || (!$check_shop_row && $shop_row['mno']!=$member['no'])) {
	die($nf_util->move_url($nf_util->page_back(), "정보가 없습니다."));
}
$shop_info = $nf_shop->shop_info($shop_row);
$category_k_arr = explode(",", $shop_info['category_arr'][0]);
$_site_title_ = $shop_row['wr_company'].' '.$shop_row['wr_subject'].' '.strtr($shop_row['wr_area'], array(","=>" "));
$_site_image_ = NFE_URL.'/data/shop/'.$shop_info['wr_main_photo'];
$_site_content_ = $shop_row['wr_subject'];

$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($shop_row['mno']));
$mno = $member['no'];
$page_code = 'shop';
$info_no = $shop_row['no'];

include '../include/header_meta.php';
include '../include/header.php';

$nf_util->sess_page_save("shop_view");

$tema_arr = explode(",", $shop_row['wr_tema']);
if(is_array($tema_arr)) $tema_arr = array_diff($tema_arr, array(""));

$price_un = unserialize($shop_row['price_info']);
$sns_info_un = unserialize($shop_row['sns_info']);

if(is_Array($price_un)) $price_cnt = count(array_diff($price_un['title'], array("")));

$db->_query("update nf_shop set `wr_hit`=`wr_hit`+1 where `no`=".intval($shop_row['no']));

$m_title = $nf_util->get_text($shop_row['wr_company']);
include NFE_PATH.'/include/m_title.inc.php';

$_km_loc['lat'] = $shop_row['wr_lat'];
$_km_loc['lng'] = $shop_row['wr_lng'];
$_km_loc['this_lat'] = $nf_shop->this_area_pos_arr['lat'];
$_km_loc['this_lng'] = $nf_shop->this_area_pos_arr['lng'];
$shop_km = $nf_util->get_distance($_km_loc);
?>
<style>
.answer { display:none; }
.answer.on { display:block; }

/* ── 모바일 업체명 고정 상단바 ────────────────────────────── */
.shop-sticky-name- {
    display: none;                  /* JS가 모바일에서만 flex로 바꿈 */
    position: fixed;
    left: 0; right: 0; top: 50px;  /* 768px 이하 글로벌 헤더 높이 */
    z-index: 8999;
    height: 46px;
    background: #111;
    color: #fff;
    align-items: center;
    padding: 0 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,.6);
    opacity: 0;
    transform: translateY(-46px);
    transition: opacity .22s ease, transform .22s ease;
    pointer-events: none;
}
.shop-sticky-name-.ssn-on- {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}
.shop-sticky-name- .ssn-back- {
    flex: 0 0 44px; height: 46px;
    background: none; border: none; cursor: pointer;
    color: #fff; display: flex; align-items: center; justify-content: center;
}
.shop-sticky-name- .ssn-back- i { font-size: 2.8rem; }
.shop-sticky-name- .ssn-company- {
    flex: 1;
    font-size: 1.55rem; font-weight: 700; letter-spacing: -.02em;
    overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
    text-align: center; padding: 0 4px;
}
.shop-sticky-name- .ssn-spacer- { flex: 0 0 44px; } /* 좌우 균형 */
@media (min-width: 769px) and (max-width: 1023px) {
    .shop-sticky-name- { top: 80px; } /* 해당 구간 글로벌 헤더 높이 */
}
@media (min-width: 1024px) {
    .shop-sticky-name- { display: none !important; } /* PC 숨김 */
}
/* ───────────────────────────────────────────────────────── */

/* Force dark tab bar — NOT sticky, override style.css */
.shop_detail .tab { background:#111111 !important; border-bottom:2px solid #2a2a2a !important; border-top:1px solid #2a2a2a !important; position:static !important; }
.shop_detail .tab li { background:#111111 !important; border:none !important; border-left:none !important; border-right:none !important; border-top:none !important; }
.shop_detail .tab li a { background:#111111 !important; color:#777 !important; border-top:none !important; font-size:1.35rem !important; padding:0.9rem 1rem 1.2rem !important; }
.shop_detail .tab li.on a, .shop_detail .tab li a:hover { background:#111111 !important; color:#ff385c !important; border-bottom-color:#ff385c !important; }
.shop_detail .tab2 h4 { background:#181818 !important; color:#b0b0b0 !important; border-color:#2a2a2a !important; }
.shop_detail .tab2 dl.course dt { color:#f0f0f0 !important; }
.shop_detail .tab2 dl.course dd { color:#3a9ab0 !important; }
.shop_detail .tab2 dl.price_info dd { color:#f0f0f0 !important; }
.shop_detail .tab_wrap { background:transparent !important; }
.shop_detail .tab_wrap2 { background:transparent !important; }
/* Content images: 모바일 반응형 — 컨테이너 너비에 맞게 축소, 확대 없음 */
.shop_detail .tab1 img,
.shop_detail .tab1 .pc-content- img,
.shop_detail .tab1 .mobile-content- img,
.shop_detail .tab1 .editor-css- img {
    display:block !important;
    max-width:100% !important;   /* 컨테이너 밖으로 절대 넘치지 않음 */
    width:auto !important;       /* 원본보다 강제 확대하지 않음 */
    height:auto !important;      /* 비율 유지 */
    margin:0.8rem auto !important;
}
/* 단, 이미지가 컨테이너보다 작아도 전체 폭을 채워야 하는 에디터 본문 이미지는 width:100% */
.shop_detail .tab1 .editor-css- img { width:100% !important; }
/* 텍스트 정렬 */
.shop_detail .tab1 .editor-css- { text-align:center; }
.shop_detail .tab1 .editor-css- p { text-align:center; font-size:1.35rem; line-height:1.75; color:#b0b0b0; }

/* ── 모바일 상품 상세페이지: 헤더 스크롤 숨김 / 푸터 숨김 ─── */
@media (max-width: 1023px) {
    /* 헤더 스크롤 숨김 애니메이션 */
    .header_menu { transition: transform 0.3s ease; }
    .header_menu.nav-hidden- { transform: translateY(-100%); }
    /* 푸터 숨김 — 하단 문자/전화 버튼만 노출 */
    footer { display: none !important; }
    /* 하단 고정 버튼 공간 확보 */
    .shop_detail { padding-bottom: 80px; }
}
</style>
<script type="text/javascript">
var ch_sort = function(el) {
	var form = document.forms['fsearch__'];
	form.sort.value = el.value;
	form.action = "#guide_sort-";
	form.submit();
}

var qna_el = {};
var click_secret = function(el, no) {
	var offset = $(el).offset();
	$(".password-box-").css({"top":offset.top+100});
	$(".password-box-").addClass("on");
	qna_el[no] = el;

	var passform = document.forms['fpassword'];
	passform.kind.value = "shop_qna";
	passform.no.value = no;
}

var click_qna = function(el) {
	$(el).closest("em").find(".answer").addClass("on");
}
</script>
<form name="fsearch__" method="get">
<input type="hidden" name="no" value="<?php echo $nf_util->get_html($_GET['no']);?>" />
<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
</form>
<div class="shop_detail"<?php echo $shop_info['photo_arr'][0];?>>

<!-- 모바일 스크롤 시 업체명 고정 상단바 -->
<div id="shop-sticky-name-" class="shop-sticky-name-">
    <button class="ssn-back-" onclick="history.back()" aria-label="뒤로가기">
        <i class="axi axi-keyboard-arrow-left"></i>
    </button>
    <span class="ssn-company-"><?php echo $nf_util->get_text($shop_row['wr_company']); ?></span>
    <div class="ssn-spacer-"></div>
</div>
<!-- //모바일 업체명 고정바 -->

	<?php
	$photo_cnt = 1;
	if(is_Array($shop_info['photo_arr'])) $photo_cnt = count($shop_info['photo_arr']);
	?>
	<?php if($shop_info['photo_arr'][0] != 0){?>
	<div class="visual is<?php echo ($photo_cnt>=3 ? 3 : $photo_cnt);?>-">
		<ul class="slider cycle-slideshow"
			<?php
			if($photo_cnt>1) {
			?>
			data-cycle-fx=carousel
			data-cycle-timeout=0
			data-cycle-carousel-visible=<?php echo $photo_cnt>=3 ? 3 : 1;?> 
			data-cycle-slides="> li"
			 data-cycle-carousel-fluid=true
			data-cycle-prev=".s_v_btn .s_v_prev"
			data-cycle-next=".s_v_btn .s_v_next"
			<?php
			}?>
			>
			<?php
			if(is_array($shop_info['photo_arr'])) { foreach($shop_info['photo_arr'] as $k=>$v) {
			?>
			<li><img src="<?php echo NFE_URL;?>/data/shop/<?php echo $v;?>"></li>
			<?php
			} }?>
			<!--이미지가 1, 2개일때 문제-->
		</ul>
		<?php
		if($photo_cnt>1) {
		?>
		<div class="s_v_btn">
			<button type="button" class="s_v_prev"><img src="../images/s_visual01.png" alt="샵 이미지 왼쪽으로 넘기기"></button>
			<button type="button" class="s_v_next"><img src="../images/s_visual02.png" alt="샵 이미지 오른쪽으로 넘기기"></button>
		</div>
		<?php
		}?>
	</div>
	<?php }?>
	<style type="text/css">
	.mobile- .slider img { width:100%; }
	</style>
<?php if($shop_info['photo_arr'][0] != 0){?>
	<div class="mobile- visual is1-">
		<div class="slider cycle-slideshow"
			<?php
			if($photo_cnt>1) {
			?>
			data-cycle-swipe=true
			data-cycle-swipe-fx=scrollHorz
			data-cycle-timeout=0
			data-cycle-prev=".s_v_prev_m"
			data-cycle-next=".s_v_next_m"
			<?php
			}?>
			>
			<?php
			if(is_array($shop_info['photo_arr'])) { foreach($shop_info['photo_arr'] as $k=>$v) {
			?>
			<img src="<?php echo NFE_URL;?>/data/shop/<?php echo $v;?>">
			<?php
			} }?>
		</div>
		<?php
		if($photo_cnt>1) {
		?>

		<button type="button" class="s_v_prev_m"><img src="../images/s_visual01.png" alt="샵 이미지 왼쪽으로 넘기기"></button>
		<button type="button" class="s_v_next_m"><img src="../images/s_visual02.png" alt="샵 이미지 오른쪽으로 넘기기"></button>

		<?php
		}?>
	</div>
<?php }?>
	<div class="wrap1400">
		<?php
		// : 스크롤 광고
		include NFE_PATH.'/include/scroll_banner.php';
		?>
		<div class="post_header">
			<?php if($env['use_shop_tema'] && is_Array($tema_arr)) { ?>
			<div class="post_tags">
				<?php foreach($tema_arr as $k=>$v) { ?>
				<span class="tag"><?php echo $cate_array['job_tema'][$v];?></span>
				<?php } ?>
			</div>
			<?php } ?>

			<h2 class="post_title"><?php echo $nf_util->get_text($shop_row['wr_subject']);?></h2>

			<div class="post_meta">
				<span class="meta-author"><i class="axi axi-person"></i> <?php echo $nf_util->get_text($shop_row['wr_company']);?></span>
				<?php if($env['use_shop_point'] && $shop_row['wr_avg_point'] > 0) {?>
				<span class="meta-star"><i class="axi axi-star3"></i> <?php echo $shop_row['wr_avg_point'];?></span>
				<?php }?>
				<span class="meta-views"><i class="axi axi-visibility"></i> <?php echo number_format($shop_row['wr_hit']);?></span>
				<span class="meta-loc"><i class="axi axi-location-on"></i> <?php echo sprintf("%0.2f", $shop_km);?>km</span>
				<?php if($env['use_shop_price'] && $shop_row['wr_price'] > 0) {?>
				<span class="meta-price"><i class="axi axi-credit-card"></i> <?php echo number_format($shop_row['wr_price']);?>원~</span>
				<?php }?>
				<?php if($shop_info['time_use'] && $shop_row['time_full']) {?>
				<span class="meta-time"><i class="axi axi-schedule"></i> 24시간</span>
				<?php } elseif($shop_info['time_use']) {
					$t1 = explode(":", $shop_row['time1']);
					$t2 = explode(":", $shop_row['time2']);
					$h1 = intval($t1[0]); $h2 = intval($t2[0]);
					$ts = ($h1<12?'AM ':'PM ').sprintf("%02d", $h1>=12?$h1-12:$h1).':'.$t1[1].' ~ '.($h2<12?'AM ':'PM ').sprintf("%02d", $h2>=12?$h2-12:$h2).':'.$t2[1];
				?>
				<span class="meta-time"><i class="axi axi-schedule"></i> <?php echo $ts;?></span>
				<?php }?>
			</div>

			<div class="post_act">
				<a href="<?php echo NFE_URL;?>/include/location_view.php" class="pact-btn pact-default"><i class="axi axi-list"></i> 업체목록</a>
				<?php if($env['use_shop_guide']) {?>
				<a href="#tab3-" class="pact-btn pact-default"><i class="axi axi-rate-review"></i> 이용후기</a>
				<?php }?>
				<?php if($shop_row['wr_phone']) { ?>
				<a href="tel:<?php echo preg_replace('/[^0-9]/', '', $shop_row['wr_phone']);?>" class="pact-btn pact-phone">
					<i class="axi axi-phone"></i> <?php echo $shop_row['wr_phone'];?>
				</a>
				<?php } ?>
				<?php if($shop_row['wr_hphone'] && $shop_row['wr_hphone'] != $shop_row['wr_phone']) { ?>
				<a href="tel:<?php echo preg_replace('/[^0-9]/', '', $shop_row['wr_hphone']);?>" class="pact-btn pact-phone">
					<i class="axi axi-phone"></i> <?php echo $shop_row['wr_hphone'];?>
				</a>
				<?php } ?>
				<?php
				$sns_info_btns = unserialize($shop_row['sns_info']);
				$sns_len = isset($sns_info_btns['name']) ? count($sns_info_btns['name']) : 0;
				for($si=0; $si<$sns_len; $si++) {
					$sname = $sns_info_btns['name'][$si];
					$saddr = $nf_util->get_domain($sns_info_btns['address'][$si]);
					$is_tg = (stripos($sname,'telegram')!==false || stripos($sname,'tg')!==false);
					$is_ka = (stripos($sname,'kakao')!==false);
					$btn_cls = $is_tg ? 'pact-tg' : ($is_ka ? 'pact-kakao' : 'pact-sns');
					$icon = $is_tg ? 'axi-send' : 'axi-link';
				?>
				<a href="<?php echo $saddr;?>" target="_blank" class="pact-btn <?php echo $btn_cls;?>">
					<i class="axi <?php echo $icon;?>"></i> <?php echo $sns_info_btns['address'][$si];?>
				</a>
				<?php } ?>
				<?php if($shop_row['wr_doro'] || $shop_info['address_txt']) { ?>
				<button type="button" class="pact-btn pact-addr" onClick="nf_util.text_copy($('#address_copy')[0], '주소복사가 완료되었습니다.')">
					<i class="axi axi-content-copy"></i> 주소복사
				</button>
				<input type="text" id="address_copy" value="(<?php echo $shop_row['wr_doro'];?>) <?php echo $shop_info['address_txt'];?>" style="display:none;" />
				<?php } ?>
				<button type="button" class="scrap-star- pact-btn pact-fav" no="<?php echo $shop_row['no'];?>" code="shop">
					<i class="axi <?php echo $nf_util->is_scrap($shop_row['no'], 'shop') ? 'axi-star3' : 'axi-star-o';?>"></i> 즐겨찾기
				</button>
				<a href="javascript:;" onClick="nf_shop.good(this, 'shop', '<?php echo intval($shop_row['no']);?>')" class="pact-btn pact-like">
					<i class="axi <?php echo $nf_util->is_good($shop_row['no'], 'shop') ? 'axi-heart2' : 'axi-heart';?>"></i> 좋아요 <span class="int--"><?php echo number_format($shop_row['wr_good']);?></span>
				</a>
				<?php if($env['use_message'] && (!$member['no'] || ($member['mb_message_view'] && $get_member['mb_message_view']))) {?>
				<button type="button" class="pact-btn pact-msg" onClick="nf_shop.click_memo('<?php echo $shop_row['no'];?>')"><i class="axi axi-mail"></i> 쪽지</button>
				<?php }?>
				<button type="button" class="pact-btn pact-report" onClick="nf_util.open_group('.report-')"><i class="axi axi-bell"></i> 신고</button>
			</div>

			<?php
			$icon_arr = array_diff(explode(",", $shop_row['wr_icon']), array(""));
			if(count($icon_arr)>0) {
			?>
			<ul class="post_icons">
				<?php foreach($icon_arr as $k=>$v) { ?>
				<li><?php echo $shop_icon_arr[$v];?></li>
				<?php } ?>
			</ul>
			<?php }?>
		</div>

		<ul class="tab">
			<li class="on"><a href="#tab1-">업체안내</a></li>
			<?php if($price_cnt>0) {?><li><a href="#tab2-">요금안내</a></li><?php }?>
			<?php if($env['use_shop_guide']) {?><li><a href="#tab3-">이용후기</a></li><?php }?>
			<?php if($env['use_shop_qna']) {?><li><a href="#tab4-">Q&A</a></li><?php }?>
			<?php if(trim(stripslashes($shop_row['wr_movie']))) {?><li><a href="#tab5-">동영상</a></li><?php }?>
		</ul>
		<img style="max-width: 800px; width: 100%;" src="../data/water.png">
		
		<div id="tab1-" style="position:absolute;margin-top:-180px;"></div>
		<div class="tab1">
			<div class="pc-content- editor-css-"><?php echo stripslashes($shop_row['wr_content']);?></div>
			<div class="mobile-content- editor-css-"><?php echo trim(strip_tags($shop_row['wr_m_content'], '<img>')) ? stripslashes($shop_row['wr_m_content']) : stripslashes($shop_row['wr_content']);?></div>
		</div>

		<div class="tab_wrap">
			<div id="tab2-" style="position:absolute;margin-top:-150px;"></div>
			<?php
			if($price_cnt>0) {
			?>
			<div class="tab2">
				<h3><span>요금 & 코스</span></h3>
				<div class="list">
					<?php
					if(is_array($price_un['title'])) { foreach($price_un['title'] as $k=>$v) {
					?>
					<h4><?php echo $v;?></h4>
					<ul>
						<?php
						if(is_array($price_un['subject'][$k])) { foreach($price_un['subject'][$k] as $k2=>$v2) {
							$price_head_arr = explode(",", $price_un['head'][$k]);
						?>
						<li>
							<dl class="course">
								<dt><?php echo $v2;?></dt>
								<dd><?php echo $price_un['sub'][$k][$k2];?></dd>
							</dl>
							<div class="price_wrap">
								<?php
								if(is_Array($price_head_arr)) { foreach($price_head_arr as $k3=>$v3) {
									$price_use = $price_un['price_use'][$k]['k_'.$k2][$k3];
									$sale = $price_un['sale'][$k]['k_'.$k2][$k3];
									$ori_price = intval($price_un['ori_price'][$k]['k_'.$k2][$k3]);
									$price = intval($price_un['price'][$k]['k_'.$k2][$k3]);
									if($price_use) {
								?>
								<dl class="price_info">
									<?php if($v3) {?><dt class="option_name line1"><?php echo $v3;?></dt><?php }?>
									<?php if($sale) {?><dt><span class="sale"><?php echo $sale;?>%</span><span class="d_price"><?php echo number_format($ori_price);?>원</span></dt><?php }?>
									<dd class="price"><span><?php echo number_format($price);?></span>원</dd>
								</dl>
								<?php
									}
								} }?>
							</div>
						</li>
						<?php
						} }?>
					</ul>
					<?php
					} }?>
				</div>
			</div>
			<?php
			}?>
			
			<div class="tab_wrap2">
				<?php
				if(trim($shop_row['wr_notice'])) {
				?>
				<div class="tab3">
					<h3><span>공지사항</span></h3>
					<div class="editor-css-">
						<?php echo stripslashes($shop_row['wr_notice']);?>
					</div>
				</div>
				<?php
				}

				$length = count($sns_info_un['name']);
				if($length>0) {
				?>
				<img style="max-width: 800px; width: 100%;" src="../data/water.png">
				<div class="tab4">
					<h3><span>SNS</span></h3>
					<ul class="s_sns">
						<?php
						for($i=0; $i<$length; $i++) {
						?>
						<li>
							<a href="<?php echo $nf_util->get_domain($sns_info_un['address'][$i]);?>" target="_blank">
								<dl>
									<dt><img src="../images/icon/sns_<?php echo $sns_info_un['name'][$i];?>.png" alt=""></dt>
									<dd><?php echo $nf_util->sns_array[$sns_info_un['name'][$i]];?></dd>
								</dl>
							</a>
						</li>
						<?php
						}?>
					</ul>
				</div>
				<?php
				}


				if($shop_info['coupon_use']) {
				?>
				<div class="tab5">
					<h3><span>할인쿠폰</span></h3>
					<div>
						<dl>
							<dt><?php echo $nf_util->get_text($shop_row['coupon_subject']);?></dt>
							<dd><b>[사용기한]</b> <?php echo $shop_row['coupon_date1'];?> ~ <?php echo $shop_row['coupon_date2'];?></dd>
						</dl>
						<ul>
							<li><span><?php echo number_format($shop_row['coupon_price']);?></span>원 할인쿠폰</li>
							<li><a href="<?php echo NFE_URL;?>/include/event.php?no=<?php echo $shop_row['no'];?>"><button type="button">쿠폰 받으러 가기</button></a></li>
						</ul>
					</div>
				</div>
				<?php
				}?>
			</div>
		</div>
		<?php if($env['use_shop_guide']) {?>
		<div id="tab3-" style="position:absolute;margin-top:-100px;"></div>
		<div class="s_review" id="tab3-">
			<?php
			$q = "nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`view`=1 and ng.`pno`=".intval($shop_row['no'])." ".$nf_shop->shop_where.$nf_shop->service_where2;
			$order = " order by ng.`no` desc";
			if($_GET['sort']=='rdate') $order = " order by ng.`rdate` desc";
			if($_GET['sort']=='star_up') $order = " order by ng.`point` desc";
			if($_GET['sort']=='star_down') $order = " order by ng.`point` asc";
			$total = $db->query_fetch("select count(*) as c from ".$q);

			$guide_arr = array();
			$guide_arr['num'] = 10;
			$guide_arr['tema'] = 'B';
			if($_GET['page_row']>0) $guide_arr['num'] = intval($_GET['page_row']);
			$guide_arr['total'] = $total['c'];
			$guide_arr['anchor'] = '#tab3-';
			$guide_paging = $nf_util->_paging_($guide_arr);

			$guide_query = $db->_query("select * from ".$q.$order." limit ".$guide_paging['start'].", ".$guide_arr['num']);
			?>
			<div id="guide_sort-"></div>
			<div class="rhead">
				<h3>
					이용후기<span>(<?php echo number_format($guide_arr['total']);?>개의 후기)</span>
					<p>
						<?php if($env['use_shop_point']) {?>
						<?php echo $nf_shop->get_point_avg_img($shop_row['wr_avg_point']);?>
						<b><?php echo $shop_row['wr_avg_point'];?></b>
						<?php }?>
					</p>
				</h3>
				<ul>
					<li>
						<select name="sort" onChange="ch_sort(this)">
							<option value="rdate" <?php echo $_GET['sort']=='rdate' ? 'selected' : '';?>>최신등록순</option>
							<option value="star_up" <?php echo $_GET['sort']=='star_up' ? 'selected' : '';?>>별점높은순</option>
							<option value="star_down" <?php echo $_GET['sort']=='star_down' ? 'selected' : '';?>>별점낮은순</option>
						</select>
					</li>
					<li><a href="<?php echo NFE_URL;?>/shop/review_form.php?pno=<?php echo $_GET['no'];?>"><button type="button">리뷰작성</button></a></li>
				</ul>
			</div>
			<div class="rbody">
				<ul>
					<?php
					switch($guide_arr['total']<=0) {
						case true:
					?>
					<li class="no_info">작성된 이용후기 내역이 없습니다. </li>
					<?php
						break;

						default:
							while($guide_row=$db->afetch($guide_query)) {
					?>
					<li class="guid-li- guide-<?php echo $guide_row['no'];?>-">
						<div>
							<p class="user"><?php if($env['use_shop_point']) {?><span><i class="axi axi-star3"></i><?php echo $guide_row['point'];?></span><?php } echo $nf_util->get_text("익명");?><em><?php echo substr($guide_row['rdate'], 0, 10);?></em></p>
							<div class="c_btn">
								<button type="button" onClick="nf_shop.good(this, 'guide', '<?php echo $guide_row['no'];?>')" good="1"><i class="axi axi-thumbs-o-up"></i><span class="int--"><?php echo number_format($guide_row['good']);?></span></button>
								<button type="button" onClick="nf_shop.good(this, 'guide', '<?php echo $guide_row['no'];?>')" good="0"><i class="axi axi-thumbs-o-down"></i><span class="int--"><?php echo number_format($guide_row['bad']);?></span></button>
							</div>
						</div>
						<?php if($env['use_shop_guide_view_auth'] && !$member['no']) {?>
						<div class="ess_login">
							로그인 후 이용 후기를 보실 수 있습니다.
						</div>
						<?php } else {?>
						<a href="<?php echo NFE_URL;?>/board/review_view.php?no=<?php echo $guide_row['no'];?>"><!--리뷰 상세페이지로 링크 잡아주세요-->
							<p class="title line1"><?php echo $nf_util->get_text($guide_row['subject']);?></p>
							<p class="r_con line2"><?php echo $nf_util->get_text($guide_row['content']);?></p>
						</a>
						<?php if($guide_row['answer']) {?>
						<button type="button" onClick="nf_shop.click_guide('<?php echo $guide_row['no'];?>')" class="all_comu">답변<img src="../images/ic/r_arrow.png" alt="답변펼치기"></button>
						<div class="answer" style="display:none;">
							<p><?php echo $nf_util->get_text($guide_row['a_content']);?></p>
						</div>
						<?php }?>
						<?php }?>
					</li>
					<?php
							}
						break;
					}
					?>
				</ul>
			</div>
			<div><?php echo $guide_paging['paging'];?></div>
		</div>
		<?php
		}?>

		<?php if($env['use_shop_qna']) {?>
		<div id="tab4-" style="position:absolute;margin-top:-160px;"></div>
		<div class="s_qa">
			<?php
			$q = "nf_shop as ns right join nf_qna as nq on ns.`no`=nq.`pno` where nq.`pno`=".intval($shop_row['no'])." ".$nf_shop->shop_where.$nf_shop->service_where2;
			$order = " order by nq.`no` desc";
			$total = $db->query_fetch("select count(*) as c from ".$q);

			$qna_arr = array();
			$qna_arr['num'] = 10;
			$qna_arr['tema'] = 'B';
			if($_GET['page_row']>0) $qna_arr['num'] = intval($_GET['page_row']);
			$qna_arr['total'] = $total['c'];
			$qna_arr['anchor'] = '#tab3-';
			$qna_paging = $nf_util->_paging_($qna_arr);

			$qna_query = $db->_query("select * from ".$q.$order." limit ".$qna_paging['start'].", ".$qna_arr['num']);
			?>
			<div class="rhead">
				<h3>
					Q & A<span>(<?php echo number_format($total['c']);?>개의 문의)</span>
				</h3>
				<ul>
					<li><a href="<?php echo NFE_URL;?>/shop/qna_form.php?pno=<?php echo $_GET['no'];?>"><button type="button">문의하기</button></a></li>
				</ul>
			</div>
			<div class="rbody">
				<ul>
					<?php
					switch($qna_arr['total']<=0) {
						case true:
					?>
					<li class="no_info">작성된 Q & A 내역이 없습니다. </li>
					<?php
						break;

						default:
							while($qna_row=$db->afetch($qna_query)) {
					?>
					<li>
						<p class="user"><span class="a_<?php echo $qna_row['answer'] ? 'ok' : 'no';?>"><?php echo $qna_row['answer'] ? '답변완료' : '답변대기';?></span><?php if($qna_row['secret']) {?><i class="axi axi-lock2"></i><?php }?><?php echo $nf_util->get_text($qna_row['nick']);?><em><?php echo substr($qna_row['rdate'], 0, 10);?></em></p><!--비회원일시 이름값 일부 출력 ex) 홍길동 → 홍OO-->
						<em>
						<?php
						include NFE_PATH.'/include/qna_part.inc.php';
						?>
						</em>
						<?php
						/*
						?>
						<!--비밀글입니다 ess_login 영역 클릭시 나타나는 레이아웃-->
						<div class="pass_regist">
							<b>비밀번호</b> : <input type="password"><button type="button">확인</button><button type="button"	class="gray">취소</button>
						</div>
						<!--//비밀글입니다 ess_login 영역 클릭시 나타나는 레이아웃-->
						*/
						?>
					</li>
					<?php
							}
						break;
					}
					?>
				</ul>
			</div>
			<div><?php echo $qna_paging['paging'];?></div>
		</div>
		<?php
		}?>

		<?php
		if(trim(stripslashes($shop_row['wr_movie']))) {
		?>
		<div id="tab5-" style="position:absolute;margin-top:-160px;"></div>
		<div class="s_video" id="tab5-">
			<div class="rhead">
				<h3>
					동영상
				</h3>
				<div>
					<?php echo stripslashes($shop_row['wr_movie']);?>
				</div>
			</div>
		</div>
		<?php
		}?>
	</div>

	<?php
	$_phone = $shop_row['wr_phone'];
	if(!$_phone) $_phone = $shop_row['wr_hphone'];
	$is_phone = strtr($_phone, array("-"=>""));
	?>
	<!--//wrap1400-->
	<ul class="mobile_call">
		<?php if(strtr($shop_row['wr_hphone'], array("-"=>""))) {?><li><a href="sms:<?php echo $shop_row['wr_hphone'];?>"><button type="button" onClick="nf_shop.click_tel('sms', '<?php echo $shop_row['no'];?>')"><i class="axi axi-mail2"></i>문자발송</button></a></li><?php }?>
		<?php if($is_phone) {?><li><a href="tel:<?php echo $_phone;?>"><button type="button" onClick="nf_shop.click_tel('phone', '<?php echo $shop_row['no'];?>')"><i class="axi axi-phone-in-talk"></i>전화걸기</button></a></li><?php }?>
	</ul>
</div>

<script>
/* 모바일 업체명 고정바: post_header가 뷰포트 위로 사라지면 표시 */
(function(){
    var bar = document.getElementById('shop-sticky-name-');
    if(!bar) return;

    // PC는 작동 안 함
    if(window.innerWidth >= 1024) return;
    bar.style.display = 'flex';

    var trigger = document.querySelector('.post_header');
    if(!trigger) return;

    if('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries){
            // post_header 하단이 고정바 아래로 완전히 올라갔을 때 표시
            var bottom = entries[0].boundingClientRect.bottom;
            if(bottom < 50) {
                bar.classList.add('ssn-on-');
            } else {
                bar.classList.remove('ssn-on-');
            }
        }, { threshold: [0, 1] });
        obs.observe(trigger);
    } else {
        // IntersectionObserver 미지원 폴백 (구형 안드로이드)
        var baseTop = trigger.getBoundingClientRect().top + window.pageYOffset + trigger.offsetHeight;
        window.addEventListener('scroll', function(){
            if(window.pageYOffset > baseTop) bar.classList.add('ssn-on-');
            else bar.classList.remove('ssn-on-');
        }, { passive: true });
    }
})();

/* 헤더 스크롤-숨김: 아래로 스크롤시 상단 메뉴바 숨김, 위로 스크롤시 복원 */
(function(){
    if(window.innerWidth >= 1024) return;
    var hdr = document.querySelector('.header_menu');
    var ssn = document.getElementById('shop-sticky-name-');
    if(!hdr) return;

    var lastY = window.pageYOffset;
    var hdrH  = hdr.offsetHeight || 50;
    /* 모바일 breakpoint별 기본 top (업체명 고정바 위치 동기화) */
    function ssnDefaultTop() {
        return window.innerWidth >= 769 ? 80 : 50;
    }

    window.addEventListener('scroll', function(){
        var y = window.pageYOffset;
        if(y > lastY && y > hdrH) {
            /* 아래로 스크롤 → 헤더 숨김 */
            hdr.classList.add('nav-hidden-');
            if(ssn && ssn.classList.contains('ssn-on-')) {
                ssn.style.top = '0px';  /* 헤더 없어지면 바로 최상단 */
            }
        } else {
            /* 위로 스크롤 → 헤더 복원 */
            hdr.classList.remove('nav-hidden-');
            if(ssn) ssn.style.top = ssnDefaultTop() + 'px';
        }
        lastY = y;
    }, {passive: true});
})();
</script>
<?php
include NFE_PATH.'/include/footer.php';
?>