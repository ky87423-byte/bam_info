<?php
include "../../engine/_core.php";
$top_menu_code = "100101";
if($_GET['code']=='ing') $top_menu_code = "100102";
if($_GET['code']=='service_end') $top_menu_code = "100103";
if($_GET['code']=='delete') $top_menu_code = "100104";
if($_GET['code']=='wait') $top_menu_code = "100106";
if($_GET['code']=='coupon') $top_menu_code = "100107";
if($_GET['code']=='jump') $top_menu_code = "100108";
include '../include/header.php'; // : 관리자 탑메뉴

$nf_util->sess_page_save("admin_shop");

$search_where = $nf_search->shop();

$_where = "";
if($_GET['code']=='wait') $_where = " and `is_wait`=0"; // : 등록대기
else if($_GET['code']!='delete') $_where = " and `is_wait`=1"; // : 등록대기아님
$_where .= $search_where['where'];
if($_GET['best']) $_where .= " and `wr_best`=1";

if($_GET['code']!='delete') $_where .= " and ns.`is_delete`=0";
if($_GET['code']=='ing') $_where .= $nf_shop->service_where2; // : 진행중
if($_GET['code']=='service_end') $_where .= $nf_shop->service_ing_where; // : 마감된
if($_GET['code']=='delete') $_where .= " and ns.`is_delete`=1"; // : 삭제한
if($_GET['code']=='coupon') $_where .= " and `coupon_use`=1"; // : 쿠폰업체
if($_GET['code']=='jump') $_where .= " and `wr_service_jump_int`>0"; // : 점프가 1건 이상 있는 업체
//if($_GET['end_date']) $_where .= " and wr_service_last<='".date("Y-m-d", strtotime($_GET['end_date'].' day'))."' and wr_service_last>='".today."'";
if($_GET['end_date']) $_where .= " and (".strtr($nf_shop->service_remain_where, array('{날짜}'=>date("Y-m-d", strtotime($_GET['end_date'].' day')))).")";
if($_GET['service'][0]) $_where .= " and (wr_service".implode(">='".today."' and wr_service", $_GET['service']).">='".today."')";
if($_GET['service_jump']) $_where .= " and `wr_service_jump_int`>0";
$q = "nf_shop as ns where 1 ".$_where;
$order = " order by ns.`no` desc";
if($_GET['code']=='jump') $order = " order by ns.`wr_jdate` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'A';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$shop_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
.service_list- { display:none; }

