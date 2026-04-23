<?php
$_site_title_ = "통합검색";
include '../include/header_meta.php';
include '../include/header.php';

$_GET['search_keyword'] = $_GET['top_keyword'];
$nf_search->insert(array('code'=>'search', 'content'=>$_GET['search_keyword']));
?>
<style type="text/css">
.loc-class- { position:absolute; }
.sub.jobtable { margin-top:2rem; }
</style>
<script type="text/javascript">
$(function(){
	$(".search-tab-body- > .btn-").click(function(){
		var index = $(this).index();
		$(".s_choice").css({"display":"none"});
		$(".s_choice").eq(index).css({"display":"block"});

		$(".search-tab-body- > .btn-").removeClass("on");
		$(this).addClass("on");
	});


	nf_category.search_put_on(document.forms['fsearch1']);
});
</script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>채용정보<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>

<div class="wrap1260 MAT20">
	<section class="search_mian sub">
		<div class="banner MBT30" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('common_H');
			echo $banner_arr['tag'];
			?>
		</div>
		<p class="s_title">통합검색</p>
		<form name="fsearch1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
		<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
		<input type="hidden" name="top_keyword" value="<?php echo $nf_util->get_html($_GET['top_keyword']);?>" />
		<input type="hidden" name="code" value="" />
		<section class="sub_search">
			<div class="depth_wrap">
				<ul class="depth_btn search-tab-body-">
					<li class="<?php echo !$_GET['code'] || $_GET['code']=='area'? 'on' : '';?> btn-"><button type="button">지역선택<span><i class="axi axi-keyboard-arrow-down"></i></span></button></li>
					<li class="<?php echo $_GET['code']=='job_part' ? 'on' : '';?> btn-"><button type="button">업·직종 선택<span><i class="axi axi-keyboard-arrow-down"></i></span></button></li>
				</ul>
				
				<!--지역선택-->
				<div class="s_choice btn_category- p1" code="area" txt="지역" style="display:<?php echo !$_GET['code'] || $_GET['code']=='area' ? 'block' : 'none';?>;">
					<ul class="choice1">
						<?php
						$count = 0;
						if(is_array($cate_p_array['area'][0])) { foreach($cate_p_array['area'][0] as $k=>$v) {
							$on = $k==$_GET['area'][0] ? 'on' : '';
						?>
						<li class="<?php echo $on;?>"><button type="button" onClick="nf_category.btn_category(this, 1)" no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></button></li>
						<?php
							$count++;
						} }
						?>
					</ul>
					<ul class="choice2" style="display:<?php echo $_GET['area'][0] ? 'block' : 'none';?>;">
					</ul>
					<ul class="choice3" style="display:<?php echo ($_GET['area'][1]) ? 'block' : 'none';?>;">
					</ul>
				</div>
				<!--//지역선택-->


				<!--업직종선택 *지역선택과 스타일 같음-->
				<div class="s_choice btn_category- p1" code="job_part" txt="업직종" style="display:<?php echo $_GET['code']=='job_part' ? 'block' : 'none';?>;">
					<ul class="choice1">
						<?php
						$count = 0;
						if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
							$on = $k==$_GET['job_part'][0] ? 'on' : '';
						?>
						<li class="<?php echo $on;?>"><button type="button" onClick="nf_category.btn_category(this, 1)" no="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></button></li>
						<?php
						} }?>
					</ul>
					<ul class="choice2" style="display:<?php echo ($_GET['job_part'][0]) ? 'block' : 'none';?>;">
					</ul>
					<ul class="choice3" style="display:<?php echo ($_GET['job_part'][0]) ? 'block' : 'none';?>;">
					</ul>
				</div>
				<!--//업직종선택-->


			</div>
			<!--//depth_wrap-->

			<div class="btn_area">
				<ul class="selection search-result-text-">
			
				</ul>
				<ul class="btn">
					<li><button type="button" onClick="nf_category.btn_category_reset(this)">초기화</button></li>
					<li><button>검색</button></li>
				</ul>
			</div>
		</section>
		</form>

		<ul class="tab_search">
			<li class="on"><a href="#loc-employ">통합검색</a></li>
			<li><a href="#loc-employ">채용정보</a></li>
			<li><a href="#loc-resume">인재정보</a></li>
			<li><a href="#loc-board">커뮤니티</a></li>
		</ul>

		<!--채용정보-->
		<div id="loc-employ" class="loc-class-"></div>
		<section class="jobtable sub">

			<?php
			$employ_where = $nf_search->employ();
			$arr = array();
			$arr['service_k'] = '1_list';
			$arr['where'] = $employ_where['where'].$nf_job->not_adult_where.$_em_where;

			$arr['order'] = " order by ne.`wr_jdate` desc";
			if($_GET['sort_employ']) $arr['order'] = " order by ".$nf_job->sort_arr['employ'][$_GET['sort_employ']][0];
			if($_GET['page_row']>0) $arr['limit'] = intval($_GET['page_row']);
			$service_arr = $nf_job->service_query('employ', $arr);

			if($service_arr['total']>0) {
				$_arr = array();
				$_arr['tema'] = 'B';
				$_arr['num'] = $service_arr['limit'];
				$_arr['total'] = $service_arr['total'];
				$paging = $nf_util->_paging_($_arr);
				$not_is_search_part = true;
				include NFE_PATH.'/employ/list.inc.php';
			}
			?>
			

		<!--인재정보-->
		<div id="loc-resume" class="loc-class-"></div>
		<section class="jobtable sub">
			<?php
			$resume_where = $nf_search->resume();
			$arr = array();
			$arr['service_k'] = '1_list';

			$arr['where'] = $resume_where['where'].$_re_where;

			$arr['order'] = " order by nr.`wr_jdate` desc";
			if($_GET['page_row']>0) $arr['limit'] = intval($_GET['page_row']);
			$service_arr = $nf_job->service_query('resume', $arr);
			if($service_arr['total']>0) {

			$_arr = array();
			$_arr['tema'] = 'B';
			$_arr['num'] = $service_arr['limit'];
			$_arr['total'] = $service_arr['total'];
			$paging = $nf_util->_paging_($_arr);
			$not_is_search_part = true;
			include NFE_PATH.'/resume/list.inc.php';
			}
			?>

		</section>

		<?php
		if(is_array($nf_board->board_table_arr)) { foreach($nf_board->board_table_arr as $bo_table=>$bo_row) {
			$_arr = array();
			//$_arr['where'] = " and `wr_parent`=0";
			$_arr['num'] = 5;
			$board_q = $nf_board->board_query($bo_table, $_arr);
			$q = $board_q['q'];
			$order = $board_q['order'];
			$total = $db->query_fetch("select count(*) as c from ".$q);
			$b_q = "select * from ".$q.$order." limit 0, ".$_arr['num'];
			$query = $db->_query($b_q);

			if($total['c']>0) {
			?>
			<div id="loc-board" class="loc-class-"></div>
			<section class="jobtable sub">
				<div class="side_con">
					<p class="s_title"><?php echo $bo_row['bo_subject'];?><span>총<em class="red"><?php echo $total['c'];?></em>건</span></p>
					<div class="select_area">
						<a href="<?php echo NFE_URL;?>/board/list.php?bo_table=<?php echo $bo_table;?>&search_keyword=<?php echo $_GET['search_keyword'];?>"><button type="button">더보기<i class="axi axi-chevron-right"></i></button></a>
					</div>
				</div>
				<table class="style3">
					<colgroup>
						<col width="18%">
						<col width="%">
						<col width="13%">
						<col width="12%">
					</colgroup>	
					<tr>
						<th>게시판</th>
						<th>제목</th>
						<th>작성자</th>
						<th>등록일</th>
					</tr>
					<?php
					while($b_row=$db->afetch($query)) {
						$bo_table = $bo_table;
						$wr_no = $b_row['wr_no'];
						$wr_subject = $b_row['wr_subject'];
						$wr_datetime = $b_row['wr_datetime'];
						$b_info = $nf_board->info($b_row, $bo_row);
					?>
					<tr>
						<td>[<?php echo $nf_util->get_text($bo_row['bo_subject']);?>]</td>
						<td class="tal"><a href="<?php echo $b_info['a_href'];?>" class="blue"><?php echo $nf_util->get_text($wr_subject);?></a></td>
						<td><?php echo $nf_util->get_text($b_info['get_name']);?></td>
						<td><?php echo date("Y.m.d", strtotime($wr_datetime));?></td>
					</tr>
					<?php
					}
					?>
				</table>
			</section>
			<?php
			}
		} }
		?>

	</section>
	
	
</div>

<!--푸터영역-->
<?php //include '../include/footer.php'; ?>

