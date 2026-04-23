<?php
$top_menu_code = '500204';
include '../include/header.php';

if(!$_GET['type']) {
	$_GET['code'] = 'shop';
	$_GET['type'] = '0_0';
}

$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_GET['code'], $_GET['type']));
$price_query = $db->_query("select * from nf_service_price where `code`=? and `type`=? order by `rank` asc", array($_GET['code'], $_GET['type']));
$price_len = $db->num_rows($price_query);
?>
<script type="text/javascript">
var service_price_write = function() {
	var form = document.forms['fwrite1'];
	if(validate(form)) {
		form.submit();
	}
}

var service_option_write = function(el) {
	var form = document.forms['fwrite2'];
	nf_util.ajax_submit(form);
	return;
}

var price_update = function(el, no) {
	var form = document.forms['fwrite1'];
	form.no.value = no;

	var tr_obj = $(el).closest("tr");
	var cnt = tr_obj.find(".date-").eq(0).text();
	var unit = tr_obj.find(".date-").eq(1).text();
	var price = tr_obj.find(".price-").text();
	var percent = tr_obj.find(".percent-").text();

	$(form).find("[name='date[]']").eq(0).val(cnt);
	$(form).find("[name='date[]']").eq(1).val(unit);
	$(form).find("[name='price']").val(price);
	$(form).find("[name='sale']").val(percent);
}

