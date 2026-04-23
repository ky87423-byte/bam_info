<div class="m_my_topbox">
<form>
	<ul>
		<li><button type="button" onclick="nf_util.all_check('#check_all', '.chk_')">전체선택</button><input type="checkbox" id="check_all" style="display:none"></li>
		<li><button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../include/regist.php" mode="delete_select_shop" tag="chk[]" check_code="checkbox">선택삭제</button></li>
	</ul>
	<div class="search">
		<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>"><button type="submit">검색</button>
	</div>
</form>
</div>
<h2>업체등록 현황 <a href="<?php echo NFE_URL;?>/mypage/list.php">더보기+</a></h2>
<ul class="s_common shop_current">
	<?php
	switch($total['c']<=0) {
		case true:
	?>
	<li class="no_info">등록된 업체정보가 없습니다.</li>
	<?php
		break;

		default:
			while($shop_row=$db->afetch($shop_query)) {
				$shop_info = $nf_shop->shop_info($shop_row);
				$get_service_info = $nf_payment->get_service_info($shop_row, 'shop');
	?>
	<li>
		<input type="checkbox" name="chk[]" class="chk_" value="<?php echo $shop_row['no'];?>">
		<div class="shop_info">
			<a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>">
				<div class="box01">
					<p class="img"><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지"></p>
					<div>
						<dl>
							<dt><b>[<?php echo $shop_info['area_end'];?>]</b><?php echo $nf_util->get_text($shop_row['wr_company']);?></dt>
							<dd>(<?php echo $shop_row['wr_doro'];?>) <?php echo $shop_info['address_txt'];?> / Tell.<?php echo $shop_row['wr_phone'].'/'.$shop_row['wr_hphone'];?></dd>
						</dl>
						<ul class="ev">
							<?php if($env['use_shop_point']) {?><li class="star"><i class="axi axi-star3"></i><?php echo $shop_row['wr_avg_point'];?></li><?php }?>
							<li class="heart"><i class="axi axi-heart2"></i><?php echo number_format($shop_row['wr_good']);?></li>
							<?php if($env['use_shop_guide']) {?><li class="commu"><i class="axi axi-ion-chatbubble-working"></i><?php echo $shop_row['wr_guide_int'];?></li><?php }?>
						</ul>
					</div>
				</div>
			</a>
			<?php /*<div class="box02">
				<p>서비스 이용내역<button type="button" onClick="location.href='<?php echo NFE_URL;?>/service/product_payment.php?code=shop&no=<?php echo $shop_row['no'];?>';">서비스 연장/추가</button></p>
				<ul class="cash">
					<?php
					foreach($get_service_info['text'] as $k=>$v) {
						$get_date = $get_service_info['date'][$k];
						$txt = in_array($v, array('점프')) ? $v : $v."업체";
						switch($v) {
							case "점프":
								$service_date = number_format($shop_row['wr_service_jump_int']).'건';
							break;

							default:
								$service_date = "~".date("Y/m/d", strtotime($get_date));
							break;
						}
					?>
					<li><?php echo $txt;?> <span>(<?php echo $service_date;?>)</span></li>
					<?php
					}?>
				</ul>
			</div>*/?>
		</div>
		<dl class="price_info">
			<dt>
				<?php if($shop_row['wr_sale']>0) {?><span class="sale"><?php echo $shop_row['wr_sale'];?>%</span><?php }?>
				<?php if($shop_row['wr_price1']>0) {?><span class="d_price"><?php echo number_format($shop_row['wr_price1']);?>원</span><?php }?>
			</dt>
			<dd><span><?php echo number_format($shop_row['wr_price']);?></span>원</dd>
		</dl>
		<?php if($env['use_shop_write']) {?>
		<ul class="t_edit">
			<li><a href="<?php echo NFE_URL;?>/mypage/regist.php?no=<?php echo $shop_row['no'];?>"><button type="button">수정</button></a></li>
			<li><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($shop_row['no']);?>" mode="delete_shop" url="../include/regist.php">삭제</button></li>
		</ul>
		<?php }?>
		<ul class="jumb_con">
			<li>
				<dl>
					<dt>자동점프</dt>
					<dd>
						<label><input type="radio" name="wr_jump_use_<?php echo $shop_row['no'];?>" onClick="nf_shop.jump_use(this, 'shop', '<?php echo $shop_row['no'];?>')" value="1" <?php echo $shop_row['wr_jump_use']==='1' ? 'checked' : '';?>>사용</label>
						<label><input type="radio" name="wr_jump_use_<?php echo $shop_row['no'];?>" onClick="nf_shop.jump_use(this, 'shop', '<?php echo $shop_row['no'];?>')" value="0" <?php echo $shop_row['wr_jump_use']==='0' ? 'checked' : '';?>>미사용</label>
					</dd>
				</dl>
			</li>
			<li>
				<dl>
					<dt>남은 점프횟수</dt>
					<dd><span class="shop_jump_int- shop_jump_int-<?php echo $shop_row['no'];?>-"><?php echo number_format(intval($shop_row['wr_service_jump_int']));?></span></dd>
				</dl>
			</li>
			<li><button type="button" onClick="nf_shop.click_jump('shop', '<?php echo $shop_row['no'];?>')">점프하기</button></li>
		</ul>
	</li>
	<?php
			}
		break;
	}
	?>
</ul>