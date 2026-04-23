<?php
$price_un = unserialize($shop_row['price_info']);
$sns_info_un = unserialize($shop_row['sns_info']);
$phone = strtr($shop_row['wr_phone'], array('-'=>'')) ? $shop_row['wr_phone'] : $member['mb_phone'];
$hphone = strtr($shop_row['wr_hphone'], array('-'=>'')) ? $shop_row['wr_hphone'] : $member['mb_hphone'];
$phone_arr = explode("-", $phone);
$hphone_arr = explode("-", $hphone);
$time1_arr = explode(":", $shop_row['time1']);
$time2_arr = explode(":", $shop_row['time2']);
$address_val = strtr($shop_row['wr_address'], array("||"=>" "));
if($env['map_engine']=='google') {
?>
<script type="text/javascript">
var map_insert = function() {
	var form = document.forms['fwrite'];
	var mapContainer = document.getElementById("map-view");
	var mapCenter = new google.maps.LatLng(38.9041, -77.0171);
	var map_option = {
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		maxZoom : 18,
		minZoom : 3,
		scrollwheel: true,
		zoom: 18,
		zoomControl: true,
		//draggableCursor:'crosshair',
		zoomControlOptions: {
			style:google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.RIGHT_CENTER
		}
	};
	var map = new google.maps.Map(mapContainer, map_option);
	var address_val = $(form).find("[name='address[]']").eq(0).val()+' '+$(form).find("[name='address[]']").eq(1).val();
	var geocoder = new google.maps.Geocoder();
	
	try{
	
	geocoder.geocode({'address':address_val},
		function(results, status){
			if(results!=""){
				var location=results[0].geometry.location;
				var iwPosition = new google.maps.LatLng(location.lat(), location.lng());
				document.getElementsByName("map_int[]")[0].value = location.lat();
				document.getElementsByName("map_int[]")[1].value = location.lng();
				return true;
			} else {
				document.getElementsByName("map_int[]")[0].value = "";
				document.getElementsByName("map_int[]")[1].value = "";
				return false;
			}
		}
	);
	}catch(e){
		alert(e.message);
	}
}

$(function(){
	setInterval(function(){
		map_insert();
	},1000);
});
</script>
<?php }?>
	
