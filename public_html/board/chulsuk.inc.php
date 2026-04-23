<?php
$txt = nl2br(stripslashes($row['text']));
?>
<div class="top">
	<p><?php echo $nf_util->get_text($row['nick']);?><span><?php echo $row['datetime'];?></span></p>
</div>
<p>
	<?php
		echo $row['text'];
	?>
</p>