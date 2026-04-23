<?php
$_SERVER['__USE_API__'] = array('editor');
$_site_title_ = '광고 · 제휴 문의';
$add_cate_arr = array('concert', 'email');
include '../include/header_meta.php';
include '../include/header.php';

$nf_util->sess_page_save("service_advert");

$bo_table = $_GET['bo_table'] = 'service_advert';

$list_skin = 'text';
$_table = $nf_board->get_table($_GET['bo_table']);
$board_q = $nf_board->board_query($_GET['bo_table']);
$q = $board_q['q'];
$order = $board_q['order'];

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
if($skin!='admin') $_arr['tema'] = 'B';
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);
$b_q = "select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num'];
if(strpos($_SERVER['PHP_SELF'], '/service/advert.php')!==false) $_SESSION['board_query_'.$_GET['bo_table']] = $b_q; // : 현재 읽은 리스트
if(!$_SESSION['board_query_'.$_GET['bo_table']]) $_SESSION['board_query_'.$_GET['bo_table']] = $b_q;
$query = $db->_query($_SESSION['board_query_'.$_GET['bo_table']]);

$_SESSION['board_list_'.$bo_table] = $_SERVER['REQUEST_URI'];
?>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>입점문의<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="common_wrap advert">
	<div class="wrap1400 sub">
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('sub_etc_F');
			echo $banner_arr['tag'];
			?>
		</div>
		<form name="fbsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
		<input type="hidden" name="mode" value="cs_center_write" />
		<input type="hidden" name="type" value="1" />
		<?php
		include NFE_PATH.'/include/etc/google_recaptcha3.inc.php';
		?>
		<div class="subcon_area commu">
			<p class="s_title">입점문의</p>
			<div class="box_wrap">
				<ul class="search_box">
					<li class="s1">
						<select name="page_row" onChange="nf_util.ch_page_row(this, 'fbsearch')">
							<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
							<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
							<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
							<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
						</select>
					</li>
					<li class="s2">
						<select name="search_field">
							<option value="">선택</option>
							<option value="wr_subject" <?php echo $_GET['search_field']=='wr_subject' ? 'selected' : '';?>>제목</option>
							<option value="sub+con" <?php echo $_GET['search_field']=='sub+con' ? 'selected' : '';?>>제목+내용</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>작성자</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<button type="submit">검색</button>
					</li>
					<li class="s3"><button type="button" onClick="location.href='<?php echo NFE_URL;?>/service/advert_regist.php'"><i class="axi axi-pencil-square"></i>글쓰기</button></li>
				</ul>

				<?php
				// : 게시판 리스트
				$skin = $bo_row['bo_type'];
				include NFE_PATH.'/board/list.inc.php';
				?>

			</div>
		</div>
		</form>
		
		<div class="banner" style="overflow:hidden;">
			<?php
			$banner_arr = $nf_banner->banner_view('sub_etc_G');
			echo $banner_arr['tag'];
			?>
		</div>
	</div>
</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>