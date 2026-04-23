<tr>
	<th><i class="axi axi-ion-android-checkmark"></i> 결제방법</th>
	<td>
		<ul class="li_float">
			<?php
			$count = 0;
			if(is_Array($nf_payment->pay_kind)) { foreach($nf_payment->pay_kind as $k=>$v) {
				if(in_array($k, array('admin'))) continue;
				if(!in_array($k, $nf_payment->pg_method_arr)) continue;
				$checked = $count===0 ? 'checked' : '';
			?>
			<li><label><input type="radio" name="pay_methods" onClick="nf_payment.click_pay_methods(this)" <?php echo $checked;?> value="<?php echo $k;?>"> <?php echo $v;?></label></li>
			<?php
				$count++;
			} }?>
		</ul>
	</td>
</tr>
<tr class="bank-tr-">
	<th><i class="axi axi-ion-android-checkmark"></i> 입금은행</th>
	<td>
		<select name="bank">
			<?php
			if(is_array($cate_p_array['online'][0])) { foreach($cate_p_array['online'][0] as $k=>$v) {
				$bank_arr = json_decode($v['wr_name']);
			?>
			<option value="<?php echo $bank_arr[0];?>/<?php echo $bank_arr[1];?>/<?php echo $bank_arr[2];?>">은행명 : <?php echo $bank_arr[0].' / 계좌번호 : '.$bank_arr[1].' / 예금주 : '.$bank_arr[2];?></option>
			<?php
			} }
			?>
		</select>
	</td>
<tr class="bank-tr-">
	<th><i class="axi axi-ion-android-checkmark"></i> 입금자명</th>
	<td><input type="text" name="depositor" value="<?php echo $member['mb_name'];?>"></td>
</tr>
<tr class="bank-tr-">
	<th>현금영수증</th>
	<td><label ><input type="checkbox" name="tax_use" value="1" onClick="nf_payment.click_tax(this)"> 발급</label>
		<ul class="li_float tax-c-">
			<li><label><input type="radio" name="pay_tax_type" value="1" onClick="nf_payment.tax_type(this)">소득공제용(일반개인용)</label></li>
			<li><label><input type="radio" name="pay_tax_type" value="2" onClick="nf_payment.tax_type(this)">지출증빙용(사업자용)</label></li>
		</ul>
	</td>
</tr>
<tr class="tax-tr-">
	<th>소득공제용(일반개인용)</th>
	<td>
		<select name="pay_tax_num_type">
			<option value="0">주민등록번호</option>
			<option value="1">휴대폰번호</option>
			<option value="2">카드번호</option>
		</select>
		<input type="text" name="pay_tax_num_person">
	</td>
</tr>
<tr class="tax-tr-">
	<th>지출증빙용(사업자용)</th>
	<td class="size1">
		사업자등록번호
		<input type="text" name="pay_tax_num_biz[]"> -
		<input type="text" name="pay_tax_num_biz[]"> -
		<input type="text" name="pay_tax_num_biz[]">
	</td>
</tr>