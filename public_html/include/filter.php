<?php
/*
검색하게 되면
가격이 1만원이하 50만원이상은 검색이 안됩니다.
*/
?>
<style type="text/css">
.filter_wrap {}
</style>
<script type="text/javascript">
var click_filter = function(display) {
	$(".filter_wrap").css({"display":display});
}

var reset_filter = function() {
	var form = document.forms['fsearch_main'];
	$(form).find("input[type=text]").val("");
	$(form).find("select").val("");
	$(form).find("input[type=checkbox]").prop("checked", false);
}
</script>
<div class="filter_wrap">
<section class="filter">
	<form name="fsearch_main" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
	<h2><i class="axi axi-ion-android-mixer"></i>필터<button type="button" onClick="click_filter('none')"><i class="axi axi-ion-close-round"></i>닫기</button></h2>
	<div class="f_wrap">
		<div class="f_etc">
			<div class="filter_search">
				<p><i class="axi axi-search3"></i></p><input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>" placeholder="키워드 입력">
			</div>
			<ul class="f_input_list">
				<li><label for="" class="style1"><input type="checkbox" name="company[]" value="time" <?php echo is_array($_GET['company']) && in_array('time', $_GET['company']) ? 'checked' : '';?>><span>영업중인 업체</span></label></li>
				<li class="c_c"><label for="" class="style1"><input type="checkbox" name="company[]" value="coupon" <?php echo is_array($_GET['company']) && in_array('coupon', $_GET['company']) ? 'checked' : '';?>><span>할인쿠폰 업체</span></label></li>
			</ul>
		</div>
		<div class="f_area parent-">
			<h3>지역</h3>
			<?php
			for($i=1; $i<=$area_depth; $i++) {
				$area1 = $_GET['wr_area'][0];
				if($i>1) $area2 = $_GET['wr_area'][1];
				$nf_category->get_area($area1, $area2);
				$area_array = array();
				if($i===1) $area_array = $cate_p_array['area'][0];
				else if($i===2 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['SI'][$area1];
				else if($i===3 && $_GET['wr_area'][$i-1]) $area_array = $cate_area_array['GU'][$area1][$area2];
			?>
			<select name="wr_area[]" wr_type="area" hname="<?php echo $i;?>차지역" needed <?php if($i<$area_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
				<option value=""><?php echo $i;?>차 지역</option>
				<?php
				if(is_array($area_array)) { foreach($area_array as $k=>$v) {
					$selected = $_GET['wr_area'][$i-1]==$v['wr_name'] ? 'selected' : '';
				?>
				<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?> no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
				<?php
				} }?>
			</select>
			<?php
			}?>
		</div>
		<?php if($env['use_shop_industry']) {?>
		<div class="f_type parent-">
			<h3>업종별</h3>
			<?php
			for($i=1; $i<=$job_part_depth; $i++) {
				$cate_k = $i===1 ? 0 : $_GET['category'][$i-2];
			?>
			<select class="check_seconds" name="category[]" hname="<?php echo $i;?>차 카테고리" needed <?php if($i<$job_part_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
				<option value=""><?php echo $i;?>차 카테고리</option>
				<?php
				if(is_array($cate_p_array['job_part'][$cate_k])) { foreach($cate_p_array['job_part'][$cate_k] as $k=>$v) {
					$selected = $_GET['category'][$i-1]==$v['no'] ? 'selected' : '';
				?>
				<option value="<?php echo $v['no'];?>" no="<?php echo $v['no'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
				<?php
				} }?>
			</select>
			<?php
			}?>
		</div>
		<?php
		}
		if($env['use_shop_price']) {
		?>
		<div class="f_pirce">
			<?php
			$range_price = 50;
			if($_GET['range_price']) $range_price = intval($_GET['range_price']);
			?>
			<h3>가격<span>1만원 - <em id="range-price-ch"><?php echo $range_price;?></em>만원</span></h3>
			<!--https://msgkingdom.com/shop/list_around.php?posy=35.14994&posx=126.843325&cos=1100 
				https://www.msgtong.co.kr/pages/_1_around/around.php 참조-->
			<input type="range" name="range_price" min="1" max="50" value="<?php echo $range_price;?>" onmousemove="nf_util.ch_range(this, '#range-price-ch')" onchange="nf_util.ch_range(this, '#range-price-ch')">
		</div>
		<?php
		}
		if($env['use_shop_tema']) {
		?>
		<div class="f_tema">
			<h3>테마</h3>
			<ul class="f_input_list">
				<?php
				if(is_array($cate_array['job_tema'])) { foreach($cate_array['job_tema'] as $k=>$v) {
					$checked = is_array($_GET['tema']) && in_array($k, $_GET['tema']) ? 'checked' : '';
				?>
				<li><label for="" class="style1"><input type="checkbox" name="tema[]" <?php echo $checked;?> value="<?php echo $k;?>"><span><?php echo $v;?></span></label></li>
				<?php
				} }?>
			</ul>
		</div>
		<?php
		}?>
		<div class="f_service">
			<h3>편의 서비스</h3>
			<ul class="f_input_list">
				<?php
				if(is_array($shop_icon_row_arr)) { foreach($shop_icon_row_arr as $k=>$icon_row) {
					$checked = is_array($_GET['icon']) && in_array($k, $_GET['icon']) ? 'checked' : '';
				?>
				<li><label class="style1"><input type="checkbox" name="icon[]" value="<?php echo $k;?>" <?php echo $checked;?>><span><?php echo $nf_util->get_text($icon_row['wr_name']);?></span></label></li>
				<?php
				} }
				?>
			</ul>
		</div>
		<div class="f_pirce">
			<?php
			$range_point = 5;
			if($_GET['range_point']) $range_point = intval($_GET['range_point']);
			?>
			<h3>평점<span>0점 - <em id="range-point-ch"><?php echo $range_point;?></em>점</span></h3>
			<!--https://msgkingdom.com/shop/list_around.php?posy=35.14994&posx=126.843325&cos=1100 
				https://www.msgtong.co.kr/pages/_1_around/around.php 참조-->
			<input type="range" name="range_point" min="0" max="5" value="<?php echo $range_point;?>" onmousemove="nf_util.ch_range(this, '#range-point-ch')" onchange="nf_util.ch_range(this, '#range-price-ch')">
		</div>
	</div>
	<div class="f_result">
		<ul>
			<li><button type="button" onClick="reset_filter()">초기화</button></li>
			<li><button type="submit">검색</button></li>
		</ul>
	</div>
	</form>
</section>
</div>