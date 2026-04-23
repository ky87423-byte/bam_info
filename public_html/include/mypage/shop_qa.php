<div class="m_my_topbox">
	<ul>
		<li><button type="button" onclick="nf_util.all_check('#check_all', '.chk_')">전체선택</button><input type="checkbox" id="check_all" style="display:none;" /></li>
		<li><button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="/include/regist.php" mode="delete_select_qna" tag="chk[]" check_code="checkbox">선택삭제</button></li>
	</ul>
	<div class="search">
		<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>"><button type="submit">검색</button>
	</div>
</div>
<h2>Q&A <a href="<?php echo NFE_URL;?>/mypage/qna.php">더보기+</a></h2>
<ul class="s_common shop_qa">
	<?php
	switch($_arr['total']<=0) {
		case true:
	?>
	<li class="no_info">Q&A 내역이 없습니다.</li>
	<?php
		break;


		default:
			while($qna_row=$db->afetch($qna_query)) {
				$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($qna_row['pno']));
				$shop_info = $nf_shop->shop_info($shop_row);
	?>
	<li class="item-">
		<input type="checkbox" name="chk[]" class="chk_" value="<?php echo $qna_row['no'];?>">
		<div class="shop_info">
			<div class="box01">
				<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>"><p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p></a>
				<div>
					<a href="#none" onClick="a_content_view(this)">
						<dl>
							<dt class="line1"><?php echo $nf_util->get_text($qna_row['subject']);?></dt>
							<dd><b>[<?php echo $shop_info['area_end'];?>]</b><?php echo $nf_util->get_text($shop_row['wr_company']);?></dd>
						</dl>
						<ul class="ev">
							<li><?php echo $nf_util->get_text($qna_row['nick']);?></li>
							<?php if(strtr($qna_row['phone'], array("-"=>""))) {?><li><?php echo $qna_row['phone'];?></li><?php }?>
							<?php if(strtr($qna_row['email'], array("@"=>""))) {?><li><?php echo $qna_row['email'];?></li><?php }?>
							<li><?php echo $qna_row['rdate'];?></li>
						</ul>
					</a>
				</div>	
			</div>			
		</div>
		<div class="answer_status">
			<a href="#none" onClick="a_content_view(this)">
				<?php if($qna_row['answer']) {?>
				<p class="answer_ok">답변완료</p>
				<?php } else {?>
				<p class="answer_no">답변대기</p>
				<?php }?>
			</a>
		</div>
		<ul class="t_edit">
			<li><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $qna_row['no'];?>" mode="delete_qna" url="../include/regist.php">삭제</button></li>
			<?php if($member['no'] && $member['no']==$qna_row['pmno'] && !$qna_row['answer']) {?>
			<li><a href="<?php echo NFE_URL;?>/shop/qna_form.php?qno=<?php echo $qna_row['no'];?>"><button type="button">답변하기</button></a></li>
			<?php }?>
		</ul>
		<div class="shop_qa_q">
			<?php echo stripslashes($qna_row['content']);?>
		</div>
		<?php if($qna_row['answer']) {?>
		<div class="shop_review_a">
			<div class="a_t">
				<p><img src="../images/icon/reply01.gif" alt="답변아이콘">[답변]</p>
				<?php if($member['no'] && $member['no']==$qna_row['pmno']) {?>
				<ul>
					<li><a href="<?php echo NFE_URL;?>/shop/qna_form.php?qno=<?php echo $qna_row['no'];?>"><button type="button">수정</button></a></li>
					<li><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $qna_row['no'];?>" mode="delete_qna_reply" url="../include/regist.php">삭제</button></li>
				</ul>
				<?php }?>
			</div>
			<div class="a">
				<?php echo stripslashes($qna_row['a_content']);?>
			</div>
		</div>
		<?php }?>
	</li>
	<?php
			}
		break;
	}
	?>
</ul>