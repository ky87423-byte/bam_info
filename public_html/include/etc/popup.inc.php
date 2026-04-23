<?php
$popup_allow = true;
if($_COOKIE['popup_'.$popup_row['no']]) $popup_allow = false;
if(!$popup_row['popup_sub_view'] && strpos($_SERVER['PHP_SELF'], NFE_URL.'/index.php')===false) $popup_allow = false;
if(!$popup_allow) return;

$css = '';
if(!$popup_row['popup_title_view'] && $popup_row['popup_height']<=60) $popup_row['popup_height'] = 60;
if($popup_row['popup_title_view'] && $popup_row['popup_height']<=94) $popup_row['popup_height'] = 94;
if($popup_row['popup_width']>0) $css .= 'width:'.intval($popup_row['popup_width']).'px;';
if($popup_row['popup_height']>0) $css .= 'height:'.intval($popup_row['popup_height']).'px;';
if($popup_row['popup_top']>0) $css .= 'top:'.intval($popup_row['popup_top']).'px;';
if($popup_row['popup_left']>0) $css .= 'left:'.intval($popup_row['popup_left']).'px;';
?>
<div class="d_popup drag-skin-" style="<?php echo $css;?>">
	<?php if($popup_row['popup_title_view']) {?><p class="pop_title"><?php echo $nf_util->get_text($popup_row['popup_title']);?></p><?php }?>
	<div><?php echo stripslashes($popup_row['popup_content']);?></div>
	<p class="pop_bottom">
		<label for="d_pop_close"><input type="checkbox" onClick="nf_util.cookie_noneWin($(this).closest('.d_popup')[0], 'popup_<?php echo $popup_row['no'];?>', '<?php echo $popup_row['no'];?>')" id="d_pop_close">하루동안 열지 않기</label>
		<button type="button" onClick="nf_util.parent_close(this, '.d_popup')">닫기</button>
	</p>
</div>