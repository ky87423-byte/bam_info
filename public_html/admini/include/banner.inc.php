<script type="text/javascript">
var click_type = function(val) {
	$(".content_tbody_").css({"display":"none"});
	$(".content_tbody_.content_"+val+"_").css({"display":"table-row-group"});

	switch(val) {
		case "image":
			$(".size_set_0").css({"display":"inline"});
			//$(".size_set_0").find("input").prop("checked", true);
			//$(".site_set_input_").css({"display":"none"});
			break;

		default:
			$(".size_set_0").css({"display":"none"});
			//$(".size_set_1").find("input").prop("checked", true);
			//$(".site_set_input_").css({"display":"inline"});
			break;
	}
}

$(function(){
	var form = document.forms['fwrite'];
	$(form).find("[name='size_set']").click(function(){
		$(".site_set_input_").css({"display":"none"});
		if($(this).val()=='1') $(".site_set_input_").css({"display":"inline"});
	});

	$(form).find("[name='type']").click(function(){
		click_type($(this).val());
	});

	click_type($(form).find("[name='type']:checked").val());
});

var ch_group = function(el) {
	var form = document.forms['fwrite'];
	var obj = $(form).find("[name='g_name']");
	switch(!$(el).val()) {
		case true:
			obj.css({"display":"inline"});
			obj.attr("needed", "needed");
		break;

		default:
			obj.css({"display":"none"});
			obj.removeAttr("needed");
		break;
	}
}
</script>
<div class="layer_pop conbox banner_write_body_" id="draggable" style="display:none;">
<form name="fwrite" action="<?php echo NFE_URL;?>/admini/regist.php" method="post" enctype="multipart/form-data" onSubmit="return validate(this)">
<input type="hidden" name="mode" value="banner_write" />
<input type="hidden" name="position" value="<?php echo $_GET['position'];?>" />
<input type="hidden" name="no" value="" />
	<div class="h6wrap">
		<h6>최상단 배너(공통) 배너등록</h6>
		<button type="button" class="close" onClick="$('.banner_write_body_').css({'display':'none'})">X 창닫기</button>
	</div>
	<table>
		<colgroup>
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th>배너그룹</th>
				<td>
					<select name="g_name_select" onChange="ch_group(this)">
						<option value="">직접입력</option>
						<?php
						if(is_array($nf_banner->get_group['group'][$_GET['position']])) {
						?>
						<optgroup label="그룹선택">
							<?php foreach($nf_banner->get_group['group'][$_GET['position']] as $k=>$v) {?>
							<option value="<?php echo $v;?>"><?php echo $v;?></option>
							<?php }?>
						</optgroup>
						<?php }?>
					</select>
					<input type="text" name="g_name" value="" hname="배너그룹" needed placeholder="그룹명을 입력해 주세요.">
				</td>
			</tr>
			<tr>
				<th>등록방식</th>
				<td>
					<?php
					$count = 0;
					if(is_array($nf_banner->banner_type)) { foreach($nf_banner->banner_type as $k=>$v) {
						$checked = $count==0 ? 'checked' : '';
					?>
					<label><input type="radio" name="type" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
					<?php
						$count++;
					} }?>
				</td>
			</tr>
			<tr>
				<th>배너크기</th>
				<td>
					<?php
					$count = 0;
					if(is_array($nf_banner->size_set)) { foreach($nf_banner->size_set as $k=>$v) {
						$checked = $count==0 ? 'checked' : '';
					?>
					<label class="size_set_<?php echo $k;?>"><input type="radio" name="size_set" id="" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
					<?php
						$count++;
					} }?>
					<div class="site_set_input_" style="display:none;">가로 <input type="text" name="width" value="" style="width:40px"> px , 세로 <input type="text" name="height" value="" style="width:40px"> px</div>
				</td>
			</tr>
			<tr>
				<th>배너간격</th>
				<td>
					<label><input type="checkbox" name="padding[]" value="top">상단띄우기</label>
					<label><input type="checkbox" name="padding[]" value="bottom">하단띄우기</label>
					<label><input type="checkbox" name="padding[]" value="left">좌측띄우기</label>
					<label><input type="checkbox" name="padding[]" value="right">우측띄우기</label>
					<span>* 선택시 해당영역이 10px씩 띄어집니다</span>
				</td>
			</tr>
		</tbody>
		<!--등록방식-이미지업로드-->
		<tbody class="content_tbody_ content_image_">
			<tr>
				<th>연결주소</th>
				<td>
					<input type="text" name="url" value=""><label for="" style="margin-left:5px;"><input type="checkbox" name="target" value="_blank" checked>새창열기</label>
					http:// 를 포함해주시기 바랍니다.
				</td>
			</tr>
			<tr>
				<th>배너파일</th>
				<td><input type="file" name="upload"><span>확장자 *.jpg, *.gif, *.png 만 가능</span></td>
			</tr>
		</tbody>
		<!--등록방식-직접입력-->
		<tbody class="content_tbody_ content_self_">
			<tr>
				<th>내용</th>
				<td><textarea type="editor" name="self_content" style="height:300px" placeholder="구글애드센스 코드를 입력해주세요."></textarea></td>
			</tr>
		</tbody>
		<!--등록방식-구글애드센스-->
		<tbody class="content_tbody_ content_adsense_">
			<tr>
				<th>내용</th>
				<td><textarea name="adsense_content" style="height:300px" placeholder="구글애드센스 코드를 입력해주세요."></textarea></td>
			</tr>
		</tbody>
	</table>
	<div class="pop_btn">
		<button type="submit" class="blue">등록하기</button>
		<button type="button" class="gray" onClick="$('.banner_write_body_').css({'display':'none'})">창닫기</button>
	</div>
</form>
</div>