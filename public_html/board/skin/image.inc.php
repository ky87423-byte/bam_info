<?php
$img_w = $bo_row['bo_image_width']>0 ? 'width:'.intval($bo_row['bo_image_width']).'px;' : '';
?>
<li>
	<a href="<?php echo $b_info['a_href'];?>">
		<p class="img" style="background-image:url(<?php echo $b_info['wr_thumb_img'];?>);<?php echo $img_w;?>"></p>
		<dl>
			<dt><?php echo $b_info['list_subject'];?></dt>
			<dd><?php echo date("Y-m-d H:i", strtotime($row['wr_datetime']));?></dd>
		</dl>
	</a>
</li>