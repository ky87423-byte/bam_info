<div class="left_menu">
	<ul>
		<li class="<?php echo $left_my['my_home'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/index.php">마이페이지 홈</a>
		</li>
		<?php
		if($member['mb_type']=='company') {
			if($env['use_shop_write']) {
		?>
		<li class="<?php echo $left_my['shop_regist'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/regist.php">업체등록</a>
		</li>
		<?php
			}
		?>
		<li class="<?php echo $left_my['shop_list'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/list.php">업체등록 현황</a>
		</li>
		<?php
		}?>
		<li class="<?php echo $left_on['couopn'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/mycoupon.php">할인쿠폰</a>
		</li>
		<?php if($env['use_shop_guide']) {?>
		<li class="<?php echo $left_my['guide'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/review.php">이용후기</a>
		</li>
		<?php }?>
		<?php if($env['use_message']) {?>
		<li class="<?php echo $left_my['mail'];?>">
			<a href="<?php echo NFE_URL;?>/member/mail.php">쪽지관리<?php if($my_message_cnt) {?><span class="new">NEW</span><?php }?></a>
		</li>
		<?php }?>
		<?php if($env['use_shop_qna']) {?>
		<li class="<?php echo $left_my['qna'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/qna.php">Q&A</a>
		</li>
		<?php }?>
		<li class="<?php echo $left_my['scrap'];?>">
			<a href="<?php echo NFE_URL;?>/mypage/scrap.php">스크랩</a>
		</li>
		<li class="<?php echo $left_on['point'];?>">
			<a href="<?php echo NFE_URL;?>/member/point_list.php">포인트 내역</a>
		</li>
		<li class="<?php echo $left_on['update_form'];?>">
			<a href="<?php echo NFE_URL;?>/member/update_form.php">회원정보 수정</a>
		</li>
	</ul>
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('common_D');
		echo $banner_arr['tag'];
		?>
	</div>
</div>