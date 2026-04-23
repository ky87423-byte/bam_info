<ul class="re_reply_list">
	<?php
	while($row=$db->afetch($query)) {
		$b_info = $nf_board->info($row, $board_info);
		$parent_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($row['wr_parent']));
		$parent_b_info = $nf_board->info($parent_row, $board_info);

		$is_blind = false;
		$is_del = false;
		if($row['wr_blind']) $is_blind = true;
		if($row['wr_del']) $is_del = true;
	?>
	<li class="comment_li-">
		<div class="top">
			<p><?php echo $nf_util->get_text($b_info['get_name']);?><span><?php echo substr($row['wr_datetime'],0,10);?></span></p>
			<ul>
				<li><a href="#none" onClick="nf_board.click_comment_comment(this, <?php echo intval($row['wr_no']);?>)">답글</a></li>
				<li><a href="#none" onClick="nf_board.auth(this, 'delete', '<?php echo $bo_table;?>', '<?php echo $row['wr_no'];?>')"> 삭제</a></li>
				<li><a href="#none" onClick="nf_board.click_report(this, '<?php echo $bo_table;?>', <?php echo intval($row['wr_no']);?>)"> 신고</a></li>
			</ul>
		</div>
		<p>
			<?php
			if(strlen($row['wr_comment_reply'])>=2 && $bo_row['bo_use_name']!=3) echo '<span class="comment-parent-name-">@'.$nf_util->get_text($parent_b_info['get_name']).' > </span>';
			echo nl2br(stripslashes($row['wr_content']));
			?>
		</p>
		<!--수정 예시-->
		<div class="reply_con_write reply_con_write-comment-" id="comment_write-<?php echo $row['wr_no'];?>">
			<?php if(!$member['no']) {?>
			<div class="input_area">
				<ul>
					<li><label>이름</label><input type="text" class="wr_name-"></li>
					<li><label>비밀번호</label><input type="password" class="wr_password-"></li>
					<li><label>자동등록방지 문자입력</label><input type="text" class="rand_number-">
					<span><img src="<?php echo NFE_URL;?>/include/rand_text.php?no=<?php echo $row['wr_no'];?>&bo_table=<?php echo $bo_table;?>" align="absmiddle"></span>
					</li>
				</ul>
			</div>
			<?php }?>
			<div class="text_area">
				<textarea class="wr_content-" placeholder="댓글을 입력하세요."></textarea>
				<button type="button" onClick="nf_board.comment_comment_insert(this)">등록</button>
			</div>
		</div>
		<!--//수정 예시-->
		<?php if(!$is_del && !$is_blind && $bo_row['bo_use_good']) {?>
		<div class="bottom">
			<ul>
				<li><button type="button" onClick="nf_board.good(this, '<?php echo $bo_table;?>', '<?php echo intval($row['wr_no']);?>')"><i class="axi axi-thumbs-o-up"></i><span class="good-int-"><?php echo number_format(intval($row['wr_good']));?></span></button></li>
				<li><button type="button" onClick="nf_board.good(this, '<?php echo $bo_table;?>', '<?php echo intval($row['wr_no']);?>', 'bad')"><i class="axi axi-thumbs-o-down"></i><span class="nogood-int-"><?php echo number_format(intval($row['wr_nogood']));?></span></button></li>
			</ul>
		</div>
		<?php }?>
	</li>
	<?php
	}?>
</ul>