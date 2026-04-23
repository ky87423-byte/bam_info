<!--게시물등록 팝업-->
<form name="fwrite" action="<?php echo NFE_URL;?>/board/regist.php" method="post" enctype="multipart/form-data" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="board_write" />
<input type="hidden" name="code" value="<?php echo $_GET['code']=='reply' ? $_GET['code'] : 'insert';?>" />
<input type="hidden" name="bo_table" value="<?php echo $nf_util->get_html($bo_table);?>" />
<input type="hidden" name="no" value="<?php echo intval($b_row['wr_no']);?>" />
<input type="hidden" name="page_code" value="admin" />
<div class="layer_pop conbox popup_box- drag-skin- board-write-">
	<div class="h6wrap">
		<h6>게시물등록</h6>
		<button class="close" type="button" onClick="$('.board-write-').css({'display':'none'})">X 창닫기</button>
	</div>
	<div class="board-write-div-">
	<?php
	include_once NFE_PATH.'/board/write.inc.php';
	?>
	</div>
	<div class="pop_btn">
		<button class="gray" type="button" onClick="$('.board-write-').css({'display':'none'})">X 창닫기</button>
		<button class="blue">저장하기</button>
	</div>
</div>
</form>
<!--//게시물등록 팝업-->