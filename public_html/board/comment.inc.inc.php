<?php
$txt = nl2br(stripslashes($row['wr_content']));
$is_secret_check = ($is_secret || $is_blind || $is_del) ? true : false;
if($is_secret) $txt = '<a href="javascript:;" onClick="nf_board.auth(this, \'read\', \''.$bo_row['bo_table'].'\', \''.$row['wr_no'].'\')"><i class="axi axi-lock2" class="blue"></i> 비밀글입니다.</a>';
if($is_secret_check) {
	if($is_blind) $txt = '블라인드된 댓글입니다.';
}

switch($is_blind || $is_del) {
	case true:
?>
<p><?php echo $txt;?></p>
<?php
	break;

	default:
?>
<div class="top">
	<p><?php echo $nf_util->get_text($b_info['get_name']);?><span><?php echo $row['wr_datetime'];?></span></p>
	<?php if(!$is_del && !$is_blind) {?>
	<ul>
		<li><a href="#none" onClick="nf_board.click_comment_comment(this, <?php echo intval($row['wr_no']);?>)">답글</a></li>
		<li><a href="#none" onClick="nf_board.auth(this, 'delete', '<?php echo $bo_table;?>', '<?php echo $row['wr_no'];?>')">삭제</a></li> <?/*nf_board.click_delete(this, '<?php echo $bo_table;?>', <?php echo intval($row['wr_no']);?>)*/?>
		<?php if(!$is_secret_check) {?>
		<li><a href="#none" onClick="nf_board.click_report(this, '<?php echo $bo_table;?>', <?php echo intval($row['wr_no']);?>)">신고</a></li>
		<?php }?>
	</ul>
	<?php }?>
</div>
<p>
	<?php
		echo $txt;
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
<div class="bottom">
	<p><button type="button" onClick="nf_board.comment_comment_view(this, '<?php echo $bo_table;?>', <?php echo intval($row['wr_no']);?>)">댓글 (<?php echo number_format(intval($row['wr_comment']));?>)</button></p>
	<?php if(!$is_del && !$is_blind && $bo_row['bo_use_good'] && !$is_secret_check) {?>
	<ul>
		<li><button type="button" onClick="nf_board.good(this, '<?php echo $bo_table;?>', '<?php echo intval($row['wr_no']);?>')"><i class="axi axi-thumbs-o-up"></i><span class="good-int-"><?php echo number_format(intval($row['wr_good']));?></span></button></li>
		<li><button type="button" onClick="nf_board.good(this, '<?php echo $bo_table;?>', '<?php echo intval($row['wr_no']);?>', 'bad')"><i class="axi axi-thumbs-o-down"></i><span class="nogood-int-"><?php echo number_format(intval($row['wr_nogood']));?></span></button></li>
	</ul>
	<?php }?>
</div>

<div class="comment_comment_list-">
	
</div>
<?php
	break;
}?>