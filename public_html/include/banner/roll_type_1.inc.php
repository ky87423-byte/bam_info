<?php
// : 롤링배너
$height_css = "";
if($max_height['height']>0) {
	//$height_css = 'height:332px;';
}
if($row['wr_roll_time']<=0) $row['wr_roll_time'] = 3; // : 롤링값 없으면 3초로
?>
<style type="text/css">
.<?php echo $position;?>-cycle-pager { top:10px !important; right:10px !important; position:absolute; z-index:100; }
.<?php echo $position;?>-cycle-pager > span { font-size:0px; }
.<?php echo $position;?>-cycle-pager > span::after { margin-right:5px; font-size:15px; content:'●'; color:#fff; }
.<?php echo $position;?>-cycle-pager > span.cycle-pager-active::after { color:#1f4675; }
</style>
<div class="banner_list_child_ banner_<?php echo $position;?>_<?php echo $group_count;?>_list_ cycle-slideshow" style="<?php echo $height_css;?>;float:left;overflow:hidden;"
	data-cycle-fx=<?php echo $this->roll_direction_txt[$row['wr_roll_direction']];?>
	<?php if(in_array($row['wr_roll_direction'], array(1, 2))) {?>data-reverse="true"<?php }?>
	data-cycle-timeout=<?php echo intval($row['wr_roll_time'])*1000;?>
	data-cycle-slides=">.item_"
	data-cycle-pager=".<?php echo $position;?>-cycle-pager"
	<?php /*
	data-cycle-prev=".prevControl"
	data-cycle-next=".nextControl"
	*/?>
>
<?php
while($ba_row=$db->afetch($banner_query)) {
	$get_banner = $this->get_banner($ba_row);
?>
<div class="item_">
	<div style="<?php echo $get_banner['css'];?>">
		<?php if($ba_row['wr_url']) {?><a href="<?php echo $this->get_http($ba_row['wr_url']);?>" <?php echo $get_banner['target'];?>><?php }?>
			<?php echo $get_banner['content'];?>
		<?php if($ba_row['wr_url']) {?></a><?php }?>
	</div>
</div>
<?php
}
?>
</div>