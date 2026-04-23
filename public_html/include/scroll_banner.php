	<div class="slide_banner">
		<div class="left_banner banner_con">
			<div class="banner_con">
				<div class="banner_wrap2">
					<div class="banner_img banner-group-br-">
						<?php
						$banner_arr = $nf_banner->banner_view('common_A');
						echo $banner_arr['tag'];
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="right_banner banner_con">
			<div class="banner_con">
				<div class="banner_wrap2">
					<div class="banner_img banner-group-br-">
						<?php
						$banner_arr = $nf_banner->banner_view('common_B');
						echo $banner_arr['tag'];
						?>
					</div>
				</div>
			</div>
		</div>
	</div>