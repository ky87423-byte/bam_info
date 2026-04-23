<style>
table.style3.text_list .notice- td {background:#fff9f7; border-bottom-color:#f8dfd9;}
table.style3.text_list .notice- td:nth-child(1) em {background:#f5360a; color:#fff; border-radius:2px; padding:3px 5px; font-size:13px;  font-weight:500;}
</style>
<tr class="<?php echo $notice_list_tr ? 'notice-' : '';?>">
	<td>
		<?php
		if($notice_list_tr) echo '<em>공지</em>';
		else {
			$paging['bunho'] = intval($paging['bunho']);
			echo number_format($paging['bunho']--);
		}
		?>
	</td>
	<td class="tal line1"><a href="<?php echo $b_info['a_href'];?>"><?php if($board_info['bo_category_list'] && !$row['wr_reply'] && $row['wr_category']) {?><span class="orange">[<?php echo $row['wr_category'];?>]</span><?php }?> <?php echo $b_info['reply_depth'].$b_info['list_subject'];?></a>
	<ul class="pc_no">
		<li><?php echo $nf_util->get_text($b_info['get_name']);?></li>
		<li><?php echo substr($row['wr_datetime'],0,10);?></li>
		<li><i class="axi axi-visibility"></i> <?php echo number_format(intval($row['wr_hit']));?></li>
	</ul>
	</td>
	<td class="m_no"><?php echo $nf_util->get_text($b_info['get_name']);?></td>
	<td class="m_no"><?php echo substr($row['wr_datetime'],0,10);?></td>
	<td class="m_no"><?php echo number_format(intval($row['wr_hit']));?></td>
</tr>
