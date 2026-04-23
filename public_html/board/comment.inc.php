<?php
if(!$bo_row['bo_use_comment']) return false;
$q = $_table." where `wr_parent`=".intval($_GET['no'])." and `wr_is_comment`=1 and wr_del=0 and `wr_comment_reply`=''";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 10;
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$query = $db->_query("select * from ".$q." order by `wr_datetime` desc limit ".$paging['start'].", ".$_arr['num']);
$nums = $db->num_rows($query);
?>
<style type="text/css">
.reply_con_write-comment- { display:none; }
.comment_comment_list- { display:none; }
</style>
<div id="reply_comment_body-" class="reply_con">

	<form name="fcomment" action="./regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
	<input type="hidden" name="mode" value="board_write" />
	<input type="hidden" name="code" value="comment_insert" />
	<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>" />
	<input type="hidden" name="no" value="<?php echo intval($b_row['wr_no']);?>" />
	<h3>전체댓글 <em class="rpy_num"><?php echo number_format(intval($b_row['wr_comment']));?></em></h3>
	<div class="reply_con_write">
		<?php if(!$member['no']) {?>
		<div class="input_area">
			<ul>
				<li><label>이름</label><input type="text" name="wr_name" hname="이름" needed></li>
				<li><label>비밀번호</label><input type="password" name="wr_password" hname="비밀번호" needed></li>
				<li><label>자동등록방지 문자입력</label><input type="text" name="rand_number" hname="자동등록방지 문자" needed>
				<span><img src="<?php echo NFE_URL;?>/include/rand_text.php?no=<?php echo $b_row['wr_no'];?>&bo_table=<?php echo $bo_table;?>" align="absmiddle"></span>
				</li>
			</ul>
		</div>
		<?php }?>
		<div class="text_area">
			<textarea name="wr_content" hname="댓글내용" needed placeholder="댓글을 입력하세요."></textarea>
			<button>등록</button>
		</div>
	</div>
	</form>

	<div class="comment_comment-form" style="display:none;">
		<form name="fcomment_comment" action="./regist.php" method="post">
		<input type="hidden" name="mode" value="board_write" />
		<input type="hidden" name="no" value="<?php echo intval($b_row['wr_no']);?>" />
		<input type="hidden" name="comment_id" value="" />
		<input type="hidden" name="code" value="" />
		<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>" />
		<input type="hidden" name="wr_name" value="" hname="이름" needed />
		<input type="password" name="wr_password" value="" hname="비밀번호" needed />
		<input type="hidden" name="rand_number" value="" hname="자동등록방지 문자" <?php echo $member['no'] ? '' : 'needed';?> />
		<textarea name="wr_content" value="" hname="댓글내용" needed></textarea>
		</form>
	</div>


	<ul class="reply_list">
		<?php 
		while($row=$db->afetch($query)) {
			$b_info = $nf_board->info($row, $board_info);

			$is_secret = $row['wr_secret'];
			if($row['mno'] && $row['mno']==$member['no']) $is_secret = false; // : 내 비밀글
			if($b_row['mno'] && $b_row['mno']==$member['no']) $is_secret = false; // : 원글 주인
			if($is_admin) $is_secret = false; // : 관리자권한
			if($_SESSION['board_view_'.$bo_table.'_'.$row['wr_no']]) $is_secret = false; // : 비밀번호를 통해 접근

			$is_blind = false;
			$is_del = false;
			if($row['wr_blind']) $is_blind = true;
			if($row['wr_del']) $is_del = true;
		?>
		<li class="comment_li-" id="comment_li-<?php echo $row['wr_no'];?>-">
			<?php
			include NFE_PATH.'/board/comment.inc.inc.php';
			?>
		</li>
		<?php
		}
		?>
	</ul>
</div>