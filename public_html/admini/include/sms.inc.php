<!-- SMS문자 보내기 -->
<div class="layer_pop2 conbox sms- popup_box-">
	<div class="h6wrap">
		<h6>SMS 문자 보내기</h6>
		<button type="button" onClick="open_box(this, 'sms-', 'none')" class="close">X 창닫기</button>
	</div>
	<?php
	include NFE_PATH.'/admini/include/sms.inc.inc.php';
	?>
</div>
<!--//SMS문자 팝업-->