<?php
if(is_array($post_service)) { foreach($post_service as $k=>$v) {
	$code = $k;
	if(is_array($v)) { foreach($v as $k2=>$v2) {
		$service_option = "";
		switch($k2) {
			case "package":
				$service_k = $k.'_'.$k2;
				if($load_db_price) {
					// : 결제페이지에서 사용
					$price_row = $db->query_fetch("select * from nf_service_package where `wr_type`=? and `wr_use`=1 and `no`=?", array($k, $v2));
				} else {
					// : 마이페이지, 결제완료페이지에서 사용
					$price_row = $price_arr[$service_k];
				}
				$package_arr = $nf_payment->get_package('job', $price_row);
				$content_ = strip_tags(implode(" + ", $package_arr['service_txt_arr']));
				$price_ = $price_row['wr_price'];
				$title_ = $price_row['wr_subject'];
			break;

			default:
				$service_k = $k.'_'.$k2;
				if($load_db_price) {
					// : 결제페이지에서 사용
					$price_row = $db->query_fetch("select * from nf_service_price where `code`=? and `type`=? and `no`=?", array($k, $k2, $v2));
				} else {
					// : 마이페이지, 결제완료페이지에서 사용
					$price_row = $price_arr[$service_k];
				}
				$check_code = $k;
				if(in_array($k2, array('read'))) $check_code = $k=='employ' ? 'resume' : 'employ';
				$content_ = number_format(intval($price_row['service_cnt'])).$nf_util->date_count_arr[$price_row['service_unit']];
				$price_ = $nf_util->get_sale($price_row['service_percent'], $price_row['service_price']);
				if(in_array($k2, array('read'))) $title_ = $nf_shop->pay_service[$check_code][$k2];
				else if(array_key_exists($k2, $nf_shop->etc_service)) $title_ = $nf_shop->etc_service[$k2].$nf_shop->kind_of[$k];
				else {
					$k2_arr = explode("_", $k2);
					$service_type = $nf_shop->service_name_k[$k][$k2_arr[0]];
					$title_ = $nf_shop->service_name_k_txt[$service_type].$nf_shop->kind_of[$k].' '.$nf_shop->service_name[$code][$service_type][$k2_arr[1]];

					if($k2_arr[1]=='border') $title_ = $nf_shop->service_name_k_txt[$service_type].$nf_shop->kind_of[$k].' 테두리강조';

					
				}

				if($k=='resume' && in_array($k2, array('0_0', '1_0'))) {
					if(is_array($post_arr['service_icon'][$k][$k2])) { foreach($post_arr['service_icon'][$k][$k2] as $k3=>$v3) {
						$service_option .= '<img src="/data/service_option/'.$v3.'" alt="" align="absmiddle">';
					} }
				}
			break;
		}

		$arr['price_hap'] += $price_;

		if($post_arr['service_option'][$k][$k2]) {
			switch($k2) {
				case "icon":
					$service_option = '&nbsp;&nbsp;<img src="/data/service_option/'.$post_arr['service_option'][$k][$k2].'" alt="" align="absmiddle">';
				break;

				case "neon":
					$service_option = '&nbsp;&nbsp;<p class="title bgcol1" style="background-color:'.$post_arr['service_option'][$k][$k2].';display:inline-block;">형광펜강조효과</p>';
				break;

				case "color":
					$service_option = '&nbsp;&nbsp;<p class="title tcol1" style="color:'.$post_arr['service_option'][$k][$k2].' !important;display:inline-block;">글자색강조효과</p>';
				break;
			}
		}



		switch($tag_skin) {
			case "skin1":
?>
<li><?php echo $nf_util->get_text($title_);?> <?php echo $service_option;?> <span>(<?php echo $nf_util->get_text($content_);?>, <?php echo number_format(intval($price_));?>원)</span></li>
<?php
			break;



			default:
?>
<tr>
	<td><?php echo $nf_util->get_text($title_);?></td>
	<td class="tal"><?php echo $nf_util->get_text($content_);?> <?php echo $service_option;?></td>
	<td>
		<?php if($price_>0) {?>
		<span class="orange"><?php echo number_format(intval($price_));?></span>원
		<?php } else {?>
		<span class="orange">무료</span>
		<?php }?>
	</td>
</tr>
<?php
			break;
		}
	} }
} }
?>