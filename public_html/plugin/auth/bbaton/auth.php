<?php
include "../../../engine/_core.php";

$url = "http://bauth.bbaton.com/oauth/authorize";
$_data = array(
	'client_id'=>$env['bbaton_id'],
	'redirect_uri'=>domain.'/include/regist.php?mode=login_bbaton',
	'response_type'=>'code',
	'scope'=>'read_profile',
);
?>
<script type="text/javascript">
location.href = "https://bauth.bbaton.com/oauth/authorize?client_id=<?php echo $_data['client_id'];?>&redirect_uri=<?php echo $_data['redirect_uri'];?>&response_type=code&scope=read_profile";
</script>