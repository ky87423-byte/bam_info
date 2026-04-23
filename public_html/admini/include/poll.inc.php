<div class="draggable_move layer_pop conbox pop-<?php echo $row['no'];?>">
	<div class="h6wrap">
		<h6>설문조사결과 보기</h6>
		<button type="button" onClick="close_layer(this)" class="close">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col width="60%">
			<col width="20%">
		</colgroup>
		<tr>
			<th colspan="3">Q. <?php echo $nf_util->get_text($row['poll_subject']);?></th>
		</tr>
		<?php
		$length = @count($poll_content);
		if($length<=0) $length = 1;
		for($i=0; $i<$length; $i++) {
		?>
		<tr>
			<td><?php echo $poll_content[$i];?></td>
			<td class="tar">그래프영역</td>
			<td class="tar">0.00% (<b class="blue">0</b>)</td>
		</tr>
		<?php
		}?>
		<tr>
			<td class="tar" colspan="3">총 투표수 <b class="orange">0 표</b></td>
		</tr>
	</table>
	<div class="pop_btn">
		<button type="button" onClick="close_layer(this)" class="gray">창닫기</button>
	</div>
</div>