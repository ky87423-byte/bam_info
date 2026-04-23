<?php if($qna_view_allow || !$qna_row['secret'] || ($member['no'] && $qna_row['mno']==$member['no'])) {?>
	<p class="title line1"><?php echo $nf_util->get_text($qna_row['subject']);?></p>
	<p class="r_con line2"><?php echo $nf_util->get_text($qna_row['content']);?></p>
	<div class="ed_qa">
		<a href="#none" onClick="nf_shop.qna_modify(this, '<?php echo intval($qna_row['no']);?>')" code="modify">수정</a>|
		<!--<?php echo NFE_URL;?>/shop/qna_form.php?no=<?php echo $qna_row['no'];?>-->
		<a href="#none" onClick="nf_shop.qna_modify(this, '<?php echo intval($qna_row['no']);?>')" code="delete">삭제</a>
	</div>
	<?php if($qna_row['answer']) {?>
	<button type="button" class="all_comu" onClick="click_qna(this)">답변<img src="../images/ic/r_arrow.png" alt="답변펼치기"></button>
	<div class="answer">
		<p><?php echo $nf_util->get_text($qna_row['a_content']);?></p>
	</div>
<?php
}?>
<?php } else {?>
	<div class="ess_login" onClick="click_secret(this, '<?php echo intval($qna_row['no']);?>')">
		비밀글 입니다.
	</div>
<?php }?>