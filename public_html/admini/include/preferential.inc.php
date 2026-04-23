<!--우대조건 팝업-->
<div class="popup_layer layer_pop conbox popup_box-" id="preferential-">
	<div class="h6wrap">
		<h6>우대조건 선택</h6>
		<button type="button" class="close" onClick="nf_util.initLayerPosition_close('preferential-')">X 창닫기</button>
	</div>
	<table class="style4">
		<colgroup>
			<col style="width:20%">
		</colgroup>
		<tbody>
			<?php
			if(is_array($cate_p_array['job_conditions'][0])) { foreach($cate_p_array['job_conditions'][0] as $k=>$v) {
				$length = count($cate_p_array['job_conditions'][$k]);
				if($length<=0) continue;
			?>
			<tr>
				<th><?php echo $v['wr_name'];?></th>
				<td>
					<ul class="li_float">
					<?php
					if(is_array($cate_p_array['job_conditions'][$k])) { foreach($cate_p_array['job_conditions'][$k] as $k2=>$v2) {
						$checked = is_array($employ_info['preferential_arr']) && in_array($k2, $employ_info['preferential_arr']) ? 'checked' : '';
					?>
					<li><label><input type="checkbox" class="put_checkbox-" name="wr_preferential[]" hname="우대조건" <?php if($register_form_arr['register_form_employ']['우대조건']['wr_0']) echo 'needed';?> value="<?php echo $k2;?>" <?php echo $checked;?>><?php echo $v2['wr_name'];?></label></li>
					<?php
					} }?>
					</ul>
				</td>
			</tr>
			<?php
			} }?>
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="button" onClick="nf_util.initLayerPosition_close('preferential-')" class="gray">X 창닫기</button>
		<button type="button" onClick="nf_category.click_checkbox_put(this, ['.put_wr_preferential'])" class="blue">적용</button>
	</div>
</div>
<!--//우대조건 팝업-->