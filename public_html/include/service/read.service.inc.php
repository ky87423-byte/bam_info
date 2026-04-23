<?php
$service_txt_k = $code=='employ' ? 'resume' : 'employ'; // : 기업회원이 이력서를 보면 resume임.;;;
if($nf_job->service_info[$service_txt_k]['read']['use']) {
	$price_query = $db->_query("select * from nf_service_price where `code`=? and `type`=? order by `rank` asc", array($service_txt_k, 'read'));
	$length = $db->num_rows($price_query);
	if($length>0) {
?>
<h4 id="<?php echo $code;?>_read"><span class="orange"><?php echo $nf_job->pay_service[$code]['read'];?></span> 상품 <a href="<?php echo $service_ahref;?>"><button type="button">서비스 신청</button></a></h4>
<div class="tablewrap resume_open">
	<table class="style2">
		<tr>
			<th>서비스 안내</th>
			<th>건수</th>
			<th>금액</th>
			<th class="choice">선택</th>
		</tr>

		<?php
		$count = 0;
		while($row=$db->afetch($price_query)) {
		?>
		<tr>
			<?php if($count===0) {?>
			<td rowspan="<?php echo $length;?>">
				<h5><?php echo $nf_job->pay_service[$code]['read'];?></h5>
				<ul class="product_info">
					<?php if($code==='employ') {?>
					<li><?php echo stripslashes($env['service_intro_arr']['resume']['read']);?></li>
					<?php } else {?>
					<li><?php echo stripslashes($env['service_intro_arr']['employ']['read']);?></li>
					<?php }?>
				</ul>
			</td>
			<?php }?>
			<td class="tac"><?php echo number_format(intval($row['service_cnt'])).strtr($row['service_unit'], $nf_util->date_count_arr);?></td>
			<td class="price tar">
				<?php if($row['service_percent']>0) {?><p class="sale"><span><?php echo $row['service_percent'];?>%</span><?php echo number_format(intval($row['service_price']));?>원</p><?php }?>
				<p><em><?php echo $nf_util->get_price_int($row['service_price'], $row['service_percent']);?></em><?php if($row['service_price']>0) {?>원<?php }?></p>
			</td>
			<td class="tac choice"><label class="radiostyle2" ><input type="checkbox" class="service_click-" name="service[<?php echo $service_txt_k;?>][read]" value="<?php echo $row['no'];?>" onClick="nf_util.one_check(this)"></label></td>
		</tr>
		<?php
			$count++;
		}?>
	</table>
</div>
<!--//tablewrap-->
<?php
	}
}?>