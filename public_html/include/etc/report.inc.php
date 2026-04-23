<form name="freport" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
<input type="hidden" name="mode" value="report_write" />
<input type="hidden" name="pno" value="<?php echo intval($info_no);?>" />
<input type="hidden" name="page_code" value="<?php echo $page_code;?>" />
<input type="hidden" name="other_page_code" value="<?php echo $other_page_code;?>" />
<div class="popup_layer report report-" style="display:none;">
	<div class="h6wrap">
		<h6 class="s_title"><?php echo $nf_job->kind_of[$page_code];?>정보 신고하기</h6>
		<button type="button" onClick="nf_util.openWin('.report-')"><i class="axi axi-ion-close-round"></i></button>
	</div>
	<table class="style1">
		<tr>
			<th>신고하기</th>
			<td>
				<select name="select" hname="신고사유" needed>
					<option value="">신고사유 선택</option>
					<?php
					if(is_array($cate_p_array['job_'.$page_code.'_report_reason'][0])) { foreach($cate_p_array['job_'.$page_code.'_report_reason'][0] as $k=>$v) {
					?>
					<option value="<?php echo intval($k);?>"><?php echo $nf_util->get_text($v['wr_name']);?></option>
					<?php
					} }
					?>
				</select>
			</td>
		</tr>
	</table>
	<ul class="btn">
		<li><button type="button" onClick="nf_util.openWin('.report-')">취소</button></li>
		<li><button>신고</button></li>
	</ul>
</div>
</form>