/* 카테고리 이동 모달 */
.cat_move- { cursor:default; padding:0 !important; min-width:540px; }
.cat_move- .cat_move_head { background:#f5f5f5; border-bottom:1px solid #ddd; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
.cat_move- .cat_move_head h6 { margin:0; font-size:15px; font-weight:700; }
.cat_move- .cat_move_body { padding:16px; }
.cat_move- .cat_notice { background:#fff8e1; border:1px solid #ffe082; border-radius:4px; padding:8px 12px; font-size:12px; color:#795548; margin-bottom:14px; }
.cat_move- .cat_section { border:1px solid #e0e0e0; border-radius:4px; margin-bottom:10px; }
.cat_move- .cat_section .cat_sec_head { background:#fafafa; padding:10px 14px; border-bottom:1px solid #e0e0e0; font-weight:600; }
.cat_move- .cat_section .cat_sec_head label { cursor:pointer; display:flex; align-items:center; gap:8px; }
.cat_move- .cat_section .cat_sec_body { padding:12px 14px; display:none; }
.cat_move- .cat_section .cat_sec_body select { margin-right:6px; }
.cat_move- .cat_section .cat_sec_body .cat_hint { font-size:11px; color:#999; margin-top:6px; }
.cat_move- .cat_footer { text-align:center; padding:12px; border-top:1px solid #eee; }
.cat_move- .cat_footer button { margin:0 4px; }
.cat_move_overlay- { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:8998; }
</style>
<script type="text/javascript">
var click_best = function(el, no) {
	var val = el.checked ? 1 : 0;
	$.post("../regist.php", "mode=click_shop_best&val="+val+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	var displays = obj.css("display");

	var flist = document.forms['flist'];
	var phone_arr = [];
	var sno_arr = [];
	var cnt = 0;
	$(flist).find("[name='chk[]']:checked").each(function(i){
		phone = $(this).closest("tr").attr("phone");
		sno = $(this).val();
		if(!in_array(phone, phone_arr) && phone) {
			phone_arr[cnt] = phone;
			sno_arr[cnt] = sno;
			cnt++;
		}
	});

	if(cnt<=0) {
		alert("상품정보를 선택해주시기 바랍니다.");
		return;
	} else {
		var fsms = document.forms['fsms'];
		fsms.code.value = "shop_list";
		$(fsms).find("[name='rphone_list']").val(phone_arr.join("\r\n"));
		$(fsms).find("[name='no_list']").val(sno_arr.join(","));
		obj.css({"display":display});
		return;
	}
}

var sel_service = function() {
	var form = document.forms['flist'];
	var no_arr = [];
	var chk_obj = $(form).find("[name='chk[]']:checked");
	if(chk_obj.length<=0) {
		alert("서비스를 승인할 정보를 선택해주시기 바랍니다.");
		return;
	}
	chk_obj.each(function(i){
		no_arr[i] = 'chk[]='+parseInt($(this).val());
	});

	location.href = root+"/admini/introduce/sevice_approval.php?mode=sel_service&"+no_arr.join("&")+"&top_menu_code=<?php echo $top_menu_code;?>";
}

// ── 카테고리 일괄 이동 ────────────────────────────────────────────────
var open_category_move = function() {
	var form   = document.forms['flist'];
	var chkObj = $(form).find("[name='chk[]']:checked");

	if (chkObj.length <= 0) {
		alert("카테고리를 변경할 업체를 선택해주세요.");
		return;
	}

	// 모달 form에 선택된 shop no 주입
	var mForm = document.forms['fcat_move'];
	$(mForm).find("input[name='chk[]']").remove();
	chkObj.each(function() {
		$(mForm).append('<input type="hidden" name="chk[]" value="' + parseInt($(this).val()) + '">');
	});

	// 선택 개수 표시
	$("#cat_move_cnt-").text(chkObj.length);

	// 카테고리 체크박스·셀렉터 초기화
	$(mForm).find("input[type='checkbox']").prop("checked", false);
	$(mForm).find("select").val("");
	$(".cat_sec_body").hide();

	// 모달 & 오버레이 표시
	$(".cat_move_overlay-").fadeIn(150);
	$(".cat_move-").fadeIn(150);
};

var close_category_move = function() {
	$(".cat_move_overlay-").fadeOut(150);
	$(".cat_move-").fadeOut(150);
};

var submit_category_move = function() {
	var mForm = document.forms['fcat_move'];

	var hasArea     = $("[name='change_area']", mForm).is(":checked");
	var hasTema     = $("[name='change_tema']", mForm).is(":checked");
	var hasCategory = $("[name='change_category']", mForm).is(":checked");

	if (!hasArea && !hasTema && !hasCategory) {
		alert("변경할 카테고리 항목(지역/테마/업종)을 하나 이상 선택해주세요.");
		return;
	}

	var cnt = $("[name='chk[]']", mForm).length;
	if (!confirm(cnt + "개 업체의 카테고리를 변경하시겠습니까?\n\n※ 이미지 파일 위치는 절대 변경되지 않습니다.")) return;

	$.post(root + "/admini/regist.php", $(mForm).serialize(), function(data) {
		data = $.parseJSON(data);
		if (data.msg) alert(data.msg);
		close_category_move();
		if (data.move) location.href = data.move;
		else location.reload();
	});
};

var reply_message = function(el) {
	var form = document.forms['fmessage'];
	var flist = document.forms['flist'];
	var chk_arr = [];
	var cnt = 0;
	$(flist).find("[name='chk[]']:checked").each(function(i){
		chk_arr[i] = $(this).val();
		cnt++;
	});
	if(cnt<=0) {
		alert("상품정보를 선택해주시기 바랍니다.");
		return;
	}
	nf_util.openWin(".message-");
	$(".input_nick-").css({"display":"inline"});
	form.admin.value = 1;
	
	$.post(root+"/include/regist.php", "mode=get_message_info&code=received&nos="+chk_arr.join(","), function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		try{
		if(data.js) eval(data.js);
		}catch(e){
			alert(e.message);
		}
	});
}
</script>
<!--전체 상품 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>
						- 해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide1-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button>
					</li>
					<?php if($_GET['code']=='ing') { ?>
					<li>
						- 서비스기간이 존재하는 상품관리 페이지 입니다.  
					</li>
					<?php }else if($_GET['code']=='service_end') { ?>
					<li>
						- 서비스기간이 만료된 상품관리 페이지 입니다.  
					</li>
					<li>
						- 서비스승인 버튼을 통해 기간을 임의 부여 해줄수 있습니다.  
					</li>
					<?php }else if($_GET['code']=='delete') { ?>
					<li>
						- 삭제된 상품관리 페이지 입니다.  
					</li>
					<li>
						- 리스트 우측에 편집을 통해 삭제된 상품을 복구 시킬수 있습니다.
					</li>
					<?php }else if($_GET['code']=='wait') { ?>
					<li>
						- 서비스기간이 존재하나 임시적으로 상품 노출을 시키지 않습니다.
					</li>
					<li>
						- 리스트 상단에 등록복구 버튼을 통해 다시 노출 시킬수 있습니다. 
					</li>
					<?php }else if($_GET['code']=='coupon') { ?>
					<li>
						- 상품등록시 쿠폰을 발행한 상품관리 페이지 입니다.  
					</li>
					<?php }else if($_GET['code']=='jump') { ?>
					<li>
						- 점프서비스를 결제한 상품이 노출되며 가장 최근에 점프된 순으로 정렬이 됩니다.   
					</li>
					<?php } ?>
				</ul>
			</div>	

			<form name="fsearch" action="" method="get">
				<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
				<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="8%">
							<col width="44%">
							<col width="8%">
							<col width="40%">
						</colgroup>
						<tbody>
							<tr>
								<th>
									<select name="rdate">
										<option value="wr_rdate" <?php echo $_GET['rdate']=='wr_rdate' ? 'selected' : '';?>>등록일</option>
										<option value="wr_udate" <?php echo $_GET['rdate']=='wr_udate' ? 'selected' : '';?>>수정일</option>
									</select>
								</th>
								<td>
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
								<th>지역</th>
								<td>
									<?php
									for($i=1; $i<=$area_depth; $i++) {
										$area1 = $_GET['wr_area'][0];
										if($i>1) $area2 = $_GET['wr_area'][1];
										$nf_category->get_area($area1, $area2);
										if($i===1) $area_array = $cate_p_array['area'][0];
										else if($i===2) $area_array = $cate_area_array['SI'][$area1];
										else if($i===3) $area_array = $cate_area_array['GU'][$area1][$area2];
									?>
									<select name="wr_area[]" wr_type="area" hname="<?php echo $i;?>차지역" needed <?php if($i<$area_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
										<option value=""><?php echo $i;?>차 지역</option>
										<?php
										if(is_array($area_array)) { foreach($area_array as $k=>$v) {
											$selected = $_GET['wr_area'][$i-1]==$v['wr_name'] ? 'selected' : '';
										?>
										<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?> no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
										<?php
										} }?>
									</select>
									<?php
									}?>
								</td>
							</tr>
							<?php if($_GET['code']!='service_end') {?>
							<tr>
								<th>서비스</th>
								<td>
									<div>
									<?php
									$count = 0;
									if(is_array($nf_shop->service_name['shop']['main'])) { foreach($nf_shop->service_name['shop']['main'] as $k=>$v) {
										$service_k = '0_'.$k;
										$checked = is_array($_GET['service']) && in_array($service_k, $_GET['service']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="service[]" <?php echo $checked;?> value="<?php echo $service_k;?>"><?php echo $v;?></label>
									<?php
									} }?>
									<label><input type="checkbox" name="service_jump" value="1" <?php echo $_GET['service_jump'] ? 'checked' : '';?>>점프</label>
									</div>
								</td>
								<th>만료일</th>
								<td>
									<select name="end_date">
										<option value="">선택하기</option>
										<option value="1" <?php echo $_GET['end_date']=='1' ? 'selected' : '';?>>1일전</option>
										<option value="2" <?php echo $_GET['end_date']=='2' ? 'selected' : '';?>>2일전</option>
										<option value="3" <?php echo $_GET['end_date']=='3' ? 'selected' : '';?>>3일전</option>
										<option value="4" <?php echo $_GET['end_date']=='4' ? 'selected' : '';?>>4일전</option>
										<option value="5" <?php echo $_GET['end_date']=='5' ? 'selected' : '';?>>5일전</option>
										<option value="6" <?php echo $_GET['end_date']=='6' ? 'selected' : '';?>>6일전</option>
										<option value="7" <?php echo $_GET['end_date']=='7' ? 'selected' : '';?>>7일전</option>
										<option value="15" <?php echo $_GET['end_date']=='15' ? 'selected' : '';?>>15일전</option>
										<option value="30" <?php echo $_GET['end_date']=='30' ? 'selected' : '';?>>30일전</option>
									</select>
								</td>
							</tr>
							<?php }?>
							<tr>
								<th>업종별</th> <!-- 1차값만 사용시 checkbox, 2차 이상 사용시 select box --->
								<td colspan=3>
									<?php
									for($i=1; $i<=$job_part_depth; $i++) {
										$cate_part_arr = $_GET['category']
									?>
									<select class="check_seconds" name="category[]" hname="<?php echo $i;?>차 카테고리" needed <?php if($i<$job_part_depth) {?>onChange="nf_category.ch_category(this, <?php echo $i;?>);"<?php }?>>
										<option value=""><?php echo $i;?>차 카테고리</option>
										<?php
										if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
											$selected = $_GET['category'][$i-1]==$v['no'] ? 'selected' : '';
										?>
										<option value="<?php echo $v['no'];?>" no="<?php echo $v['no'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }?>
									</select>
									<?php
									}?>
								</td>
							</tr>
							<tr>
								<th>테마별</th>
								<td colspan=3>
									<div>
									<?php
									if(is_array($cate_array['job_tema'])) { foreach($cate_array['job_tema'] as $k=>$v) {
										$checked = is_array($_GET['tema']) && in_array($k, $_GET['tema']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="tema[]" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
									} }?>
									</div>
								</td>
							</tr>
							<tr>
								<th>BEST업체</th>
								<td colspan=3>
									<div>
									<label><input type="checkbox" name="best" value="1" <?php echo $_GET['best'] ? 'checked' : '';?>>BEST업체</label>
									</div>
								</td>
							</tr>

						</tbody>
					</table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="wr_company" <?php echo $_GET['search_field']==='wr_company' ? 'selected' : '';?>>업체명</option>
							<option value="wr_id" <?php echo $_GET['search_field']==='wr_id' ? 'selected' : '';?>>아이디</option>
							<option value="wr_phone" <?php echo $_GET['search_field']==='wr_phone' ? 'selected' : '';?>>전화번호</option>
							<option value="wr_hphone" <?php echo $_GET['search_field']==='wr_hphone' ? 'selected' : '';?>>핸드폰번호</option>
							<option value="wr_subject" <?php echo $_GET['search_field']==='wr_subject' ? 'selected' : '';?>>상품 제목</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			
			<h6>상품 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_shop" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onClick="open_box(this, 'sms-')"><i class="axi axi-mail"></i> 문자발송</button></a>
				<button type="button" class="gray" onClick="reply_message(this)"><i class="axi axi-ion-chatbox-working"></i> 쪽지발송</button></a>
				<button type="button" class="blue" onclick="open_category_move()"><i class="axi axi-move"></i> 카테고리 이동</button>
				<?php if($_GET['code']!='delete') {?>
				<?php if($_GET['code']!='wait') {?>
				<!-- 등록대기 상품관리에서 미노출 -->
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '등록대기로 설정하시겠습니까?')" url="../regist.php" mode="shop_select_wait" tag="chk[]" check_code="checkbox">등록대기</button></a>
				<!-- 등록대기 상품관리에서 노출 -->
				<?php }?>
				<?php if($_GET['code']=='wait') {?>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '등록복구로 설정하시겠습니까?')" url="../regist.php" mode="shop_select_wait_repair" tag="chk[]" check_code="checkbox">등록복구</button></a>
				<?php }?>
				<button type="button" onClick="sel_service()" class="red">선택 서비스 승인</button>
				<?php }?>
				<a href="./shop_modify.php"><button type="button" class="blue"><strong>+</strong> 상품등록</button></a>
			</div>
			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="7%">
					<col>
					<col width="12%">
					<col width="12%">
					<col width="8%">
					<col width="15%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>BEST업체</th>
						<th>이미지</th>
						<th>상품정보</th>
						<th colspan=2>등록정보</th>
						<th>클릭횟수</th>
						<th>서비스/기간</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="9" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($shop_row=$db->afetch($shop_query)) {
								$shop_info = $nf_shop->shop_info($shop_row);
								$get_service_info = $nf_payment->get_service_info($shop_row, 'shop');

								$phone_val = $shop_row['wr_hphone'] ? $shop_row['wr_hphone'] : $shop_row['hphone'];
								if(!strtr($phone_val, array("-"=>""))) echo $phone_val = "";
					?>
					<tr class="tac" mb_id="<?php echo $shop_row['wr_id'];?>" phone="<?php echo $phone_val;?>" mno="<?php echo intval($shop_row['mno']);?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $shop_row['no'];?>"></td>
						<td><input type="checkbox" name="best[]" <?php echo $shop_row['wr_best'] ? 'checked' : '';?> onClick="click_best(this, '<?php echo $shop_row['no'];?>')" class="best_" value="<?php echo $shop_row['no'];?>"></td>
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지" style="width:80px;height:80px;"></td>
						<td class="product tal">
							<ul>
								<li><a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>" target="_blank"><b class="blue"><span>제목</span> : <?php echo $nf_util->get_text($shop_row['wr_subject']);?></b></a></li>
								<li><span>지역</span> : <?php echo strtr(strtr($shop_row['wr_area'], array(","=>" ")), $cate_array['area']);?></li>
								<?php /*<li><span>테마</span> : <?php echo strtr(strtr($shop_row['wr_tema'], array(","=>", ")), $cate_array['job_tema']);?></li>*/?>
								<li><span>업종</span> : <?php echo $shop_info['category_txt'];?> </li>
							</ul>
						</td>
						<td class="product tal">
							<ul>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><b class="blue"><span>업체명</span> : <?php echo $nf_util->get_text($shop_row['wr_company']);?></b></li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>아이디</span> : <?php echo $shop_row['wr_id'];?></li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>이&nbsp;&nbsp;&nbsp;름</span> : <?php echo $shop_row['wr_name'];?></li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>연락처</span> : <?php echo $shop_row['wr_phone'];?> </li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>핸드폰</span> : <?php echo $shop_row['wr_hphone'];?> </li>
							</ul>
						</td>
						<td>
							<ul>
								<li><b>등록일</b> : <?php echo date("Y/m/d H:i", strtotime($shop_row['wr_rdate']));?></li>
								<li><b>수정일</b> : <?php echo date("Y/m/d H:i", strtotime($shop_row['wr_udate']));?></li>
								<!-- 점프업체 상품관리 시에만 마지막 점프일이 노출됨 --> 
								<li><b>점프일</b> : <?php echo ($shop_row['wr_jdate']!='1000-01-01 00:00:00') ? date("Y/m/d H:i", strtotime($shop_row['wr_jdate'])) : '<span style="color:red;">점프안함</span>';?></li>
							</ul>
						</td>						
						<td>
							<ul>
								<li><b>조회수</b> : <?php echo number_format($shop_row['wr_hit']);?>회</li>
								<li><b>문자수</b> : <?php echo number_format($shop_row['click_sms']);?>회</li>
								<li><b>전화수</b> : <?php echo number_format($shop_row['click_phone']);?>회</li>
							</ul>
						</td>
						<td class="job_service_list service_td-">
							<ul class="tal">
								<?php
								ob_start();
								foreach($get_service_info['text'] as $k=>$v) {
									$get_date = $get_service_info['date'][$k];
								?>
								<li><b><?php echo $v;?></b> : ~ <?php echo date("Y/m/d", strtotime($get_date));?></li>
								<?php
								}
								if($shop_row['wr_service_jump_int']>0) {
								?>
								<li><b>점프</b> : <?php echo number_format($shop_row['wr_service_jump_int']);?>건(<?php echo $shop_row['wr_jump_use'] ? '사용중' : '미사용중';?>)</li>
								<?php
								}
								echo $service_tag = ob_get_clean();
								if(!$service_tag) {
								?>
								<span class="blue">서비스 기간 없음</span>
								<?php
								}?>
							</ul>
						</td>
						<td style="text-align:center">
							<?php if($_GET['code']!='delete') {?>
							<a href="<?php echo NFE_URL;?>/admini/introduce/shop_modify.php?no=<?php echo $shop_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<?php }?>
							<button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($shop_row['no']);?>" mode="delete_shop" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
							<?php if($_GET['code']!='delete') {?>
							<a href="./shop_modify.php?info_no=<?php echo intval($shop_row['no']);?>"><button type="button" class="gray common"><i class="axi axi-content-copy"></i> 복사하기</button></a>
							<a href="./sevice_approval.php?no=<?php echo $shop_row['no'];?>&top_menu_code=<?php echo $top_menu_code;?>"><button type="button" class="red common">서비스 승인</button></a>
							<?php }?>
							<?php if($_GET['code']=='delete') {?>
							<!-- 삭제한 상품관리 때는 삭제하기, 복구하기 버튼만 노출. 나머지 버튼 다 미노출 -->
							<button type="button" class="red common" onclick="nf_util.ajax_post(this, '복구하시겠습니까?')" no="<?php echo intval($shop_row['no']);?>" mode="delete_shop_repair" url="../regist.php">복구하기</button>
							<?php }?>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>

			<div><?php echo $paging['paging'];?></div>
			</form>

			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_shop" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onClick="open_box(this, 'sms-')"><i class="axi axi-mail"></i> 문자발송</button></a>
				<button type="button" class="gray" onClick="reply_message(this)"><i class="axi axi-ion-chatbox-working"></i> 쪽지발송</button></a>
				<button type="button" class="blue" onclick="open_category_move()"><i class="axi axi-move"></i> 카테고리 이동</button>
				<?php if($_GET['code']!='delete') {?>
				<!-- 등록대기 상품관리에서 미노출 -->
				<?php if($_GET['code']!='wait') {?>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '등록대기로 설정하시겠습니까?')" url="../regist.php" mode="shop_select_wait" tag="chk[]" check_code="checkbox">등록대기</button></a>
				<?php }?>
				<!-- 등록대기 상품관리에서 노출 -->
				<?php if($_GET['code']=='wait') {?>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '등록복구로 설정하시겠습니까?')" url="../regist.php" mode="shop_select_wait_repair" tag="chk[]" check_code="checkbox">등록복구</button></a>
				<?php }?>
				<button type="button" onClick="sel_service()" class="red">선택 서비스 승인</button>
				<?php }?>
				<button type="button" class="blue"><strong>+</strong> 상품등록</button></a>
			</div>
		</div>
		<!--//payconfig conbox-->

		
	</section>
</div>
<!--//wrap-->
<?php
include NFE_PATH.'/admini/include/sms.inc.php'; // : 문자
//include '../include/note.inc.php'; // 쪽지보내기 레이아웃
?>

<!-- ============================================================
     카테고리 일괄 이동 모달
     ※ 이미지 파일(wr_photo, wr_main_photo)은 절대 변경하지 않음
     ※ wr_area / wr_tema / wr_category 필드만 UPDATE
============================================================ -->
<div class="cat_move_overlay-"></div>
<div class="layer_pop2 conbox cat_move- popup_box-" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:8999;width:580px;">

	<div class="cat_move_head">
		<h6><i class="axi axi-move"></i> 카테고리 일괄 이동 &mdash; <strong id="cat_move_cnt-" style="color:#1a73e8;">0</strong>개 업체 선택됨</h6>
		<button type="button" onclick="close_category_move()" class="close" style="background:none;border:none;font-size:16px;cursor:pointer;">✕</button>
	</div>

	<div class="cat_move_body">
		<p class="cat_notice">
			<i class="axi axi-ion-information-circled"></i>
			<strong>이미지 파일은 이동하지 않습니다.</strong> 지역·테마·업종 정보만 변경됩니다.<br>
			체크한 항목만 변경되고, 체크하지 않은 항목은 기존 값을 유지합니다.
		</p>

		<form name="fcat_move" method="post">
		<input type="hidden" name="mode" value="shop_category_move">
		<!-- chk[] : open_category_move() 에서 동적 주입 -->

		<!-- ── 지역 변경 ────────────────────────────────────────────── -->
		<div class="cat_section">
			<div class="cat_sec_head">
				<label>
					<input type="checkbox" name="change_area" value="1"
						onchange="$(this).closest('.cat_section').find('.cat_sec_body').toggle(this.checked);">
					지역 변경
				</label>
			</div>
			<div class="cat_sec_body">
				<?php
				for($_ci=1; $_ci<=$area_depth; $_ci++) {
					$_area1_c = "";
					if($_ci>1) $_area1_c = "";
					$_area_arr_c = array();
					if($_ci===1) $_area_arr_c = $cate_p_array['area'][0];
				?>
				<select name="wr_area[]" wr_type="area"
					<?php if($_ci<$area_depth) {?>onchange="nf_category.ch_category(this, <?php echo $_ci; ?>);"<?php }?>>
					<option value=""><?php echo $_ci; ?>차 지역 (선택)</option>
					<?php
					if(is_array($_area_arr_c)) { foreach($_area_arr_c as $_k=>$_v) {
					?>
					<option value="<?php echo $nf_util->get_html($_v['wr_name']);?>"><?php echo $nf_util->get_html($_v['wr_name']);?></option>
					<?php } } ?>
				</select>
				<?php } ?>
				<p class="cat_hint">* 빈 값(선택 안 함)으로 저장하려면 "차 지역 (선택)"을 그대로 두세요.</p>
			</div>
		</div>

		<!-- ── 테마 변경 ────────────────────────────────────────────── -->
		<div class="cat_section">
			<div class="cat_sec_head">
				<label>
					<input type="checkbox" name="change_tema" value="1"
						onchange="$(this).closest('.cat_section').find('.cat_sec_body').toggle(this.checked);">
					테마 변경
				</label>
			</div>
			<div class="cat_sec_body">
				<?php
				if(is_array($cate_array['job_tema'])) { foreach($cate_array['job_tema'] as $_tk=>$_tv) {
				?>
				<label style="margin-right:10px;display:inline-block;margin-bottom:4px;">
					<input type="checkbox" name="tema_category[]" value="<?php echo $_tk;?>">
					<?php echo $nf_util->get_text($_tv);?>
				</label>
				<?php } } ?>
				<p class="cat_hint">* 체크한 테마로 전체 교체됩니다. (기존 테마 삭제 후 교체)</p>
				<p class="cat_hint">* 아무것도 체크하지 않으면 테마가 빈 값으로 저장됩니다.</p>
			</div>
		</div>

		<!-- ── 업종 변경 ────────────────────────────────────────────── -->
		<div class="cat_section">
			<div class="cat_sec_head">
				<label>
					<input type="checkbox" name="change_category" value="1"
						onchange="$(this).closest('.cat_section').find('.cat_sec_body').toggle(this.checked);">
					업종 변경
				</label>
			</div>
			<div class="cat_sec_body">
				<?php
				for($_ji=1; $_ji<=$job_part_depth; $_ji++) {
					$_cate_k_c = $_ji===1 ? 0 : null;
					$_cate_arr_c = $_ji===1 ? $cate_p_array['job_part'][0] : array();
				?>
				<select class="check_seconds" name="category[0][]" wr_type="job_part"
					<?php if($_ji<$job_part_depth) {?>onchange="nf_category.ch_category(this, <?php echo $_ji; ?>);"<?php }?>>
					<option value=""><?php echo $_ji; ?>차 업종 (선택)</option>
					<?php
					if(is_array($_cate_arr_c)) { foreach($_cate_arr_c as $_k=>$_v) {
					?>
					<option value="<?php echo $_v['no'];?>" no="<?php echo $_v['no'];?>"><?php echo $nf_util->get_text($_v['wr_name']);?></option>
					<?php } } ?>
				</select>
				<?php } ?>
				<p class="cat_hint">* 선택한 업종으로 전체 교체됩니다. (기존 업종 삭제 후 교체)</p>
			</div>
		</div>

		<div class="cat_footer">
			<button type="button" class="blue" onclick="submit_category_move()">
				<i class="axi axi-check"></i> 변경하기
			</button>
			<button type="button" class="gray" onclick="close_category_move()">취소</button>
		</div>
		</form>
	</div>
</div>
<!-- //카테고리 일괄 이동 모달 -->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->