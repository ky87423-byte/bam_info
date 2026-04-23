<?php
$top_menu_code = "600101";
include '../include/header.php';

$wr_type = 'board_menu';
$q = "nf_category where `wr_type`=? and `pno`=".intval($_POST['pno']);
$query = $db->_query("select * from ".$q." order by `wr_rank` asc", array($wr_type));
$lens = $db->num_rows($query);
?>
<style type="text/css">
.add_form_view { display:none; }
.conbox.layer_pop { display:none; cursor:pointer; }
.btn-add- { display:none; }
</style>
<script type="text/javascript">
var board_click = function(el, no) {
	var depth = $(el).closest(".category_in-").attr("index");
	if(depth=='1') {
		var form_board = document.forms['fboard'];
		form_board.bno.value = no;
	}
	$.post("../regist.php", "mode=board_cate_click&no="+no+"&depth="+depth, function(data) {
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var click_insert = function() {
	var form = document.forms['fboard'];
	form.submit();
}

var delete_board = function(el, no) {
	if(confirm("삭제하시겠습니까?\n삭제하시면 지금까지 등록된 데이터들도 모두 삭제됩니다.")) {
		$.post("../regist.php" , "mode=delete_board&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}
}


var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.layer_pop").css({"display":"none"});
	var obj = $(".conbox."+code);
	obj.css({"display":display});
	if(display=='none') return;

	var no = $(el).attr("no");

	if(code=='board-auth-') {
		$.post("../regist.php", "mode=open_bo_level&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.js) eval(data.js);
		});
	}
}


var click_save = function() {
	var form = document.forms['fblist'];
	form.action = "../regist.php";
	form.mode.value = "board_save_update";
	form.submit();
}


var move_board = function(el) {
	var obj = $(".move_board-");
	var offset = $(el).offset();
	obj.css({"top":offset.top-60, "left":offset.left});
	obj.addClass("on");
}

var move_board_submit = function(el) {
	var listform = document.forms['fblist'];
	var len = $("[name='chk[]']:checked").length;
	if(len<=0) {
		alert("이동할 게시판을 하나이상 선택해주시기 바랍니다.");
		return false;
	} else {
		if(validate(el)) {
			var form = document.forms['fmove'];
			var board_no_arr = [];
			form.board_no.value = "";
			$("[name='chk[]']:checked").each(function(){
				board_no_arr.push($(this).val());
			});
			form.board_no.value = board_no_arr.join(",");
			return true;
		}
	}
	return false;
}

var ch_bo_type = function(el, bo_table) {
	$.post("../regist.php", "mode=ch_board_bo_type&bo_table="+bo_table+"&val="+el.value, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>

<style type="text/css">
.move_board- { position:absolute; z-index:2; background:#f5f5f5; border:1px solid #ddd; padding:10px; display:none; }
.move_board-.on { display:inline-block; }
.move_board- select { border:1px solid #aaa; padding:3px 5px; }
</style>

<div class="move_board-">
<form name="fmove" action="../regist.php" method="post" onSubmit="return move_board_submit(this)">
	<input type="hidden" name="mode" value="board_move_process" />
	<input type="hidden" name="board_no" value="" />
	선택한 게시판을 이동시킬 메뉴를 선택하세요
	<select name="cate_board[]" hname="1차메뉴" needed onChange="nf_category.ch_category(this, 1)">
	<option value="">1차 메뉴명</option>
	<?php if(is_array($nf_board->board_menu[0])) { foreach($nf_board->board_menu[0] as $k=>$v) {?>
	<option value="<?php echo $v['no'];?>"><?php echo $v['wr_name'];?></option>
	<?php } }?>
	</select>
	<select name="cate_board[]" hname="2차메뉴" needed>
	<option value="">2차 메뉴명</option>
	</select>
	<span>(으)로 이동 <button type="submit" style="border:1px solid #ccc; color:#444; background:linear-gradient(#fff,#eee); border-radius:3px; padding:3px 5px; font-weight:bold; ">OK확인</button> <button type="button" onClick="$('.move_board-').removeClass('on')" style="border:1px solid #ccc; color:#444; background:linear-gradient(#fff,#eee); border-radius:3px; padding:3px 5px; font-weight:bold; ">닫기</button></span>
</form>
</div>

<!-- 게시판관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<form name="fwrite" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="category_insert" />
	<input type="hidden" name="wr_type" value="<?php echo $wr_type;?>" />
	<input type="hidden" name="ajax" value="1" />
	<input type="hidden" name="no" value="" />
	<input type="hidden" name="pno" value="" />
	<input type="hidden" name="depth" value="" />
	<input type="hidden" name="colspan" value="" />
	<div class="put_category_form" style="display:none;">
	</div>
	</form>

	<form name="fboard" action="./board_add.php" method="get">
	<input type="hidden" name="bno" value="" />
	</form>

	<section class="board_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide6-1','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<?php
			$depth = 2;
			$cate_txt = '메뉴 설정';
			include NFE_PATH.'/admini/include/category.multi.inc.php';
			?>



			<form name="fblist" action="" method="post">
			<input type="hidden" name="mode" value="" />
			<h6>게시판관리</h6>
			<div class="table_top_btn">
				<button type="button" onClick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'fblist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onClick="move_board(this)" class="gray"><strong><i class="axi axi-loop"></i></strong> 게시판이동</button>
				<button type="button" class="gray" onClick="click_save()"><strong>S</strong> 저장하기</button>
				<button type="button" class="blue btn-add-" onClick="click_insert()"><strong>+</strong> 게시판추가</button>
			</div>
			<table class="table4" style="width:100%;">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="20%">
					<col width="%">
					<col width="6%">
					<col width="4%">
					<col width="6%">
					<col width="6%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>출력순서</th>
						<th>메뉴</th>
						<th>게시판명(게시판고유ID)</th>
						<th>게시물수</th>
						<th>출력형태</th>
						<th>권한</th>
						<th>미리보기</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody class="board_cate_list-">
					<?php
					$query = $db->_query("select * from nf_board where 1 order by `rank` asc");
					while($row=$db->afetch($query)) {
						$parent0 = $db->query_fetch("select * from nf_category where `no`=".intval($row['pcode']));
						$parent1 = $db->query_fetch("select * from nf_category where `no`=".intval($row['code']));
						include NFE_PATH.'/admini/include/board_cate_list.inc.php';
					}
					?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onClick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'fblist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_board" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onClick="move_board(this)" class="gray"><strong><i class="axi axi-loop"></i></strong> 게시판이동</button>
				<button type="button" class="gray" onClick="click_save()"><strong>S</strong> 저장하기</button>
				<button type="button" class="blue btn-add-" onClick="click_insert()"><strong>+</strong> 게시판추가</button>
			</div>
		</div>
		</form>
		<!--//conbox-->

		
		<?php
		include NFE_PATH.'/admini/include/board_auth.inc.php';
		//include NFE_PATH.'/admini/include/board_bunru.inc.php';
		?>

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->