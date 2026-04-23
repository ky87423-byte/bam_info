<div class="customoverlay" no="<?php echo $shop_row['no'];?>">
	<div class="img">
		<img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지">
		<?php if($env['use_shop_industry']) {?><span class="shop_type"><?php echo $shop_info['category_1_txt'];?></span><?php }?>
		<ul class="ev">
			<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $shop_row['wr_avg_point'];?></li><?php }?>
			<li class="heart"><i class="axi axi-heart2"></i><?php echo $shop_row['wr_good'];?></li>
			<li class="location"><i class="axi axi-location-on"></i><?php echo $nf_util->get_distance_int($km);?></li>
		</ul>
	</div>
	<div class="info_wrap">
		<p class="title"><?php echo $nf_util->get_text($shop_row['wr_company']);?></p>
		<p class="add">(<?php echo $shop_row['wr_doro'];?>) <?php echo $shop_info['address_txt'];?></p>
		<?php
		if($env['use_shop_price']) {
		?>
		<ul class="product">
			<?php if($shop_row['wr_sale']>0) {?><li class="sale"><span><?php echo $shop_row['wr_sale'];?></span>%</li><?php }?>
			<li class="price"><span><?php echo number_format($shop_row['wr_price']);?></span>원</li>
			<?php if($shop_row['wr_sale']>0) {?><li class="d_price"><?php echo number_format($shop_row['wr_price1']);?>원</li><?php }?>
		</ul>
		<?php
		}?>
		<button type="button" onClick="window.open('<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>')">상세페이지</button>
	</div>
	
</div>