<?php
include "../../engine/_core.php";
$top_menu_code = "100107";
include '../include/header.php'; // : 관리자 탑메뉴

$search_where = $nf_search->shop();

$_where = " and `is_wait`=1"; // : 등록대기아님
$_where .= $search_where['where'];
if($_GET['best']) $_where .= " and `wr_best`=1";

if(!$_GET['code']) $_where .= " and ns.`is_delete`=0";
if($_GET['code']=='ing') $_where .= $nf_shop->service_where2; // : 진행중
if($_GET['code']=='service_end') $_where .= $nf_shop->service_ing_where; // : 마감된
if($_GET['code']=='delete') $_where .= " and ns.`is_delete`=1"; // : 삭제한
//if($_GET['end_date']) $_where .= " and wr_service_last<='".date("Y-m-d", strtotime($_GET['end_date'].' day'))."' and wr_service_last>='".today."'";
if($_GET['end_date']) $_where .= " and (".strtr($nf_shop->service_remain_where, array('{날짜}'=>date("Y-m-d", strtotime($_GET['end_date'].' day')))).")";
if($_GET['service'][0]) $_where .= " and (wr_service".implode(">='".today."' and wr_service", $_GET['service']).">='".today."')";
if($_GET['service_jump']) $_where .= " and wr_service_jump>0";
$q = "nf_shop as ns where coupon_use=1 ".$_where;
$order = " order by ns.`no` desc";
if($_GET['code']=='jump') $order = " order by ns.`wr_jdate` desc";

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$shop_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
.service_list- { display:none; }
</style>

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
					<li>
						- 상품등록시 쿠폰을 발행한 상품관리 페이지 입니다.  
					</li>					
				</ul>
			</div>	

			<form name="fsearch" action="" method="get">
				<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
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
			
			<h6>쿠폰 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
				<button type="button" class="gray"><i class="axi axi-mail"></i> 문자발송</button></a>
				<button type="button" class="gray"><i class="axi axi-ion-chatbox-working"></i> 쪽지발송</button></a>
			</div>
			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="7%">
					<col>
					<col width="17%">
					<col width="12%">
					<col width="25%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>이미지</th>
						<th>상품정보</th>
						<th colspan=2>등록정보</th>
						<th>쿠폰정보</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="7" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($shop_row=$db->afetch($shop_query)) {
								$shop_info = $nf_shop->shop_info($shop_row);
								$get_service_info = $nf_payment->get_service_info($shop_row, 'shop');
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $shop_row['no'];?>"></td>						
						<td><img src="<?php echo NFE_URL.$shop_info['main_photo_url'];?>" alt="업체이미지" style="width:80px;height:80px;"></td>
						<td class="product tal">
							<ul>
								<li><a href="<?php echo NFE_URL;?>/shop/index.php?no=<?php echo $shop_row['no'];?>" target="_blank"><b class="blue"><span>제목</span> : <?php echo $nf_util->get_text($shop_row['wr_subject']);?></b></a></li>
								<li><span>지역</span> : <?php echo strtr(strtr($shop_row['wr_area'], array(","=>" ")), $cate_array['area']);?></li>
								<li><span>테마</span> : <?php echo strtr(strtr($shop_row['wr_tema'], array(","=>", ")), $cate_array['job_tema']);?></li>
								<li><span>업종</span> : <?php echo $shop_info['category_txt'];?></li>
							</ul>
						</td>
						<td class="product tal">
							<ul>
								<li><b class="blue"><span>업체명</span> : <?php echo $nf_util->get_text($shop_row['wr_company']);?></b></li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>아이디</span> : <?php echo $shop_row['wr_id'];?></li>
								<li onClick="member_mno_click(this)" mno="<?php echo $shop_row['mno'];?>"><span>이&nbsp;&nbsp;&nbsp;름</span> : <?php echo $shop_row['wr_name'];?></li>
								<li><span>연락처</span> : <?php echo $shop_row['wr_phone'];?> </li>
								<li><span>핸드폰</span> : <?php echo $shop_row['wr_hphone'];?> </li>
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
						<td class="product tal">
							<ul>
								<li><b class="blue"><span>쿠폰제목</span> : <?php echo $nf_util->get_text($shop_row['coupon_subject']);?></b></li>
								<li><span>쿠폰금액</span> : <?php echo number_format($shop_row['coupon_price']);?>원</li>
								<li><span>사용기간</span> : <?php echo date("Y/m/d", strtotime($shop_row['coupon_date1']));?> ~ <?php echo date("Y/m/d", strtotime($shop_row['coupon_date2']));?></li>
								<li><span>사용건수</span> : <?php echo number_format($shop_row['coupon_use_int']);?>건 사용 / 총 <?php echo number_format($shop_row['coupon_allow_int']);?>건 </li>
								<li><span>쿠폰제한</span> : <?php echo $nf_shop->coupon_limit_arr[$shop_row['coupon_limit']];?> </li>
							</ul>
						</td>

						<td style="text-align:center">
							<a href="<?php echo NFE_URL;?>/admini/introduce/shop_modify.php?no=<?php echo $shop_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($shop_row['no']);?>" mode="delete_shop" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
							<a href="./shop_modify.php?info_no=<?php echo intval($shop_row['no']);?>"><button type="button" class="gray common"><i class="axi axi-content-copy"></i> 복사하기</button></a>
							<a href="<?php echo NFE_URL;?>/admini/introduce/coupon_use.php?shop_no=<?php echo $shop_row['no'];?>"><button type="button" class="red common">쿠폰사용내역</button></a>
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
				<button type="button" class="gray"><i class="axi axi-mail"></i> 문자발송</button></a>
				<button type="button" class="gray"><i class="axi axi-ion-chatbox-working"></i> 쪽지발송</button></a>
			</div>
		</div>
		<!--//payconfig conbox-->

		
	</section>
</div>
<!--//wrap-->
<?php include '../include/note.inc.php'; ?> <!--쪽지보내기 레이아웃-->
<?php include '../include/footer.php'; ?> <!--관리자 footer-->