<!--복리후생 팝업-->
<div class="popup_layer layer_pop conbox popup_box-" id="welfare-">
	<div class="h6wrap">
		<h6>복리후생 선택</h6>
		<button type="button" class="close" onClick="nf_util.initLayerPosition_close('welfare-')">X 창닫기</button>
	</div>
	<table class="style4">
		<colgroup>
			<col style="width:21%">
		</colgroup>
		<tbody>
			<?php
			$checked = "";
			if(is_array($cate_p_array['job_welfare'][0])) { foreach($cate_p_array['job_welfare'][0] as $k=>$v) {
				if(is_array($cate_p_array['job_welfare'][$k])) $length = count($cate_p_array['job_welfare'][$k]);
				if($length<=0) continue;
			?>
			<tr>
				<th><?php echo $v['wr_name'];?></th>
				<td>
					<ul class="li_float">
					<?php
					if(is_array($cate_p_array['job_welfare'][$k])) { foreach($cate_p_array['job_welfare'][$k] as $k2=>$v2) {
						$checked = is_array($employ_info['welfare_arr']) && in_array($k2, $employ_info['welfare_arr']) ? 'checked' : '';
					?>
					<li><label><input type="checkbox" class="put_checkbox-" name="wr_welfare[]" hname="복리후생" <?php if($register_form_arr['register_form_employ']['복리후생']['wr_0']) echo 'needed';?> value="<?php echo $k2;?>" <?php echo $checked;?>><?php echo $v2['wr_name'];?></label></li>
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
		<button type="button" class="gray" onClick="nf_util.initLayerPosition_close('welfare-')">닫기</button>
		<button type="button" onClick="nf_category.click_checkbox_put(this, ['.put_wr_welfare'])" class="blue">적용</button>
	</div>
</div>
<!--//복리후생 팝업-->
