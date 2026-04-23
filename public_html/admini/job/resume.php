<?php
include "../../engine/_core.php";
$top_menu_code = "100201";
if($_GET['code']=='service_end') $top_menu_code = "100204";
include '../include/header.php';

$nf_util->sess_page_save("admin_resume_list");

$where_arr = $nf_search->resume();
$service_where = $nf_search->service_where('resume');
$_where = $where_arr['where'];
if($_GET['code']=='ing') $_where .= " and (".$service_where['where'].")".$nf_job->resume_where;
if($_GET['code']=='audit') $_where .= " and `is_audit`=1";
if($_GET['code']=='end') $_where .= $nf_job->not_end_date_where;
if($_GET['code']=='service_end') $_where .= " and (".strtr($service_where['where'], array(" or "=>" and ", ">="=>"<")).")";
if($_GET['mno']) $_where .= " and nr.mno=".intval($_GET['mno']);
$q = $where_arr['table']." where nr.`is_delete`=0 ".$_where;
$order = " order by nr.`no` desc";
if($_GET['sort']) $order = " order by nr.`".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['admin_resume_q'] = $q.$order;
$resume_query = $db->_query("select * from ".$_SESSION['admin_resume_q']." limit ".$paging['start'].", ".$_arr['num']);
?>

<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
.service_list- { display:none; }
</style>
<script type="text/javascript">
var ch_career = function(el) {
	var display = el.value==='2' ? 'inline' : 'none';
	$(".wr_career-").css({"display":display});
}

