<?php
$_SERVER['__USE_API__'] = array('editor');
$add_cate_arr = array('on2on', 'concert');
$top_menu_code = '600203';
if($_GET['type']==='1') $top_menu_code = '600204';
include '../include/header.php';

$cate_k = $_GET['type']==='1' ? 'concert' : 'on2on';
$nf_util->sess_page_save("qna_list");

$where_arr = $nf_search->cs();
$_where = $where_arr['where'];

$q = "nf_cs as cs where `wr_type`=".intval($_GET['type'])." ".$_where;
$order = " order by `no` desc";
if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['admin_qna_q'] = $q.$order;
$cs_query = $db->_query("select * from ".$_SESSION['admin_qna_q']." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
.tr_wr_content- { display:none; }
</style>
<script type="text/javascript">
var click_wr_cate = function(k) {
	var form = document.forms['fsearch'];
	form.wr_cate.value = k;
	form.submit();
}

var click_subject = function(el) {
	var index = $(el).closest("tr").index();
	var display = $(el).closest("table").find("tbody").find("tr").eq(index+1).css("display");
	display = display=='none' ? 'table-row' : 'none';
	$(el).closest("table").find("tbody").find("tr").eq(index+1).css({"display":display});
}

var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	if(display=='none') {
		obj.css({"display":display});
		return;
	}

	var no = $(el).attr("no");

	var mb_id = $(el).closest("tr").attr("mb_id");
	$.post("../regist.php", "mode=open_box&type=member&mb_id="+mb_id+"&code="+code+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 고객문의 관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 문의글에 답변을 하면 자동으로 메일발송되며 '답변여부'가 'X'에서 '<b class="blue">O</b>'로 바뀝니다.</li>
					<li>- 문의글의 제목을 클릭하시면 문의내용과 답변글을 확인할 수 있습니다.</li>
					<li>- [고객문의] 작성시 분류 변경하는 방법 좌측메뉴의 분류관리에서 하실수 있습니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
				<input type="hidden" name="sort_lo" value="<?php echo $nf_util->get_html($_GET['sort_lo']);?>" />
				<input type="hidden" name="type" value="<?php echo intval($_GET['type']);?>" />
				
				<div class="search ass_list">
					 <table class="">
						<colgroup>
							<col width="5%">
						</colgroup>
						<tbody>
							<tr>
								<th>기간</th>
								<td colspan="3">
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
							</tr>
							<tr>
								<th>분류</th>
								<td>
									<select name="wr_cate">
										<option value="">전체</option>
										<?php
										if(is_array($cate_p_array[$cate_k][0])) { foreach($cate_p_array[$cate_k][0] as $k=>$v) {
											$selected = $k==$_GET['wr_cate'] ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo stripslashes($v['wr_name']);?></option>
										<?php
										} }?>
									</select>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>제목</option>
							<option value="wr_content" <?php echo $_GET['search_field']=='wr_content' ? 'selected' : '';?>>내용</option>
							<option value="wr_subject||wr_content" <?php echo $_GET['search_field']=='wr_subject||wr_content' ? 'selected' : '';?>>제목+내용</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>작성자</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>

			<ul class="sub_category">
				<li class="<?php echo $_GET['wr_cate'] ? '' : 'on';?>"><a href="#none" onClick="click_wr_cate('')">전체</a></li>
				<?php
				if(is_array($cate_p_array[$cate_k][0])) { foreach($cate_p_array[$cate_k][0] as $k=>$v) {
					$on = $k==$_GET['wr_cate'] ? 'on' : '';
				?>
				<li class="<?php echo $on;?>"><a href="#none" onClick="click_wr_cate('<?php echo $k;?>')"><?php echo $v['wr_name'];?></a></li>
				<?php
				} }?>
			</ul>
			<h6><?php echo $nf_util->cs_type_arr[$_GET['type']];?>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_cs" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="7%">
					<col width="6%">
					<col width="7%">
					<col width="7%">
					<col width="5%">
					<col width="5%">
				</colgroup>
				<?php
				$wr_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='wr_name' ? 'desc' : 'asc';
				$wr_hit_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='wr_hit' ? 'desc' : 'asc';
				$wr_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='wr_date' ? 'desc' : 'asc';
				$wr_result_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='wr_result' ? 'desc' : 'asc';
				?>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>제목</th>
						<th><a href="#none" onClick="nf_util.click_sort('wr_name', '<?php echo $wr_name_order;?>')">작성자<?php echo $wr_name_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('wr_hit', '<?php echo $wr_hit_order;?>')">조회<?php echo $wr_hit_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('wr_date', '<?php echo $wr_wdate_order;?>')">문의일<?php echo $wr_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('wr_result', '<?php echo $wr_result_order;?>')">답변여부<?php echo $wr_result_order=='desc' ? '▲' : '▼';?></a></th>
						<th>답변</th>
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
							while($cs_row=$db->afetch($cs_query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $cs_row['no'];?>"></td>
						<td class="tal"><b class="MAR5">[<?php echo $nf_util->get_text($cate_p_array[$cate_k][0][$cs_row['wr_cate']]['wr_name']);?>]</b><a href="#none" onClick="click_subject(this)" class="blue"><?php echo $nf_util->get_text($cs_row['wr_subject']);?></a></td>
						<td><?php echo $nf_util->get_text($cs_row['wr_name']);?></td>
						<td><?php echo number_format(intval($cs_row['wr_hit']));?></td>
						<td><?php echo substr($cs_row['wr_date'],0,10);?></td>
						<td><?php echo $cs_row['wr_result'] ? '<b class="blue">O</b>' : 'X';?></td>
						<td><button type="button" onClick="open_box(this, 'cs-')" no="<?php echo $cs_row['no'];?>" class="gray common">Re 답변</button></td>
						<td><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $cs_row['no'];?>" mode="delete_cs" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제</button></td>
					</tr>
					<tr class="tr_wr_content-">
						<td colspan="8">
							<dl class="num3 mb3 pb3" style="border-bottom:1px dotted #d9d9d9">
								<label class="m m0"><b>ID :</b> <?php echo $cs_row['wr_id'];?></label> / <b>E-mail :</b> <?php echo $cs_row['wr_email'];?> 
								<label class="m">/ <b>전화번호 :</b> <?php echo $cs_row['wr_phone'];?></label> 
								<!-- <a onClick="MM_showHideLayers('pop_sms','','show')" class="btn"><h1 class="btn17">SMS</h1></a>  -->
							</dl>
							<dl class="bdot bg_col pd15 mt5"><?php echo stripslashes($cs_row['wr_content']);?></dl>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_cs" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>
			<div><?php echo $paging['paging'];?></div>
			</form>
		</div>
		<!--//conbox-->


		<?php
		include "../include/cs.inc.php";
		?>

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->