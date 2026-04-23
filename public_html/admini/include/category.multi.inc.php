<form name="flist" method="post">
<input type="hidden" name="mode" value="" />
<div class="category_wrap">
	<?php
	$depth = 2;
	if($category_this[1]>1) $depth = $category_this[1];

	for($i=0; $i<$depth; $i++) {
		$insert_tag = false;
		ob_start();
	?>
	<div class="category_1 category category_in-" index="<?php echo intval($i);?>" pno="">
		<div class="category_area">
			<h3><?php echo $i+1;?>차 <?php echo $cate_txt;?></h3>
			<table class="table4">
				<colgroup>
					<col width="7%">
					<col width="">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>출력</th>
						<th>항목</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody class="cate_list">
					<?php
					if($i===0 && $lens>0) {
						while($row=$db->afetch($query)) {
							if(is_file(NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php')) {
								include NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php';
							} else {
								include NFE_PATH.'/admini/include/category/multi_basic.inc.php';
							}
						}
					} else {
						$colspan = 4;
						if(!(in_array($_GET['code'], array('job_part')) && $i===0)) $colspan = 3;
						ob_start();
					?>
					<tr >
						<td align="center" class="not_list-" colspan="<?php echo $colspan;?>"><span><?php echo $i+1;?>차</span> <?php echo $cate_txt;?>을(를) 설정해주세요.</td>
					</tr>
					<?php
						$not_list_tag = ob_get_clean();
						echo $not_list_tag;
					}
					?>
				</tbody>
				<tbody>
				<?php
				$row = array();
				$insert_tag = true;
				if(is_file(NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php'))
					include NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php';
				else
					include NFE_PATH.'/admini/include/category/multi_basic.inc.php';
				?>
				</tbody>
			</table>
		</div>
		<div class="button_area">
			<ul>
				<li><button type="button" class="orange" onClick="nf_category.ch_rank(this, 'up')">▲위로</button></li>
				<li><button type="button" class="orange" onClick="nf_category.ch_rank(this, 'down')">▼아래로</button></li>
				<li><button type="button" class="blue_base" onClick="nf_category.ch_rank(this, 'first')">▲맨처음</button></li>
				<li><button type="button" class="blue_base" onClick="nf_category.ch_rank(this, 'end')">▼끝으로</button></li>
			</ul>
			<button type="button" class="blue" onClick="nf_category.insert_tr(this)">+추가</button>
			<input type="hidden" name="colspan" value="<?php echo $colspan;?>" />
		</div>
	</div>
	<!--//category_1-->
	<?php
		$category_box[$i] = ob_get_clean();
	}


	for($i=0; $i<$depth; $i++) {
		echo $category_box[$i];
		if($i%3<2 && $i<($depth-1)) echo '<em>▶</em>';
	}
?>
</div>
</form>