<section class="shop_regist">
	<p class="s_title">업체등록</p>
	<?php
	if(strpos($_SERVER['PHP_SELF'], '/admini/')!==false) {
	?>
	<div class="h_no">
		<h6>등록방법</h6>
		<table>
			<colgroup>
				<col width="10%">
			</colgroup>
			<tr>
			<th>등록방법 <em class="ess">*</em></th>
			<td>
				<label><input type="radio" name="input_type" value="self" checked>직접등록</label>
				<label><input type="radio" name="input_type" value="load" <?php echo $em_row['input_type']=='load' || $member_row ? 'checked' : '';?>>불러오기</label>
			</td>
			</tr>
			</tbody>
			<tbody class="input_type- load-" style="display:<?php echo $em_row['input_type']=='load' || $member_row ? 'table-row-group' : 'none';?>;">
			<tr><!--등록방법-불러오기 선택시 노출-->
				<th>회원선택 <em class="ess">*</em></th>
				<td>
					<input type="text" id="find_member-" onkeypress="if(event.keyCode=='13'){event.preventDefault(); enterkey();}" value="<?php echo $nf_util->get_html($member_row['mb_id']);?>" class="input10">
					<button type="button" class="basebtn blue" onClick="find_member('company')">회원검색</button><span>이름,아이디,이메일로 검색</span>
					<ul class="calling MAT10 find_member_put-">
						<li>이름,아이디,이메일로 검색해주시기 바랍니다.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>등록된 업체 선택</th>
				<td>
					<select name="load_shop" onChange="nf_shop.select_info(this)">
						<option value="">등록된 업체 선택</option>
						<?php
						if(is_array($shop_query)) { foreach($shop_query as $k=>$row) {
							$selected = $row['no']==$_GET['info_no'] ? 'selected' : '';
						?>
						<option value="<?php echo $row['no'];?>" <?php echo $selected;?>><?php echo $row['wr_subject'];?></option>
						<?php
						} }?>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<?php
	}?>
	<h6 class="h_no">업체등록</h6>
	<table class="style1">
		<colgroup>
			<col style="width:20%">
		</colgroup>
		<tr>
			<th><i class="axi axi-ion-android-checkmark"></i>업체명 <em class="ess">*</em></th>
			<td><input type="text" class="long100" name="company_name" value="<?php echo $nf_util->get_html($shop_row['wr_company']);?>" hname="업체명" needed></td>
		</tr>
		<tr>
			<th><i class="axi axi-ion-android-checkmark"></i>제목 <em class="ess">*</em></th>
			<td><input class="long100" type="text" name="subject" placeholder="짧게 작성하는 업체 소개글 영역입니다." value="<?php echo $nf_util->get_html($shop_row['wr_subject']);?>" hname="제목" needed></td>
		</tr>
		
		<tr class="img_up">
			<th>업체이미지 <em class="ess">*</em></th>
			<td>
				<button type="button" class="s_r_base gray" onClick="nf_shop.upload(this, 'shop')">업체이미지 업로드</button><button type="button" class="s_r_base gray" onClick="nf_shop.photo_delete(this, 'shop')">파일삭제</button>
				<div>
					<p class="blue">
						* 업체 상세페이지에서 출력될 이미지
						<br>* 권장사이즈 : 가로 635px * 세로 420px
					</p>
					<p class="img shop-photo-paste-">
						<?php
						$photo_cnt = 0;
						if(is_array($shop_info['photo_arr'])) { foreach($shop_info['photo_arr'] as $k=>$v) {
							$v_arr = explode("/", $v);
							if(is_file(NFE_PATH.'/data/shop/'.$v)) {
						?>
						<span class="photo-item-" style="position:relative;z-index:2;"><input type="checkbox" name="shop_photo_chk[]" style="position:absolute;top:0px;right:8px;" /><input type="hidden" name="shop_photo[]" value="<?php echo $v_arr[1];?>" /><img src="<?php echo NFE_URL;?>/data/shop/<?php echo $v;?>" alt="업체이미지" onClick="nf_shop.click_photo(this)"></span>
						<?php
								$photo_cnt++;
							}
						} }
						?>
						<span class="not-image-" style="display:<?php echo $photo_cnt>0 ? 'none' : 'inline';?>;">업체이미지를 업로드해주세요.</span>
						<input type="text" style="position:absolute;opacity:0;left:0px;z-index:1;" class="check_photo-" message="업체이미지를 업로드해주세요." value="<?php echo $photo_cnt>0 ? 1 : "";?>" />
					</p>
				</div>
			</td>
		</tr>
		<tr class="img_up">
			<th>대표이미지 <em class="ess">*</em></th>
			<td>
				<button type="button" class="s_r_base gray" onClick="nf_shop.upload(this, 'photo')">대표이미지 업로드</button><button type="button" class="s_r_base gray" onClick="nf_shop.photo_delete(this, 'photo')">파일삭제</button>
				<div>
					<p class="blue">
						* 메인페이지 상품리스트 및 내주변 등 상품 리스트에 출력될 이미지
						<br>* 권장사이즈 : 가로 520px * 세로 365px
					</p>
					<p class="img photo-photo-paste-">
						
						<?php
						if(is_file(NFE_PATH.$shop_info['main_photo_url'])) {
							$main_photo_arr = explode("/", $shop_row['wr_main_photo']);
						?>
							<span class="photo-item-" style="position:relative;"><input type="hidden" name="photo_photo[]" value="<?php echo $main_photo_arr[1];?>" /><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="대표이미지"></span>
						<?php
						}
						?>
						<span class="not-image-" style="display:<?php echo (is_file(NFE_PATH.$shop_info['main_photo_url'])) ? 'none' : 'inline';?>;">대표이미지를 업로드해주세요.</span>
						<input type="text" class="check_photo-" style="position:absolute;opacity:0;left:0px;" message="대표이미지를 업로드해주세요." value="<?php echo $photo_cnt>0 ? 1 : "";?>" />
					</p>
				</div>
			</td>
		</tr>
		
		<tr class="area">
			<th><i class="axi axi-ion-android-checkmark"></i>지역 <em class="ess">*</em></th>
			<td class="parent-">
				<?php
				for($i=1; $i<=$area_depth; $i++) {
					$area1 = $shop_info['area_arr'][0];
					if($i>1) $area2 = $shop_info['area_arr'][1];
					$nf_category->get_area($area1, $area2);
					if($i===1) $area_array = $cate_p_array['area'][0];
					else if($i===2) $area_array = $cate_area_array['SI'][$area1];
					else if($i===3) $area_array = $cate_area_array['GU'][$area1][$area2];
				?>
				<select name="wr_area[]" wr_type="area" hname="<?php echo $i;?>차지역" <?php echo $i===1 ? 'needed' : '';?> <?php if($i<$area_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
					<option value=""><?php echo $i;?>차 카테고리</option>
					<?php
					if(is_array($area_array)) { foreach($area_array as $k=>$v) {
						$selected = $shop_info['area_arr'][$i-1]==$v['wr_name'] ? 'selected' : '';
					?>
					<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?> no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
					<?php
					} }?>
				</select>
				<?php
				}?>
			</td>
		</tr>
		<tr class="addr">
			<?php
			$address_arr = explode("||", $shop_row['wr_address']);
			?>
			<th>주소 </th>
			<td class="area-address-">
				<input type="text" name="address[]" value="<?php echo $nf_util->get_text($address_arr[0]);?>" hname="주소"  class="address1-" placeholder="주소를 입력하세요."><button type="button" class="s_r_base gray" onClick="sample2_execDaumPostcode(this)">우편번호</button>
				<input type="text" name="address[]" value="<?php echo $nf_util->get_text($address_arr[1]);?>" hname="상세주소"  class="extraAddress-" placeholder="상세주소를 입력하세요.">
				<?php
				$address_move_latlng = true;
				include NFE_PATH.'/include/post.daum.php';
				include NFE_PATH.'/plugin/map/load_map.js.php';
				?>
				<div style="visibility:hidden;position:absolute;">
					<div id="map-view" style="width:100%;height:300px;"></div>
					<div class="_map_input">
						<input type="hidden" name="map_int[]" value="<?php echo $shop_row['wr_lat'];?>" />
						<input type="hidden" name="map_int[]" value="<?php echo $shop_row['wr_lng'];?>" />
					</div>
					<input type="hidden" name="doro_post" class="post-" value="<?php echo $nf_util->get_text($shop_row['wr_doro']);?>" />
					<script type="text/javascript">
					nf_map.map_start("map-view", {'lat':"<?php echo $shop_row['wr_lat'];?>", 'lng':"<?php echo $shop_row['wr_lng'];?>"});
					nf_map.get_location();
					nf_map.address_move_marker("<?php echo $shop_row['wr_lat'];?>", "<?php echo $shop_row['wr_lng'];?>", {});
					</script>
				</div>
			</td>
		</tr>
		<?php
		if($env['use_shop_industry']) {
		?>
		<tr class="cate_type">
			<th><i class="axi axi-ion-android-checkmark"></i>업종별 <em class="ess">*</em></th>
			<td>
				<ul class="paste-body-">
					<?php
					if(is_array($shop_info['category_arr'])) $job_part_cha = count($shop_info['category_arr']);
					if($job_part_cha<=0) $job_part_cha = 1;
					for($i=0; $i<$job_part_cha; $i++) {
					?>
					<li class="parent-">
						<?php
						for($j=1; $j<=$job_part_depth; $j++) {
							$cate_part_arr = explode(",", $shop_info['category_arr'][$i]);
							$cate_k = $j==1 ? 0 : $cate_part_arr[$j-1];
						?>
						<select class="check_seconds" name="category[<?php echo $i;?>][]" hname="<?php echo $j;?>차 카테고리" <?php echo $j===1 ? 'needed' : '';?> <?php if($j<$job_part_depth) {?>onChange="nf_category.ch_category(this, <?php echo $j;?>);"<?php }?>>
							<option value=""><?php echo $j;?>차 카테고리</option>
							<?php
							if(is_array($cate_p_array['job_part'][$cate_k])) { foreach($cate_p_array['job_part'][$cate_k] as $k=>$v) {
								$selected = $cate_part_arr[$j]==$v['no'] ? 'selected' : '';
							?>
							<option value="<?php echo $v['no'];?>" no="<?php echo $v['no'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
							<?php
							} }?>
						</select>
						<?php
						}?>
						<?php
						if($i===0) {
						?>
						<button type="button" class="base2 black btn--" onClick="nf_util.clone_tag(this, 'ul', 3)">추가</button>
						<?php
						} else {
						?>
						<button type="button" class="base2 gray btn--" onClick="nf_util.clone_tag(this, 'ul', 3)">삭제</button>
						<?php
						}?>
					</li>
					<?php
					}?>
				</ul>
			</td>
		</tr>
		<?php
		}
		if($env['use_shop_price']) {
		?>
		<!--대표요금설정은 관리자에서 미사용시 영역 아예 삭제해주세요-->
		<tr class="main_price">
			<th><i class="axi axi-ion-android-checkmark"></i>대표요금설정 <em class="ess">*</em></th>
			<td>
				<ul class="price_set parent-price-">
					<li><span>정상가</span><input type="text" class="price1--" name="price1" onKeyUp="nf_shop.sale_price_calc(this)" value="<?php if($shop_row['wr_price1']>0) echo intval($shop_row['wr_price1']);?>"> 원</li>
					<li><span>판매가</span><input type="text" class="price--" name="price" hname="판매가" needed onKeyUp="nf_shop.sale_price_calc(this)" value="<?php if($shop_row['wr_price']>0) echo intval($shop_row['wr_price']);?>"> 원</li>
					<li><span>할인율</span><input type="text" class="sale--" name="sale" onKeyUp="nf_shop.sale_price_calc(this)" value="<?php if($shop_row['wr_sale']>0) echo intval($shop_row['wr_sale']);?>"> %</li>
				</ul>
			</td>
		</tr>
		<?php
		}?>
		
		<tr class="center_text">
			<th colspan="2"><i class="axi axi-ion-android-checkmark"></i>상세내용</th>
		</tr>
		<tr>
			<td class="full_td" colspan="2"><textarea type="editor" name="content" hname="상세내용" needed style="height:300px;"><?php echo stripslashes($shop_row['wr_content']);?></textarea></td>
		</tr>
		<tr class="center_text">
			<th colspan="2">공지사항</th>
		</tr>
		<tr>
			<td class="full_td" colspan="2"><textarea type="editor" name="notice" hname="공지사항" style="height:300px;"><?php echo stripslashes($shop_row['wr_notice']);?></textarea></td>
		</tr>
		
		<?php /* ?>
		<tr class="">
			<th rowspan="">할인쿠폰 설정</th>
			<td class="full_td" colspan="2">
				<div class="copuon_set ">
					<table class="in_table">
						<tr>
							<th>사용여부</th>
							<td>
								<ul class="w_list">
									<li><label><input type="radio" name="coupon_use" value="1" <?php echo $shop_row['coupon_use'] ? 'checked' : '';?>>사용함</label></li>
									<li><label><input type="radio" name="coupon_use" value="0" <?php echo !$shop_row['coupon_use'] ? 'checked' : '';?>>사용안함</label></li>
								</ul>
							</td>
						</tr>
						<tr>
							<th>쿠폰 금액</th>
							<td><input type="text" class="w10" name="coupon_price" value="<?php echo intval($shop_row['coupon_price']);?>" hname="쿠폰금액">원 할인</td>
						</tr>
						<tr>
							<th>사용가능건수</th>
							<td><input type="text" class="w10" name="coupon_allow_int" value="<?php echo intval($shop_row['coupon_allow_int']);?>" hname="사용가능건수">건<em class="bojo">* 설정된 건수가 모두 소진되면 자동으로 할인쿠폰 이벤트가 마무리됩니다.</em></td>
						</tr>
						<tr>
							<th>사용기한</th>
							<td><input type="text" name="coupon_date1" value="<?php echo $nf_util->get_html($shop_row['coupon_date1']);?>" hname="사용기간" class="datepicker_json_coupon w10"> ~ <input type="text" name="coupon_date2" value="<?php echo $nf_util->get_html($shop_row['coupon_date2']);?>" hname="사용기간" class="datepicker_json_coupon w10"></td>
						</tr>
						<tr>
							<th>쿠폰제목</th>
							<td><input type="text" name="coupon_subject" value="<?php echo $nf_util->get_html($shop_row['coupon_subject']);?>" hname="쿠폰제목" class="long100"></td>
						</tr>
						<tr>
							<th>안내사항</th>
							<td><textarea name="coupon_content"><?php echo stripslashes($shop_row['coupon_content']);?></textarea></td>
						</tr>
						<tr>
							<th>쿠폰제한</th>
							<td>
								<ul class="w_list">
									<?php
									if(is_array($nf_shop->coupon_limit_arr)) { foreach($nf_shop->coupon_limit_arr as $k=>$v) {
									?>
									<li><label><input type="radio" name="coupon_limit" value="<?php echo $k;?>" <?php echo $shop_row['coupon_limit']==$k ? 'checked' : '';?>><?php echo $v;?></label></li>
									<?php
									} }?>
								</ul>
								<ul class="restriction blue">
									<li>* <b>1인 1회</b>는 한사람이 해당 쿠폰을 한 번 밖에 다운을 받을 수 없습니다.</li>
									<li>* <b>1인 1일 1회</b>는 한사람이 하루에 한번만 다운이 가능하며 다음날 1회 다운이 가능합니다.</li>
									<li>* <b>1인 무제한</b>은 한사람이 원하는 만큼 할인쿠폰을 다운받을 수 있습니다.</li>
								</ul>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr><?php */?>
		<tr class="phone">
			<th>업체 전화번호</th>
			<td><input class="w10" type="text" name="phone[]" value="<?php echo $phone_arr[0];?>"> - <input class="w10" type="text" name="phone[]" value="<?php echo $phone_arr[1];?>"> - <input class="w10" type="text" name="phone[]" value="<?php echo $phone_arr[2];?>"></td>
		</tr>
		<tr class="phone">
			<th>휴대폰번호</th>
			<td><input class="w10" type="text" name="hphone[]" value="<?php echo $hphone_arr[0];?>"> - <input class="w10" type="text" name="hphone[]" value="<?php echo $hphone_arr[1];?>"> - <input class="w10" type="text" name="hphone[]" value="<?php echo $hphone_arr[2];?>"></td>
		</tr>
		<tr class="time_type">
			<th>영업시간</th>
			<td>
				<ul>
					<li>
						<select name="time1_apm">
							<option value="am" <?php echo $time1_arr[0]<12 ? 'selected' : '';?>>AM</option>
							<option value="pm" <?php echo $time1_arr[0]>=12 ? 'selected' : '';?>>PM</option>
						</select>
					</li>
					<li>
						<select name="time1">
							<?php
							$time1_int = $time1_arr[0];
							if($time1_arr[0]>=12) $time1_int -= 12;
							for($i=0; $i<12; $i++) {
								$_int = sprintf("%02d", $i);
								$selected = $time1_int==$i ? 'selected' : '';
							?>
							<option value="<?php echo $_int;?>" <?php echo $selected;?>><?php echo $_int;?>시</option>
							<?php
							}?>
						</select>
						:
					</li>
					<li>
						<select name="time1_m">
							<?php
							for($i=0; $i<=5; $i++) {
								$_int = sprintf("%02d", $i*10);
								$selected = $_int==$time1_arr[1] ? 'selected' : '';
							?>
							<option value="<?php echo $_int;?>" <?php echo $selected;?>><?php echo $_int;?>분</option>
							<?php
							}?>
						</select>
						<b>~</b>
					</li>
				</ul>
				<ul>
					<li>
						<select name="time2_apm">
							<option value="am" <?php echo $time2_arr[0]<12 ? 'selected' : '';?>>AM</option>
							<option value="pm" <?php echo $time2_arr[0]>=12 ? 'selected' : '';?>>PM</option>
						</select>
					</li>
					<li>
						<select name="time2">
							<?php
							$time1_int = $time2_arr[0];
							if($time2_arr[0]>=12) $time1_int -= 12;
							for($i=0; $i<=12; $i++) {
								$_int = sprintf("%02d", $i);
								$selected = $time1_int==$_int ? 'selected' : '';
							?>
							<option value="<?php echo $_int;?>" <?php echo $selected;?>><?php echo $_int;?>시</option>
							<?php
							}?>
						</select>
						:
					</li>
					<li>
						<select name="time2_m">
							<?php
							for($i=0; $i<=5; $i++) {
								$_int = sprintf("%02d", $i*10);
								$selected = $_int==$time2_arr[1] ? 'selected' : '';
							?>
							<option value="<?php echo $_int;?>" <?php echo $selected;?>><?php echo $_int;?>분</option>
							<?php
							}?>
						</select>
					</li>
				</ul>
				<p><input type="checkbox" name="full_time" value="1" <?php echo $shop_row['time_full'] ? 'checked' : '';?>> 24시간</p>
				&nbsp;&nbsp; <input name="time_text" type="text" class="txt" style="width:240px" value="<?php echo $nf_util->get_html($shop_row['time_text']);?>" placeholder="ex) 휴무일 매월 2/4주 일요일">
			</td>
		</tr>
		<?php
		if($env['use_shop_tema']) {
		?>
		<tr class="cate_tema">
			<th>테마별</th>
			<td>
				<ul>
					<?php
					$job_tema_arr = explode(",", $shop_row['wr_tema']);
					if(is_array($cate_array['job_tema'])) { foreach($cate_array['job_tema'] as $k=>$v) {
						$checked = in_array($k, $job_tema_arr) ? 'checked' : '';
					?>
					<li><label><input type="checkbox" name="tema_category[]" <?php echo $checked;?> value="<?php echo $k;?>"><?php echo $v;?></label></li>
					<?php
					} }?>
				</ul>
			</td>
		</tr>
		<?php
		}?>
		<tr>
			<th>아이콘</th>
			<td>
				<ul class="service_icon <?php echo $env['icon_type']=='text' ? 's_icon_txt' : 's_icon_img';?>">
					<?php
					$icon_arr = explode(",", $shop_row['wr_icon']);
					if(is_array($shop_icon_row_arr)) { foreach($shop_icon_row_arr as $k=>$icon_row) {
						$checked = in_array($icon_row['wr_id'], $icon_arr) ? 'checked' : '';
					?>
					<li>
						<label>
							<?php
							if($env['icon_type']=='text') {
							?>
							<p><?php echo $nf_util->get_text($icon_row['wr_name']);?></p>
							<?php
							} else {
							?>
							<p><img src="<?php echo NFE_URL.$nf_shop->attach_dir['icon'].$icon_row['wr_image'];?>" alt="<?php echo $nf_util->get_html($icon_row['wr_name']);?>"></p>
							<?php
							}?>
							<input type="checkbox" name="wr_icon[]" <?php echo $checked;?> value="<?php echo intval($icon_row['wr_id']);?>">
						</label>	
					</li>
					<?php
					} }?>
				</ul>
			</td>
		</tr>
		<tr>
			<th>동영상</th>
			<td>
				<input class="long100" type="text" name="movie" value="<?php echo $nf_util->get_html($shop_row['wr_movie']);?>" placeholder="ex) <iframe width='640' height='360' src='https://www.youtube.com/embed/9iJDbZbm9BY' frameborder='0' allowfullscreen></iframe>">
				<p class="bojo">* 동영상 태그 입력</p>
			</td>
		</tr>
		<tr>
			<th>SNS</th>
			<td class="paste-body-">
				<?php
				if(is_array($sns_info_un['name'])) $length = count($sns_info_un['name']);
				if($length<=0) $length = 1;
				for($i=0; $i<$length; $i++) {
				?>
				<dl class="parent-">
					<dt>
						<span>SNS명</span>
						<select name="sns_name[]">
							<option value="">선택하기</option>
							<?php
							if(is_array($nf_util->sns_array)) { foreach($nf_util->sns_array as $k=>$v) {
								$selected = $k==$sns_info_un['name'][$i] ? 'selected' : '';
							?>
							<option value="<?=$k;?>" <?=$selected;?>><?=$v;?></option>
							<?php
							} }
							?>
						</select>
					</dt>
					<dd>
						<span>주소</span>
						<input type="text" name="sns_address[]" value="<?php echo $nf_util->get_html($sns_info_un['address'][$i]);?>">
						<?php if($i===0) {?>
						<button type="button" class="gray s_r_base" onClick="nf_util.clone_tag(this, '.paste-body-', 999)">추가</button>
						<?php } else {?>
						<button type="button" class="gray s_r_base" onClick="nf_util.clone_tag(this, '.paste-body-', 999)">삭제</button>
						<?php }?>
					</dd>
				</dl>
				<?php
				}?>
			</td>
		</tr>
	</table>
</section>