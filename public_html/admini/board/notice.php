<?php
$top_menu_code = '600202';
$add_cate_arr = array('notice');
include '../include/header.php';

$_where = "";
$_date_arr = array();
if($_GET['date1']) $_date_arr[] = "`wr_date`>='".addslashes($_GET['date1'])." 00:00:00'";
if($_GET['date2']) $_date_arr[] = "`wr_date`<='".addslashes($_GET['date2'])." 23:59:59'";
if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";
if($_GET['wr_type']) $_where .= " and `wr_type`='".addslashes($_GET['wr_type'])."'";

$_keyword_arr = array();
if(trim($_GET['search_keyword'])) {
	if($_GET['search_field']=='wr_subject||wr_content') {
		$_where .= " and (`wr_subject` like '%".addslashes($_GET['search_keyword'])."%' or `wr_content` like '%".addslashes($_GET['search_keyword'])."%')";
	} else {
		$_keyword_arr['wr_subject'] = "`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_content'] = "`wr_content` like '%".addslashes($_GET['search_keyword'])."%'";
		$_keyword_arr['wr_name'] = "`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
		$_where .= " and ".$_keyword_arr[$_GET['search_field']];
	}
}

$q = "nf_notice where 1 ".$_where;
$order = " order by `no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$notice_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var search_func = function(txt) {
	var form = document.forms['fsearch'];
	form.submit();
}

var search_txt = function(txt) {
	var form = document.forms['fsearch'];
	form.wr_type.value = txt;
	search_func();
}
</script>
<!-- 공지사항관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 제목을 클릭하시면 내용을 확인할 수 있습니다.</li>
					<li>- [공지사항] 작성시 분류 변경하는 방법 좌측메뉴의 분류관리에서 하실수 있습니다.</li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
			<input type="hidden" name="page_row" value="<?php echo intval($_GET['page_row']);?>" />
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
									<select name="wr_type">
										<option value="">전체</option>
										<?php
										if(is_array($cate_p_array['notice'][0])) { foreach($cate_p_array['notice'][0] as $k=>$v) {
											$selected = $_GET['wr_type']==$v['wr_name'] ? 'selected' : '';
										?>
										<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
										<?php
										} }
										?>
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
						<button type="button" onClick="document.forms['fsearch'].reset()" class="black">초기화</button>
					</div>
				</div>
			</form>
			<!--//search-->



			<ul class="sub_category">
				<li class="on"><a onClick="search_txt('')">전체</a></li>	
				<?php
				if(is_array($cate_p_array['notice'][0])) { foreach($cate_p_array['notice'][0] as $k=>$v) {
				?>
				<li><a onClick="search_txt('<?php echo $v['wr_name'];?>')"><?php echo $v['wr_name'];?></a></li>
				<?php
				} }
				?>
			</ul>
			<h6>공지사항관리
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
					</select>
				</p>	
			</h6>

			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_notice" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/board/notice_insert.php"><button type="button" class="blue"><strong>+</strong> 공지사항등록</button></a>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="8%">
					<col width="6%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>제목</th>
						<th><a href="">조회▼</a></th>
						<th><a href="">등록일▼</a></th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($total['c']<=0) {
						case true:
					?>
					<td colspan="6" class="no_list"></td>
					<?php
						break;


						default:
							while($row=$db->afetch($notice_query)) {
								$file_arr = array_diff($nf_util->get_unse($row['wr_file']), array(""));
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
						<td class="tal"><?php if($row['wr_type']) {?><b class="MAR5">[<?php echo $row['wr_type'];?>]</b><?php }?>
							<a href="<?php echo NFE_URL;?>/board/notice_view.php?no=<?php echo $row['no'];?>" target="_blank" class="blue"><?php echo $nf_util->get_text($row['wr_subject']);?></a>
							<?php if(count($file_arr)>0) {?><img src="../../images/ic/file.gif" alt="파일다운"><?php }?>
						</td>
						<td><?php echo number_format(intval($row['wr_hit']));?></td>
						<td><?php echo substr($row['wr_date'],0,10);?></td>
						<td>
							<a href="./notice_insert.php?no=<?php echo $row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_notice" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button>
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
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_notice" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/board/notice_insert.php"><button type="button" class="blue"><strong>+</strong> 공지사항등록</button></a>
			</div>
			</form>
		</div>
		<!--//conbox-->


		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->