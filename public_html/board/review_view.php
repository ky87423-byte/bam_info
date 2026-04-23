<?php
$_site_title_ = "이용후기 상세";
include_once "../engine/_core.php";
if (!$env['use_shop_guide']) {
	die($nf_util->move_url($nf_util->page_back(), "사용하지 않는 기능입니다."));
}

$_where = " and ng.`view`=1 " . $nf_shop->shop_where . $nf_shop->service_where2;
$guide_row = $db->query_fetch("select * from nf_shop as ns right join nf_guide as ng on ns.`no`=ng.`pno` where ng.`no`=" . intval($_GET['no']) . $_where);
if (!$guide_row) {
	die($nf_util->move_url($nf_util->page_back(), "후기정보가 없습니다."));
}

if ($env['use_shop_guide_view_auth'] && !$member['no']) {
	die($nf_util->move_url(NFE_URL . '/member/login.php', "회원만 가능합니다."));
}

$shop_info = $nf_shop->shop_info($guide_row);
$category_k_arr = explode(",", $shop_info['category_arr'][0]);
$_site_title_ = $shop_info['area_end'] . ' ' . $cate_array['job_part'][$category_k_arr[0]] . ' ' . $guide_row['wr_company'];
$_site_image_ = NFE_URL . '/data/shop/' . $shop_info['wr_main_photo'];
$_site_content_ = $guide_row['wr_subject'];

include '../include/header_meta.php';
include '../include/header.php';

$shop_info = $nf_shop->shop_info($guide_row);
$db->_query("update nf_guide set `visit`=`visit`+1 where `no`=" . intval($guide_row['no']));

$m_title = "이용후기 상세";
include NFE_PATH . '/include/m_title.inc.php';

$b_row = $db->query_fetch("select * from nf_guide where `no`=".intval($_GET['no']));

$my_btn_view = $member['no'] && $b_row['mno'] == $member['no'] || !$b_row['mno'] && !$b_row['wr_id'] ? true : false;
?>
<style type="text/css">
	xmp {
		line-height: 2;
		font-size: 15px;
	}
</style>
<script type="text/javascript">
	var click_good = function (el, code, no) {
		$.post("../include/regist.php", "mode=click_guide_good&code=" + code + "&no=" + no, function (data) {
			data = $.parseJSON(data);
			if (data.msg) alert(data.msg);
			if (data.move) location.href = data.move;
			if (data.js) eval(data.js);
		});
	}
</script>
<section class="sub wrap1400" style="margin-top:2rem;">
	<div class="subcon_area">
		<section class="commu view">
			<div class="view_wrap">
				<h2><?php echo $nf_util->get_text($guide_row['subject']); ?></h2>
				<div class="cmt_view_hd">
					<ul class="cmt_view_info">
						<li class="id"><?php echo $nf_util->get_text("익명"); ?></li>
						<li class="date"><?php echo substr($guide_row['rdate'], 0, 10); ?></li>
						<li>추천 : <em
								class="view-good-int-"><?php echo number_format($guide_row['wr_guide_good']); ?></em>
						</li>
					</ul>
					<ul class="cmt_view_fnc">
						<li class="sns_gp">
							<?php
							ob_start();
							include NFE_PATH . '/include/etc/sns.inc.php';
							$sns_link_tag = strtr(ob_get_clean(), array('<li>' => '<span>', '</li>' => '</span>'));
							echo $sns_link_tag;
							?>
						</li>
					</ul>
				</div>
			</div>
			<div class="review_shop">
				<div class="shop_info">
					<p><img src="<?php echo NFE_URL . $shop_info['main_photo_url']; ?>" alt="샵이미지"></p>
					<div>
						<p class="shop_name line1"><?php echo $nf_util->get_text($guide_row['wr_company']); ?></p>
						<p class="shop_title line1"><?php echo $nf_util->get_text($guide_row['wr_content']); ?></p>
						<ul class="ev">
							<?php if ($env['use_shop_point']) { ?>
								<li class="star"><i class="axi axi-star3"></i>평점 <?php echo $guide_row['wr_avg_point']; ?>
								</li><?php } ?>
							<li class="heart line1"><i class="axi axi-heart2"></i>좋아요
								<?php echo $guide_row['wr_good']; ?></li>
							<li class="shop_infor line1"><a
									href="<?php echo NFE_URL; ?>/shop/index.php?no=<?php echo $guide_row['pno']; ?>">업체정보보기</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="shop_info2">
					<dl>
						<dt>업체지역</dt>
						<dd><?php echo strtr(strtr($guide_row['wr_area'], array("," => " ")), $cate_array['area']); ?></dd>
					</dl>
					<dl>
						<dt>업체주소</dt>
						<dd>(<?php echo $guide_row['wr_doro']; ?>) <?php echo $shop_info['address_txt']; ?></dd>
					</dl>
					<dl>
						<dt>전화번호</dt>
						<dd><?php echo $guide_row['wr_phone']; ?> / <?php echo $guide_row['wr_hphone']; ?></dd>
					</dl>
				</div>
			</div>
			<div class="cmt_view_con">
				<?php if ($env['use_shop_point']) { ?>
					<div class="my_star">
						<span>작성자 평점</span> <?php echo $nf_shop->get_point_avg_img($guide_row['point']); ?>
					</div>
				<?php } ?>
				<p><?php echo stripslashes($guide_row['content']); ?></p>
				<ul class="about_post">
					<li>
						<button type="button" onClick="nf_shop.good(this, 'guide', '<?php echo $guide_row['no']; ?>')"
							good="1">
							<dl>
								<dt><i class="axi axi-thumbs-o-up"></i></dt>
								<dd class="int--"><?php echo number_format($guide_row['good']); ?></dd>
							</dl>
						</button>
					</li>
					<li>
						<button type="button" onClick="nf_shop.good(this, 'guide', '<?php echo $guide_row['no']; ?>')"
							good="0">
							<dl>
								<dt><i class="axi axi-thumbs-o-down"></i></dt>
								<dd class="int--"><?php echo number_format($guide_row['bad']); ?></dd>
							</dl>
						</button>
					</li>
				</ul>
			</div>
			<div class="cmt_view_bottom">
				<ul>
					<li><a href="/include/review.php">목록보기</a></li>
					<?php if ($my_btn_view) { ?>
						<li><a href="<?php echo NFE_URL; ?>/shop/review_form.php?no=<?php echo $guide_row['no']; ?>"><button
									type="button">수정</button></a></li>
						<li><a><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')"
									no="<?php echo $guide_row['no']; ?>" mode="delete_guide"
									url="../include/regist.php">삭제</button></a></li>
					<?php } ?>
					<?php if ($nf_board->auth($bo_table, 'reply') && in_array($bo_row['bo_type'], array('text', 'talk'))) { ?>
						<li><a
								href="<?php echo NFE_URL; ?>/board/write.php?bo_table=<?php echo $bo_table; ?>&code=reply&no=<?php echo $b_row['wr_no']; ?>">답변</a>
						</li><?php } ?>
				</ul>
			</div>

			<?php if ($guide_row['answer']) { ?>
				<div class="review_answer"><!--리뷰 답변-->
					<h2><i class="axi-ion-chatbox-working"></i>업체 답변</h2>
					<div>
						<?php echo stripslashes($guide_row['a_content']); ?>
					</div>
				</div>
			<?php } ?>
		</section>
	</div>
</section>
<!--푸터영역-->
<?php
include '../include/footer.php';
?>