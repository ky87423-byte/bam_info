<div class="m_my_topbox">
	<ul>
		<li><button type="button" onClick="nf_util.all_check('#check_all', '.chk_')">전체선택</button><input type="checkbox" id="check_all" style="display:none;" /></li>
		<li><button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_scrap" para="code=shop" tag="chk[]" check_code="checkbox">선택삭제</button></li>
	</ul>
	<div class="search">
		<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>"><button type="button">검색</button>
	</div>
</div>
<h2>스크랩 <a href="<?php echo NFE_URL;?>/mypage/scrap.php">더보기+</a></h2>
<ul class="s_common shop_scrap">
	<?php
	switch($_arr['total']<=0) {
		case true:
	?>
	<li class="no_info">스크랩 내역이 없습니다.</li>
	<?php
		break;


		default:
			while($scrap_row=$db->afetch($scrap_query)) {
				$shop_info = $nf_shop->shop_info($scrap_row);
	?>
	<li>
		<input type="checkbox" name="chk[]" class="chk_" value="<?php echo $scrap_row['no'];?>">
		<div class="shop_info">
			<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $scrap_row['ns_no'];?>">
				<div class="box01">
					<p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p>
					<div>
						<dl>
							<dt><b>[<?php echo $shop_info['area_end'];?>]</b><?php echo $nf_util->get_text($scrap_row['wr_company']);?></dt>
							<dd>(<?php echo $scrap_row['wr_doro'];?>) <?php echo $shop_info['address_txt'];?> / Tell.<?php echo $scrap_row['wr_phone'];?>, HP.<?php echo $scrap_row['wr_hphone'];?></dd>
						</dl>
						<ul class="ev">
							<li class="star"><i class="axi axi-star3"></i><?php echo $scrap_row['wr_avg_point'];?></li>
							<li class="heart"><i class="axi axi-heart2"></i>5</li>
							<li class="commu"><i class="axi axi-ion-chatbubble-working"></i>13</li>
						</ul>
					</div>	
				</div>
			</a>
		</div>
		<ul class="t_edit">
			<li><button type="button"onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $scrap_row['no'];?>" mode="delete_scrap" url="<?php echo NFE_URL;?>/include/regist.php" para="code=shop">삭제</button></li>
		</ul>
	</li>
	<?php
			}
		break;
	}
	?>
</ul>