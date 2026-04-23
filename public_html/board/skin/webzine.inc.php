<tr>
	<td><?php echo $paging['bunho']--;?></td>
	<td class="tal line2">
		<a href="<?php echo $b_info['a_href'];?>">
			<p class="img" style="background-image:url(<?php echo $b_info['wr_thumb_img'];?>);"></p>
			<div class="text_area">
				<p class="li_tit" class=""><?php if($board_info['bo_category_list'] && !$row['wr_reply'] && $row['wr_category']) {?><span class="orange">[<?php echo $row['wr_category'];?>]</span> <?php }?><?php echo $b_info['list_subject'];?></p>
				<p class="li_txt"><?php if(!$row['wr_secret']) echo $nf_util->get_text($b_info['wr_content_txt']);?></p>
			</div>
			<ul class="pc_no">
				<li><?php echo $nf_util->get_text($b_info['get_name']);?></li>
				<li><?php echo substr($row['wr_datetime'],0,10);?></li>
				<li><i class="axi axi-visibility"></i> <?php echo number_format(intval($row['wr_hit']));?></li>
			</ul>
		</a>
	</td>
	<td  class="m_no"><?php echo $nf_util->get_text($b_info['get_name']);?></td>
	<td  class="m_no"><?php echo substr($row['wr_datetime'],0,10);?></td>
	<td  class="m_no"><?php echo number_format(intval($row['wr_hit']));?></td>
</tr>