var click_service = function(el) {
	var display = $(el).closest(".service_td-").find(".service_list-").css("display");
	display = display=='none' ? 'block' : 'none';
	$(el).closest(".service_td-").find(".service_list-").css({"display":display});
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

	location.href = root+"/admini/job/resume_approval.php?mode=sel_service&"+no_arr.join("&")+"&top_menu_code=<?php echo $top_menu_code;?>";
}
</script>
<!--이력서 관리-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<?php if($_GET['code'] == 'service_end') { ?>
					<li>해당 페이지는 등록된 이력서의 유료 서비스기간이 만료된 이력서 리스트 입니다.</li>
					<?php }else{ ?>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide1-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<?php } ?>
				</ul>
			</div>
			<div class="search">
				<form name="fsearch" action="" method="get">
					<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
					<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
					<input type="hidden" name="sort_lo" value="<?php echo $nf_util->get_html($_GET['sort_lo']);?>" />
					<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
					<table>
						<colgroup>
							<col width="8%">
							<col width="42%">
							<col width="8%">
							<col width="42%">
						</colgroup>
						<tbody>
							<tr>
								<th>등록일</th>
								<td colspan="3">
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
							</tr>
							<tr>
								<th>서비스</th>
								<td colspan="3">
									<div>
										<?php
										$count = 0;
										if(is_array($nf_job->service_name['resume'])) { foreach($nf_job->service_name['resume'] as $k=>$v) {
											if(is_array($v)) { foreach($v as $k2=>$v2) {
												$val = $count.'_'.$k2;
												$checked = (is_array($_GET['service']) && in_array($val, $_GET['service'])) ? 'checked' : '';
										?>
										<label><input type="checkbox" name="service[]" value="<?php echo $val;?>" <?php echo $checked;?>><?php echo $v2;?></label>
										<?php
											} }
											$count++;
										} }
										?>
										</div>

										<div>
										<?php
										if(is_array($nf_job->etc_service)) { foreach($nf_job->etc_service as $k=>$v) {
											$val = '_'.$k;
											$checked = (is_array($_GET['service']) && in_array($val, $_GET['service'])) ? 'checked' : '';
										?>
										<label><input type="checkbox" name="service[]" value="<?php echo $val;?>" <?php echo $checked;?>><?php echo $v;?></label>
										<?php
										} }
										?>
										</div>
								</td>
							</tr>
							<tr>
								<th>직종</th>
								<td>
									<select name="wr_job_type[]" onChange="nf_category.ch_category(this, 1)">
										<option value="">= 1차 직종선택 =</option>
										<?php
										$selected = "";
										$job_part1 = $_GET['wr_job_type'][0];
										if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
											$selected = $_GET['wr_job_type'][0]==$k ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }?>
									</select>
									<select name="wr_job_type[]" onChange="nf_category.ch_category(this, 2)">
										<option value="">= 2차 직종선택 =</option>
										<?php
										$selected = "";
										$job_part2 = $_GET['wr_job_type'][1];
										if(is_array($cate_p_array['job_part'][$job_part1])) { foreach($cate_p_array['job_part'][$job_part1] as $k=>$v) {
											$selected = $_GET['wr_job_type'][1]==$k ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }
										?>
									</select>
									<select name="wr_job_type[]">
										<option value="">= 3차 직종선택 =</option>
										<?php
										$selected = "";
										if(is_array($cate_p_array['job_part'][$job_part2])) { foreach($cate_p_array['job_part'][$job_part2] as $k=>$v) {
											$selected = $_GET['wr_job_type'][2]==$k ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }
										?>
									</select>
								</td>
								<th>지역</th>
								<td>
									<select name="wr_area[]" onChange="nf_category.ch_category(this, 1)" wr_type="area">
										<option value="">-- 시·도 --</option>
										<?php
										$checked = "";
										$area1 = $_GET['wr_area'][0];
										$nf_category->get_area($area1);
										if(is_array($cate_p_array['area'][0])) { foreach($cate_p_array['area'][0] as $k=>$v) {
											$selected = $_GET['wr_area'][0]==$v['wr_name'] ? 'selected' : '';
										?>
										<option value="<?php echo $v['wr_name'];?>" no="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }?>
									</select>
									<select name="wr_area[]">
										<option value="">-- 시·군·구 --</option>
										<option value="all" <?php echo $_GET['wr_area'][1]=='all' ? 'selected' : '';?>>전체</option>
										<?php
										$checked = "";
										$area2 = $_GET['wr_area'][1];
										if(is_array($cate_area_array['SI'][$area1])) { foreach($cate_area_array['SI'][$area1] as $k=>$v) {
											$selected = $_GET['wr_area'][1]==$v['wr_name'] ? 'selected' : '';
										?>
										<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }?>
									</select>
								</td>
							</tr>
							<tr>
								<th>경력</th>
								<td>
									<?php
									$count = 0;
									if(is_array($nf_job->career_type)) { foreach($nf_job->career_type as $k=>$v) {
										if(!$k) continue;
										$checked = $_GET['wr_career_type'][0]==$k ? 'checked' : '';
									?>
									<label><input type="checkbox" name="wr_career_type[]" onClick="nf_util.one_check(this);ch_career(this)" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
										$count++;
									} }
									?>
									<span class="wr_career-" style="display:<?php echo $_GET['wr_career_type'][0]==2 ? 'inline' : 'none';?>;">
										<select name="wr_career">
											<option value="">= 경력선택 =</option>
											<option value="6 down">6개월이하</option>
											<option value="12 down">1년이하</option>
											<?php
											for($i=1; $i<=10; $i++) {
												$_int = $i*12;
												$selected = $_GET['wr_career']==$_int ? 'selected' : '';
											?>
											<option value="<?php echo $_int;?>" <?php echo $selected;?>><?php echo $i;?>년이상</option>
											<?php
											}
											?>
										</select>
									</span>
								</td>
								<th>성별</th>
								<td>
									<?php
									if(is_array($nf_util->gender_arr)) { foreach($nf_util->gender_arr as $k=>$v) {
										$checked = $_GET['wr_gender'][0]==$k ? 'checked' : '';
									?>
									<label><input type="checkbox" name="wr_gender[]" onClick="nf_util.one_check(this)" <?php echo $checked;?> value="<?php echo $k;?>"><?php echo $v;?></label>
									<?php
									} }?>
								</td>
							</tr>
							<tr>
								<th>최종학력</th>
								<td colspan="3">
									<?php
									$count = 0;
									if(is_array($nf_job->school)) { foreach($nf_job->school as $k=>$v) {
										$checked = is_array($_GET['wr_school_ability']) && in_array($k, $_GET['wr_school_ability']) ? 'checked' : '';
									?>
									<label><input type="checkbox" name="wr_school_ability[]" value="<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
									<?php
										$count++;
									} }
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="mb_name" <?php echo $_GET['search_field']==='mb_name' ? 'selected' : '';?>>이름</option>
							<option value="mb_id" <?php echo $_GET['search_field']==='mb_id' ? 'selected' : '';?>>아이디</option>
							<option value="wr_subject" <?php echo $_GET['search_field']==='wr_subject' ? 'selected' : '';?>>이력서제목</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
					</div>
				</form>
			</div>
			<!--//search-->
			
			<h6>이력서 정보 관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건이 검색되었습니다.</span>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_resume" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onClick="sel_service()" class="red">선택 서비스 승인</button>
				<a href="./resume_modify.php"><button type="button" class="blue"><strong>+</strong> 이력서등록</button></a>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="8%">
					<col width="15%">
					<col width="%">
					<col width="10%">
					<col width="18%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>등급구분</th>
						<th>회원등급</th>
						<th>이름/성별/나이/아이디</th>
						<th colspan="2">이력서</th>
						<th>서비스/기간</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="8" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($re_row=$db->afetch($resume_query)) {
								$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($re_row['mno']));
								$mem_indi = $db->query_fetch("select * from nf_member_individual where `mno`=".intval($re_row['mno']));
								$resume_info = $nf_job->resume_info($re_row);
								$get_service_info = $nf_payment->get_service_info($re_row, 'resume');
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $re_row['no'];?>"></td>
						<td>
							<?php if($re_row['wr_is_admin']) {?>
							<span class="gray">관리자</span>
							<?php } else {?>
							사용자
							<?php }?>
						</td>
						<td>
							<?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?>
						</td>
						<td class="job_info tal">
							<a href="#none" onClick="member_mno_click(this)" class="blue" mno="<?php echo $mem_row['no'];?>">
								<ul>
									<li><span>이름</span> : <?php echo $mem_row['mb_name'];?></li>
									<li><span>성별</span> : <?php echo $nf_util->gender_arr[$mem_row['mb_gender']];?></li>
									<li><span>나이</span> : <?php echo $nf_util->get_age($mem_row['mb_birth']);?>세</li>
									<li><span>아이디</span> : <?php echo $mem_row['mb_id'];?></li>
								</ul>
							</a>
						</td>
						<td align="left"><a href="<?php echo NFE_URL;?>/resume/resume_detail.php?no=<?php echo $re_row['no'];?>" target="_blank" class="blue"><?php echo stripslashes($re_row['wr_subject']);?></a></td>
						<td>
							<ul>
								<li>등록일 : <?php echo date("Y/m/d", strtotime($re_row['wr_wdate']));?></li>
								<li>수정일 : <?php echo date("Y/m/d", strtotime($re_row['wr_udate']));?></li>
								<li>조회 : <?php echo number_format(intval($re_row['wr_hit']));?>건</li>
							</ul>
						</td>
						<td class="job_service_list service_td-">
							<?php
							if($get_service_info['count']>0) {
							?>
							<button type="button" onClick="click_service(this)" class="gray" style="color:#158fe7; padding:2px 3px; font-weight:700;"><i class="axi axi-plus2"></i> 서비스보기</button>
							<ul class="tal service_list-">
								<?php
								foreach($get_service_info['text'] as $k=>$v) {
									$get_date = $get_service_info['date'][$k];
								?>
								<li><span><?php echo $v;?></span> : ~ <?php echo date("Y/m/d", strtotime($get_date));?></li>
								<?php
								}?>
							</ul>
							<?php
							} else {
							?>
							<span>서비스없음</span>
							<?php
							}?>
						</td>
						<td style="text-align:center">
							<a href="<?php echo NFE_URL;?>/admini/job/resume_modify.php?no=<?php echo $re_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($re_row['no']);?>" mode="delete_resume" url="<?php echo NFE_URL;?>/include/regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제하기</button>
							<a href="./resume_modify.php?info_no=<?php echo $re_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-content-copy"></i> 복사하기</button></a>
							<a href="./resume_approval.php?no=<?php echo $re_row['no'];?>&top_menu_code=<?php echo $top_menu_code;?>"><button type="button" class="red common">서비스 승인</button></a>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_resume" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onClick="sel_service()" class="red">선택 서비스 승인</button>
				<button type="button" class="blue"><strong>+</strong> 이력서등록</button></a>
			</div>
		</div>
		<!--//payconfig conbox-->

		<?php
		include NFE_PATH.'/admini/include/member.inc.php';
		?>
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->