<?php
if(is_array($nf_category->kind_arr)) { foreach($nf_category->kind_arr as $k=>$v) {
	$category_groups[$v[2]][$k] = $v;
} }
?>
<div class="ass_list">
	<table>
		<colgroup>
			<col width="10%">
		</colgroup>
		<?php
		if(is_array($category_groups)) { foreach($category_groups as $k=>$v) {
		?>
		<tr>
			<th><?php echo $nf_category->kind_group[$k];?></th>
			<td>
				<ul>
					<?php
					if(is_array($v)) { foreach($v as $k2=>$v2) {
						$on = $k2==$_GET['code'] ? 'on' : '';
					?>
					<li class="<?php echo $on;?>"><a href="../config/category_insert.php?code=<?php echo $k2;?>"><?php echo $v2[0];?></a></li>
					<?php
					} }?>
				</ul>
			</td>
		</tr>
		<?php
		} }?>
	</table>
</div>