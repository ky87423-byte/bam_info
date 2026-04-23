<?php
$top_menu_code_head = substr($top_menu_code, 0, 3);

$quick_query = $db->_query("select * from nf_admin_quick order by `no` asc");
?>
<style type="text/css">
.left_menu { display:none; }
.left_menu.on { display:block; }
.left_menu_close { display:none; }
.left_menu_close.on { display:block; }
</style>
<script type="text/javascript">
var close_left_menu = function() {
	var left_menu = $(".left_menu").attr("class");
	if(left_menu.indexOf("on")>=0) {
		$(".left_menu").removeClass("on");
		$(".left_menu_close").addClass("on");
	} else {
		$(".left_menu").addClass("on");
		$(".left_menu_close").removeClass("on");
	}
}
</script>
<nav class="left_nav">
	<div>
		<b>관리자</b>
		<p><?php echo $_SESSION[$nf_admin->sess_adm_uid];?></p>
		<a href="<?php echo NFE_URL;?>/admini/regist.php?mode=logout_process"><button type="button">로그아웃</button></a>
	</div>
	<dl>
		<dt class="nav_quick">
			<a href=""><img src="../../images/admini/lmn_l08.gif" alt="퀵메뉴바로보기"></a>
			<dl>
				<dt>퀵메뉴바로보기</dt>
				<?php
				while($row=$db->afetch($quick_query)) {
					$get_top_menu = $nf_admin->get_top_menu($row['wr_top_menu_code']);
				?>
				<dd><a href="<?php echo $get_top_menu['sub_menu_url'];?>"><?php echo $get_top_menu['sub_menu_txt'];?></a><a onclick="del_favorite(this)" code="<?php echo $row['wr_top_menu_code'];?>">X</a></dd>
				<?php
				}
				?>
			</dl>
		</dt>
		<dd>퀵메뉴</dd>
	</dl>
	<dl>
		<dt><a href="<?php echo NFE_URL;?>/" target="_blank"><img src="../../images/admini/lmn_l02.gif" alt="홈페이지바로가기"></a></dt>
		<dd>홈페이지</dd>
	</dl>
	<dl>
		<dt><a href="<?php echo NFE_URL;?>/admini/introduce/index.php"><img src="../../images/admini/lmn_l04.gif" alt="상품관리"></a></dt>
		<dd>상품관리</dd>
	</dl>
	<dl>
		<dt><a href="<?php echo NFE_URL;?>/admini/config/sadmin.php"><img src="../../images/admini/lmn_l01.gif" alt="부관리자관리"></a></dt>
		<dd>부관리자관리</dd>
	</dl>
	<dl>
		<dt><a href="<?php echo NFE_URL;?>/admini/board/index.php"><img src="../../images/admini/lmn_l03.gif" alt="게시판관리"></a></dt>
		<dd>게시판관리</dd>
	</dl>
	<dl>
		<dt><a href="<?php echo NFE_URL;?>/admini/design/index.php"><img src="../../images/admini/lmn_l06.gif" alt="디자인관리"></a></dt>
		<dd>디자인관리</dd>
	</dl>
	<dl>
		<dt><a href="javascript:;" onclick="window.open('<?php echo NFE_URL;?>/admini/pop/guide.php','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')"><img src="../../images/admini/lmn_l07.gif" alt="메뉴얼"></a></dt>
		<dd>메뉴얼</dd>
	</dl>
</nav>




<nav class="left_menu on">
	<div>
		<h3><?php echo $_top_menus_[$top_menu_code_head.'000'];?></h3>
		<h6><?php echo $_menu_array_[$top_menu_code_head]['eng_name'];?></h6>
		<button type="button" onClick="close_left_menu()">닫기</button>
	</div>
	<?php
	if(is_array($_menu_array_[$top_menu_code_head]['menus'])) { foreach($_menu_array_[$top_menu_code_head]['menus'] as $k=>$v) {
		if($admin_info['wr_level']<10 && !in_array($v['code'], $_get_sadmin_['admin_menu_array'])) continue;
	?>
	<ul>
		<li><?php echo $v['name'];?></li>
		<ul class="menu_list">
			<?php
			if(is_array($v['sub_menu'])) { foreach($v['sub_menu'] as $k2=>$v2) {
				if($admin_info['wr_level']<10 && !in_array($k2, $_get_sadmin_['admin_menu_array'])) continue;
				$on = $top_menu_code==$k2 ? 'on' : '';
				$new_icon = '';
				//if($v2['new'] && $is_new[$top_menu_code_head][$k2]>0) $new_icon = '<img src="../../images/admini/new.png" alt="new" align="absmiddle">';
			?>
			<li class="<?php echo $on;?>"><a href="<?php echo $v2['url'];?>"><?php echo $v2['name'].$new_icon;?></a></li>
			<?php } }?>
		</ul>
	</ul>
	<?php
	} }?>
</nav>
<nav class="left_menu_close">
	<div onClick="close_left_menu()"><i class="axi axi-arrow-circle-right"></i><br><br>O<br>P<br>E<br>N</div>		
</nav>
<!--left_menu-->