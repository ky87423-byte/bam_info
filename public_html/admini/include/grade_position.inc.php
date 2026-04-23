<!--직급/직책 팝업-->
<div class="popup_layer layer_pop conbox popup_box-" id="grade_position-">
	<div class="h6wrap">
		<h6>직급/직책 선택</h6>
		<button type="button" class="close" onClick="nf_util.initLayerPosition_close('grade_position-')">X 창닫기</button>
	</div>
	<table class="style4">
		<colgroup>
			<col style="width:20%">
		</colgroup>
		<tbody>
			<tr>
				<th>직급</th>
				<td>
					<ul class="li_float">
					<?php
					$checked = "";
					if(is_array($cate_p_array['job_grade'][0])) { foreach($cate_p_array['job_grade'][0] as $k=>$v) {
						$checked = is_array($employ_info['grade_arr']) && in_array($k, $employ_info['grade_arr']) ? 'checked' : '';
					?>
					<li><label><input type="checkbox" name="wr_grade[]" name="wr_preferential[]" hname="직급" <?php if($register_form_arr['register_form_employ']['직급/직책']['wr_0']) echo 'needed';?> value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v['wr_name'];?></label></li>
					<?php
					} }?>
					</ul>
				</td>
			</tr>
			<tr>
				<th>직책</th>
				<td>
					<ul class="li_float">
					<?php
					$checked = "";
					if(is_array($cate_p_array['job_position'][0])) { foreach($cate_p_array['job_position'][0] as $k=>$v) {
						$checked = is_array($employ_info['position_arr']) && in_array($k, $employ_info['position_arr']) ? 'checked' : '';
					?>
					<li><label><input type="checkbox" class="put_checkbox-" name="wr_position[]" name="wr_preferential[]" hname="직책" <?php if($register_form_arr['register_form_employ']['직급/직책']['wr_0']) echo 'needed';?> value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v['wr_name'];?></label></li>
					<?php
					} }?>
					</ul>
				</td>
			</tr>
		</tbody>	
	</table>
	<div class="pop_btn">
		<button type="button" class="gray" onClick="nf_util.initLayerPosition_close('grade_position-')">닫기</button>
		<button type="button" onClick="nf_category.click_checkbox_put(this, ['.put_wr_grade', '.put_wr_position'])" class="blue">적용</button>
	</div>
</div>
<!--//직급/직책 팝업-->