var price_delete = function(el, no) {
	if(confirm("삭제하시겠습니까?")) {
		$.post("../regist.php", "mode=service_price_delete&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}
}

this.clone_option = function(el) {
	var type = $(el).text().indexOf("추가")>=0 ? 'add' : 'del';
	switch(type) {
		case "add":
			var obj = $(el).closest(".paste-body-").find('li').eq(0).clone();
			obj.find("[type=checkbox]").prop("checked", false);
			obj.find("[type=text]").val("");
			obj.find("button").html("- 삭제");
			if(obj.find(".color_picker").length>0) {
				obj.find(".color_picker").each(function(){
					obj.find(".sp-replacer").remove();
					nf_util.spectrum_func($(this)[0]);
				});
			}
			$(el).closest(".paste-body-").append(obj);
		break;

		default:
			var form = document.forms['fwrite2'];
			var len = $(el).closest(".paste-body-").find('li').length;
			if(len<=1) {
				alert("2개이상 있을경우 삭제가 가능합니다.");
				return;
			}
			if(confirm("삭제하시겠습니까?")) {
				var sun = $(el).closest('li').index();
				$.post("../regist.php", "mode=service_option_delete&no="+form.no.value+"&sun="+sun, function(data){
					data = $.parseJSON(data);
					if(data.msg) alert(data.msg);
					if(data.move) location.href = data.move;
					if(data.js) eval(data.js);
				});
			}
		break;
	}
}

var service_option_rank = function() {
	var form = document.forms['fwrite2'];
	var para = $(form).serialize();
	$.post("../regist.php", para+"&mode=service_option_rank_update", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var update_icon = function(el) {
	var form = document.forms['fwrite2'];
	var sun = $(el).closest("tr").index();
	form.sun.value = sun;
	var icon = $(el).closest("tr").find(".icon-").find("img").attr("src");
	$(form).find(".icon-update-tr").find("img").attr("src", icon);
	$(form).find(".icon-update-tr").css({"display":"table-row"});
}

var delete_icon = function(el) {
	if(confirm("삭제하시겠습니까?")) {
		var form = document.forms['fwrite2'];
		var sun = $(el).closest("tr").index();
		$.post("../regist.php", "mode=service_icon_delete&no="+form.no.value+"&sun="+sun, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}
}

var service_write = function(el) {
	var form = document.forms['fwrite1'];
	var para = $(form).serialize();
	$.post("../regist.php", para+"&mode=service_write", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>


<!-- 서비스별금액설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide5-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="13%">
					</colgroup>
					<tr>
						<th>서비스 업체정보</th>
						<td>
							<ul>
								<?php
								$count = 0;
								if(is_array($nf_shop->service_name['shop']['main'])) { foreach($nf_shop->service_name['shop']['main'] as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='shop' ? 'on' : '';
									if($on==='on') $service_name = $v;
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=shop&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
									$count++;
								} }

								if(is_array($nf_shop->service_etc)) { foreach($nf_shop->service_etc as $k=>$v) {
									$service_k = '0_'.$k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='shop' ? 'on' : '';
									if($on==='on') $service_name = $v;
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=shop&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
					<tr>
						<th>업체정보 옵션서비스</th>
						<td>
							<ul>
								<?php
								if(is_array($nf_shop->etc_service)) { foreach($nf_shop->etc_service as $k=>$v) {
									$service_k = $k;
									$on = $service_k==$_GET['type'] && $_GET['code']=='shop' ? 'on' : '';
									if($on==='on') $service_name = $v;
								?>
								<li class="<?php echo $on;?>"><a href="./service_pay_config.php?code=shop&type=<?php echo $service_k;?>"><?php echo $v;?></a></li>
								<?php
								} }
								?>
							</ul>
						</td>
					</tr>
				</table>
			</div>

			
			<h6>
				<?php
				$head_service = '기타 서비스';
				if($_GET['code']) {
					if($nf_shop->service_name_k[$_GET['code']][substr($_GET['type'],0,1)]) $head_service = $nf_shop->service_name_k_txt[$nf_shop->service_name_k[$_GET['code']][substr($_GET['type'],0,1)]].' 페이지';
					if($_GET['code']) $head_service .= ' '.$nf_shop->kind_of[$_GET['code']].'정보';
				} else {
					$head_service = '기타 서비스';
				}
				echo $head_service.' > '.$service_name;
				?>
				<p class="h6_right">
					<button class="s_basebtn3 green" onclick="void(window.open('../../admini/pop/guide_servicesum.html','','width=989,height=900,resizable=no,scrollbars=yes'))">금액설정가이드</button>
				</p>
			</h6>

			<form name="fwrite1" action="../regist.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="service_price_write" />
			<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
			<input type="hidden" name="type" value="<?php echo $nf_util->get_html($_GET['type']);?>" />
			<input type="hidden" name="no" value="" />
			<table class="">
				<colgroup>
					<col width="10%">
					<col width="%">
				</colgroup>
				<?php
				if(strpos($_GET['type'], '_list')!==false) {
				?>
				<tr>
					<th>유료/무료 설정</th>
					<td>
						<label><input type="radio" name="is_pay" value="1" checked>유료</label>
						<label><input type="radio" name="is_pay" value="0" <?php echo !$service_row['is_pay'] ? 'checked' : '';?>>무료</label>
						<input type="hidden" name="use" value="1" /><?php /*무조건 사용*/?>
						<button type="button" class="basebtn blue" onclick="service_write(this)">저장하기</button>
					</td>
				</tr>
				<?php
				} else {
				?>
				<tr>
					<th>사용/미사용 설정</th>
					<td>
						<label><input type="radio" name="use" value="1" checked>사용</label>
						<label><input type="radio" name="use" value="0" <?php echo !$service_row['use'] ? 'checked' : '';?>>미사용</label>

						<button type="button" class="basebtn blue" onclick="service_write(this)">저장하기</button>
					</td>
				</tr>
				<?php
				}?>
				<tr>
					<th>서비스 설정</th>
					<td>
						<input type="text" name="date[]" hname="서비스기간" needed class="input5" placeholder="0">
						<select name="date[]" class="select5" hname="서비스기간" needed>
							<?php
							if(in_array($_GET['type'], $nf_shop->service_count_arr)) {
								?>
								<option value="count">건</option>
								<?php
							}

							if(!in_array($_GET['type'], $nf_shop->service_only_count_arr) && is_array($nf_util->date_arr)) { foreach($nf_util->date_arr as $k=>$v) {
							?>
								<option value="<?php echo $k;?>"><?php echo $v;?></option>
								<?php
							} }
							?>
						</select>
						<input type="text" name="price" hname="서비스금액" onkeyup="this.value=this.value.number_format()" class="input5 MAL10" placeholder="0">원,
						할인율 <input type="text" name="sale" hname="할인율" class="input5" placeholder="0"> %
						<button type="button" class="blue common" onClick="service_price_write()"><strong>+</strong> 등록 or 수정</button> <!--리스트 수정버튼 눌렀을때, '수정'텍스트로 변환-->
						<span>* 금액을 '0'으로 등록시 무료로 설정 됩니다</span>
					</td>
				</tr>
				<tr>
					<th>서비스금액</th>
					<td class="conbox ">
						<div class="table_top_btn " style="border-bottom:none;">
							<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'fwrite1', '순서값을 저장하시겠습니까?')" no="" hname="순서" mode="rank_update_service_price" url="../regist.php" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
						</div>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="40">
								<col width="40">
								<col width="5%">
								<col width="10%">
							</colgroup>
							<tr>
								<th class="tac">순서</th>
								<th class="tac">설정값</th>
								<th class="tac">금액</th>
								<th class="tac">할인율</th>
								<th class="tac">편집</th>
							</tr>
							<?php
							switch($price_len<=0) {
								case true:
							?>
							<tr><td colspan="5" align="center">금액을 입력해주시기 바랍니다.</td></tr>
							<?php
								break;


								default:
									while($price_row=$db->afetch($price_query)) {
							?>
							<tr>
								<td><input type="text" name="rank[]" value="<?php echo intval($price_row['rank']);?>" ><input type="hidden" name="hidd[]" value="<?php echo $price_row['no'];?>" /></td>
								<td>
									<?php echo $price_row['service_cnt'];?><?php echo $nf_util->date_count_arr[$price_row['service_unit']];?><em class="date-" style="display:none;"><?php echo $price_row['service_cnt'];?></em><em class="date-" style="display:none;"><?php echo $price_row['service_unit'];?></em>
								</td>
								<td>
									<?php if($price_row['service_price']<=0) {?>
									<b class="red">무료</b>
									<?php } else {?>
									<?php if($price_row['service_percent']>0) {?>
									<span class="line-through"><?php echo number_format(intval($price_row['service_price']));?></span> => <?php echo number_format(intval($nf_util->get_sale($price_row['service_percent'], $price_row['service_price'])));?>원
									<?php } else {
										echo number_format(intval($price_row['service_price']));?>원
									<?php
									}
									}?>
									<em class="price-" style="display:none;"><?php echo number_format(intval($price_row['service_price']));?></em>
								</td>
								<td><em class="percent-"><?php echo $price_row['service_percent'];?></em>%</td>
								<td>
									<button type="button" class="common gray" onClick="price_update(this, '<?php echo $price_row['no'];?>')"><i class="axi axi-plus2"></i>수정하기</button>
									<button type="button" class="common gray" onClick="price_delete(this, '<?php echo $price_row['no'];?>')"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
							<?php
									}
								break;
							}
							?>
						</table>
					</td>
				</tr>
			</table>
			</form>


			<form name="fwrite2" action="../regist.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="service_option_write" />
			<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
			<input type="hidden" name="type" value="<?php echo $nf_util->get_html($_GET['type']);?>" />
			<input type="hidden" name="no" value="<?php echo $service_row['no'];?>" />
			<input type="hidden" name="sun" value="" />
			<?php
			if($_GET['type']=='busy') {
			?>
			<h6><?php echo $nf_shop->kind_of[$_GET['code']];?>정보 옵션서비스 > 급구 > 아이콘설정</h6> <!--급구에 사용-->
			<table>
				<tr>
					<th>현재 등록된 아이콘</th>
					<td><img src="<?php echo NFE_URL;?>/data/service_option/<?php echo $service_row['option'];?>?t=<?php echo time();?>" alt=""></td>
				</tr><tr>
					<th>아이콘 업로드</th>
					<td><em><input type="file" name="attach" hname="급구아이콘" needed class="input20"></em><button type="button" class="s_basebtn2 gray MAL5" onClick="service_option_write(this)">등록</button> <span>* 권장 사이즈 : 17px * 16px , 업로드 확장자 : *.jpg, *.gif, *.png</span></td>
				</tr>
			</table>
			<?php
			}?>

			<?php
			if(in_array($_GET['type'], array('icon')) || in_array($_GET['type'], array('0_0', '1_0')) && $_GET['code']=='resume') {
				$option_arr = explode("/", $service_row['option']);
			?>
			<h6>
				<?php if($_GET['code']=='resume') {?>
				포커스 아이콘 설정
				<?php } else {?>
				<?php echo $nf_shop->kind_of[$_GET['code']];?>정보 옵션서비스 > 아이콘 > 아이콘설정
				<?php }?>
			</h6> <!--아이콘에 사용-->
			<table class="">
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>아이콘 등록</th>
					<td>
						<em><input type="file" name="attach" hname="아이콘" needed class="input20"></em><button type="button" class="s_basebtn2 gray MAL5" onClick="service_option_write(this)">등록</button>

						<button type="button" class="s_basebtn2 gray MAL5" onclick="service_option_rank()" style="float:right;">순서저장</button>
					</td>
				</tr>
				<tr class="icon-update-tr" style="display:none;">
					<th>등록된 아이콘</th>
					<td><img src="../../images/icon_company_00.gif" alt=""></td>
				</tr>
				<tr>
					<th>아이콘목록</th>
					<td>
						<table class="table3 tac">
							<colgroup>
								<col width="5%">
								<col width="">
								<col width="10%">
							</colgroup>	
							<tr>
								<th class="tac">순서</th>
								<th class="tac">아이콘</th>
								<th class="tac">편집</th>
							</tr>
							<?php
							if(is_array($option_arr)) { foreach($option_arr as $k=>$v) {
							?>
							<tr>
								<td><input type="text" name="rank[]" value="<?php echo ($k+1);?>" ></td>
								<td class="icon-"><img src="<?php echo NFE_URL;?>/data/service_option/<?php echo $v;?>" alt=""></td>
								<td>
									<button type="button" class="common gray" onClick="update_icon(this)"><i class="axi axi-plus2"></i>수정하기</button>
									<button type="button" class="common gray" onClick="delete_icon(this)"><i class="axi axi-minus2"></i>삭제하기</button>
								</td>
							</tr>
							<?php
							} }?>
						</table>
					</td>
				</tr>	
			</table>
			<?php
			}?>

			<?php
			if(in_array($_GET['type'], array('neon', 'color'))) {
				$option_arr = explode("/", $service_row['option']);
			?>
			<h6><?php echo $nf_shop->kind_of[$_GET['code']];?>정보 옵션서비스 > 형광펜 > 색상설정</h6> <!--형광펜, 글자색에 사용-->
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th rowspan="2">형광색 설정</th>
					<td>
						<ul class="paste-body-">
							<?php
							$length = 1;
							if($option_arr[0]) $length = count($option_arr);
							for($i=0; $i<$length; $i++) {
							?>
							<li class="MAB5"><input type="text" name="color[]" value="<?php echo $option_arr[$i];?>" hname="색상" needed class="color_picker input5" placeholder="0"><button type="button" class="gray basebtn MAL5 " onClick="clone_option(this)">+ <?php echo $i===0 ? '추가' : '삭제';?></button></li>
							<?php
							}?>
						</ul>
					</td>
				</tr>
				<tr>
					<td><button type="button" class="basebtn blue" onClick="service_option_write(this)">저장하기</button></td>
				</tr>	
			</table>
			<?php
			}?>
			</form>


		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->