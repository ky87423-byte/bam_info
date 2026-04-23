<div class="left_menu">
	<ul>
		<li class="<?php echo $left_on['company'];?>">
			<a href="<?php echo NFE_URL;?>/service/company.php">회사소개</a>
		</li>
		<li>
			<a href="<?php echo NFE_URL;?>/service/advert.php">입점문의</a>
		</li>
		<li class="<?php echo $left_on['terms'];?>">
			<a href="<?php echo NFE_URL;?>/service/terms.php">이용약관</a>
		</li>
		<li class="<?php echo $left_on['privacy_policy'];?>">
			<a href="<?php echo NFE_URL;?>/service/privacy_policy.php">개인정보처리방침</a>
		</li>
		<li class="<?php echo $left_on['location_policy'];?>">
			<a href="<?php echo NFE_URL;?>/service/location_policy.php">위치정보수집</a>
		</li>
		<li class="<?php echo $left_on['board_criterion'];?>">
			<a href="<?php echo NFE_URL;?>/service/board_criterion.php">게시판관리기준</a>
		</li>
		<li>
			<a href="<?php echo NFE_URL;?>/service/cs_center.php">고객센터</a>
		</li>
	</ul>
	<div class="banner" style="overflow:hidden;">
		<?php
		$banner_arr = $nf_banner->banner_view('common_E');
		echo $banner_arr['tag'];
		?>
	</div>
</div>