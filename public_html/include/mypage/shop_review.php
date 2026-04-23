<div class="m_my_topbox">
	<ul>
		<li><button type="button" onclick="nf_util.all_check('#check_all', '.chk_')">전체선택</button><input type="checkbox" id="check_all" style="display:none;" /></li>
		<li><button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="/include/regist.php" mode="delete_select_guide" tag="chk[]" check_code="checkbox">선택삭제</button></li>
	</ul>
	<div class="search">
		<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>"><button type="submit">검색</button>
	</div>
</div>
<h2>이용후기 <a href="<?php echo NFE_URL;?>/mypage/review.php">더보기+</a></h2>
<ul class="s_common shop_review">
	<?php
	switch($_arr['total']<=0) {
		case true:
	?>
	<li class="no_info">이용후기 내역이 없습니다.</li>
	<?php
		break;


		default:
			while($guide_row=$db->afetch($guide_query)) {
				$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($guide_row['pno']));
				$shop_info = $nf_shop->shop_info($shop_row);
	?>
	<li class="item-">
		<input type="checkbox" name="chk[]" class="chk_" value="<?php echo $guide_row['no'];?>">
		<div class="shop_info">
			<div class="box01">
				<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>" target="_blank"><p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p></a>
				<div>
					<a href="#none" onClick="a_content_view(this)">
						<dl>
							<dt class="line1"><?php echo $nf_util->get_text($guide_row['subject']);?></dt>
							<dd><b>[<?php echo $shop_info['area_end'];?>]</b><?php echo $nf_util->get_text($shop_row['wr_company']);?></dd>
						</dl>
						<ul class="ev">
							<li><?php echo $nf_util->get_text("익명");?></li>
							<li><?php echo $guide_row['rdate'];?></li>
							<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $guide_row['point'];?></li><?php }?>
						</ul>
					</a>
				</div>	
			</div>			
		</div>
		<ul class="t_edit">
			<?php
			if($member['no']==$guide_row['pmno']) {
				if($guide_row['view']) {
			?>
			<li><button type="button" class="blue" onclick="nf_util.ajax_post(this, '미출력으로 설정하시겠습니까?')" no="<?php echo $guide_row['no'];?>" mode="guide_open_not" url="../include/regist.php">출력</button></li>
			<?php } else {?>
			<li><button type="button" class="red" onclick="nf_util.ajax_post(this, '출력으로 설정하시겠습니까?')" no="<?php echo $guide_row['no'];?>" mode="guide_open" url="../include/regist.php">미출력</button></li>
			<?php
				}
			}?>
			<?php if($member['no'] && $member['no']==$guide_row['pmno'] && !$guide_row['answer']) {?>
			<li><a href="<?php echo NFE_URL;?>/shop/review_form.php?qno=<?php echo $guide_row['no'];?>"><button type="button" class="blue">답변하기</button></a></li> <!--답변완료 됬으면 버튼 사라지기-->
			<?php }?>
			<li><a href="<?php echo NFE_URL;?>/shop/review_form.php?no=<?php echo $guide_row['no'];?>"><button type="button">수정</button></a></li>
			<li><a><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $guide_row['no'];?>" mode="delete_guide" url="../include/regist.php">삭제</button></a></li>
		</ul>
		<div class="shop_review_q">
			<?php echo stripslashes($guide_row['content']);?>
		</div>
		<?php if($guide_row['answer']) {?>
		<div class="shop_review_a">
			<div class="a_t">
				<p><img src="../images/icon/reply01.gif" alt="답변아이콘">[답변]</p>
				<?php if($member['no'] && $member['no']==$guide_row['pmno']) {?>
				<ul>
					<li><a href="<?php echo NFE_URL;?>/shop/review_form.php?qno=<?php echo $guide_row['no'];?>"><button type="button">수정</button></a></li>
					<li><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $guide_row['no'];?>" mode="delete_guide_reply" url="../include/regist.php">삭제</button></li>
				</ul>
				<?php }?>
			</div>
			<div class="a">
				<?php echo stripslashes($guide_row['a_content']);?>
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